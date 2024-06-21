<?php

namespace App\Http\Livewire\Products;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\CategoryValue;
use Livewire\WithFileUploads;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    use WithFileUploads;

    public $brands;
    public $categories;
    public $attributes;
    public $attribute_values;
    public $selectedCategory = NULL;
    public $selectedCategoryValue = NULL;
    public $category_values;
    public $brand_id;
    public $status;
    public $name;
    public $department;
    public $model;
    public $serial_number;
    public $manufacturer;
    public $description;
    public $image;
    public $user_id;
    public $product;
    public $product_id;
    public $previous_image;



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

    public function mount($product){
        $product_attribute = $product->product_attributes->first();
        $this->brands = Brand::all();
        $this->categories = Category::all();
        $this->category_values = CategoryValue::all();
        $this->selectedCategory = $product->category_id;
        $this->selectedCategoryValue = $product->category_value_id;
        $this->name = $product->name;
        $this->model = $product->model;
        $this->department = $product->department;
        $this->brand_id = $product->brand_id;
        $this->manufacturer = $product->manufacturer;
        $this->description = $product->description;
        $this->previous_image = $product->filename;
        $this->product_id = $product->id;
    }


    public function updatedSelectedCategory($category)
    {
        if (!is_null($category) ) {
        $this->category_values = CategoryValue::where('category_id', $category)->get();
        }
    }
    public function updatedSelectedCategoryValue($category_value)
    {
        if (!is_null($category_value) ) {
        $this->brands = Brand::where('category_value_id', $category_value)->get();
        $this->attributes = Attribute::where('category_value_id', $category_value)->get();
        }
    }
    

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'selectedCategory' => 'required',
        'selectedCategoryValue' => 'required',
        'brand_id' => 'required',
        'manufacturer' => 'required',
        'image' => 'nullable|image',
        'description' => 'nullable',
        'serial_number' => 'required',
        'name' => 'required|unique:products,name,NULL,id,deleted_at,NULL',
        'model' => 'required|unique:products,model,NULL,id,deleted_at,NULL',
    ];
    public function update(){

        if ($this->image) {
                $image = $this->image;
                $fileNameWithExt = $image->getClientOriginalName();
                //get filename
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                //get extention
                $extention = $image->getClientOriginalExtension();
                //file name to store
                $fileNameToStore= $filename.'_'.time().'.'.$extention;
                $image->storeAs('/uploads', $fileNameToStore, 'path');
        }

        $product =  Product::find($this->product_id);
        $product->user_id = Auth::user()->id;
        $product->category_id = $this->selectedCategory;
        $product->category_value_id = $this->selectedCategoryValue;
        $product->brand_id = $this->brand_id;
        $product->name = $this->name;
        $product->model = $this->model;
        $product->department = $this->department;
        $product->manufacturer = $this->manufacturer;
        $product->description = $this->description;
        if (isset($fileNameToStore)) {
            $product->filename = $fileNameToStore;
        }
        $product->status = '1';
        $product->update();

    
        return redirect(route('products.index'));
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Product Updated Successfully!!"
        ]);
    }


    public function render()
    {
        return view('livewire.products.edit');
    }
}
