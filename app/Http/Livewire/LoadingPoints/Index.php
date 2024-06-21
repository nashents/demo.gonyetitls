<?php

namespace App\Http\Livewire\LoadingPoints;

use Livewire\Component;
use App\Models\LoadingPoint;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use App\Exports\LoadingPointsExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    protected $queryString = ['search'];

    private $loading_points;
    public $loading_point_id;
    public $status;
    public $name;
    public $contact_name;
    public $contact_surname;
    public $email;
    public $phonenumber;
    public $lat;
    public $long;
    public $user_id;
    public $expiry_date;
    public $description;

    private function resetInputFields(){
        $this->name = '';
        $this->contact_name = '';
        $this->contact_surname = '';
        $this->email = '';
        $this->phonenumber = '';
        $this->lat = '';
        $this->long = '';
        $this->expiry_date = '';
        $this->description = '';

    }

    public function mount(){
       
    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'name' => 'required|unique:loading_points,name,NULL,id,deleted_at,NULL|string|min:2',
    
    ];

    public function exportLoadingPointsCSV(Excel $excel){

        return $excel->download(new LoadingPointsExport, 'loading_points.csv', Excel::CSV);
    }
    public function exportLoadingPointsPDF(Excel $excel){

        return $excel->download(new LoadingPointsExport, 'loading_points.pdf', Excel::DOMPDF);
    }
    public function exportLoadingPointsExcel(Excel $excel){

        return $excel->download(new LoadingPointsExport, 'loading_points.xlsx');
    }

    public function store(){
        try{

        $loading_point = new LoadingPoint;
        $loading_point->user_id = Auth::user()->id;
        $loading_point->name = $this->name;
        $loading_point->contact_name = $this->contact_name;
        $loading_point->contact_surname = $this->contact_surname;
        $loading_point->email = $this->email;
        $loading_point->phonenumber = $this->phonenumber;
        $loading_point->lat = $this->lat;
        $loading_point->long = $this->long;
        $loading_point->expiry_date = $this->expiry_date;
        $loading_point->description = $this->description;
        $loading_point->status = '1';
        $loading_point->save();

        $this->dispatchBrowserEvent('hide-loadingPointModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Loading Point Created Successfully!!"
        ]);
    }
    catch(\Exception $e){
    $this->dispatchBrowserEvent('hide-loadingPointModal');
    $this->dispatchBrowserEvent('alert',[
        'type'=>'error',
        'message'=>"Something goes wrong while creating loading points!!"
    ]);
}
    }

    public function edit($id){

    $loading_point = LoadingPoint::find($id);
    $this->user_id = $loading_point->user_id;
    $this->name = $loading_point->name;
    $this->contact_name = $loading_point->contact_name;
    $this->contact_surname = $loading_point->contact_surname;
    $this->phonenumber = $loading_point->phonenumber;
    $this->email = $loading_point->email;
    $this->lat = $loading_point->lat;
    $this->long = $loading_point->long;
    $this->status = $loading_point->status;
    $this->expiry_date = $loading_point->expiry_date;
    $this->description = $loading_point->description;
    $this->loading_point_id = $loading_point->id;
    $this->dispatchBrowserEvent('show-loadingPointEditModal');

    }

    public function update()
    {
        if ($this->loading_point_id) {
            try{
            $loading_point = LoadingPoint::find($this->loading_point_id);
            $loading_point->user_id = Auth::user()->id;
            $loading_point->name = $this->name;
            $loading_point->contact_name = $this->contact_name;
            $loading_point->contact_surname = $this->contact_surname;
            $loading_point->phonenumber = $this->phonenumber;
            $loading_point->email = $this->email;
            $loading_point->lat = $this->lat;
            $loading_point->long = $this->long;
            $loading_point->expiry_date = $this->expiry_date;
            $loading_point->description = $this->description;
            $loading_point->status = $this->status;
            $loading_point->update();

            $this->dispatchBrowserEvent('hide-loadingPointEditModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Loading Point Updated Successfully!!"
            ]);

        }
        catch(\Exception $e){
        $this->dispatchBrowserEvent('hide-loadingPointEditModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'error',
            'message'=>"Something goes wrong while updating loading points!!"
        ]);
    }
        }
    }
    public function render()
    {
        if (isset($this->search)) {
            return view('livewire.loading-points.index',[
                'loading_points' => LoadingPoint::where('name','like', '%'.$this->search.'%')
                ->orWhere('lat','like', '%'.$this->search.'%')
                ->orWhere('long','like', '%'.$this->search.'%')
                ->orWhere('contact_name','like', '%'.$this->search.'%')
                ->orWhere('expiry_date','like', '%'.$this->search.'%')
                ->orWhere('contact_surname','like', '%'.$this->search.'%')
                ->orWhere('email','like', '%'.$this->search.'%')
                ->orWhere('phonenumber','like', '%'.$this->search.'%')
                ->orderBy('created_at','desc')->paginate(10),
               
            ]);
        }else {
            return view('livewire.loading-points.index',[
                'loading_points' => LoadingPoint::orderBy('created_at','desc')->paginate(10)
            ]);
        }
       
    }
}
