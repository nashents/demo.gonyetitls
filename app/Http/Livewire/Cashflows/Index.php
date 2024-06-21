<?php

namespace App\Http\Livewire\Cashflows;

use Carbon\Carbon;
use App\Models\Horse;
use App\Models\Vendor;
use App\Models\Account;
use App\Models\Expense;
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
use Illuminate\Support\Facades\Session;

class Index extends Component
{
    use WithFileUploads;

    public $cashflows;
    public $cashflow;
    public $horses;
    public $horse_id;
    public $selectedType;
    public $selectedCategory;
    public $selectedFrom;
    public $selectedTo;
    public $comments;
    public $description;
    public $notes;
    public $transaction_category;
    public $transaction_type;
    public $bank_accounts;
    public $bank_account_id;
    public $account_types;
    public $account_type_id;
    public $account_type;
    public $accounts;
    public $account;
    public $account_id;
    public $customer = null;
    public $vendor = null;
    public $selectedAccount;
    public $vendors;
    public $vendor_id;
    public $expenses;
    public $expense_id;
    public $customers;
    public $customer_id;
   
    public $receipt;
    public $invoice;
   
    public $amount;
    public $currency_id;
    public $date;

    public function mount(){
       
        $this->selectedAccount = "All";

        if ($this->selectedAccount == "All") {
            $this->cashflows = CashFlow::all();
        }else{
            $this->cashflows = collect();
        }
       
        $this->accounts = Account::orderBy('name','asc')->get();
        $this->customers = Customer::orderBy('name','asc')->get();
        $this->expenses = Expense::orderBy('name','asc')->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
        $this->currencies = Currency::orderBy('name','asc')->get();
        $this->bank_accounts = BankAccount::orderBy('name','asc')->get();
        $this->account_types =  AccountType::orderBy('created_at','asc')->get();
        $this->horses = Horse::latest()->get();
       
        $this->from = null;
        $this->to = null;
        $this->category = null;
        $this->type = null;
    }
    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'invoice' => 'required|file',
    ];

    private function resetInputFields(){
        $this->invoice = '';
    }

    public function updatedSelectedAccount($account){
        if (!is_null($account)) {
            if ($account == "All") {
                $this->cashflows = CashFlow::all();
            }else {
                $this->cashflows = Cashflow::where('account_id', $account)->orderBy('created_at','desc')->get();
            }
          
        }
      
    }
    public function updatedSelectedType($type){
        if (!is_null($type)) {
            if (isset($this->selectedCategory)) {
                $this->cashflows = Cashflow::where('type', $type)
                                           ->where('category', $this->selectedCategory)->get();
            }else {
                $this->cashflows = Cashflow::where('type', $type)->get();
            }
          
        }
      
    }

    public function delete($id){
        $this->cashflow = CashFlow::find($id);
        $this->dispatchBrowserEvent('show-cashflowDeleteModal');
    }
    public function deleteTransaction(){
        $this->cashflow->delete();
        $this->dispatchBrowserEvent('hide-cashflowDeleteModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Transaction Deleted Successfully!!"
        ]);
       
    }

    public function storeIncomeTransaction(){

        $account = Account::find($this->account_id);
        $current_balance = $account->balance;

        $cashflow = new CashFlow;
        $cashflow->user_id = Auth::user()->id;
        $cashflow->account_id = $this->account_id;
        if ($this->customer == TRUE) {
            $cashflow->customer_id = $this->customer_id;
        }
     
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
       
        $this->dispatchBrowserEvent('hide-incomeModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Income Transaction Added Successfully!!"
        ]);

        return redirect(request()->header('Referer'));
       
    }
    public function storeExpenseTransaction(){

        $account = Account::find($this->account_id);
        $current_balance = $account->balance;

        if ($current_balance > $this->amount) {
        $account->balance = $current_balance - $this->amount;
        $account->update();

        $cashflow = new CashFlow;
        $cashflow->user_id = Auth::user()->id;
        $cashflow->account_id = $this->account_id;
        if ($this->customer == TRUE) {
            $cashflow->customer_id = $this->customer_id;
        }
        if ($this->vendor == TRUE) {
            $cashflow->vendor_id = $this->vendor_id;
        }
        $cashflow->date = $this->date;
        $cashflow->expense_id = $this->expense_id;
        $cashflow->notes = $this->notes;
        $cashflow->currency_id = $account->currency->id;
        $cashflow->transaction_type = $this->transaction_type;
        $cashflow->transaction_category = $this->transaction_category;
        $cashflow->amount = $this->amount;
        $cashflow->save();     
        
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
       
        $this->dispatchBrowserEvent('hide-expenseModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>" Expense Transaction Added Successfully!!"
        ]);

        return redirect(request()->header('Referer'));

    }else {
        $this->dispatchBrowserEvent('hide-transactionModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Transaction failed, amount to withdraw exceeds account floating balance!!"
        ]);
        return redirect(request()->header('Referer'));
    }
    }

    public function search(){
        if (isset($this->selectedCategory) && $this->selectedType == NULL) {
            $this->cashflows = Cashflow::whereBetween('created_at',[$this->from, $this->to] )
            ->where('category', $this->selectedCategory)->latest()->get();
        }elseif (isset($this->selectedType) && $this->selectedCategory == NULL) {
            $this->cashflows = Cashflow::whereBetween('created_at',[$this->from, $this->to] )
            ->where('type', $this->selectedType)->latest()->get();
        }elseif (isset($this->selectedCategory) && isset($this->selectedType)) {
            $this->cashflows = Cashflow::whereBetween('created_at',[$this->from, $this->to] )
            ->where('type', $this->selectedType)
            ->where('category', $this->selectedCategory)->latest()->get();
        }else{
            $this->cashflows = Cashflow::whereBetween('created_at',[$this->from, $this->to] )->latest()->get();
        }
       
    }
    public function updatedSelectedCategory($category){
        if (!is_null($category)) {
            if (isset($this->selectedType)) {
                $this->cashflows = Cashflow::where('category', $category)
                                           ->where('type', $this->selectedType)->get();
            }else {
                $this->cashflows = Cashflow::where('category', $category)->get();
            }
        }
      
    }

    public function update($id){
        $cashflow = CashFlow::find($id);
        $this->cashflow_id = $cashflow->id;
        $this->dispatchBrowserEvent('show-receiptUploadModal');

        }
        public function uploadInvoice(){
            $this->receiptUpload = 1;
            if($this->invoice){
                $file = $this->invoice;
                // get file with ext
                $fileNameWithExt = $file->getClientOriginalName();
                //get filename
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                //get extention
                $extention = $file->getClientOriginalExtension();
                //file name to store
                $fileNameToStore = $filename.'_'.time().'.'.$extention;
                $file->storeAs('/documents', $fileNameToStore, 'my_files');

            }
        $cashflow = CashFlow::find( $this->cashflow_id);
        $cashflow->user_id = Auth::user()->id;
        if (isset($fileNameToStore)) {
            $cashflow->invoice = $fileNameToStore;
        }
        $cashflow->update();
        $this->dispatchBrowserEvent('hide-receiptUploadModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Invoice Uploaded Successfully!!"
        ]);
        }

    public function render()
    {
        // $this->cashflows = CashFlow::all();
        return view('livewire.cashflows.index',[
            'cashflows' => $this->cashflows
        ]);
    }
}
