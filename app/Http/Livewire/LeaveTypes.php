<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LeaveTypes extends Component
{
    public $leave_type_id;
    public $leave_types;
    public $name;
    public $updateMode;

    public function mount(){
      $this->leave_types = $leave_types = LeaveType::latest()->get();
    }

    private function resetInputFields(){
        $this->name = '';
    }
    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'name' => 'required|unique:leave_types,name,NULL,id,deleted_at,NULL|string|min:2',
    ];

    public function store(){
        try{
        $leave_type = new LeaveType;
        $leave_type->user_id = Auth::user()->id;
        $leave_type->name = $this->name;
        $leave_type->save();
        $this->dispatchBrowserEvent('hide-leaveTypeModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Leave Type Created Successfully!!"
        ]);

        }catch(\Exception $e){
            // Set Flash Message
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Something went wrong while creating Leave Type!!"
            ]);
        }
    }

    public function edit($id){
    $leave_type = LeaveType::find($id);
    $this->updateMode = true;
    $this->user_id = $leave_type->user_id;
    $this->name = $leave_type->name;
    $this->leave_type_id = $leave_type->id;
    $this->dispatchBrowserEvent('show-leaveTypeEditModal');

    }
    public function update()
    {
        if ($this->leave_type_id) {
            try{
            $leave_type = LeaveType::find($this->leave_type_id);
            $leave_type->update([
                'name' => $this->name,
            ]);
            $this->dispatchBrowserEvent('hide-leaveTypeEditModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Leave Type Updated Successfully!!"
            ]);


            }catch(\Exception $e){
                // Set Flash Message
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'error',
                    'message'=>"Something went wrong while creating Leave Type!!"
                ]);
            }
        }
    }
    public function render()
    {
        $this->leave_types = $leave_types = LeaveType::latest()->get();
        return view('livewire.leave-types',[
            'leave_types' => $this->leave_types
        ]);
    }
}
