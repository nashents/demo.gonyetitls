<?php

namespace App\Http\Livewire\TrailerAssignments;

use App\Models\Horse;
use App\Models\Trailer;
use Livewire\Component;
use App\Models\Transporter;
use Livewire\WithPagination;
use App\Models\TrailerAssignment;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $transporters;
    public $selectedTransporter;
    private $assignments;
    public $assignment;
    public $assignment_id;
    public $horses;
    public $selectedHorse;
    public $trailers;
    public $trailer_id;
    public $starting_odometer;
    public $ending_odometer;
    public $end_date;
    public $start_date;
    public $comments;


    public function mount(){
        $this->transporters = Transporter::orderBy('name','asc')->get();
        $this->horses = collect();
        $this->trailers = collect();
        $this->starting_odometer = 0;
    }

    private function resetInputFields(){
        $this->selectedTransporter = "";
        $this->selectedHorse = "";
        $this->trailer_id = "";
        $this->starting_odometer = "";
        $this->start_date = "";
        $this->ending_odometer = "";
        $this->end_date= "";
        $this->comments = "";
    }
    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'selectedHorse' => 'required',
        'trailer_id' => 'required|unique:trailer_assignments,trailer_id,NULL,id,deleted_at,NULL',
        'selectedTransporter' => 'required',
        'starting_odometer' => 'required',
        'start_date' => 'required',
        'comments' => 'nullable|string',
    ];
    public function updatedSelectedTransporter($id){
        if (!is_null($id)) {
            $this->horses = Horse::query()->with('horse_make:id,name','horse_model:id,name')->where('transporter_id',$id)
            ->where('status', 1)
            ->where('service',0)
            ->orderBy('registration_number','asc')->get();
            $this->trailers = Trailer::where('transporter_id',$id)
            ->where('status', 1)
            ->where('service',0)
            ->orderBy('registration_number','asc')->get();
        }
    }
    public function updatedSelectedHorse($horse){
        if (!is_null($horse)) {
            $this->starting_odometer = Horse::find($horse)->mileage;
        }
    }

    public function store(){
        $assignment = new TrailerAssignment;
        $assignment->user_id = Auth::user()->id;
        $assignment->transporter_id = $this->selectedTransporter;
        $assignment->trailer_id = $this->trailer_id;
        $assignment->horse_id = $this->selectedHorse;
        $assignment->starting_odometer = $this->starting_odometer;
        $assignment->start_date = $this->start_date;
        $assignment->comments = $this->comments;
        $assignment->status = 1;
        $assignment->save();

        $this->dispatchBrowserEvent('hide-assignmentModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Trailer - Horse Assignment Successful!!"
        ]);
      

    }

    public function edit($id){
        $assignment = TrailerAssignment::find($id);
        $this->user_id = $assignment->user_id;
        $this->selectedTransporter = $assignment->transporter_id;
        $this->selectedHorse = $assignment->horse_id;
        $this->trailer_id = $assignment->trailer_id;
        $this->starting_odometer = $assignment->starting_odometer;
        $this->ending_odometer = $assignment->ending_odometer;
        $this->horses = Horse::query()->with('horse_make:id,name','horse_model:id,name')
        ->where('status', 1)
        ->where('service',0)
        ->orderBy('registration_number','asc')->get();
        $this->trailers = Trailer::where('status', 1)
        ->where('service',0)
        ->orderBy('registration_number','asc')->get();
        $this->start_date = $assignment->start_date;
        $this->end_date = $assignment->end_date;
        $this->comments = $assignment->comments;
        $this->status = $assignment->status;
        $this->assignment_id = $assignment->id;
        $this->dispatchBrowserEvent('show-assignmentEditModal');

        }


        public function update()
        {
            if ($this->assignment_id) {
                $assignment = TrailerAssignment::find($this->assignment_id);
                $assignment->user_id = Auth::user()->id;
                $assignment->horse_id = $this->selectedHorse;
                $assignment->transporter_id = $this->selectedTransporter;
                $assignment->trailer_id = $this->trailer_id;
                $assignment->starting_odometer = $this->starting_odometer;
                $assignment->ending_odometer = $this->ending_odometer;
                $assignment->start_date = $this->start_date;
                $assignment->end_date = $this->end_date;
                $assignment->comments = $this->comments;
                $assignment->status = 1;
                $assignment->update();
                $this->dispatchBrowserEvent('hide-assignmentEditModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Trailer - Horse Assignment Updated Successfully!!"
                ]);

            }else {
                $this->dispatchBrowserEvent('hide-assignmentEditModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Assignment not found!!"
                ]);
            }
        }

        public function unAssignment($id){
            $assignment = TrailerAssignment::find($id);
            $this->assignment_id = $assignment->id;
            $this->starting_odometer = $assignment->starting_odometer;
            $this->dispatchBrowserEvent('show-unAssignmentModal');
        }

        public function updateAssignment(){
           $assignment = TrailerAssignment::find($this->assignment_id);
           $assignment->ending_odometer = $this->ending_odometer;
           $assignment->end_date = $this->end_date;
           $assignment->status = 0;
           $assignment->update();
           
           $this->dispatchBrowserEvent('hide-unAssignmentModal');
           $this->resetInputFields();
           $this->dispatchBrowserEvent('alert',[
               'type'=>'success',
               'message'=>"Trailer - Horse UnAssignment Successful!!"
           ]);
        }

    public function render()
    {
       
        return view('livewire.trailer-assignments.index',[
            'assignments' => TrailerAssignment::latest()->paginate(10),
            'starting_odometer' =>  $this->starting_odometer
        ]);
    }
}
