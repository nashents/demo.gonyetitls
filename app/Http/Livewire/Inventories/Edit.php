<?php

namespace App\Http\Livewire\Inventories;

use App\Models\Store;
use App\Models\Vendor;
use App\Models\Product;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Purchase;
use App\Models\HorseMake;
use App\Models\Inventory;
use App\Models\HorseModel;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Edit extends Component
{
    use WithFileUploads;


    
    public $stores;
    public $store_id;
    public $currencies;
    public $currency_id;
    public $category_id;
    public $vendors;
    public $vendor_id;
    public $products;
    public $selectedProduct;
    public $purchase_date;
    public $qty;
    public $rate;
    public $value;
    public $weight;
    public $measurement;
    public $residual_value;
    public $life;
    public $depreciation_type;
    public $warranty_exp_date;
    public $condition;
    public $inventory_number;
    public $selectedPurchase;
    public $purchase_products;
    public $purchases;
    public $part_number;
    public $serial_number;
    public $purchase_type;
    public $description;
    public $balance;
    public $status;
    public $user_id;

    public $horse_makes;
    public $horse_make_id;
    public $horse_models;
    public $horse_model_id;
    public $vehicle_models;
    public $vehicle_model_id;
    public $vehicle_makes;
    public $selectedVehicleMake;
    public $selectedHorseMake;

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

    public function mount($inventory){
        $this->products = Product::orderBy('name','asc')->get();
        $this->purchases = Purchase::where('department','inventory')->latest()->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
        $this->currencies = Currency::latest()->get();
        $this->vehicle_makes = VehicleMake::orderBy('name','asc')->get();
        $this->horse_makes = HorseMake::orderBy('name','asc')->get();
        $this->horse_models = HorseModel::orderBy('name','asc')->get();
        $this->vehicle_models = VehicleModel::orderBy('name','asc')->get();
        $this->vendor_id = $inventory->vendor_id;
        $this->currency_id = $inventory->currency_id;
        $this->balance = $inventory->balance;
        $this->stores = Store::latest()->get();
        $this->selectedProduct = $inventory->product_id;
        $this->selectedHosrseMake = $inventory->horse_make_id;
        $this->selectedVehicleMake = $inventory->vehicle_make_id;
        $this->horse_model_id = $inventory->horse_model_id;
        $this->vehicle_model_id = $inventory->vehicle_model_id;
        $this->category_id = $inventory->category_id;
        $this->purchase_date = $inventory->purchase_date;
        $this->qty = $inventory->qty;
        $this->value = $inventory->value;
        $this->weight = $inventory->weight;
        $this->measurement = $inventory->measurement;
        $this->store_id = $inventory->store_id;
        $this->status = $inventory->status;
        $this->rate = $inventory->rate;
        $this->residual_value = $inventory->residual_value;
        $this->purchase_type = $inventory->purchase_type;
        $this->description = $inventory->description;
        $this->depreciation_type = $inventory->depreciation_type;
        $this->inventory_number = $inventory->inventory_number;
        $this->part_number = $inventory->part_number;
        $this->serial_number = $inventory->serial_number;
        $this->selectedPurchase = $inventory->purchase_id;
        $purchase_order = Purchase::find($this->selectedPurchase);
        if (isset($purchase_order)) {
            $this->purchase_products = $purchase_order->$purchase_products;
        }
        $this->condition = $inventory->condition;
        $this->warranty_exp_date = $inventory->warranty_exp_date;
        $this->life = $inventory->life;
        $this->inventory_id = $inventory->id;
    }

    public function updatedSelectedPurchase($purchase){
        if (!is_null($purchase)) {
            $this->vendor_id = Purchase::find($purchase)->vendor->id;
            $this->currency_id = Purchase::find($purchase)->currency->id;
        }
      
    }

    public function updatedSelectedVehicleMake($vehicle_make){
        if (!is_null($vehicle_make)) {
            $this->vehicle_models = VehicleModel::where('vehicle_make_id', $vehicle_make)->get();
        }
    }
    public function updatedSelectedHorseMake($horse_make){
        if (!is_null($horse_make)) {
            $this->horse_models = HorseModel::where('horse_make_id', $horse_make)->get();
        }
    }
    public function updated($value){
        $this->validateOnly($value);
    }

    protected $rules = [
        // 'residual_value' => 'required',
        'currency_id' => 'required',
        'selectedProduct' => 'required',
        // 'vendor_id' => 'required',
        // 'inventory_number' => 'required',
        'part_number' => 'required',
        // 'store_id' => 'required',
        // 'selectedPurchase' => 'required',
        'qty' => 'required',
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

        $inventory = inventory::find($this->inventory_id);
        $inventory->user_id = Auth::user()->id;
        $inventory->vendor_id = $this->vendor_id ? $this->vendor_id : null;
        $inventory->store_id = $this->store_id ? $this->store_id : null;
        $inventory->product_id = $this->selectedProduct ? $this->selectedProduct : null;
        $inventory->currency_id = $this->currency_id ?  $this->currency_id : null;
        $inventory->rate = $this->rate;
        $inventory->qty = $this->qty;
        $inventory->measurement = $this->measurement;
        $inventory->weight = $this->weight;
        $inventory->balance = $this->balance;
        $inventory->residual_value = $this->residual_value;
        $inventory->depreciation_type = $this->depreciation_type;
        $inventory->purchase_date = $this->purchase_date;
        $inventory->purchase_type = $this->purchase_type;
        $inventory->purchase_id = $this->selectedPurchase ? $this->selectedPurchase : null;
        $inventory->horse_make_id = $this->selectedHorseMake ? $this->selectedHorseMake : null;
        $inventory->horse_model_id = $this->horse_model_id ? $this->horse_model_id : null;
        $inventory->vehicle_make_id = $this->selectedVehicleMake ? $this->selectedVehicleMake : null;
        $inventory->vehicle_model_id = $this->vehicle_model_id ? $this->vehicle_model_id : null;
        $inventory->condition = $this->condition;
        $inventory->part_number = $this->part_number;
        $inventory->serial_number = $this->serial_number;
        $inventory->warranty_exp_date = $this->warranty_exp_date;
        $inventory->life = $this->life;
        $inventory->description = $this->description;
        $inventory->status = $this->status;
        $inventory->update();

        Session::flash('success','Invetory Updated Successfully!!');
        return redirect(route('inventories.index'));
       
    }

    public function render()
    {
        $this->products = Product::where('department','inventory')->orderBy('name','asc')->get();
        $this->purchases = Purchase::where('authorization','approved')
        ->where('department','inventory')->latest()->get();
        $this->vendors = Vendor::orderBy('name','asc')->get();
    //     $value = 'Auto Parts';
    //     $this->vendors = Vendor::with(['vendor_type'])
    //    ->whereHas('vendor_type', function($q) use($value) {
    //    $q->where('name', '=', $value);
    //     })->get();
        $this->currencies = Currency::orderBy('name','asc')->get();
        $this->stores = Store::orderBy('name','asc')->get();
        return view('livewire.inventories.edit',[
            'products' => $this->products,
            'purchases' => $this->purchases,
            'vendors' => $this->vendors,
            'currencies' => $this->currencies,
            'stores' => $this->stores,
        ]);
    }
}
