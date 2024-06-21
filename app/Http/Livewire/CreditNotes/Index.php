<?php

namespace App\Http\Livewire\CreditNotes;

use App\Models\Cargo;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Receipt;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CreditNote;
use App\Models\Destination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{
    use WithFileUploads;

    public $invoices;
    public $invoice_products;
    public $invoice;
    public $invoice_id;
    public $customers;
    public $currencies;
    public $currency_id;
    public $receipt_number;
    public $invoice_number;
    public $date;
    public $amount;
    public $receipt;
    public $balance;

    public function mount(){

         $departments = Auth::user()->employee->departments;
        foreach($departments as $department){
            $department_names[] = $department->name;
        }
        $roles = Auth::user()->roles;
        foreach($roles as $role){
            $role_names[] = $role->name;
        }
        $ranks = Auth::user()->employee->ranks;
        foreach($ranks as $rank){
            $rank_names[] = $rank->name;
        }
        if (in_array('Admin', $role_names) || in_array('Super Admin', $role_names)) {
            $this->credit_notes = CreditNote::latest()->get();
        } else {
            $this->credit_notes = CreditNote::where('user_id',Auth::user()->id)->latest()->get();
        }
        $this->customers = Customer::all();
        $this->currencies = Currency::all();
        $this->invoices = Invoice::latest()->get();

    }

    public function credit_noteNumber(){
       
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

            $credit_note = CreditNote::orderBy('id', 'desc')->first();

        if (!$credit_note) {
            $credit_note_number =  $initials .'FS'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $credit_note->id + 1;
            $credit_note_number =  $initials .'FS'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $credit_note_number;


    }
    private function resetInputFields(){
        $this->invoice_number = '';
        $this->receipt_number = '';
        $this->amount = '';
        $this->currency_id = '';
        $this->receipt = '';
    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'invoice_number' => 'required',
        'receipt_number' => 'required',
        'currency_id' => 'required',
        'receipt' => 'nullable|file',
    ];

    public function addReceipt($id){
        $this->invoice_id = $id;
        $this->invoice = Invoice::find($id);
        $this->invoice_number = $this->invoice->invoice_number;
        $this->receipt_number = $this->receiptNumber();
        $this->dispatchBrowserEvent('show-receiptModal');
    }
    public function storeReceipt(){

        if(isset($this->receipt)){
            $file = $this->receipt;
            // get file with ext
            $fileNameWithExt =  $file->getClientOriginalName();
            //get filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //get extention
            $extention =  $file->getClientOriginalExtension();
            //file name to store
            $fileNameToStore= $filename.'_'.time().'.'.$extention;
            $file->storeAs('/receipts', $fileNameToStore, 'my_files');

        }
        $invoice = $this->invoice;
        $receipts = Receipt::where('invoice_id',$invoice->id )->get();
        $old_receipt = Receipt::where('invoice_id',$invoice->id )->latest()->first();

        $receipt = new Receipt;
        $receipt->user_id = Auth::user()->id;
        $receipt->invoice_id = $this->invoice_id;
        $receipt->invoice_number = $this->invoice_number;
        $receipt->receipt_number = $this->receiptNumber();
        $receipt->currency_id = $this->currency_id;
        $receipt->amount = $this->amount;
        $receipt->date = $this->date;

        if (isset($old_receipt)) {
        $receipt->balance = $old_receipt->balance - $this->amount ;
        }else{
        $receipt->balance = $invoice->total - $this->amount;
        }

        if (isset($fileNameToStore)) {
        $receipt->filename = $fileNameToStore;
        }
        $receipt->save();

        $this->dispatchBrowserEvent('hide-receiptModal');
        $this->resetInputFields();
        return redirect(route('receipts.index'));
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Receipt Added Successfully!!"
        ]);


    }

    public function render()
    {
        return view('livewire.credit-notes.index');
    }
}
