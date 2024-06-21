<?php

namespace App\Http\Livewire\Accounts;

use Carbon\Carbon;
use App\Models\Account;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Document;
use App\Models\AccountType;
use App\Models\BankAccount;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithFileUploads;

    public $grouped_account_types;
    public $account_types;
    public $account_type_id;
    public $name;
    public $amount;
    public $notes;
    public $receipt;
    public $date;
    public $description;
    public $transaction_type;
    public $transaction_category;
    public $accounts;
    public $account_id;
    public $account_reference;
    public $currencies;
    public $selectedCurrency;
    public $bank_accounts;
    public $bank_account_id;
    public $customers;
    public $customer_id;
    public $categories;
    public $category_id;

    public function mount(){
        $this->accounts = Account::latest()->get();
        $this->currencies = Currency::orderBy('name','asc')->get();
        $this->bank_accounts = collect();
        $this->customers = Customer::orderBy('name','asc')->get();
        $this->account_types =  AccountType::orderBy('created_at','asc')->get();
        // $this->grouped_account_types = $this->account_types->groupBy('group');
    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'name' => 'required|unique:accounts,name,NULL,id,deleted_at,NULL|string|min:2',
    ];

    public function updatedSelectedCurrency($id){
        $this->bank_accounts = BankAccount::where('currency_id',$id)->orderBy('name','asc')->get();
    }

    private function resetInputFields(){
        $this->account_type_id = '';
        $this->name = '';
        $this->selectedCurrency = '';
        $this->description = '';
        $this->bank_account_id = '';
        $this->account_reference = '';
        $this->customer_id = '';
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
            $account_number =  $initials .'A'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $account->id + 1;
            $account_number =  $initials .'A'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $account_number;


    }

    

    public function store(){
        // try{
        $account = new Account;
        $account->user_id = Auth::user()->id;
        $account->name = $this->name;
        $account->account_reference = $this->account_reference;
        $account->account_type_id = $this->account_type_id;
        $account->bank_account_id = $this->bank_account_id ? $this->bank_account_id : null;
        $account->customer_id = $this->customer_id ? $this->customer_id : null;
        $account->currency_id = $this->selectedCurrency;
        $account->description = $this->description;
        $account->save();

        $this->dispatchBrowserEvent('hide-accountModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Account Created Successfully!!"
        ]);

        // return redirect()->route('accounts.index');

    //     }
    //     catch(\Exception $e){
    //     // Set Flash Message
    //     $this->dispatchBrowserEvent('alert',[
    //         'type'=>'error',
    //         'message'=>"Something goes wrong while creating account!!"
    //     ]);
    // }
    }

    public function edit($id){
    $account = Account::find($id);
    $this->user_id = $account->user_id;
    $this->name = $account->name;
    $this->account_type_id = $account->account_type_id;
    $this->selectedCurrency = $account->currency_id;
    $this->bank_account_id = $account->bank_account_id;
    $this->customer_id = $account->customer_id;
    $this->description = $account->description;
    $this->account_reference = $account->account_reference;
    $this->account_id = $account->id;
    $this->dispatchBrowserEvent('show-accountEditModal');

    }


    public function update()
    {
        if ($this->account_id) {
            try{
            $account = Account::find($this->account_id);
            $account->user_id = Auth::user()->id;
            $account->name = $this->name;
            $account->currency_id = $this->selectedCurrency;
            $account->bank_account_id = $this->bank_account_id ? $this->bank_account_id : null;
            $account->customer_id = $this->customer_id ? $this->customer_id : null;
            $account->account_type_id = $this->account_type_id;
            $account->update();
            
            $this->dispatchBrowserEvent('hide-accountEditModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Account Type Updated Successfully!!"
            ]);


            // return redirect()->route('accounts.index');
            }
            catch(\Exception $e){
            $this->dispatchBrowserEvent('hide-accountEditModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Something goes wrong while creating account!!"
            ]);
          }
        }
    }

    public function showTransaction($id){
        $this->account = Account::find($id);
        $this->account_id = $id;
        $this->dispatchBrowserEvent('show-transactionModal');

    }

    public function storeTransaction(){
        try{

        $account = Account::find($this->account_id); 
        $current_balance = $account->balance; 
        if ($this->transaction_type == "Withdrawal") {
          
            if ($current_balance >= $this->amount) {
                $cashflow = new CashFlow;
                $cashflow->user_id = Auth::user()->id;
                $cashflow->account_id = $this->account_id;
                $cashflow->date = $this->date;
                $cashflow->description = $this->description;
                $cashflow->notes = $this->notes;
                $cashflow->currency_id = $account->currency->id;
                $cashflow->transaction_type = $this->transaction_type;
                $cashflow->transaction_category = $this->transaction_category;
                $cashflow->amount = $this->amount;
                $cashflow->save();              
                $account->balance = $current_balance - $this->amount;
                $account->update();

                if(isset($this->receipt)){
                    $file = $this->receipt;
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
                $document->cash_flow_id = $cashflow->id;
                $document->category = 'cash_flow';
                $document->title = "Receipt";
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
               
              
        
                $this->dispatchBrowserEvent('hide-transactionModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Transaction Added Successfully!!"
                ]);
        
                return redirect()->route('accounts.show',$account->id);
            }else {

                $this->dispatchBrowserEvent('hide-transactionModal');
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'error',
                    'message'=>"Transaction failed, amount to withdraw exceeds floating balance!!"
                ]);
            }
            
        }elseif ($this->transaction_type == "Deposit") {
            $cashflow = new CashFlow;
            $cashflow->account_id = $this->account_id;
            $cashflow->date = $this->date;
            $cashflow->description = $this->description;
            $cashflow->notes = $this->notes;
            $cashflow->currency_id = $account->currency->id;
            $cashflow->transaction_type = $this->transaction_type;
            $cashflow->transaction_category = $this->transaction_category;
            $cashflow->amount = $this->amount;
            $cashflow->save();
            $account->balance = $current_balance + $this->amount;
            $account->update();

            if(isset($this->receipt)){
                $file = $this->receipt;
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
            $document->cash_flow_id = $cashflow->id;
            $document->category = 'cash_flow';
            $document->title = "Receipt";
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
           
          
    
            $this->dispatchBrowserEvent('hide-transactionModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Transaction Added Successfully!!"
            ]);
    
            return redirect()->route('accounts.show',$account->id);
        }
        

    

        }
        catch(\Exception $e){
        // Set Flash Message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'error',
            'message'=>"Something goes wrong while creating cargo!!"
        ]);
    }
}

    public function render()
    {
        $this->account_types =  AccountType::orderBy('created_at','asc')->get();
        $this->accounts = Account::latest()->get();
        $this->bank_accounts = BankAccount::where('currency_id',$this->selectedCurrency)->orderBy('name','asc')->get();
        $this->customers = Customer::orderBy('name','asc')->get();
        return view('livewire.accounts.index',[
            'accounts' => $this->accounts,
            'bank_accounts' => $this->bank_accounts,
            'customers' => $this->customers,
            'account_types' => $this->account_types,
        ]);
    }
}
