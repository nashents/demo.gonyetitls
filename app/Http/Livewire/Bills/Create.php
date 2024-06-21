<?php

namespace App\Http\Livewire\Bills;


use App\Models\Bill;
use App\Models\Vendor;
use App\Models\Account;
use App\Models\Product;
use Livewire\Component;
use App\Models\Currency;
use App\Models\BillAccount;
use App\Models\BillExpense;
use App\Models\Transporter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Create extends Component
{


    public $bill;
    public $bill_id;
    public $bill_number;
    public $vendors;
    public $exchange_rate;
    public $exchange_amount;
    public $vendor_id;
    public $products;
    public $selectedProduct = [];
    public $transporters;
    public $transporter_id;
    public $currencies;
    public $currency_id;
    public $bill_date;
    public $due_date;
    public $notes;
    public $accounts;
    public $selectedAccount = [];
    public $description;
    public $amount;
    public $qty;
    public $subtotal;
    public $total;
    public $bill_total;
    public $tax_rate = [];
    public $tax_amount;
    public $total_tax_amount;
    public $user_id;


    public $item_name;
    public $item_description;
    public $item_price;
    public $tax_id;
    public $tax;
    public $tax_accounts;
    public $selectedTax = [];
    public $expense_account_id;
    public $sell = False;
    public $buy = True;

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

    public function mount(){
        $this->bill_number = $this->billNumber();
        $this->currencies = Currency::orderBy('name','asc')->get();
        $this->products = Product::where('buy',True)->orderBy('name','asc')->get();
        $this->tax_accounts = Account::whereHas('account_type', function ($query) {
            return $query->where('name','Sales Taxes');
        })->orderBy('name','asc')->get();
        $this->accounts = Account::whereHas('account_type.account_type_group', function ($query) {
            return $query->where('name','Expenses');
        })->orderBy('name','asc')->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
        $this->transporters = Transporter::orderBy('name','asc')->get();
    }

    public function billDate(){
        if ($this->due_date == "") {
            $this->due_date  = $this->bill_date;
        }
    }

    public function updatedSelectedProduct($id, $key){
        if (!is_null($id)) {
            $product = Product::find($id);
            if (isset($product)) {
                if ($product->price) {
                    $this->amount[$key] = $product->price;
                }
                if ($product->description) {
                    $this->description[$key] = $product->description;
                }
                if ($product->expense_account_id) {
                    $this->selectedAccount[$key] = $product->expense_account_id;
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

    public function storeItem(){
        // try{
       
            $product = new Product;
            $product->user_id = Auth::user()->id;
            $product->name = $this->item_name;
            $product->description = $this->item_description;
            $product->price = $this->item_price;
            $product->sell = $this->sell;
            $product->buy = $this->buy;
            $product->expense_account_id = $this->expense_account_id;
            $product->tax_id = $this->tax_id;
            $product->save(); 
    
            $this->dispatchBrowserEvent('hide-product_serviceModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Item Created Successfully!!"
            ]);
    
            // }
            //     catch(\Exception $e){
            //     // Set Flash Message
            //     $this->dispatchBrowserEvent('alert',[
            //         'type'=>'error',
            //         'message'=>"Something went wrong while creating item!!"
            //     ]);
            //  }
        }

    public function updated($value){
        $this->validateOnly($value);
    }

    protected $rules = [
        'currency_id' => 'required',
        'bill_date' => 'required',
        'due_date' => 'required',
        'selectedProduct.0' => 'required',
        'selectedAccount.0' => 'required',
        'description.0' => 'required',
        'qty.0' => 'required',
        'amount.0' => 'required',
        'selectedAccount.*' => 'required',
        'selectedProduct.*' => 'required',
        'description.*' => 'required',
        'qty.*' => 'required',
        'amount.*' => 'required',
    ];

    public function store(){

        $bill = new Bill;
        $bill->user_id = Auth::user()->id;
        $bill->vendor_id = $this->vendor_id;
        $bill->currency_id = $this->currency_id;
        $bill->category = "Vendor";
        $bill->bill_number = $this->bill_number;
        $bill->bill_date = $this->bill_date;
        $bill->due_date = $this->due_date;
        $bill->notes = $this->notes;
        $bill->save();

        if (isset($this->selectedProduct)) {
            foreach($this->selectedProduct as $key => $value){

                $bill_expense = new BillExpense;
                $bill_expense->bill_id = $bill->id;
                $bill_expense->currency_id = $bill->currency_id;

                if (isset($this->selectedProduct[$key])) {
                    $bill_expense->product_id = $this->selectedProduct[$key];
                }

                if (isset($this->selectedAccount[$key])) {
                    $bill_expense->account_id = $this->selectedAccount[$key];
                }

                if (isset($this->description[$key])) {
                    $bill_expense->description = $this->description[$key];
                }

                if (isset($this->qty[$key])) {
                    $bill_expense->qty = $this->qty[$key];
                }

                if (isset($this->amount[$key])) {
                    $bill_expense->amount = $this->amount[$key];
                }

                if (isset($this->selectedTax[$key])) {
                    $bill_expense->tax_id = $this->selectedTax[$key];
                }

                if (isset($this->amount[$key]) && isset($this->qty[$key])) {
                $this->subtotal = ($this->amount[$key] * $this->qty[$key]);
                $bill_expense->subtotal = $this->subtotal;
                }
              
                if (isset($this->tax_rate[$key])) {
                    $this->tax_amount = ($this->subtotal * ($this->tax_rate[$key] / 100 ));
                    $bill_expense->tax_amount =  $this->tax_amount;
                    $this->subtotal_incl =  $this->tax_amount + $this->subtotal ;
                }else{
                    $this->subtotal_incl = $this->subtotal;
                }
                $bill_expense->subtotal_incl = $this->subtotal_incl;
                $bill_expense->save();

                $this->total_tax_amount =  $this->total_tax_amount + $this->tax_amount;
                $this->total = $this->total +  $this->subtotal_incl;
            }
        }

        $bill = Bill::find($bill->id);
        $bill->tax_amount = $this->total_tax_amount;
        $bill->subtotal = $this->subtotal;
        $bill->total = $this->total;
        $bill->balance = $this->total;
        $bill->exchange_rate = $this->exchange_rate;
        $bill->exchange_amount = $this->exchange_amount;
        $bill->update();

        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Bill Created Successfully!!"
        ]);

        return redirect()->route('bills.index');
    }


    public function render()
    {

        if ((isset($this->exchange_rate) && $this->exchange_rate > 0)  &&  ( isset($this->total) && $this->total > 0 )) {

            $this->exchange_amount = $this->exchange_rate * $this->total;

        }
            $this->products = Product::where('buy',True)->orderBy('name','asc')->get();
            $this->currencies = Currency::orderBy('name','asc')->get();
            $this->accounts = Account::whereHas('account_type.account_type_group', function ($query) {
                return $query->where('name','Expenses');
            })->orderBy('name','asc')->get();
            $this->vendors = Vendor::orderBy('name','asc')->get();
            $this->transporters = Transporter::orderBy('name','asc')->get();
            return view('livewire.bills.create',[
                'accounts' => $this->accounts,
                'currencies' => $this->currencies,
                'vendors' => $this->vendors,
                'transporters' => $this->transporters,
                'products' => $this->products,
            ]);



    }
}
