<?php

namespace App\Http\Livewire\Stores;

use App\Models\Store;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{



    public $stores;
    public $store_id;
    public $status;
    public $name;
    public $country;
    public $city;
    public $suburb;
    public $street_address;
    public $user_id;

    private function resetInputFields(){
        $this->name = '';
        $this->country = '';
        $this->city = '';
        $this->suburb = '';
        $this->street_address = '';
    }
    public function mount(){
        $this->stores = Store::latest()->get();
    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'name' => 'required|unique:stores,name,NULL,id,deleted_at,NULL|string|min:2',
        'country' => 'required',
        'city' => 'required',
        'suburb' => 'required',
        'street_address' => 'required',
    ];

    public function store(){
        $store = new Store;
        $store->user_id = Auth::user()->id;
        $store->name = $this->name;
        $store->country = $this->country;
        $store->city = $this->city;
        $store->suburb = $this->suburb;
        $store->street_address = $this->street_address;
        $store->status = '1';
        $store->save();
        $this->dispatchBrowserEvent('hide-storeModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Store Created Successfully!!"
        ]);
    }

    public function edit($id){
    $store = Store::find($id);
    $this->user_id = $store->user_id;
    $this->name = $store->name;
    $this->country = $store->country;
    $this->city = $store->city;
    $this->suburb = $store->suburb;
    $this->street_address = $store->street_address;
    $this->status = $store->status;
    $this->store_id = $store->id;
    $this->dispatchBrowserEvent('show-storeEditModal');

    }

    public function update()
    {
        if ($this->store_id) {
            $store = Store::find($this->store_id);
            $store->update([
                'user_id' => Auth::user()->id,
                'name' => $this->name,
                'country' => $this->country,
                'city' => $this->city,
                'suburb' => $this->suburb,
                'street_address' => $this->street_address,
                'status' => $this->status,
            ]);

            $this->dispatchBrowserEvent('hide-storeEditModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Store Updated Successfully!!"
            ]);

        }
    }
    public function render()
    {
        $this->stores = Store::latest()->get();
        return view('livewire.stores.index',[
            'stores' => $this->stores
        ]);
    }
}
