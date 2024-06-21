<?php

namespace App\Http\Livewire\TyreAssignments;

use App\Models\Tyre;
use App\Models\Horse;
use App\Models\Trailer;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\TyreDetail;
use App\Models\TyreDispatch;
use Livewire\WithPagination;
use App\Models\TyreAssignment;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';


    public $search;
    protected $queryString = ['search'];

    private $tyre_assignments;
    public $tyre_assignment_id;
    public $tyres;
    public $type = NULL;
    public $tyre_id;
    public $horses;
    public $horse_id;
    public $vehicles;
    public $vehicle_id;
    public $trailers;
    public $trailer_id;
    public $position;
    public $axle;
    public $starting_odometer;
    public $ending_odometer;
    public $description;
    public $status;

    public function mount(){
        $this->resetPage();
        $this->tyres = Tyre::where('status',1)->orderBy('tyre_number','asc')->get();
        $this->vehicles = Vehicle::where('status',1)->orderBy('registration_number','asc')->get();
        $this->trailers = Trailer::where('status', 1)->orderBy('registration_number','asc')->get();
        $this->horses = Horse::where('status',1)->orderBy('registration_number','asc')->get();
    }

    private function resetInputFields(){
        $this->vehicle_id = '';
        $this->tyre_id = '';
        $this->horse_id = '';
        $this->trailer_id = '';
        $this->position = '';
        $this->starting_odometer = '';
        $this->description = '';
        $this->position = '';
        $this->axle = '';
        $this->type = '';
    }


    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'type' => 'required',
        'trailer_id' => 'required',
        'vehicle_id' => 'required',
        'horse_id' => 'required',
        'tyre_id' => 'required',
        'starting_odometer' => 'required',
        'position' => 'required',
        'axle' => 'required',
        'description' => 'nullable|string',
    ];

    public function store(){

        $assignment = new TyreAssignment;
        $assignment->user_id = Auth::user()->id;
        $assignment->tyre_id = $this->tyre_id;
        $assignment->type = $this->type;
        if ($this->type == "Horse") {
            $assignment->horse_id = $this->horse_id;
        }elseif ($this->type == "Trailer") {
            $assignment->trailer_id = $this->trailer_id;
        }elseif ($this->type == "Vehicle") {
            $assignment->vehicle_id = $this->vehicle_id;
        }
        $assignment->starting_odometer = $this->starting_odometer;
        $assignment->position = $this->position;
        $assignment->axle = $this->axle;
        $assignment->description = $this->description;
        $assignment->status = 1;
        $assignment->save();

        $tyre = Tyre::find($this->tyre_id);
        $dispatch = new TyreDispatch;
        $dispatch->tyre_assignment_id = $assignment->id;
        $dispatch->tyre_id = $this->tyre_id;
        $dispatch->tyre_number = $tyre->tyre_number;
        $dispatch->serial_number = $tyre->serial_number;
        $dispatch->width = $tyre->width;
        $dispatch->aspect_ratio = $tyre->aspect_ratio;
        $dispatch->diameter =  $tyre->diameter;
        $dispatch->horse_id = $this->horse_id;
        $dispatch->vehicle_id = $this->vehicle_id;
        $dispatch->trailer_id = $this->trailer_id;
        $dispatch->save();

        $tyre = Tyre::find($this->tyre_id);
        $tyre->status = 0;
        $tyre->update();

        $this->dispatchBrowserEvent('hide-tyre_assignmentModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Tyre Assignment Saved Successfully!!"
        ]);

        return redirect(request()->header('Referer'));

    }

    public function edit($id){

        $this->tyres = Tyre::latest()->get();
        $assignment = TyreAssignment::find($id);
        $this->user_id = $assignment->user_id;
        $this->horse_id = $assignment->horse_id;
        $this->vehicle_id = $assignment->vehicle_id;
        $this->trailer_id = $assignment->trailer_id;
        $this->type = $assignment->type;
        $this->tyre_id = $assignment->tyre_id;
        $this->starting_odometer = $assignment->starting_odometer;
        $this->ending_odometer = $assignment->ending_odometer;
        $this->position = $assignment->position;
        $this->axle = $assignment->axle;
        $this->description = $assignment->description;
        $this->status = $assignment->status;
        $this->tyre_assignment_id = $assignment->id;
        $this->dispatchBrowserEvent('show-tyre_assignmentEditModal');

        }


        public function update()
        {
            if ($this->tyre_assignment_id) {

                $assignment = TyreAssignment::find($this->tyre_assignment_id);
                $assignment->user_id = Auth::user()->id;
                if ($this->type == "Horse") {
                    $assignment->horse_id = $this->horse_id;
                }elseif ($this->type == "Trailer") {
                    $assignment->trailer_id = $this->trailer_id;
                }elseif ($this->type == "Vehicle") {
                    $assignment->vehicle_id = $this->vehicle_id;
                }
                $assignment->tyre_id = $this->tyre_id;
                $assignment->type = $this->type;
                $assignment->starting_odometer = $this->starting_odometer;
                $assignment->ending_odometer = $this->ending_odometer;
                $assignment->position = $this->position;
                $assignment->axle = $this->axle;
                $assignment->description = $this->description;
                if ($this->ending_odometer) {
                    $assignment->status = 0;
                }
               
                $assignment->update();

                $this->dispatchBrowserEvent('hide-tyre_assignmentEditModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Tyre Assignment Updated Successfully!!"
                ]);

                return redirect(request()->header('Referer'));

            }else {
                $this->dispatchBrowserEvent('hide-tyre_assignmentEditModal');
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'error',
                    'message'=>"Tyre Assignment Not Found!!"
                ]);
            }
        }

        public function unAssignment($id){
            $assignment = Assignment::find($id);
            $this->assignment_id = $assignment->id;
            $this->dispatchBrowserEvent('show-unAssignmentModal');
        }

        public function updateAssignment(){
           $assignment = Assignment::find($this->assignment_id);
           $assignment->ending_odometer = $this->ending_odometer;
           $assignment->end_date = $this->end_date;
           $assignment->status = 0;
           $assignment->update();
           Session::flash('success','Driver horse Unassignment Successful');
            $this->dispatchBrowserEvent('hide-unAssignmentModal');
            return redirect()->route('assignments.index');
        }

        public function updatingSearch()
        {
            $this->resetPage();
        }
    public function render()
    {

        if (isset($this->search)) {
            return view('livewire.tyre-assignments.index',[
                'tyre_assignments' => TyreAssignment::query()->with('horse','vehicle','trailer','tyre','tyre.product','tyre.product.brand')
                ->whereHas('tyre', function ($query) {
                    return $query->where('tyre_number', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('tyre.product', function ($query) {
                    return $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('tyre.product.brand', function ($query) {
                    return $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('tyre', function ($query) {
                    return $query->where('serial_number', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('horse', function ($query) {
                    return $query->where('registration_number', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('vehicle', function ($query) {
                    return $query->where('registration_number', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('trailer', function ($query) {
                    return $query->where('registration_number', 'like', '%'.$this->search.'%');
                })
               
                ->orderBy('created_at','desc')->paginate(10),
        
            ]);
        }
        else {
           
            return view('livewire.tyre-assignments.index',[
                'tyre_assignments' => TyreAssignment::query()->with('horse','vehicle','trailer','tyre')->orderBy('created_at','desc')->paginate(10),
            ]);
          
        }
      
      
    }
}
