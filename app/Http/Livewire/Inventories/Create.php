<?php

namespace App\Http\Livewire\Inventories;


use App\Models\Brand;
use App\Models\Store;
use App\Models\Branch;
use App\Models\Vendor;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\Purchase;
use App\Models\Attribute;
use App\Models\HorseMake;
use App\Models\Inventory;
use App\Models\Department;
use App\Models\HorseModel;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\CategoryValue;
use Livewire\WithFileUploads;
use App\Models\AttributeValue;
use App\Models\InventorySerial;
use App\Models\ProductAttribute;
use App\Models\InventoryDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Create extends Component
{
    use WithFileUploads;


    public $stores;
    public $store_id;
    public $currencies;
    public $currency_id;
    public $category_id;
    public $vendors;
    public $vendor_id;
    public $weight;
    public $measurement;
    public $products;
    public $selectedProduct;
    public $purchase_date;
    public $qty;
    public $rate;
    public $value;
    public $residual_value;
    public $life;
    public $depreciation_type;
    public $warranty_exp_date;
    public $condition;
    public $inventory_number;
    public $purchase_products;
    public $selectedPurchase;
    public $purchases;
    public $part_number;
    public $serial_number;
    public $purchase_type;
    public $description;
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

    public function updatedSelectedPurchase($purchase){
        if (!is_null($purchase)) {
            $this->vendor_id = Purchase::find($purchase)->vendor->id;
            $this->currency_id = Purchase::find($purchase)->currency->id;
            $this->purchase_products = Purchase::find($purchase)->purchase_products;
        }
      
    }

    public function mount(){
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
        $this->vehicle_makes = VehicleMake::all();
        $this->horse_makes = HorseMake::all();
        $this->horse_models = HorseModel::all();
        $this->vehicle_models = VehicleModel::all();
    }


  public function inventoryNumber(){
       
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

            $inventory = Inventory::orderBy('id', 'desc')->first();

        if (!$inventory) {
            $inventory_number =  $initials .'I'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $inventory->id + 1;
            $inventory_number =  $initials .'I'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $inventory_number;


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
        // 'currency_id' => 'required',
        'selectedProduct' => 'required',
        // 'vendor_id' => 'required',
        // 'inventory_number' => 'required',
        'part_number' => 'required',
        'selectedPurchase' => 'required',
        'qty' => 'required',
        // 'condition' => 'required',
        // 'rate' => 'required',
        'value' => 'required',
        'purchase_date' => 'required',
        // 'purchase_type' => 'required',
        // 'life' => 'required',
        // 'warranty_exp_date' => 'required',
        // 'description' => 'nullable',
    ];

    public function updatedSelectedProduct($product)
    {
        if (!is_null($product) ) {
        $this->category_id = Product::find($product)->category_id;
        }
    }

    public function store(){
    
        if (isset($this->selectedProduct)) {
        foreach ($this->selectedProduct as $key => $value) {

        if (isset($this->qty[$key])) {

            for ($i=0; $i < $this->qty[$key] ; $i++) { 

                $inventory = new Inventory;
                $inventory->user_id = Auth::user()->id;
                $inventory->vendor_id = $this->vendor_id ? $this->vendor_id : NULL;
                $inventory->currency_id = $this->currency_id ? $this->currency_id : null;

                if (isset($this->selectedProduct[$key])) {
                    $inventory->product_id = $this->selectedProduct[$key];
                }
                if (isset($this->part_number[$key])) {
                    $inventory->part_number = $this->part_number[$key];
                }
                if (isset($this->serial_number[$key])) {
                    $inventory->serial_number = $this->serial_number[$key];
                }
                if (isset($this->rate[$key])) {
                    $inventory->rate = $this->rate[$key];
                }
                $inventory->qty = 1;
                if (isset($this->rate[$key])) {
                    $inventory->value = 1 * $this->rate[$key];
                }
               
                $inventory->residual_value = $this->residual_value;
                $inventory->horse_make_id = $this->selectedHorseMake ? $this->selectedHorseMake : null;
                $inventory->horse_model_id = $this->horse_model_id ? $this->horse_model_id : null;
                $inventory->vehicle_make_id = $this->selectedVehicleMake ? $this->selectedVehicleMake : null;
                $inventory->vehicle_model_id = $this->vehicle_model_id ? $this->vehicle_model_id : null;
                $inventory->store_id = $this->store_id ? $this->store_id : null;
                $inventory->depreciation_type = $this->depreciation_type;
                $inventory->purchase_date = $this->purchase_date;
                $inventory->measurement = $this->measurement;
                $inventory->weight = $this->weight;
                $inventory->balance = $this->weight;
                $inventory->purchase_type = $this->purchase_type;
                $inventory->purchase_id = $this->selectedPurchase ? $this->selectedPurchase : null;
                $inventory->condition = $this->condition;
                $inventory->inventory_number = $this->inventoryNumber();
                $inventory->warranty_exp_date = $this->warranty_exp_date;
                $inventory->life = $this->life;
                $inventory->description = $this->description;
                $inventory->status = '1';
                $inventory->save();

            }

          }
        }
        Session::flash('success','Invetory Item(s) Added Successfully!!');
        return redirect(route('inventories.index'));
       
        

        }else {
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Select Product(s) to continue!!"
            ]);
        }



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
        return view('livewire.inventories.create',[
            'products' => $this->products,
            'purchases' => $this->purchases,
            'vendors' => $this->vendors,
            'currencies' => $this->currencies,
            'stores' => $this->stores,
        ]);
    }
}
