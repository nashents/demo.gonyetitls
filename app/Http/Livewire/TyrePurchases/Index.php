<?php

namespace App\Http\Livewire\TyrePurchases;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\Account;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Document;
use App\Models\Purchase;
use App\Models\VendorType;
use App\Models\CategoryValue;
use Livewire\WithFileUploads;
use App\Models\PurchaseProduct;
use App\Models\PurchaseDocument;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithFileUploads;

    public $purchase_number;
    public $purchases;
    public $purchase_id;
    public $currencies;
    public $currency_id;
    public $category_values;
    public $categories;
    public $selectedCategory;
    public $selectedCategoryValue;
    public $value = 0;
    public $description;
    public $department;
    public $date;

    public $booking_id;
    public $bookings;
    
    public $accounts;
    public $selectedAccount;
    public $expenses;
    public $expense_id;

    public $vendor_id;
    public $vendors;
    public $vendor_types;
    public $selectedVendorType;


    public $title;
    public $vendor;
    public $file;
    public $expires_at;

    public $products;
    public $product_id;
    public $qty = 1;
    public $rate;

    public $inputs = [];
    public $i = 1;
    public $n = 1;

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);
    }
    private function resetInputFields(){
        $this->title = '';
        $this->file = '';
        $this->vendor = '';
    }

    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    
    public $documentInputs = [];
    public $m = 1;
    public $o = 1;
    public function documentsAdd($m)
    {
        $m = $m + 1;
        $this->m = $m;
        array_push($this->documentInputs ,$m);
    }

    public function documentsRemove($m)
    {
        unset($this->documentInputs[$m]);
    }


    public function purchaseNumber(){

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

            $purchase = Purchase::orderBy('id', 'desc')->first();

        if (!$purchase) {
            $purchase_number =  $initials .'PO'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $purchase->id + 1;
            $purchase_number =  $initials .'PO'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $purchase_number;


    }

    public function mount($category){

        $this->department = $category;
        $this->products = collect();

        $this->purchases = Purchase::where('department',$this->department)->latest()->get();
        $this->bookings = Booking::where('authorization','approved')->where('status',1)->latest()->get();
        $this->vendor_types = VendorType::latest()->get();
        $this->accounts = Account::whereHas('account_type.account_type_group', function ($query) {
            return $query->where('name','Expenses');
        })->get();
        $this->expenses = Expense::orderBy('name','asc')->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
        $this->currencies = Currency::all();
        $this->categories = Category::latest()->get();
        $this->category_values = collect();
        $this->purchase_number = $this->purchaseNumber();
    }

   
    public function updatedSelectedAccount($id)
    {
        if (!is_null($id) ) {
        $this->expenses = Expense::where('account_id', $id)->orderBy('name','asc')->get();
        }
    }
    public function updatedSelectedCategory($category)
    {
        if (!is_null($category) ) {
        $this->category_values = CategoryValue::where('category_id', $category)->orderBy('name','asc')->get();
        $this->products = Product::where('category_id', $category)->orderBy('product_number','asc')->get();
        }
    }
    public function updatedSelectedCategoryValue($id)
    {
        if (!is_null($id) ) {
            $this->products = Product::where('category_value_id', $id)->orderBy('product_number','asc')->get();
        }
    }
    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'date' => 'required',
        'currency_id' => 'required',
        'selectedVendorType' => 'required',
        'vendor_id' => 'required',
        'purchase_number' => 'required',
        'selectedCategory' => 'required',
        'selectedCategoryValue' => 'required',
        'value' => 'required',
        'product_id.0' => 'required',
        'rate.0' => 'required',
        'qty.0' => 'required',
        'product_id.*' => 'required',
        'rate.*' => 'required',
        'qty.*' => 'required',
    ];
  

    public function store(){
        $purchase = new Purchase;
        $purchase->user_id = Auth::user()->id;
        $purchase->purchase_number = $this->purchase_number;
        $purchase->date = $this->date;
        $purchase->department = $this->department;
        $purchase->booking_id = $this->booking_id;
        $purchase->description = $this->description;
        $purchase->account_id = $this->selectedAccount;
        $purchase->expense_id = $this->expense_id;
        $purchase->category_id = $this->selectedCategory;
        $purchase->category_value_id = $this->selectedCategoryValue;
        $purchase->vendor_id = $this->vendor_id;
        $purchase->vendor_type_id = $this->selectedVendorType;
        $purchase->currency_id = $this->currency_id;
        $purchase->status = '1';
        $purchase->save();
        $this->purchase_id = $purchase->id;

        if (isset($this->product_id)) {
            foreach ($this->product_id as $key => $value) {
              $product = new PurchaseProduct;
              $product->purchase_id = $purchase->id;
              if (isset($this->product_id[$key])) {
                $product->product_id = $this->product_id[$key];
              }
              if (isset($this->rate[$key])) {
                $product->rate = $this->rate[$key];
              }
              if (isset($this->qty[$key])) {
                $product->qty = $this->qty[$key];
              }
              if ((isset($this->qty[$key]) && $this->qty[$key] > 0) && (isset($this->rate[$key]) && $this->rate[$key] > 0)) {
                $product->value = $this->qty[$key] * $this->rate[$key];
              }
             
              $product->save();

              if ((isset($this->qty[$key]) && $this->qty[$key] > 0) && (isset($this->rate[$key]) && $this->rate[$key] > 0)) {
              $this->value = $this->value + ($this->rate[$key] * $this->qty[$key]);
              }

            }

          }

          $purchase = Purchase::find($this->purchase_id);
          $purchase->value = $this->value;
          $purchase->update();

          
        if (isset($this->file) && isset($this->title) && $this->file != "") {
    
            foreach ($this->file as $key => $value) {
              if(isset($this->file[$key])){
                  $file = $this->file[$key];
                  // get file with ext
                  $fileNameWithExt = $file->getClientOriginalName();
                  //get filename
                  $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                  //get extention
                  $extention = $file->getClientOriginalExtension();
                  //file name to store
                  $fileNameToStore= $filename.'_'.time().'.'.$extention;
                  $file->storeAs('/documents', $fileNameToStore, 'my_files');

              }
              $document = new Document;
              $document->purchase_id = $purchase->id;
              $document->category = 'purchase';
              if(isset($this->title[$key])){
              $document->title = $this->title[$key];
              }
              if (isset($fileNameToStore)) {
                  $document->filename = $fileNameToStore;
              }
              if(isset($this->expires_at[$key])){
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
    
        }


          $this->dispatchBrowserEvent('hide-purchaseModal');
          $this->dispatchBrowserEvent('alert',[
              'type'=>'success',
              'message'=>"Purchase Order Created Successfully!!"
          ]);
    }

    public function quotation($id){
        $purchase = Purchase::find($id);
        $this->purchase_id = $purchase->id;
        $this->dispatchBrowserEvent('show-purchaseQuotationsModal');
    }
    

    public function edit($id){
        $purchase = Purchase::find($id);
        $this->purchase_number = $purchase->purchase_number;
        $this->date = $purchase->date;
        $this->currency_id = $purchase->currency_id;
        $this->vendor_id = $purchase->vendor_id;
        $this->booking_id = $purchase->booking_id;
        $this->selectedVendorType = $purchase->vendor_type_id;
        $this->selectedAccount = $purchase->account_id;
        $this->expense_id = $purchase->expense_id;
        $this->selectedCategory = $purchase->category_id;
        $this->status = $purchase->status;
        $this->description = $purchase->description;
        $this->products = $purchase->purchase_products;
        $this->purchase_id = $purchase->id;
        $this->dispatchBrowserEvent('show-purchaseEditModal');
    }
    public function update(){
        
        if ($this->purchase_id) {
            $purchase = Purchase::find($this->purchase_id);
            $purchase->user_id = Auth::user()->id;
            $purchase->purchase_number = $this->purchase_number;
            $purchase->date = $this->date;
            $purchase->description = $this->description;
            $purchase->account_id = $this->selectedAccount;
            $purchase->expense_id = $this->expense_id;
            $purchase->booking_id = $this->booking_id;
            $purchase->vendor_id = $this->vendor_id;
            $purchase->vendor_type_id = $this->selectedVendorType;
            $purchase->category_id = $this->selectedCategory;
            $purchase->currency_id = $this->currency_id;
            $purchase->status = '1';
            $purchase->update();
    
              $this->dispatchBrowserEvent('hide-purchaseEditModal');
              $this->dispatchBrowserEvent('alert',[
                  'type'=>'success',
                  'message'=>"Purchase Order Updated Successfully!!"
              ]);
            }
    }
    public function render()
    {
        $this->purchases = Purchase::where('department',$this->department)->latest()->get();
        $this->vendors = Vendor::orderBy('name', 'asc')->get();
        return view('livewire.tyre-purchases.index',[
            'purchases' => $this->purchases,
            'vendors' => $this->vendors,

        ]);
    }
}
