<?php

namespace App\Http\Livewire\Assets;


use Carbon\Carbon;
use App\Models\Asset;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Vendor;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Purchase;
use App\Models\Attribute;
use App\Models\Department;
use App\Models\VendorType;
use App\Models\AssetDetail;
use App\Models\AssetSerial;
use App\Models\AssetDocument;
use App\Models\CategoryValue;
use Livewire\WithFileUploads;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Create extends Component
{
    use WithFileUploads;


    public $purchases;
    public $selectedPurchase;
    public $purchase_products;
    public $currencies;
    public $currency_id;
    public $vendor_types;
    public $vendors;
    public $vendor_id;
    public $selectedVendorType;
    public $category_id;
    public $categories;
    public $selectedCategory;
    public $category_values;
    public $selectedCategoryValue;
    public $products;
    public $selectedProduct;
    public $purchase_date;
    public $qty = 1;
    public $rate = 0;
    public $value = 0;
    public $residual_value;
    public $life;
    public $depreciation_type;
    public $warranty_exp_date;
    public $condition;
    public $asset_number;
    public $serial_number;
    public $purchase_type;
    public $description;
    public $user_id;

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

    public function mount(){
        $this->products = Product::where('department','asset')->orderBy('name','asc')->get();
        $this->category_values = collect();
        $this->vendor_types = VendorType::orderBy('name','asc')->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
        $this->currencies = Currency::latest()->get();
        $this->categories = Category::orderBy('name','asc')->get();
        $this->purchases = Purchase::where('department','asset')->latest()->get();
    }

    public function assetNumber(){
       
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

            $asset = Asset::orderBy('id', 'desc')->first();

        if (!$asset) {
            $asset_number =  $initials .'A'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $asset->id + 1;
            $asset_number =  $initials .'A'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $asset_number;


    }

    public function updated($value){
        $this->validateOnly($value);
    }

    protected $rules = [
        // 'residual_value' => 'required',
        // 'currency_id' => 'required',
        // 'selectedPurchase' => 'required',
        'selectedProduct' => 'required',
        'selectedCategory' => 'required',
        'selectedCategoryValue' => 'required',
        // 'asset_number' => 'required',
        // 'serial_number' => 'required',
        // 'qty' => 'required',
        // 'condition' => 'required',
        // 'rate' => 'required',
        // 'value' => 'required',
        'purchase_date' => 'required',
        // 'purchase_type' => 'required',
        // 'life' => 'required',
        // 'warranty_exp_date' => 'required',
        // 'description' => 'nullable',
    ];
    public function updatedSelectedCategory($category)
    {
        if (!is_null($category) ) {
        $this->category_values = CategoryValue::where('category_id', $category)->orderBy('name','asc')->get();
        }
    }
  
    public function updatedSelectedCategoryValue($category_value)
    {
        if (!is_null($category_value) ) {
        $this->products = Product::where('category_value_id', $category_value)->orderBy('name','asc')->get();
        }
    }

    public function updatedSelectedPurchase($purchase)
    {
        if (!is_null($purchase) ) {
        $this->currency_id = Purchase::find($purchase)->currency_id;
        $this->vendor_id = Purchase::find($purchase)->vendor->id;
        $this->selectedVendorType = Vendor::find($this->vendor_id)->vendor_type_id;
        $this->purchase_products = Purchase::find($purchase)->purchase_products;
        }
    }

    public function updatedSelectedProduct($product)
    {
        if (!is_null($product) ) {
        $this->category_id = Product::find($product)->category_id;
        }
    }

    public function updatedSelectedVendorType($vendor_type_id)
    {
        if (!is_null($vendor_type_id) ) {
        $this->vendors = Vendor::where('vendor_type_id', $vendor_type_id)->latest()->get();
        }
    }

    public function store(){

        if (isset($this->serial_number)) {
            foreach ($this->serial_number as $key => $value) {

        $asset = new Asset;
        $asset->user_id = Auth::user()->id;
        $asset->purchase_id = $this->selectedPurchase;
        $asset->product_id = $this->selectedProduct;
        $asset->vendor_id = $this->vendor_id;
        $asset->vendor_type_id = $this->selectedVendorType;
        $asset->currency_id = $this->currency_id;
        $asset->category_id = $this->selectedCategory;
        $asset->category_value_id = $this->selectedCategoryValue;
        $asset->rate = $this->rate;
        $asset->residual_value = $this->residual_value;
        $asset->depreciation_type = $this->depreciation_type;
        $asset->purchase_date = $this->purchase_date;
        $asset->purchase_type = $this->purchase_type;
        $asset->condition = $this->condition;
        $asset->asset_number = $this->assetNumber();
        $asset->serial_number = $this->serial_number[$key];
        $asset->warranty_exp_date = $this->warranty_exp_date;
        $asset->life = $this->life;
        $asset->description = $this->description;
        $asset->status = '1';

        $asset->save();


            }
        }else {
            for ($i=0; $i < $this->qty ; $i++) { 

            $asset = new Asset;
            $asset->user_id = Auth::user()->id;
            $asset->purchase_id = $this->selectedPurchase;
            $asset->product_id = $this->selectedProduct;
            $asset->vendor_id = $this->vendor_id;
            $asset->vendor_type_id = $this->selectedVendorType;
            $asset->category_id = $this->selectedCategory;
            $asset->category_value_id = $this->selectedCategoryValue;
            $asset->currency_id = $this->currency_id;
            $asset->rate = $this->rate;
            $asset->residual_value = $this->residual_value;
            $asset->depreciation_type = $this->depreciation_type;
            $asset->purchase_date = $this->purchase_date;
            $asset->purchase_type = $this->purchase_type;
            $asset->condition = $this->condition;
            $asset->asset_number = $this->assetNumber();
            $asset->warranty_exp_date = $this->warranty_exp_date;
            $asset->life = $this->life;
            $asset->description = $this->description;
            $asset->status = '1';
            $asset->save();
            }
        }


        return redirect(route('assets.index'));
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Asset Created Successfully!!"
        ]);
    }

    public function render()
    {
        $this->products = Product::where('department','asset')->orderBy('name','asc')->get();
        $this->vendor_types = VendorType::orderBy('name','asc')->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
        $this->purchases = Purchase::where('department','asset')->latest()->get();

        return view('livewire.assets.create',[
            'products' => $this->products,
            'vendor_types' => $this->vendor_types,
            'vendors' => $this->vendors,
            'purchases' => $this->purchases,
        ]);
    }
}
