<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Trip;
use App\Models\Invoice;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Edit extends Component
{


    public $invoices;
    public $search;
    public $invoice_number;
    public $invoice_id;
    public $customers;
    public $trips;
    public $trip;
    public $trip_id;
    public $customer_id;
    public $bank_accounts;
    public $bank_account_id;
    public $company_id;
    public $currencies;
    public $destinations;
    public $exchange_rate;
    public $exchange_amount;
    public $selectedCurrency ;
    public $selectedTrip;
    public $tax_rate;
    public $selectedCustomer;
    public $tax_amount;
    public $invoice_amount;
    public $date;
    public $expiry;
    public $subheading;
    public $memo;
    public $footer;
    public $subtotal = 0;
    public $total = 0;
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

    public function updatedSelectedTrip($id){
        if (!is_null($id)) {
            $trip = Trip::find($id);
            $this->invoice_amount = $trip->turnover;
        }
    }

    public function mount($invoice){
    $this->invoice = $invoice;
    $this->user_id = $invoice->user_id;
    $this->customer_id = $invoice->customer_id;
    $this->selectedCurrency = $invoice->currency_id;
    $this->subheading = $invoice->subheading;
    $this->footer = $invoice->footer;
    $this->selectedCustomer = $invoice->customer_id;
    $this->exchange_rate = $invoice->exchange_rate;
    $this->exchange_amount = $invoice->exchange_amount;
    $this->memo = $invoice->memo;
    // $items = $invoice->invoice_items;

    // if (isset($items)) {
    //     foreach ($items as $item) {
    //         $this->selectedProduct[] = $item->product_id;
    //         $this->selectedTax[] = $item->tax_id;
    //         $this->selectedTax[] = $item->tax_id;
    //     }
    // }
    $this->date = $invoice->date;
    $this->expiry = $invoice->expiry;
    $this->tax_amount = $invoice->tax_amount;
    $this->company_id = $invoice->company_id;
    $this->invoice_id = $invoice->id;
    $this->trip_id = $invoice->trip_id;
    $this->invoice_number = $invoice->invoice_number;
    $this->subtotal = $invoice->subtotal;
    $this->total = $invoice->total;
    $this->bank_account_id = $invoice->bank_account_id;
    $this->invoice_amount = $invoice->total;
    $this->trips = Trip::where('trip_status','!=', 'Cancelled')->latest()->get();
    $this->bank_accounts = BankAccount::orderBy('name','asc')->get();
    $this->customers = Customer::orderBy('name','asc')->get();
    $this->currencies = Currency::all();

    }
    public function updated($value){
        $this->validateOnly($value);
    }
   

    protected $rules = [
        'memo' => 'required',
        'subheading' => 'required',
        'expiry' => 'required',
        'date' => 'required',
        'footer' => 'required',
    ];

    public function update()
    {
        if ($this->invoice_id) {
            
            $invoice = Invoice::find($this->invoice_id);
            $invoice->company_id = $this->company_id;
            $invoice->bank_account_id = $this->bank_account_id;
            $invoice->currency_id = $this->selectedCurrency;
            if (isset($this->selectedCustomer)) {
                $invoice->customer_id = $this->selectedCustomer;
            }
            $invoice->invoice_number = $this->invoice_number;
            $invoice->date = $this->date;
            $invoice->expiry = $this->expiry;
            $invoice->memo = $this->memo;
            $invoice->footer = $this->footer;
            $invoice->subheading = $this->subheading;
            $invoice->update();
    
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Invoice Updated Successfully!!"
            ]);
          
            return redirect()->route('invoices.index');

        }
    }
    public function render()
    {
        if ((isset($this->exchange_rate) && $this->exchange_rate > 0)  &&  ( isset($this->invoice_amount) && $this->invoice_amount > 0 )) {

            $this->exchange_amount = $this->exchange_rate * $this->invoice_amount;

        }

        return view('livewire.invoices.edit');
    }
}
