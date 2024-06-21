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
use Illuminate\Support\Facades\Session;

class Create extends Component
{
    use WithFileUploads;

    public $brands;
    public $department;
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
    public $product_number;
    public $manufacturer;
    public $description;
    public $image;
    public $user_id;



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


    public function mount($category){
        $this->brands = collect();
        $this->product_number =$this->productNumber();
        $this->categories = Category::all();
        $this->department = $category;
        $this->category_values = collect();
        $this->attributes = collect();
        $this->attribute_values = collect();
    }

    public function updatedSelectedCategory($category)
    {
        if (!is_null($category) ) {
        $this->category_values = CategoryValue::where('category_id', $category)->get();
        $this->brands = Brand::where('category_id', $category)->get();
        $this->attributes = Attribute::where('category_id', $category)->get();
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
        'product_number' => 'required',
        'description' => 'nullable',
        'name' => 'required',
    ];

    public function productNumber(){

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

        $product = Product::where('department','tyre')->orderBy('id','desc')->first();

        if (!$product) {
            $product_number =  $initials .'TP'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $product->id + 1;
            $product_number =  $initials .'TP'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $product_number;


    }

    public function store(){
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
        $product->product_number = $this->product_number;
        $product->department = $this->department;
        $product->manufacturer = $this->manufacturer;
        $product->description = $this->description;
        if (isset($fileNameToStore)) {
            $product->filename = $fileNameToStore;
        }
        $product->status = '1';

        $product->save();

        if (isset($this->selectedAttribute)) {
            foreach ($this->selectedAttribute as $key => $model) {
                $product_attribute = new ProductAttribute;
                $product_attribute->product_id = $product->id;
                $product_attribute->attribute_id = $this->selectedAttribute[$key];
                $product_attribute->attribute_value_id = $this->attribute_value_id[$key];
                $product_attribute->save();
              }
        }


        return redirect(route('tyre_products.index'));
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Product Created Successfully!!"
        ]);
    }

    public function render()
    {
        return view('livewire.tyre-products.create');
    }
}
