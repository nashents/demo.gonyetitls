<?php

namespace App\Http\Livewire\Drivers;

use App\Models\Rank;
use App\Models\Role;
use App\Models\User;
use App\Models\Count;
use App\Models\Branch;
use App\Models\Driver;
use App\Models\Country;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Document;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\Province;
use App\Models\Department;
use App\Models\Transporter;
use Livewire\WithFileUploads;
use App\Models\DepartmentHead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class Edit extends Component
{

    use WithFileUploads;


    public $name;
    public $middle_name;
    public $surname;
    public $gender;
    public $dob;
    public $idnumber;
    public $passport_number;
    public $phonenumber;
    public $countries;
    public $selectedCountry;
    public $provinces;
    public $province_id;

    public $experience;
    public $license_number;
    public $use_email_as_username;

    public $user;
    public $user_id;
    public $driver_id;
    public $driver;
    public $class;
    public $email;
    public $post;
    public $address;
    public $department_id;
    public $departments;
    public $transporters;
    public $transporter_id;
    public $branch_id;
    public $branches;
    public $role_id;
    public $roles;
    public $currencies;
    public $currency_id;
    public $salary;
    public $frequency;
    public $ranks;
    public $rank_id;
    public $start_date;
    public $end_date;
    public $leave_days;
    public $accrual_rate;
    public $duration;
    public $expiration;
    public $next_of_kin;
    public $relationship;
    public $contact;
    public $status;

  
    public $city;
    public $suburb;
    public $street_address;

    public $reference;
    public $reference_phonenumber;

    public $selectedDepartment = Null;
    public $job_title;
    public $job_titles;
    public $employee_id;

    public $expires_at;
    public $title;
    public $file;

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

    public function mount($id){
        $this->departments = Department::all();
        $this->transporters = Transporter::orderBy('name','asc')->get();
        $this->branches = Branch::all();
        $this->roles = Role::all();
        $this->ranks = Rank::all();
        $this->countries = Country::orderBy('name','asc')->get();
        $this->provinces = Province::orderBy('name','asc')->get();
        $driver = Driver::find($id);
        $this->currencies = Currency::latest()->get();
        $this->driver = $driver;
        $this->driver_id = $driver->id;
        $employee = Employee::find($driver->employee->id);
        $this->user = $driver->user;
        $this->user_id = $this->user->id;
        $this->employee = $employee;
        $this->employee_id = $this->employee->id;
        $this->documents = $employee->documents;
        foreach($this->documents as $key => $value){
            $this->title[$key] = $value->title;
        }
       foreach ($employee->departments as $department) {
        $this->selectedDepartment[] = $department->id;
       }

        foreach ($employee->user->roles as $role) {
            $this->role_id[] = $role->id;
        }
        foreach ($employee->ranks as $rank) {
            $this->rank_id[] = $rank->id;
        }
     
        $this->job_titles = JobTitle::all();
        $this->job_title = $employee->post;
        $this->user_id = $driver->user->id;
        $this->user_id = $employee->user_id;
        $this->license_number = $driver->license_number;
        $this->class = $driver->class;
        $this->transporter_id = $driver->transporter_id;
        $this->experience = $driver->experience;
        $this->reference_phonenumber = $driver->reference_phonenumber;
        $this->reference = $driver->reference;
        $this->employee_number = $employee->employee_number;
        $this->name = $employee->name ;
        $this->middle_name = $employee->middle_name ;
        $this->surname = $employee->surname;
        $this->phonenumber = $employee->phonenumber ;
        $this->email =   $employee->email ;
        $this->salary =   $employee->salary ;
        $this->use_email_as_username = $employee->user ? $employee->user->use_email_as_username : "1";
        $this->currency_id =   $employee->currency_id ;
        $this->frequency =   $employee->frequency ;
        $this->pin = $employee->pin;
        $this->gender = $employee->gender;
        $this->dob =  $employee->dob;

        $selected_country = $employee->country; 
        $country = Country::where('name',$selected_country)->first();
        if (isset($country)) {
            $this->selectedCountry = $country->id;
        }
        $selected_province =   $employee->province;
        $province = Province::where('name', $selected_province)->first();
        if (isset($province)) {
            $this->province_id = $province->id;
        }
        $this->city = $employee->city ;
       
        $this->suburb = $employee->suburb;
        $this->street_address = $employee->street_address;
        $this->idnumber = $employee->idnumber;
        $this->passport_number = $driver->passport_number;
        $this->status = $driver->status;
        $this->post = $employee->post;
        $this->start_date = $employee->start_date;
        $this->end_date = $employee->end_date;
        $this->duration = $employee->duration;
        $this->expiration = $employee->expiration;
        $this->next_of_kin = $employee->next_of_kin;
        $this->relationship = $employee->relationship;
        $this->contact = $employee->contact;
        $this->leave_days= $employee->leave_days;
        $this->accrual_rate= $employee->accrual_rate;
        $this->branch_id = $employee->branch_id;
        $this->selected_departments = $employee->departments;
      }

      private function resetInputFields(){
          $this->name = '';
          $this->middle_name = '';
          $this->surname = '';
          $this->dob = '';
          $this->gender = '';
          $this->phonenumber = '';
          $this->email = '';
          $this->idnumber = '';
          $this->post = '';
          $this->selectedCountry = '';
          $this->status = '';
          $this->currency_id = '';
          $this->salary = '';
          $this->frequency = '';
          $this->city = '';
          $this->transporter_id = '';
          $this->province_id = '';
          $this->suburb = '';
          $this->street_address = '';
          $this->start_date = '';
          $this->end_date = '';
          $this->leave_days = '';
          $this->accrual_rate = '';
          $this->expiration = '';
          $this->duration = '';
          $this->contact = '';
          $this->next_of_kin = '';
          $this->relationship = '';
          $this->reference_phonenumber = '';
          $this->reference = '';
      }
      public function updated($value){
          $this->validateOnly($value);
      }
      protected $messages =[

        'title.*.required' => 'Title field is required',
        'file.*.required' => 'File field is required',
        'department_id.required' => 'Select Department',
        'branch_id.required' => 'Select Branch',
        'role_id.required' => 'Select Role',

    ];

      protected $rules = [
          'name' => 'required|alpha|min:2',
          'middle_name' => 'nullable|alpha|min:2',
          'surname' => 'required|alpha|min:2',
          'gender' => 'required',
          'dob' => 'required|date',
          'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
          'phonenumber' => 'required|unique:employees,phonenumber,NULL,id,deleted_at,NULL|max:13',
          'idnumber' => 'required|string|unique:employees,idnumber,NULL,id,deleted_at,NULL|max:15',
          'passport_number' => 'nullable|string|unique:drivers,passport_number,NULL,id,deleted_at,NULL|max:15',
          'license_number' => 'required|string|unique:drivers,license_number,NULL,id,deleted_at,NULL|max:15',
          'experience' => 'required',
          'class' => 'required',
          'transporter_id' => 'required',
          'reference' => 'nullable|string|min:2',
          'reference_phonenumber' => 'nullable|max:13',
          'selectedCountry' => 'required|string',
          'city' => 'required',
          'province_id' => 'required',
          'suburb' => 'required',
          'street_address' => 'required',
          'start_date' => 'required',
          'end_date' => 'required',
          'expiration' => 'required',
          'next_of_kin' => 'required',
          'relationship' => 'required',
          'contact' => 'required',
          'duration' => 'required',
          'leave_days' => 'required',
          'accrual_rate' => 'required',
          'job_title' => 'required',
          'branch_id' => 'required',
          'department_id' => 'required',
          'title.0' => 'nullable|string',
          'file.0' => 'nullable|file|mimes:docx,doc,pdf,xls,xlsx,pptx|max:10000',
          'title.*' => 'required',
          'file.*' => 'required|file|mimes:docx,doc,pdf,xls,xlsx,pptx|max:10000',
      ];

      public function generatePIN($digits = 4){
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while($i < $digits){
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }

    public function updatedSelectedDepartment($department)
    {
        if (!is_null($department) ) {
        $this->job_titles = JobTitle::where('department_id', $department)->get();
        }
    }

   

    public function update(){
   
        try{

          $user = User::find($this->user_id);
          $user->name = $this->name;
          $user->surname = $this->surname;
          $user->category = 'driver';
          $user->email = $this->email;
          $user->phonenumber = $this->phonenumber;
          $user->use_email_as_username = $this->use_email_as_username;
          if ($this->use_email_as_username == TRUE) {
          $user->username = $this->email;
          }else{
          $user->username = $this->phonenumber;
          }
          $user->update();
          $user->roles()->detach();
          $user->roles()->sync($this->role_id);

          $employee = Employee::find($this->employee_id);
          $employee->user_id = $user->id;
          $employee->name = $this->name;
          $employee->middle_name = $this->middle_name;
          $employee->surname = $this->surname;
          $employee->phonenumber = $this->phonenumber;
          $employee->email = $this->email;
          $employee->gender = $this->gender;
          $employee->dob = $this->dob;

          $country = Country::find($this->selectedCountry);
          if (isset($country)) {
            $employee->country = $country->name;
          }
          $province = Province::find($this->province_id);
          if (isset($province)) {
            $employee->province = $province->name;
          }

          $employee->city = $this->city;
          $employee->salary = $this->salary;
          $employee->frequency = $this->frequency;
          $employee->currency_id = $this->currency_id;
        
          $employee->suburb = $this->suburb;
          $employee->street_address = $this->street_address;
          $employee->idnumber = $this->idnumber;
          $employee->post = $this->job_title;
          $employee->duration = $this->duration;
          $employee->expiration = $this->expiration;
          $employee->next_of_kin = $this->next_of_kin;
          $employee->relationship = $this->relationship;
          $employee->contact = $this->contact;
          $employee->start_date = $this->start_date;
          $employee->end_date = $this->end_date;
          $employee->leave_days = $this->leave_days;
          $employee->accrual_rate = $this->accrual_rate;
          $employee->branch_id = $this->branch_id;
          $employee->update();
          $employee->departments()->detach();
          $employee->departments()->sync($this->selectedDepartment);
          $employee->ranks()->detach();
          $employee->ranks()->sync($this->rank_id);

          $rank = Rank::find($this->rank_id[0]);
          if ($rank->name == "HOD") {
            $department_head = DepartmentHead::where('department_id', $this->selectedDepartment[0])->first();
          if ($department_head) {
            $department_head->employee_id = $employee->id;
            $department_head->department_id = $this->selectedDepartment[0];
            $department_head->update();
          }else {
            $new_department_head = new DepartmentHead;
            $new_department_head->employee_id = $employee->id;
            $new_department_head->department_id = $this->selectedDepartment[0];
            $new_department_head->save();
          }

          }
          $driver = Driver::find($this->driver_id);
          $driver->user_id = $user->id;
          $driver->transporter_id = $this->transporter_id;
          $driver->employee_id = $employee->id;
          $driver->license_number = $this->license_number;
          $driver->passport_number = $this->passport_number;
          $driver->class = $this->class;
          $driver->experience = $this->experience;
          $driver->reference = $this->reference;
          $driver->reference_phonenumber = $this->reference_phonenumber;
          $driver->status = $this->status;
          $driver->update();

          foreach ($this->documents as $document) {
              $document->delete();
          }

          if (isset($this->file)) {
            foreach ($this->file as $key => $value) {
              if(isset($this->file[$key])){
                  $file = $this->file[$key];
                  // get file with ext
                  $fileNameWithExt = $file->getClientOriginalName();
                  //get filename
                  $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                  //get extention
                  $extention = $file->getClientOriginalExtension();
                  //file name to store
                  $fileNameToStore= $filename.'_'.time().'.'.$extention;
                  $file->storeAs('/documents', $fileNameToStore, 'my_files');

              }
              $document = new Document;
              $document->employee_id = $employee->id;
              $document->category = 'employee';
              if(isset($this->title[$key])){
              $document->title = $this->title[$key];
              }
              if (isset($fileNameToStore)) {
                  $document->filename = $fileNameToStore;
              }
              if(isset($this->expires_at[$key])){
                  $document->expires_at = Carbon::create($this->expires_at[$key])->toDateTimeString();
                  $today = now()->toDateTimeString();
                  $expire = Carbon::create($this->expires_at[$key])->toDateTimeString();
                  if ($today <=  $expire) {
                      $document->status = 1;
                  }else{
                      $document->status = 0;
                  }
              }else {
                $document->status = 1;
              }
              $document->save();

            }
                   # code...
          }
          Session::flash('success','Driver updated successfully');
          $this->resetInputFields();
          return redirect()->route('drivers.index');
        }
        catch(\Exception $e){
        // Set Flash Message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'error',
            'message'=>"Something went wrong while updating driver!!"
        ]);
    }
      }

    public function render()
    {
        $this->departments = Department::orderBy('name','asc')->get();
        $this->branches = Branch::orderBy('name','asc')->get();
        $this->job_titles = JobTitle::latest()->get();
        $this->countries = Country::orderBy('name','asc')->get();
        $this->provinces = Province::orderBy('name','asc')->get();

        return view('livewire.drivers.edit',[
            'departments' => $this->departments,
            'branches' => $this->branches,
            'job_titles' => $this->job_titles,
            'countries' => $this->countries,
            'provinces' => $this->provinces,
        ]);
    }
}
