<?php

namespace App\Http\Livewire\Tyres;

use App\Models\Tyre;
use App\Models\Product;
use Livewire\Component;
use App\Models\TyreDetail;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $tyres;
    public $tyre_id;
    public $tyre_number;
    public $serial_number;
    public $product_id;
    public $products;
    public $width;
    public $aspect_ratio;
    public $diameter;
    public $qty;
    public $rate;
    public $user_id;

    public function mount(){
        $this->tyres = Tyre::latest()->get();
        $this->products = Product::where('department','tyre')->get();
    }
    public function updated($value){
        $this->validateOnly($value);
    }

    protected $rules = [
        'product_id' => 'required',
        'rate' => 'required',
        'tyre_number' => 'required',
        'serial_number' => 'required',
        'width' => 'required',
        'aspect_ratio' => 'required',
        'diameter' => 'required',
    ];

    public function edit($id){
        $tyre = TyreDetail::find($id);
        $this->user_id = $tyre->user_id;
        $this->tyre_id = $tyre->tyre_id;
        $this->tyre_number = $tyre->tyre_number;
        $this->serial_number = $tyre->serial_number;
        $this->product_id = $tyre->product_id;
        $this->width = $tyre->width;
        $this->aspect_ratio = $tyre->aspect_ratio;
        $this->diameter = $tyre->diameter;
        $this->rate = $tyre->rate;
        $this->tyre_id = $tyre->id;
        $this->dispatchBrowserEvent('show-tyreEditModal');

        }

        public function update(){
            $tyre = Tyre::find($this->tyre_id);
            $tyre->tyre_id = $this->tyre_id;
            $tyre->tyre_number = $this->tyre_number;
            $tyre->serial_number = $this->serial_number;
            $tyre->product_id = $this->product_id;
            $tyre->width = $this->width;
            $tyre->aspect_ratio = $this->aspect_ratio;
            $tyre->diameter = $this->diameter;
            $tyre->rate = $this->rate;
            $tyre->update();

            $this->dispatchBrowserEvent('hide-tyreEditModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Tyre Updated Successfully!!"
            ]);
        }
    public function render()
    {
        $this->tyres = Tyre::latest()->get();
        return view('livewire.tyres.index',[
            'tyres' => $this->tyres
        ]);
    }
}
