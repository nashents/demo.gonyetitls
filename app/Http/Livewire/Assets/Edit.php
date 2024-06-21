<?php

namespace App\Http\Livewire\Assets;

use App\Models\Asset;
use App\Models\Vendor;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Purchase;
use App\Models\VendorType;
use App\Models\CategoryValue;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
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

    public function mount($asset){
        $this->products = Product::where('department','asset')->orderBy('name','asc')->get();
        $this->category_values = CategoryValue::orderBy('name','asc')->get();
        $this->vendor_types = VendorType::orderBy('name','asc')->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
        $this->currencies = Currency::latest()->get();
        $this->categories = Category::orderBy('name','asc')->get();
        $this->purchases = Purchase::where('department','asset')->latest()->get();
        $this->vendor_id = $asset->vendor_id;
        $this->selectedVendorType = $asset->vendor_type_id;
        $this->currency_id = $asset->currency_id;
        $this->selectedProduct = $asset->product_id;
        $this->selectedCategory = $asset->category_id;
        $this->selectedCategoryValue = $asset->category_value_id;
        $this->purchase_date = $asset->purchase_date;
        $this->purchase_id = $asset->purchase_id;
        $this->qty = $asset->qty;
        $this->value = $asset->value;
        $this->rate = $asset->rate;
        $this->residual_value = $asset->residual_value;
        $this->purchase_type = $asset->purchase_type;
        $this->description = $asset->description;
        $this->depreciation_type = $asset->depreciation_type;
        $this->asset_number = $asset->asset_number;
        $this->serial_number = $asset->serial_number;
        $this->condition = $asset->condition;
        $this->warranty_exp_date = $asset->warranty_exp_date;
        $this->life = $asset->life;
        $this->asset_id = $asset->id;
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

    public function updated($value){
        $this->validateOnly($value);
    }

    protected $rules = [
        // 'residual_value' => 'required',
        // 'currency_id' => 'required',
        'selectedProduct' => 'required',
        // 'vendor_id' => 'required',
        'asset_number' => 'required',
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



    public function update(){

        $asset = Asset::find($this->asset_id);
        $asset->user_id = Auth::user()->id;
        $asset->vendor_id = $this->vendor_id;
        $asset->vendor_type_id = $this->selectedVendorType;
        $asset->product_id = $this->selectedProduct;
        $asset->category_id = $this->selectedCategory;
        $asset->category_value_id = $this->selectedCategoryValue;
        $asset->currency_id = $this->currency_id;
        $asset->rate = $this->rate;
        $asset->value = $this->rate;
        $asset->residual_value = $this->residual_value;
        $asset->depreciation_type = $this->depreciation_type;
        $asset->purchase_date = $this->purchase_date;
        $asset->purchase_type = $this->purchase_type;
        $asset->condition = $this->condition;
        $asset->asset_number = $this->asset_number;
        $asset->serial_number = $this->serial_number;
        $asset->warranty_exp_date = $this->warranty_exp_date;
        $asset->life = $this->life;
        $asset->description = $this->description;
        $asset->status = '1';

        $asset->update();


        return redirect(route('assets.index'));
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Asset Updated Successfully!!"
        ]);
    }

    public function render()
    {
        $this->products = Product::where('department','asset')->orderBy('name','asc')->get();
        $this->vendor_types = VendorType::orderBy('name','asc')->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
        $this->purchases = Purchase::where('department','asset')->latest()->get();
        return view('livewire.assets.edit',[
            'products' => $this->products,
            'vendor_types' => $this->vendor_types,
            'vendors' => $this->vendors,
            'purchases' => $this->purchases,
        ]);
    }
}
