<?php

namespace App\Http\Livewire\Payments;

use App\Models\Account;
use App\Models\Payment;
use App\Models\Receipt;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\AccountType;
use App\Models\BankAccount;
use App\Models\Denomination;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithFileUploads;

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    protected $queryString = ['search'];
    public $from;
    public $to;
    public $payment_filter;
    private $invoices;
    public $invoice_products;
    public $invoice;
    public $invoice_id;
    public $customers;
    public $currencies;
    public $selectedCurrency;
    public $receipt_number;
    public $invoice_number;
    public $account_type;
    public $date;
    public $amount;
    public $receipt;
    public $balance;   
    public $customer_accounts;
    public $customer_account;
    public $selectedCustomerAccount;
    public $accounts;
    public $account_id;
    public $bank_accounts;
    public $bank_account_id;
    public $trips;
    public $trip;
    public $trip_id;
    public $notes;
    public $invoice_balance;
    public $pop;
    public $reference_code;
    public $invoice_currency;
    public $name;
    public $denomination;
    public $denomination_qty;
    public $surname;
    public $user_id;
    public $selectedCustomer;
    public $current_balance;
    public $mode_of_payment;
    public $specify_other;

    public $expires_at;
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
   
    public $payments;
    public $payment_id;


    public function receiptNumber(){

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
    
        $receipt = Receipt::orderBy('id','desc')->first();
    
        if (!$receipt) {
            $receipt_number =  $initials .'R'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $receipt->id + 1;
            $receipt_number =  $initials .'R'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }
    
        return  $receipt_number;
    
    
    }
    public function paymentNumber(){

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
    
        $payment = Payment::orderBy('id','desc')->first();
    
        if (!$payment) {
            $payment_number =  $initials .'P'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $payment->id + 1;
            $payment_number =  $initials .'P'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }
    
        return  $payment_number;
    
    
    }
    

    private function resetInputFields(){
        $this->selectedCustomer = '';
        $this->selectedCurrency = '';
        $this->name = '';
        $this->surname = '';
        $this->notes = '';
        $this->mode_of_payment = "" ;
        $this->specify_other = "" ;
        $this->account_id = "" ;
        $this->reference_code = "" ;
        $this->bank_account_id = "" ;
    }

    public function mount(){
        $this->payments = Payment::latest()->get();
        $this->customers = Customer::orderBy('name','asc')->get();
        $this->resetPage();
        $this->payment_filter = "created_at";
        $this->currencies = Currency::latest()->get();
        $this->bank_accounts = collect();
        $this->accounts = collect();
    }

    public function updatedSelectedCustomerAccount($id){
        if (!is_null($id)) {
            $account = Account::find($id);
            $this->selectedCurrency = $account->currency_id;
            $this->bank_accounts = BankAccount::where('currency_id',$account->currency_id)->get();
            $this->accounts = Account::where('account_type_id',1)->where('currency_id',$account->currency_id)->latest()->get();
        } 
    }
    public function updatedSelectedCustomer($id){
        if (!is_null($id)) {
            $this->customer_accounts = Account::where('customer_id',$id)->latest()->get();
        } 
    }
    public function updatedSelectedCurrency($id){
        if (!is_null($id)) {
            $this->bank_accounts = BankAccount::where('currency_id',$id)->get();
            $this->accounts = Account::where('account_type_id',1)->where('currency_id',$id)->latest()->get();
        } 
    }

    public function accountNumber(){
       
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

            $account = Account::orderBy('id', 'desc')->first();

        if (!$account) {
            $account_number =  $initials .'CA'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $account->id + 1;
            $account_number =  $initials .'CA'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $account_number;


    }

    public function recordPayment(){

        $payment = new Payment;
        $payment->company_id = Auth::user()->employee->company ? Auth::user()->employee->company->id : "";
        $payment->customer_id = $this->selectedCustomer;
        $payment->user_id = Auth::user()->id;
        $payment->currency_id = $this->selectedCurrency;
        $payment->payment_number = $this->paymentNumber();   
        $payment->name = $this->name;
        $payment->notes = $this->notes;
        $payment->surname = $this->surname;
        $payment->mode_of_payment = $this->mode_of_payment;
        $payment->specify_other = $this->specify_other;
        $payment->category = "customer";
        $payment->reference_code = $this->reference_code;
        $payment->bank_account_id = $this->bank_account_id;
        $payment->account_id = $this->account_id;

        if (isset($this->customer_account)) {
           
            $payment->customer_account_id = $this->customer_account->id;
        }else {
           
            $customer = Customer::find($this->selectedCustomer);
            $this->account_type = AccountType::where('name','Customer Prepayments & Customer Credits')->first();
            $currency = Currency::find($this->selectedCurrency);
            $customer_account = new Account;
            $customer_account->account_number = $this->accountNumber();
            $customer_account->name = $customer->name ." ". $currency->name;
            $customer_account->account_type_id =  $this->account_type->id ?  $this->account_type->id : null;
            $customer_account->currency_id = $currency->id ? $currency->id : null;
            $customer_account->customer_id = $customer->id ? $customer->id : null;
            $customer_account->save();
            $this->customer_account = $customer_account;

            $payment->customer_account_id = $customer_account->id;
        }
      
        $payment->amount = $this->amount;
        $payment->date = $this->date;
        $payment->save();

       

        if(isset($this->pop)){
            $file = $this->pop;
            // get file with ext
            $fileNameWithExt = $file->getClientOriginalName();
            //get filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //get extention
            $extention = $file->getClientOriginalExtension();
            //file name to store
            $fileNameToStore = $filename.'_'.time().'.'.$extention;
            $file->storeAs('/documents', $fileNameToStore, 'my_files');

            $document = new Document;
            $document->payment_id = $payment->id;
            $document->category = 'payment';
            $document->title = "Proof Of Payment";
            if (isset( $fileNameToStore)) {
                $document->filename = $fileNameToStore;
            }
            if(isset($this->expires_at)){
                $document->expires_at = Carbon::create($this->expires_at[$key])->toDateTimeString();
                $today = now()->toDateTimeString();
                $expire = Carbon::create($this->expires_at[$key])->toDateTimeString();
                if ($today <=  $expire) {
                    $document->status = 1;
                }else{
                    $document->status = 0;
                }
            }else {
            $document->status = 1;
            }
            $document->save();
  
        }

        if ($this->denomination) {
            foreach ($this->denomination as $key => $value) {
                $denomination = new Denomination;
                $denomination->payment_id = $payment->id;
                if (isset($this->denomination[$key])) {
                    $denomination->denomination = $this->denomination[$key];
                }
              if (isset($this->denomination_qty[$key])) {
                $denomination->quantity =  $this->denomination_qty[$key];
              }
               
                $denomination->save();
            }
        }



        if (isset($this->customer_account)) {
            $customer_account = Account::find($this->customer_account->id);
            $current_balance = $customer_account->balance;
            $customer_account->balance = $current_balance + $this->amount;
            $customer_account->update();
        }
        if (isset($this->account_id)) {
            $account = Account::find($this->account_id);
            $current_balance = $account->balance;
            $account->balance = $current_balance + $this->amount;
            $account->update();
        }

        
        if (isset($this->customer_account)) {
            $cashflow = new CashFlow;
            $cashflow->user_id = Auth::user()->id;
            $cashflow->customer_id = $this->selectedCustomer;
            $cashflow->currency_id = $this->selectedCurrency;
            $cashflow->payment_id = $payment->id;
            $cashflow->account_id = $this->customer_account->id;
            $cashflow->type = 'InDirect';
            $cashflow->sub_type = 'Income';
            $cashflow->category = 'Customer';
            $cashflow->transaction_type = 'Deposit';
            $cashflow->transaction_category = "Customer Prepayments & Customer Credits";
            $cashflow->date = $payment->date;
            $cashflow->amount = $payment->amount;
            $cashflow->save();  
        }
        if (isset($this->account_id)) {
            $cashflow = new CashFlow;
            $cashflow->user_id = Auth::user()->id;
            $cashflow->customer_id = $this->selectedCustomer;
            $cashflow->currency_id = $this->selectedCurrency;
            $cashflow->payment_id = $payment->id;
            $cashflow->account_id = $this->account_id;
            $cashflow->type = 'InDirect';
            $cashflow->sub_type = 'Income';
            $cashflow->category = 'Customer';
            $cashflow->transaction_type = 'Deposit';
            $cashflow->date = $payment->date;
            $cashflow->amount = $payment->amount;
            $cashflow->save();  
        }

      
      
        $receipt =  new Receipt;
        $receipt->payment_id = $payment->id;
        $receipt->company_id = $payment->company_id;
        $receipt->currency_id = $payment->currency_id;
        $receipt->receipt_number = $this->receiptNumber(); ;
        $receipt->user_id = Auth::user()->id;
        $receipt->amount = $this->amount;
        $receipt->date = $this->date;
        $receipt->save();

       
        $this->dispatchBrowserEvent('hide-paymentModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Payment Recorded Successfully!!"
        ]);
    
       
    }

    public function render()
    {
        $this->payments = Payment::latest()->get();
        $this->customers = Customer::orderBy('name','asc')->get();
        $this->customer_accounts = Account::where('customer_id',$this->selectedCustomer)->latest()->get();

        if (isset($this->selectedCustomer) && isset($this->selectedCurrency)) {
          
        $this->account_type = AccountType::where('name','Customer Prepayments & Customer Credits')->first();
       
        $this->customer_account = Account::where('customer_id',$this->selectedCustomer)->where('currency_id',$this->selectedCurrency)->where('account_type_id', $this->account_type->id)->first();
        }
        return view('livewire.payments.index',[
            'payments' => $this->payments,
            'customers' => $this->customers,
            'customer_accounts' => $this->customer_accounts,
        ]);
    }
}
