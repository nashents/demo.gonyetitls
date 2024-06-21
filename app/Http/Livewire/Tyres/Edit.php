<?php

namespace App\Http\Livewire\Tyres;

use App\Models\Tyre;
use App\Models\Store;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Purchase;
use App\Models\TyreDetail;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Edit extends Component
{
    use WithFileUploads;

    public $products;
    public $selectedProduct;

    public $condition;
    public $currencies;
    public $vendors;
    public $vendor_id;
    public $currency_id;
    public $date;
    public $rate;
    public $serial_number;
    public $purchase_date;
    public $type;
    public $tyre_id;
    public $quantity;

    public $residual_value;
    public $life;
    public $purchase_type;
    public $depreciation_type;
    public $warranty_exp_date;
    public $purchase_products;
    public $selectedPurchase;
    public $purchases;
    public $stores;
    public $store_id;

    public $amount = "0";
    public $description;

    public $assigned;


    public $title;
    public $file;


    public $qty;
    public $width;
    public $tyre_number;
    public $aspect_ratio;
    public $diameter;
    public $thread_depth;
    public $life_span;


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
    public $o = 1;


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

    public function mount($id){
        $tyre = Tyre::find($id);
        $this->tyre = $tyre;
        $this->tyre_id = $id;
        $this->width = $tyre->width;
        $this->type = $tyre->type;
        $this->diameter = $tyre->diameter;
        $this->thread_depth = $tyre->thread_depth;
        $this->life_span = $tyre->life_span;
        $this->aspect_ratio = $tyre->aspect_ratio;
        $this->selectedProduct = $tyre->product_id;
        $this->serial_number = $tyre->serial_number;
        $this->status = $tyre->status;
        $this->stores = Store::latest()->get();
        $this->currencies = Currency::orderBy('name','asc')->get();
        $this->products = Product::where('department','tyre')->orderBy('name','asc')->get();
        $this->purchases = Purchase::where('authorization','approved')->latest()->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
      //   $value = 'Tyres';
      //   $this->vendors = Vendor::with(['vendor_type'])
      //  ->whereHas('vendor_type', function($q) use($value) {
      //  $q->where('name', '=', $value);
      //   })->get();
        $this->store_id =  $tyre->store_id;
        $this->vendor_id = $tyre->vendor_id;
        $this->currency_id = $tyre->currency_id;
        $this->rate = $tyre->rate;
        $this->purchase_date = $tyre->purchase_date;
        $this->tyre_number = $tyre->tyre_number;
        $this->condition = $tyre->condition;
        $this->residual_value = $tyre->residual_value;
        $this->life = $tyre->life;
        $this->warranty_exp_date = $tyre->warranty_exp_date;
        $this->purchase_type = $tyre->purchase_type;
        $this->depreciation_type = $tyre->depreciation_type;
        $this->description = $tyre->description;
        $this->selectedPurchase = $tyre->purchase_id;
        if ($this->selectedPurchase) {
          $this->vendor_id = Purchase::find($this->selectedPurchase)->vendor->id;
          $this->currency_id = Purchase::find($this->selectedPurchase)->currency->id;
          $this->purchase_products = Purchase::find($this->selectedPurchase)->purchase_products;
        }
       

      }
      public function updated($value){
          $this->validateOnly($value);
      }
      protected $messages =[

        'selectedProduct.*.required' => 'Product field is required',
        'aspect_ratio.*.required' => 'Aspect Ratio field is required',
        'serial_number.*.required' => 'Serial Number field is required',
        'selectedProduct.0.required' => 'Product field is required',
        'aspect_ratio.0.required' => 'Aspect Ratio field is required',
        'serial_number.0.required' => 'Serial Number field is required',
        'vendor_id.required' => 'Select Vendor',

    ];
      protected $rules = [
        'aspect_ratio.*' => 'required',
        'width.*' => 'required',
        'diameter.*' => 'required',
        'serial_number.*' => 'required',
        'type.*' => 'required',
        'selectedProduct.*' => 'required',
        'aspect_ratio.0' => 'required',
        'type.0' => 'required',
        'width.0' => 'required',
        'diameter.0' => 'required',
        'serial_number.0' => 'required',
        'selectedProduct.0' => 'required',
        'vendor_id' => 'required',
      ];

      public function updatedSelectedPurchase($purchase){
        if (!is_null($purchase)) {
            $this->vendor_id = Purchase::find($purchase)->vendor->id;
            $this->currency_id = Purchase::find($purchase)->currency->id;
            $this->purchase_products = Purchase::find($purchase)->purchase_products;
        }
      
    }

      public function update(){

              $tyre = Tyre::find($this->tyre_id);
              $tyre->user_id = Auth::user()->id;
              $tyre->product_id = $this->selectedProduct;
              $tyre->serial_number = $this->serial_number;
              $tyre->type = $this->type;
              $tyre->rate = $this->rate;
              $tyre->width = $this->width;
              $tyre->diameter = $this->diameter;
              $tyre->thread_depth = $this->thread_depth;
              $tyre->life_span = $this->life_span;
              $tyre->aspect_ratio = $this->aspect_ratio;
              $tyre->tyre_number = $this->tyre_number;
              $tyre->currency_id = $this->currency_id;
              $tyre->store_id = $this->store_id;
              $tyre->purchase_id = $this->selectedPurchase;
              $tyre->vendor_id = $this->vendor_id;
              $tyre->condition = $this->condition;
              $tyre->description = $this->description;
              $tyre->depreciation_type = $this->depreciation_type;
              $tyre->purchase_date = $this->purchase_date;
              $tyre->purchase_type = $this->purchase_type;
              $tyre->purchase_id = $this->selectedPurchase ? $this->selectedPurchase : null;
              $tyre->warranty_exp_date = $this->warranty_exp_date;
              $tyre->life = $this->life;
              $tyre->description = $this->description;
              $tyre->status = $this->status;

              $tyre->update();

        Session::flash('success','Tyre(s) added successfully');
        return redirect()->route('tyres.index');
      }

    public function render()
    {
        return view('livewire.tyres.edit');
    }
}
