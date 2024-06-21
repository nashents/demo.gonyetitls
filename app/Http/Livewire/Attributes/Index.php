<?php

namespace App\Http\Livewire\Attributes;

use Livewire\Component;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\CategoryValue;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{

    public $attributes;
    public $attribute_id;
    public $status;
    public $value;
    public $attribute;
    public $user_id;
    public $categories;
    public $selectedCategory = NULL;
    public $category_values;
    public $category_value_id;


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


    public function mount(){
        $this->attributes = Attribute::latest()->get();
        $this->values = AttributeValue::latest()->get();
        $this->categories = Category::all();
        $this->category_values = CategoryValue::all();
    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'selectedCategory' => 'required',
        'category_value_id' => 'required',
        'attribute' => 'required|unique:attributes,name,NULL,id,deleted_at,NULL|string',
        'value' => 'unique:attribute_values,name,NULL,id,deleted_at,NULL|string',
    ];
    private function resetInputFields(){
        $this->attribute = '';
        $this->value = '';
        $this->selectedCategory = '';
        $this->category_value_id = '';
        $this->status = '';
    }
    public function updatedSelectedCategory($category)
    {
        if (!is_null($category) ) {
        $this->category_values = CategoryValue::where('category_id', $category)->get();
        }
    }
    public function store(){

        $attribute = new Attribute;
        $attribute->user_id = Auth::user()->id;
        $attribute->category_id = $this->selectedCategory;
        $attribute->category_value_id = $this->category_value_id;
        $attribute->name = $this->attribute;
        $attribute->status = '1';
        $attribute->save();


        foreach ($this->value as $key => $model) {
            $value = new AttributeValue;
            $value->user_id = Auth::user()->id;
            $value->attribute_id = $attribute->id;
            $value->name = $this->value[$key];
            $value->status = 1;
            $value->save();
          }

        $this->dispatchBrowserEvent('hide-attributeModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Attribute Created Successfully!!"
        ]);
        return redirect(request()->header('Referer'));

    }

        public function showValue($id){
            $attribute = Attribute::find($id);
            $this->attribute_id = $attribute->id;
            $this->dispatchBrowserEvent('show-attributeValueModal');

        }

        public function storeValue(){
            $value = new AttributeValue;
            $value->user_id = Auth::user()->id;
            $value->attribute_id = $this->attribute_id;
            $value->name = $this->value;
            $value->status = '1';
            $value->save();

            $this->dispatchBrowserEvent('hide-attributeValueModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Value Created Successfully!!"
            ]);
        }
    public function editValue($id){

        $value = AttributeValue::find($id);
        $this->user_id = $value->user_id;
        $this->attribute_id = $value->attribute_id;
        $this->value = $value->name;
        $this->status = $value->status;
        $this->value_id = $value->id;
        $this->dispatchBrowserEvent('show-attributeValueEditModal');

        }

        public function updateValue()
        {
            if ($this->value_id) {
                $value = AttributeValue::find($this->value_id);
                $value->update([
                    'user_id' => Auth::user()->id,
                    'name' => $this->value,
                    'status' => $this->status,
                ]);

            $this->dispatchBrowserEvent('hide-attributeValueEditModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Value Updated Successfully!!"
            ]);

            }
        }

    public function edit($id){

    $attribute = Attribute::find($id);
    $this->user_id = $attribute->user_id;
    $this->selectedCategory = $attribute->category_id;
    $this->category_value_id = $attribute->category_value_id;
    $this->attribute = $attribute->name;
    $this->status = $attribute->status;
    $this->attribute_id = $attribute->id;
    $this->dispatchBrowserEvent('show-attributeEditModal');

    }

    public function update()
    {
        if ($this->attribute_id) {
            $attribute = Attribute::find($this->attribute_id);
            $attribute->update([
                'user_id' => Auth::user()->id,
                'name' => $this->attribute,
                'category_value_id' => $this->category_value_id,
                'category_id' => $this->selectedCategory,
                'status' => $this->status,
            ]);

        $this->dispatchBrowserEvent('hide-attributeEditModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Attribute Updated Successfully!!"
        ]);

        }
    }
    public function render()
    {
        $this->attributes = Attribute::latest()->get();
        return view('livewire.attributes.index',[
            'attributes' => $this->attributes
        ]);
    }
}
