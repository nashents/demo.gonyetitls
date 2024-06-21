<?php

namespace App\Http\Livewire\Deductions;

use Livewire\Component;
use App\Models\Deduction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{


    public $deductions;
    public $type;
    public $description;
    public $name;
    public $status;
  

    public $deduction_id;
    public $user_id;

    public function mount(){
        $this->deductions = Deduction::latest()->get();
    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'type' => 'required',
        'name' => 'required|unique:deductions,name,NULL,id,deleted_at,NULL|string|min:2',
    ];

    private function resetInputFields(){
        $this->type = '';
        $this->name = '';
        $this->description = '';
    }

    public function store(){
        try{
        $deduction = new Deduction;
        $deduction->user_id = Auth::user()->id;
        $deduction->name = $this->name;
        $deduction->type = $this->type;
        $deduction->description = $this->description;
        $deduction->status =1;
        $deduction->save();

        $this->dispatchBrowserEvent('hide-deductionModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Deduction Created Successfully!!"
        ]);


        }
        catch(\Exception $e){
        // Set Flash Message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'error',
            'message'=>"Something goes wrong while creating deduction!!"
        ]);
    }
    }

    public function edit($id){
    $deduction = Deduction::find($id);
    $this->user_id = $deduction->user_id;
    $this->name = $deduction->name;
    $this->type = $deduction->type;
    $this->description = $deduction->description;
    $this->deduction_id = $deduction->id;
    $this->status = $deduction->status;
    $this->dispatchBrowserEvent('show-deductionEditModal');

    }


    public function update()
    {
        if ($this->deduction_id) {
            try{
            $deduction = deduction::find($this->deduction_id);
            $deduction->user_id = Auth::user()->id;
            $deduction->name = $this->name;
            $deduction->type = $this->type;
            $deduction->description = $this->description;
            $deduction->status = $this->status;
            $deduction->update();

            $this->dispatchBrowserEvent('hide-deductionEditModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Deduction Updated Successfully!!"
            ]);


            // return redirect()->route('deductions.index');
            }
            catch(\Exception $e){
            $this->dispatchBrowserEvent('hide-deductionEditModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Something goes wrong while creating deduction!!"
            ]);
          }
        }
    }


    public function render()
    {
        $this->deductions = Deduction::latest()->get();
        return view('livewire.deductions.index',[
            'deductions'=>   $this->deductions
        ]);
    }
}
