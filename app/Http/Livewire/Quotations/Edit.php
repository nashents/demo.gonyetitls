<?php

namespace App\Http\Livewire\Quotations;

use App\Models\Cargo;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\BankAccount;
use App\Models\Destination;
use App\Models\QuotationProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Edit extends Component
{


    public $bank_accounts;
    public $bank_account_id;
    public $quotations;
    public $quotation_products;
    public $quotation_product_id;
    public $quotation;
    public $quotation_number;
    public $quotation_id;
    public $customers;
    public $selectedCustomer;
    public $company_id;
    public $cargos;
    public $cargo_id;
    public $currencies;
    public $destinations;
    public $currency_id;
    public $vat;
    public $date;
    public $expiry;
    public $subheading;
    public $memo;
    public $footer;
    public $subtotal = 0;
    public $total = 0;
    public $from;
    public $to;
    public $weight;
    public $rate;
    public $freight;
    public $user_id;
    public $exchange_rate;
    public $exchange_amount;

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

    public function updatedSelectedCustomer($id){
        $this->selectedCustomer = $id;
        $this->customer = Customer::find($this->selectedCustomer);
        $this->currency_id = $this->customer->currency ? $this->customer->currency->id  : NULL;
    }


    public function mount($quotation){
        $this->quotation = $quotation;
        $this->quotation_products = $quotation->quotation_products;

        if (isset( $this->quotation_products)) {
        foreach($this->quotation_products as $key => $value){
            $this->quotation_product_id[$key] = $value->id;
            $this->cargo_id[$key] = $value->cargo_id;
            $this->weight[$key] = $value->weight;
            $this->freight[$key] = $value->freight;
            $this->rate[$key] = $value->rate;
            $this->from[$key] = $value->from;
            $this->to[$key] = $value->to;
             }
        }
        $this->user_id = $quotation->user_id;
        $this->selectedCustomer = $quotation->customer_id;
        $this->currency_id = $quotation->currency_id;
        $this->company_id = $quotation->company_id;
        $this->bank_account_id = $quotation->bank_account_id;
        $this->subheading = $quotation->subheading;
        $this->quotation_number = $quotation->quotation_number;
        $this->footer = $quotation->footer;
        $this->memo = $quotation->memo;
        $this->vat = $quotation->vat;
        $this->date = $quotation->date;
        $this->expiry = $quotation->expiry;
        $this->quotation_id = $quotation->id;
        $this->currencies = Currency::latest()->get();
        $this->cargos = Cargo::orderBy('name','asc')->get();
        $this->customers = Customer::orderBy('name','asc')->get();
        $this->bank_accounts = BankAccount::orderBy('name','asc')->get();
        $this->destinations = Destination::with('country')->get()->sortBy('city')->sortBy('country.name');
    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $messages =[

        'cargo_id.*.required' => 'Cargo field is required',
        'to.*.required' => 'To field is required',
        'from.*.required' => 'From field is required',
        'weight.*.required' => 'Weight field is required',
        'rate.*.required' => 'Rate field is required',
        'freight.*.required' => 'Freight field is required',

        'cargo_id.0.required' => 'Cargo field is required',
        'to.0.required' => 'To field is required',
        'from.0.required' => 'From field is required',
        'weight.0.required' => 'Weight field is required',
        'rate.0.required' => 'Rate field is required',
        'freight.0.required' => 'Freight field is required',
    ];

    protected $rules = [
        'selectedCustomer' => 'required',
        'quotation_number' => 'required',
        // 'memo' => 'required',
        // 'subheading' => 'required',
        // 'expiry' => 'required',
        'date' => 'required',
        // 'footer' => 'required',
        'currency_id' => 'required',

        'cargo_id.0' => 'required',
        'from.0' => 'required',
        'to.0' => 'required',
        'freight.0' => 'required',
        // 'rate.0' => 'required',
        'weight.0' => 'required',

        'cargo_id.*' => 'required',
        'from.*' => 'required',
        'to.*' => 'required',
        'freight.*' => 'required',
        // 'rate.*' => 'required',
        'weight.*' => 'required',

    ];

    public function update(){

        $quotation = Quotation::find($this->quotation_id);
        $quotation->user_id = Auth::user()->id;
        $quotation->company_id = $this->company_id;
        $quotation->customer_id = $this->selectedCustomer;
        $quotation->currency_id = $this->currency_id;
        $quotation->date = $this->date;
        $quotation->expiry = $this->expiry;
        $quotation->memo = $this->memo;
        $quotation->footer = $this->footer;
        $quotation->subheading = $this->subheading;
        $quotation->exchange_rate = $this->exchange_rate;
        
        if (isset($this->exchange_rate) && isset($quotation->total)) {
           $exchange_amount = $this->exchange_rate * $quotation->total;
           $quotation->exchange_amount = $exchange_amount;
        }
        $quotation->update();

    //     if (isset($this->cargo_id )) {
    //     foreach ($this->cargo_id as $key => $value) {
    //         $quotation_product = QuotationProduct::find($this->quotation_products[$key]);

    //         $quotation_product[$key]->quotation_id =  $this->quotation_id;
    //         $quotation_product[$key]->cargo_id = $this->cargo_id[$key];
    //         $quotation_product[$key]->to = $this->to[$key];
    //         $quotation_product[$key]->from = $this->from[$key];
    //         $quotation_product[$key]->loading_point_id = $this->loading_point_id[$key];
    //         $quotation_product[$key]->offloading_point_id = $this->offloading_point_id[$key];
    //         $quotation_product[$key]->rate = $this->rate[$key];
    //         $quotation_product[$key]->weight = $this->weight[$key];
    //         $quotation_product[$key]->freight = $this->freight[$key];
    //         $quotation_product[$key]->update();

    //         // $this->freight = $this->rate[$key] * $this->weight[$key];
    //         $this->subtotal = $this->subtotal + $this->freight[$key];

    //     }

    // }

        $quotation->update();

        Session::flash('success','Quotation updated successfully');

        $this->dispatchBrowserEvent('hide-quotationModal');

        return redirect()->route('quotations.index');
    }










    public function render()
    {

            return view('livewire.quotations.edit');



    }
}
