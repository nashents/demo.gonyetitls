<?php

namespace App\Http\Livewire\Leaves;

use App\Models\Leave;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Pending extends Component
{
    public $leaves;
    public $management_leaves;
    public $leave_id;
    public $decision;
    public $reason;


    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'decision' => 'required',
        'reason' => 'required|string',
    ];
    public function mount(){

        $this->management_leaves = Leave::where('hod_decision', 'approved')
        ->where('management_decision', 'pending')
        ->where('user_id' ,'!=', Auth::user()->id)
        ->latest()->get();

        $this->leaves = Leave::where('hod_decision', 'pending')
        ->where('management_decision','pending')
        ->where('user_id' ,'!=', Auth::user()->id)
        ->where('department_id' , Auth::user()->employee->departments->first()->id)
        ->latest()->get();
    }

    public function authorize($id){
        $leave = Leave::find($id);
        $this->leave_id = $id;
        $this->dispatchBrowserEvent('show-decisionModal');
    }

    public function decision(){

        $leave = Leave::find($this->leave_id);

        $ranks = Auth::user()->employee->ranks;
        foreach ($ranks as $rank) {
            $rank_names[] = $rank->name;
        }

        if (in_array('Management',$rank_names)) {
        $leave->management_id = Auth::user()->id;
        $leave->management_decision = $this->decision;
        if ($this->decision == "approved") {
            $leave->user->employee->leave_days = $leave->user->employee->leave_days - $leave->days;
        }
        $leave->management_reply = $this->reason;

        }elseif (Auth::user()->employee->department_head) {
        $leave->hod_id = Auth::user()->id;
        $leave->hod_decision = $this->decision;
        $leave->hod_reply = $this->reason;
        }
        $leave->update();

        if ($this->decision == "approved") {
            $this->dispatchBrowserEvent('hide-decisionModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Application Approved Successfully!!"
            ]);
            return redirect()->route('leaves.approved');
        }else {
            $this->dispatchBrowserEvent('hide-decisionModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Application Rejected Successfully!!"
            ]);
            return redirect()->route('leaves.rejected');

        }



    }
    public function render()
    {

        $this->management_leaves = Leave::where('hod_decision', 'approved')
        ->where('management_decision', 'pending')
        ->where('user_id' ,'!=', Auth::user()->id)
        ->latest()->get();

        $this->leaves = Leave::where('hod_decision', 'pending')
        ->where('management_decision','pending')
        ->where('user_id' ,'!=', Auth::user()->id)
        ->where('department_id' , Auth::user()->employee->departments->first()->id)
        ->latest()->get();

        return view('livewire.leaves.pending',[
            'management_leaves' => $this->management_leaves,
            'leaves' => $this->leaves,
        ]);
    }
}
