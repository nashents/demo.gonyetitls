<?php

namespace App\Http\Livewire\TyreProducts;

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
    public $selectedAttribute = NULL;
    public $category_values;
    public $attribute_value_id;
    public $brand_id;
    public $status;
    public $name;
    public $manufacturer;
    public $description;
    public $image;
    public $user_id;
    public $product;
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
        $this->brands = Brand::all();
        $this->categories = Category::all();
        $this->category_values = CategoryValue::all();
        $this->attributes = Attribute::all();
        $this->attribute_values = AttributeValue::all();
        $this->selectedCategory = $product->category_id;
        $this->selectedCategoryValue = $product->category_value_id;
        $this->name = $product->name;
        $this->model = $product->model;
        $this->manufacturer = $product->manufacturer;
        $this->description = $product->description;
        $this->previous_image = $product->filename;
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
    public function updatedSelectedAttribute($attribute)
    {
        if (!is_null($attribute) ) {
        $this->attribute_values = AttributeValue::where('attribute_id', $attribute)->get();
        }
    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'selectedCategory' => 'required',
        'selectedCategoryValue' => 'required',
        'selectedAttribute' => 'required',
        'attribute_value_id' => 'required',
        'brand_id' => 'required',
        'manufacturer' => 'required',
        'image' => 'nullable|image',
        'description' => 'nullable',
        'name' => 'required|unique:products,name,NULL,id,deleted_at,NULL',
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

        $product = new Product;
        $product->user_id = Auth::user()->id;
        $product->category_id = $this->selectedCategory;
        $product->category_value_id = $this->selectedCategoryValue;
        $product->brand_id = $this->brand_id;
        $product->name = $this->name;
        $product->manufacturer = $this->manufacturer;
        $product->description = $this->description;
        if (isset($fileNameToStore)) {
            $product->filename = $fileNameToStore;
        }
        $product->status = '1';

        $product->update();

        foreach ($this->selectedAttribute as $key => $model) {
            $product_attribute = ProductAttribute::where('attribute_id', $this->selectedAttribute)->get();
            $product_attribute->product_id = $product->id;
            $product_attribute->attribute_id = $this->selectedAttribute[$key];
            $product_attribute->attribute_value_id = $this->attribute_value_id[$key];
            $product_attribute->update();
          }

        return redirect(route('tyre_products.index'));
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Product Updated Successfully!!"
        ]);
    }


    public function render()
    {
        return view('livewire.tyre-products.edit');
    }
}
