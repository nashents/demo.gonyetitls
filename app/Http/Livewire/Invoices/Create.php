<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Tax;
use App\Models\Trip;
use App\Models\Cargo;
use App\Models\Count;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Product;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\BankAccount;
use App\Models\Destination;
use App\Models\InvoiceItem;
use App\Models\InvoiceTrip;
use App\Models\InvoiceCount;
use App\Models\TripDocument;
use App\Models\ProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Create extends Component
{
    public $search;
    protected $queryString = ['search'];
    public $from;
    public $to;

    public $reason;

    public $products;
    public $selectedProduct = [];
    public $description;
    public $qty = [];
    public $amount = [];
    public $invoices;
    public $invoice_number;
    public $number;
    public $invoice_id;
    public $initials;
    public $customers;
    public $trips;
    public $exchange_rate;
    public $exchange_amount;
    public $trip;
    public $invoice_trip;
    public $trip_id;
    public $selectedCustomer;
    public $bank_accounts;
    public $bank_account_id;
    public $company_id;
    public $currencies;
    public $destinations;
    public $selectedCurrency;
    public $selectedTrip = [];
    public $trip_sum = [];
    public $tax_rate = [];
    public $tax_amount;
    public $total_tax_amount;
    public $invoice_amount = 0;
    public $invoice_sub_amount = 0;
    public $turnover = 0;
    public $invoice_total_amount = 0;
    public $date;
    public $expiry;
    public $subheading;
    public $memo;
    public $footer;

    public $item_name;
    public $item_description;
    public $item_price;
    public $tax_id;
    public $tax;
    public $tax_accounts;
    public $selectedTax = [];
    public $income_accounts;
    public $expense_accounts;
    public $income_account_id;
    public $expense_account_id;
    public $sell = True;
    public $buy = False;
    
    public $item_subtotal = 0;
    public $subtotal = 0;
    public $subtotal_incl = 0;
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

    private function resetInputFields(){
        $this->item_name = '';
        $this->item_description = '';
        $this->item_price = '';
        $this->tax_id = '';
        $this->sell = True;
        $this->buy = False;
    }

    public function updatedSelectedCustomer($id){

        $this->customer = Customer::find($id);
        $this->initials = $this->customer->initials;
        $this->invoice_number = $this->invoiceNumber();
       
    
    }

 

    public function invoiceNumber(){
        if (Auth::user()->employee->company->invoice_serialize_by_customer == TRUE) {
            if (isset($this->initials) &&  $this->initials != NULL) {
            }else {
                $str = Auth::user()->employee->company->name;
                $words = explode(' ', $str);
                if (isset($words[1][0])) {
                    $this->initials = $words[0][0].$words[1][0];
                }else {
                    $this->initials = $words[0][0];
                }
            }
            $invoice = Invoice::where('customer_id', $this->selectedCustomer)->orderBy('id', 'desc')->get()->first();
    
            if (!$invoice) {
                $this->number = 1;
                $invoice_number =  $this->initials .'I'. str_pad(1, 5, "0", STR_PAD_LEFT);
            }else {
                if ($invoice->number) {
                    $this->number = $invoice->number + 1;
                }else{
                    $this->number = $invoice->id + 1;
                }
               
                $invoice_number = $this->initials .'I'. str_pad($this->number, 5, "0", STR_PAD_LEFT);
            }
        
            return  $invoice_number;
        }else {
            $str = Auth::user()->employee->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $this->initials = $words[0][0].$words[1][0];
            }else {
                $this->initials = $words[0][0];
            }
            $invoice = Invoice::orderBy('id','desc')->first();
            if (!$invoice) {
                $this->number = 1;
                $invoice_number =  $this->initials .'I'. str_pad(1, 5, "0", STR_PAD_LEFT);
            }else {
                $this->number = $invoice->id + 1;
                $invoice_number =  $this->initials .'I'. str_pad($this->number, 5, "0", STR_PAD_LEFT);
            }
        
            return  $invoice_number;
        }
    
    }

    public function mount(){
        $this->invoice_number = $this->invoiceNumber();
        $this->invoices = Invoice::latest()->get();
        $this->tax_accounts = Account::whereHas('account_type', function ($query) {
            return $query->where('name','Sales Taxes');
        })->orderBy('name','asc')->get();
        $this->expense_accounts = Account::whereHas('account_type.account_type_group', function ($query) {
            return $query->where('name','Expenses');
        })->orderBy('name','asc')->get();
        $this->income_account_id = Account::where('name','Sales')->first()->id;
     
        $this->bank_accounts = BankAccount::latest()->get();
        $this->products = Product::where('sell',True)->orderBy('name','asc')->get();
        $this->currencies = Currency::orderBy('name','asc')->get();
      
        $this->customers = Customer::orderBy('name','asc')->get();
        if (Auth::user()->employee->company) {
            $this->memo = Auth::user()->employee->company->invoice_memo;
            $this->footer = Auth::user()->employee->company->invoice_footer;
            $this->company_id = Auth::user()->employee->company->id;
        }elseif (Auth::user()->company) {
            $this->memo = Auth::user()->company->invoice_memo;
            $this->footer = Auth::user()->company->invoice_footer;
            $this->company_id = Auth::user()->company->id;
        }
    }


   
    public function updatedSelectedProduct($id, $key){
        if (!is_null($id)) {
            $product = Product::find($id);
            if (isset($product)) {
                if ($product->sell_price) {
                    $this->amount[$key] = $product->sell_price;
                }
                if ($product->tax_id) {
                    $this->selectedTax[$key] = $product->tax_id;
                    $tax = Account::find($product->tax_id);
                    if (isset($tax)) {
                        $this->tax_rate[$key] = $tax->rate;
                    }
                    
                }  
            }
           
        }
    }

    public function updatedSelectedTax($id, $key){
        if(!is_null($id)){
            $tax = Account::find($id);
            if (isset($tax)) {
                $this->tax_rate[$key] = $tax->rate;
            }
           
        }
    }

    public function updatedSelectedTrip($id, $key){
      
        if (!is_null($id)) {
            
            $trip = Trip::find($id);
            if (isset($trip)) {
                $this->selectedCurrency = $trip->currency_id; 
                $this->amount[$key] = $trip->turnover; 
                $this->qty[$key] = 1; 
            }
           
            
        }

        if(isset($this->amount[$key])){
            $this->invoice_amount = $this->invoice_amount + $this->amount[$key];
        }
       
    }

   


    public function updated($value){
        $this->validateOnly($value);
    }
    protected $messages =[

        'trip_id.required' => 'Trip field is required',
        'bank_account_id.required' => 'Bank Account field is required',

    ];

    protected $rules = [
        'trip_id' => 'required',
        'invoice_number' => 'required',
        'memo' => 'required',
        'subheading' => 'required',
        'expiry' => 'required',
        'date' => 'required',
        'footer' => 'required',
        'item_name' => 'required',
    ];

    public function storeItem(){
    try{
    
        $product = new Product;
        $product->user_id = Auth::user()->id;
        $product->name = $this->item_name;
        $product->description = $this->item_description;
        $product->sell_price = $this->item_price;
        $product->sell = $this->sell;
        $product->buy = $this->buy;
        $product->account_id = $this->income_account_id;
        $product->expense_account_id = $this->expense_account_id;
        $product->tax_id = $this->tax_id;
        $product->save(); 

        $this->dispatchBrowserEvent('hide-product_serviceModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Item Created Successfully!!"
        ]);

        }
            catch(\Exception $e){
            // Set Flash Message
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Something went wrong while creating item!!"
            ]);
         }
    }


 
  

    public function store(){
     
        
        // try {
      
        $invoice = new Invoice;
        $invoice->user_id = Auth::user()->id;
        $invoice->company_id = $this->company_id;
        $invoice->bank_account_id = $this->bank_account_id;
        $invoice->currency_id = $this->selectedCurrency;
        if (isset($this->selectedCustomer)) {
            $invoice->customer_id = $this->selectedCustomer;
        }
       
        $invoice->invoice_number = $this->invoice_number;
        $invoice->number = $this->number;
        $invoice->account_id = $this->income_account_id;
        $invoice->date = $this->date;
        $invoice->expiry = $this->expiry;
        $invoice->memo = $this->memo;
        $invoice->footer = $this->footer;
        $invoice->subheading = $this->subheading;
        $invoice->reason = $this->reason;
        $invoice->save();

        if ($this->reason == "Trip") {
     
        if (isset($this->selectedTrip)) {
            foreach ($this->selectedTrip as $key => $value) {

                if (isset($this->selectedTrip[$key])) {
                   
               
                
                $trip = Trip::find($this->selectedTrip[$key]);
                
                $cargo = $trip->cargo;
                $weight = $trip->weight.'tons' ;
                $cargo_name = $cargo ? $cargo->name : "";

                if (isset($cargo)) {
                    if ($trip->cargo->type == "Solid") {
                        $cargo_measurement = $trip->quantity.' '. $trip->measurement;
                    }else {
                        $cargo_measurement =  $trip->litreage_at_20.' '. $trip->measurement;
                    }
                    
                }else{
                    $cargo_measurement = "";
                }
              
              

                if ($trip->horse) {
                    $regnumber = $trip->horse ? $trip->horse->registration_number : "";
                }elseif ($trip->vehicle) {
                    $regnumber = $trip->vehicle ? $trip->vehicle->registration_number : "";
                }else{
                    $regnumber = "";
                }
                
                $origin = Destination::find($trip->from);
                $origin_city = $origin ? $origin->city : "";
                $destination = Destination::find($trip->to);
                $destination_city = $destination ? $destination->city : "";
                $symbol =  $trip->currency ? $trip->currency->symbol : "";

             

                if ($trip->freight_calculation) {
                    if ($trip->freight_calculation == "flat_rate") {
                     
                       $formula = "R";
                       $variables =  $symbol.$trip->rate;
                    }
                    elseif($trip->freight_calculation == "rate_weight"){
                       
                        if ($trip->cargo) {
                            if ($trip->cargo->type == "Solid") {
                                $formula = "R*W";
                                $variables = $symbol.$trip->rate.'*'.$trip->weight;
                            }elseif ($trip->cargo->type == "Liquid") {
                                $formula = "R*L";
                                $variables = $symbol.$trip->rate.'*'.$trip->litreage_at_20;
                            }
                        }
                      
                    }
                    elseif($trip->freight_calculation == "rate_distance"){
                        $formula = "R*D";
                        $variables = $symbol.$trip->rate.'*'.$trip->distance;
                    }
                    elseif($trip->freight_calculation == "rate_weight_distance"){
                        if ($trip->cargo) {
                            if ($trip->cargo->type == "Solid") {
                                $formula = "R*W*D";
                                $variables = $symbol.$trip->rate.'*'.$trip->weight.'*'.$trip->distance;
                            }elseif ($trip->cargo->type == "Liquid") {
                                $formula = "R*L*D";
                                $variables = $symbol.$trip->rate.'*'.$trip->litreage_at_20.'*'.$trip->distance;
                            }
                        }
                    }else {
                        $formula = "";
                        $variables = "";
                    }
                }
                
                $lp = $trip->loading_point ? $trip->loading_point->name : "";
                $op = $trip->offloading_point ? $trip->offloading_point->name : "";
                $from = $origin_city.' '.$lp ;
                $to = $destination_city.' '.$op ;
                $rate = $trip->rate;
                $quantity = $trip->quantity.' '.$trip->measurement;
                $litreage = $trip->litreage_at_20.' '.$trip->measurement;
                $trip_number = $trip->trip_number;
                $document = TripDocument::where('trip_id',$trip->id)->where('title','POD')->get()->first();
                $pod_number = $document ? $document->document_number : "";
              

                if (isset($cargo) && $cargo->type == "Solid") {
                    $cargo_description = $cargo_name .' '.$weight.' '. $quantity;
                }elseif (isset($cargo) && $cargo->type == "Liquid") {
                    $cargo_description =  $cargo_name.' '.$weight .' '. $litreage;
                }else {
                    $cargo_description = "";
                }
               
                $load_details = $cargo_description .' '.$formula .' '.$variables;

                $invoice_item = new InvoiceItem;
                $invoice_item->invoice_id = $invoice->id;

                
                if (isset($this->selectedTax[$key])) {
                    $invoice_item->tax_id = $this->selectedTax[$key];
                }
              

                if (isset($this->tax_rate[$key])) {
                    $invoice_item->tax_rate = $this->tax_rate[$key];
                }

                if (isset($this->selectedTrip[$key])) {
                    $invoice_item->trip_id = $this->selectedTrip[$key];
                }

                $invoice_item->trip_details = $load_details .' '.  $from .' to '.  $to .' '.  $regnumber.' '.$pod_number;

                $invoice_item->qty = $this->qty[$key];
                $invoice_item->amount = $this->amount[$key];

                if ((isset($this->amount[$key]) && isset($this->qty[$key]))) {
                    $this->subtotal = $this->amount[$key]*$this->qty[$key];
                    $invoice_item->subtotal = $this->subtotal;
                }
                if (isset($this->tax_rate[$key])) {
                    $this->tax_amount = ($this->subtotal * ($this->tax_rate[$key] / 100 ));
                    $invoice_item->tax_amount =  $this->tax_amount;
                    $this->subtotal_incl =  $this->tax_amount + $this->subtotal ;
                }else{
                    $this->subtotal_incl = $this->subtotal;
                }
               
                $invoice_item->subtotal_incl = $this->subtotal_incl;
                $invoice_item->save();

                $this->total_tax_amount =  $this->total_tax_amount + $this->tax_amount;
                $this->total = $this->total +  $this->subtotal_incl;

            }
            }
        }

     
        $invoice = Invoice::find($invoice->id);
      
        $invoice->tax_amount =  $this->total_tax_amount;
        $invoice->subtotal = $this->subtotal;
        $invoice->total = $this->total;
        $invoice->exchange_rate = $this->exchange_rate;
        $invoice->exchange_amount = $this->exchange_amount;
        $invoice->balance = $this->total;
        $invoice->update();

    }elseif ($this->reason == "General Invoice") {
      
        if (isset($this->selectedProduct)) {
            foreach($this->selectedProduct as $key => $value){
                $invoice_item = new InvoiceItem;
                $invoice_item->invoice_id = $invoice->id;

                if (isset($this->selectedProduct[$key])) {
                    $invoice_item->product_id = $this->selectedProduct[$key];
                }
                if (isset($this->qty[$key])) {
                    $invoice_item->qty = $this->qty[$key];
                }
                if (isset($this->amount[$key])) {
                    $invoice_item->amount = $this->amount[$key];
                }
                 if ((isset($this->amount[$key]) && isset($this->qty[$key]))) {
                    $this->subtotal = $this->amount[$key]*$this->qty[$key];
                    $invoice_item->subtotal = $this->subtotal;
                }
                if (isset($this->tax_rate[$key])) {
                    $this->tax_amount = ($this->subtotal * ($this->tax_rate[$key] / 100 ));
                    $invoice_item->tax_amount =  $this->tax_amount;
                    $this->subtotal_incl =  $this->tax_amount + $this->subtotal ;
                }else{
                    $this->subtotal_incl = $this->subtotal;
                }
               
                $invoice_item->subtotal_incl = $this->subtotal_incl;
                $invoice_item->save();

                $this->total_tax_amount =  $this->total_tax_amount + $this->tax_amount;
                $this->total = $this->total +  $this->subtotal_incl;
            }

        }
        

        $invoice = Invoice::find($invoice->id);
      
        $invoice->tax_amount =  $this->total_tax_amount;
        $invoice->subtotal = $this->subtotal;
        $invoice->total = $this->total;
        $invoice->exchange_rate = $this->exchange_rate;
        $invoice->exchange_amount = $this->exchange_amount;
        $invoice->balance = $this->total;
        $invoice->update();
    }

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Invoice Created Successfully!!"
        ]);

        return redirect()->route('invoices.index');

        
        //     }
        //     catch(\Exception $e){
        //     // Set Flash Message
        //     $this->dispatchBrowserEvent('alert',[
        //         'type'=>'error',
        //         'message'=>"Something went wrong while creating invoice!!"
        //     ]);
        // }
    }

    public function invoiceDate(){
        if ($this->expiry == "") {
            $this->expiry  = $this->date;
        }
    }


    public function render()
    {

        $this->products = Product::where('sell',True)->orderBy('name','asc')->get();

        if ((isset($this->exchange_rate) && $this->exchange_rate > 0)  &&  ( isset($this->total) && $this->total > 0 )) {

            $this->exchange_amount = $this->exchange_rate * $this->total;

        }

           $this->tax_accounts = Account::whereHas('account_type', function ($query) {
            return $query->where('name','Sales Taxes');
        })->get();

        if (isset($this->from) && isset($this->to)) {
            if (isset($this->search)) {
                $this->trips = Trip::query()->with('customer:id,name','loading_point:id,name','offloading_point:id,name','currency')->where('authorization','approved')->whereBetween('created_at',[$this->from, $this->to] )
                            ->where('authorization', 'approved')
                            ->where('trip_status','!=', 'Cancelled')
                            ->where('trip_number', 'like', '%'.$this->search.'%')
                            ->orWhere('start_date', 'like', '%'.$this->search.'%')
                            ->orWhere('turnover', 'like', '%'.$this->search.'%')
                            ->orWhere('freight', 'like', '%'.$this->search.'%')
                            ->orWhereHas('customer', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('vehicle', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('trip_documents', function ($query) {
                            return $query->where('document_number', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('currency', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                            })->orderby('created_at','desc')->take(100)->get();
            }else {
                $this->trips = Trip::query()->with('customer:id,name','loading_point:id,name','offloading_point:id,name','currency')->where('trip_status','!=', 'Cancelled')->where('authorization','approved')->whereBetween('created_at',[$this->from, $this->to] )->orderBy('created_at','desc')->take(100)->get();
               
            }
           
        }
        elseif (isset($this->search)) {
         
            $this->trips = Trip::query()->with('customer','loading_point','offloading_point','currency')
                ->where('authorization', 'approved')
                ->where('trip_status','!=', 'Cancelled')
                ->where('trip_number', 'like', '%'.$this->search.'%')
                ->orWhere('start_date', 'like', '%'.$this->search.'%')
                ->orWhere('turnover', 'like', '%'.$this->search.'%')
                ->orWhere('freight', 'like', '%'.$this->search.'%')
                ->orWhereHas('customer', function ($query) {
                return $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('trip_documents', function ($query) {
                    return $query->where('document_number', 'like', '%'.$this->search.'%');
                    })
                ->orWhereHas('horse', function ($query) {
                return $query->where('registration_number', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('vehicle', function ($query) {
                    return $query->where('registration_number', 'like', '%'.$this->search.'%');
                    })
                ->orWhereHas('currency', function ($query) {
                return $query->where('name', 'like', '%'.$this->search.'%');
                })->orderby('created_at','desc')->take(100)->get();
        }
        else{
            $this->trips = Trip::query()->with('customer:id,name','loading_point:id,name','offloading_point:id,name','currency')->where('trip_status','!=', 'Cancelled')->where('authorization','approved')->orderBy('created_at','desc')->take(100)->get();
        }
       
            return view('livewire.invoices.create',[
                'trips' => $this->trips,
                'trip_id' => $this->trip_id,
                'invoice_amount' =>$this->invoice_amount,
            ]);



    }
}
