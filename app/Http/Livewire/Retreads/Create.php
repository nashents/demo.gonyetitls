<?php

namespace App\Http\Livewire\Retreads;

use App\Models\Tyre;
use App\Models\Horse;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Retread;
use App\Models\Trailer;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Purchase;
use App\Models\TyreCount;
use App\Models\TyreDetail;
use App\Models\RetreadTyre;
use App\Models\TyreDispatch;
use Livewire\WithFileUploads;
use App\Models\RetreadDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Create extends Component
{
    use WithFileUploads;

    public $tyre_dispatches;
    public $tyre_dispatch_id;
    public $rate;
    public $trailers;
    public $trailer_id;
    public $horses;
    public $horse_id;
    public $vehicles;
    public $vehicle_id;
    public $vendors;
    public $vendor_id;
    public $currencies;
    public $currency_id;
    public $date;
    public $collection_date;
    public $total;
    public $description;

    public $title;
    public $file;

    public $inputs = [];
    public $i = 1;
    public $n = 1;

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);
    }

    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    public $documents_inputs = [];
    public $p = 1;


    public function documentsAdd($p)
    {
        $p = $p + 1;
        $this->p = $p;
        array_push($this->documents_inputs ,$p);
    }

    public function documentsRemove($p)
    {
        unset($this->documents_inputs[$p]);
    }

    public function mount(){
        $this->vehicles = Vehicle::where('status',1)->latest()->get();
        $this->trailers = Trailer::where('status', 1)->latest()->get();
        $this->horses = Horse::where('status',1)->latest()->get();
        $this->currencies = Currency::all();
        $this->retread_number = $this->retreadNumber();
        $this->tyre_dispatches = TyreDispatch::all();
        $value = 'tyres';
        $this->vendors = Vendor::with(['vendor_type'])
       ->whereHas('vendor_type', function($q) use($value) {
       $q->where('name', '=', $value);
        })->get();
      }

      public function updated($value){
          $this->validateOnly($value);
      }
      protected $messages =[

        'tyre_dispatch_id.*.required' => 'Tyre Dispatch field is required',
        'rate.*.required' => 'Rate field is required',
        'diameter.*.required' => 'Diameter field is required',
        'currency_id.required' => 'Select Currency',
        'vendor_id.required' => 'Select Vendor',

    ];
      protected $rules = [
          'vendor_id' => 'required',
          'retread_number' => 'required',
          'currency_id' => 'required',
          'date' => 'required',
          'collection_date' => 'required',
          'description' => 'required',
          'tyre_dispatch_id.*' => 'required',
          'rate.*' => 'required',
      ];


      public function retreadNumber(){

        if (isset(Auth::user()->company)) {
            $str = Auth::user()->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }elseif (isset(Auth::user()->employee->company)) {
            $str = Auth::user()->employee->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }

            $retread = Retread::orderBy('id','desc')->first();

        if (!$retread) {
            $retread_number =  $initials .'R'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $retread->id + 1;
            $retread_number =  $initials .'R'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $retread_number;


    }


      public function store(){

          $retread = new Retread;
          $retread->user_id = Auth::user()->id;
          $retread->retread_number = $this->retreadNumber();
          $retread->currency_id = $this->currency_id;
          $retread->vendor_id = $this->vendor_id;
          $retread->date = $this->date;
          $retread->collection_date = $this->collection_date;
          $retread->description = $this->description;
          $retread->save();

          if (isset($this->tyre_dispatch_id)) {

          foreach ($this->tyre_dispatch_id as $key => $value) {
            $tyre_dispatch = TyreDispatch::find($this->tyre_dispatch_id[$key]);
            $retread_tyre = new RetreadTyre;
            $retread_tyre->retread_id = $retread->id;
            if (isset($this->tyre_dispatch_id[$key])) {
            $retread_tyre->tyre_dispatch_id = $this->tyre_dispatch_id[$key];
            }
            if (isset($this->horse_id[$key])) {
                $retread_tyre->horse_id = $this->horse_id[$key];
            }
            if (isset($this->vehicle_id[$key])) {
                $retread_tyre->vehicle_id = $this->vehicle_id[$key];
            }
            if (isset($this->trailer_id[$key])) {
                $retread_tyre->trailer_id = $this->trailer_id[$key];
            }
            if (isset($this->rate[$key])) {
                $retread_tyre->rate = $this->rate[$key];
                $this->total =   $this->total + $this->rate[$key];
            }
            $retread_tyre->tyre_number =   $tyre_dispatch->tyre_number;
            if ($tyre_dispatch->tyre_detail) {
            $retread_tyre->name =   $tyre_dispatch->tyre_detail->product ? $tyre_dispatch->tyre_detail->product->name : "undefined";
            }
            $retread_tyre->width =   $tyre_dispatch->width;
            $retread_tyre->aspect_ratio =   $tyre_dispatch->aspect_ratio;
            $retread_tyre->diameter =   $tyre_dispatch->diameter;
            $retread_tyre->save();

            $tyre_dispatch = TyreDispatch::find($this->tyre_dispatch_id[$key]);
            $tyre_dispatch->status = 0;
            $tyre_dispatch->update();



          }
        }

            $retread = Retread::find($retread->id);
            $retread->total =   $this->total;
            $retread->update();

          Session::flash('success','Retread Created Successfully');
          return redirect()->route('retreads.index');
      }

    public function render()
    {

        return view('livewire.retreads.create');
    }
}
