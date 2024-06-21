<?php

namespace App\Http\Livewire\Sells;

use App\Models\Sell;
use App\Models\Account;
use App\Models\Product;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\SellItem;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Auth;

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
    public $sells;
    public $sell_number;
    public $number;
    public $sell_id;
    public $initials;
    public $customers;
    public $trips;
    public $exchange_rate;
    public $exchange_amount;
    public $trip;
    public $sell_trip;
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
    public $sell_amount = 0;
    public $sell_sub_amount = 0;
    public $turnover = 0;
    public $sell_total_amount = 0;
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
        $this->sell_number = $this->sellNumber();
       
    
    }

 

    public function sellNumber(){
        if (Auth::user()->employee->company->sell_serialize_by_customer == TRUE) {
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
            $sell = Sell::where('customer_id', $this->selectedCustomer)->orderBy('id', 'desc')->get()->first();
    
            if (!$sell) {
                $this->number = 1;
                $sell_number =  $this->initials .'I'. str_pad(1, 5, "0", STR_PAD_LEFT);
            }else {
                if ($sell->number) {
                    $this->number = $sell->number + 1;
                }else{
                    $this->number = $sell->id + 1;
                }
               
                $sell_number = $this->initials .'I'. str_pad($this->number, 5, "0", STR_PAD_LEFT);
            }
        
            return  $sell_number;
        }else {
            $str = Auth::user()->employee->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $this->initials = $words[0][0].$words[1][0];
            }else {
                $this->initials = $words[0][0];
            }
            $sell = Sell::orderBy('id','desc')->first();
            if (!$sell) {
                $this->number = 1;
                $sell_number =  $this->initials .'I'. str_pad(1, 5, "0", STR_PAD_LEFT);
            }else {
                $this->number = $sell->id + 1;
                $sell_number =  $this->initials .'I'. str_pad($this->number, 5, "0", STR_PAD_LEFT);
            }
        
            return  $sell_number;
        }
    
    }

    public function mount(){
        $this->sell_number = $this->sellNumber();
        $this->sells = Sell::latest()->get();
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
            $this->memo = Auth::user()->employee->company->sell_memo;
            $this->footer = Auth::user()->employee->company->sell_footer;
            $this->company_id = Auth::user()->employee->company->id;
        }elseif (Auth::user()->company) {
            $this->memo = Auth::user()->company->sell_memo;
            $this->footer = Auth::user()->company->sell_footer;
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



    public function updated($value){
        $this->validateOnly($value);
    }
    protected $messages = [

        'trip_id.required' => 'Trip field is required',
        'bank_account_id.required' => 'Bank Account field is required',

    ];

    protected $rules = [
        'trip_id' => 'required',
        'sell_number' => 'required',
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
     
        $sell = new Sell;
        $sell->user_id = Auth::user()->id;
        $sell->company_id = $this->company_id;
        $sell->bank_account_id = $this->bank_account_id;
        $sell->currency_id = $this->selectedCurrency;
        if (isset($this->selectedCustomer)) {
            $sell->customer_id = $this->selectedCustomer;
        }
       
        $sell->sell_number = $this->sell_number;
        $sell->number = $this->number;
        $sell->account_id = $this->income_account_id;
        $sell->date = $this->date;
        $sell->expiry = $this->expiry;
        $sell->memo = $this->memo;
        $sell->footer = $this->footer;
        $sell->subheading = $this->subheading;
        $sell->reason = $this->reason;
        $sell->save();

      
        if (isset($this->selectedProduct)) {
            foreach($this->selectedProduct as $key => $value){
                $sell_item = new SellItem;
                $sell_item->sell_id = $sell->id;

                if (isset($this->selectedProduct[$key])) {
                    $sell_item->product_id = $this->selectedProduct[$key];
                }
                if (isset($this->qty[$key])) {
                    $sell_item->qty = $this->qty[$key];
                }
                if (isset($this->amount[$key])) {
                    $sell_item->amount = $this->amount[$key];
                }
                 if ((isset($this->amount[$key]) && isset($this->qty[$key]))) {
                    $this->subtotal = $this->amount[$key]*$this->qty[$key];
                    $sell_item->subtotal = $this->subtotal;
                }
                if (isset($this->tax_rate[$key])) {
                    $this->tax_amount = ($this->subtotal * ($this->tax_rate[$key] / 100 ));
                    $sell_item->tax_amount =  $this->tax_amount;
                    $this->subtotal_incl =  $this->tax_amount + $this->subtotal ;
                }else{
                    $this->subtotal_incl = $this->subtotal;
                }
               
                $sell_item->subtotal_incl = $this->subtotal_incl;
                $sell_item->save();

                $this->total_tax_amount =  $this->total_tax_amount + $this->tax_amount;
                $this->total = $this->total +  $this->subtotal_incl;
            }

        }
        

        $sell = Sell::find($sell->id);
      
        $sell->tax_amount =  $this->total_tax_amount;
        $sell->subtotal = $this->subtotal;
        $sell->total = $this->total;
        $sell->exchange_rate = $this->exchange_rate;
        $sell->exchange_amount = $this->exchange_amount;
        $sell->balance = $this->total;
        $sell->update();
  

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Sell Created Successfully!!"
        ]);

        return redirect()->route('sells.index');

        
        //     }
        //     catch(\Exception $e){
        //     // Set Flash Message
        //     $this->dispatchBrowserEvent('alert',[
        //         'type'=>'error',
        //         'message'=>"Something went wrong while creating sell!!"
        //     ]);
        // }
    }

    public function sellDate(){
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
       
            return view('livewire.sells.create',[
                'trips' => $this->trips,
                'trip_id' => $this->trip_id,
                'sell_amount' =>$this->sell_amount,
            ]);



    }
}
