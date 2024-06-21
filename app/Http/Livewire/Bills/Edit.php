<?php

namespace App\Http\Livewire\Bills;


use App\Models\Bill;
use App\Models\Trip;
use App\Models\Vendor;
use App\Models\Expense;
use Livewire\Component;
use App\Models\Currency;
use App\Models\BillExpense;
use App\Models\Transporter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Edit extends Component
{
    public $bill;
    public $bill_id;
    public $bill_number;
    public $transporters;
    public $transporter_id;
    public $vendors;
    public $vendor_id;
    public $trips;
    public $trip_id;
    public $currencies;
    public $currency_id;
    public $exchange_rate;
    public $exchange_amount;
    public $bill_date;
    public $due_date;
    public $notes;
    public $expenses;
    public $expense_id;
    public $description;
    public $amount;
    public $qty;
    public $subtotal;
    public $total;
    public $bill_total;
    public $user_id;

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
    

    public function billNumber(){

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

        $bill = Bill::latest()->orderBy('id','desc')->first();

        if (!$bill) {
            $bill_number =  $initials .'B'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $bill->id + 1;
            $bill_number =  $initials .'B'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $bill_number;


    }

    public function mount($id){
        $this->bill_id = $id;
        $this->bill = Bill::find($id);
        $this->trips = Trip::latest()->get();
        $this->currencies = Currency::orderBy('name','asc')->get();
        $this->expenses = Expense::orderBy('name','asc')->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
        $this->transporters = Transporter::orderBy('name','asc')->get();
        $this->currency_id = $this->bill->currency_id;
        $this->exchange_rate = $this->bill->exchange_rate;
        $this->exchange_amount = $this->bill->exchange_amount;
        $this->trip_id = $this->bill->trip_id;
        $this->vendor_id = $this->bill->vendor_id;
        $this->transporter_id = $this->bill->transporter_id;
        $this->bill_date = $this->bill->bill_date;
        $this->bill_number = $this->bill->bill_number;
        $this->due_date = $this->bill->due_date;
        $this->notes = $this->bill->notes;


    }

    public function updated($value){
        $this->validateOnly($value);
    }
   

    protected $rules = [
        'currency_id' => 'required',
        'bill_date' => 'required',
        'due_date' => 'required',
        'bill_date' => 'required',
    ];

    public function update(){

        $bill =  Bill::find($this->bill_id);
        $bill->user_id = Auth::user()->id;
        $bill->vendor_id = $this->vendor_id;
        $bill->transporter_id = $this->transporter_id;
        $bill->currency_id = $this->currency_id;
        $bill->bill_date = $this->bill_date;
        $bill->due_date = $this->due_date;
        $bill->notes = $this->notes;
        $bill->exchange_rate = $this->exchange_rate;
        $bill->exchange_amount = $this->exchange_amount;
        $bill->update();

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Bill Updated Successfully!!"
        ]);

        return redirect()->route('bills.index');
    }


    public function render()
    {

        if ((isset($this->exchange_rate) && $this->exchange_rate > 0)  &&  ( isset($this->total) && $this->total > 0 )) {

            $this->exchange_amount = $this->exchange_rate * $this->total;

        }

        $this->currencies = Currency::orderBy('name','asc')->get();
        $this->expenses = Expense::orderBy('name','asc')->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
        $this->transporters = Transporter::orderBy('name','asc')->get();
        return view('livewire.bills.edit',[
            'expenses' => $this->expenses,
            'currencies' => $this->currencies,
            'vendors' => $this->vendors,
            'transporters' => $this->transporters,
        ]);





    }
}
