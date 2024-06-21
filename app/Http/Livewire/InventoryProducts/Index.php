<?php

namespace App\Http\Livewire\InventoryProducts;

use App\Models\Brand;
use App\Models\Store;
use App\Models\Value;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class Index extends Component
{

    public $products;

    public function mount(){
        $this->products = Product::where('department','inventory')->latest()->get();
    }

    public function render()
    {
        return view('livewire.inventory-products.index');
    }
}
