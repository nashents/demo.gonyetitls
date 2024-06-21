<?php

namespace App\Http\Livewire\Drivers;

use Carbon\Carbon;
use App\Models\Rank;
use App\Models\Role;
use App\Models\User;
use App\Models\Count;
use App\Models\Branch;
use App\Models\Driver;
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
use App\Mail\AccountCreationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\Country;

class Create extends Component
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

    public $experience;
    public $license_number;
    public $countries;
    public $selectedCountry;
    public $provinces;
    public $province_id;

    public $transporters;
    public $transporter_id;
    public $class;
    public $email;
    public $post;
    public $address;
    public $department_id;
    public $departments;
    public $branch_id;
    public $branches;
    public $currencies;
    public $currency_id;
    public $salary;
    public $frequency;
    public $role_id;
    public $roles;
    public $ranks;
    public $rank_id;
    public $start_date;
    public $end_date;
    public $leave_days;
    public $accrual_rate;
    public $duration;
    public $expiration;
    public $expiry_date;
    public $next_of_kin;
    public $relationship;
    public $contact;

    public $city;
    public $suburb;
    public $street_address;
    public $use_email_as_username;

    public $reference;
    public $reference_phonenumber;

    public $selectedDepartment = Null;
    public $job_title;
    public $job_titles;
    public $employee_id;

    public $title;
    public $file;
    public $expires_at;

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
        $this->departments = Department::orderBy('name','asc')->get();
        $this->transporters = Transporter::orderBy('name','asc')->get();
        $this->branches = Branch::orderBy('name','asc')->get();
        $this->roles = Role::orderBy('name','asc')->get();
        $this->currencies = Currency::latest()->get();
        $this->ranks = Rank::orderBy('name','asc')->get();
        $this->job_titles = JobTitle::latest()->get();
        $this->use_email_as_username = 1;
        $this->countries = Country::orderBy('name','asc')->get();
        $this->provinces = collect();
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
          $this->currency_id = '';
          $this->salary = '';
          $this->frequency = '';
          $this->post = '';
          $this->selectedCountry = '';
          $this->city = '';
          $this->province_id = '';
          $this->suburb = '';
          $this->street_address = '';
          $this->start_date = '';
          $this->end_date = '';
          $this->leave_days = '';
          $this->accrual_rate = '';
          $this->expiry_date = '';
          $this->expiration = '';
          $this->relationship = '';
          $this->duration = '';
          $this->contact = '';
          $this->next_of_kin = '';
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
          'license_number' => 'required|string|unique:drivers,license_number,NULL,id,deleted_at,NULL|max:15',
          'passport_number' => 'nullable|string|unique:drivers,passport_number,NULL,id,deleted_at,NULL|max:15',
          'experience' => 'required',
          'class' => 'required',
          'reference' => 'nullable|string|min:2',
          'reference_phonenumber' => 'nullable|max:13',
          'selectedCountry' => 'required|string',
          'city' => 'required',
          'province_id' => 'required',
          'suburb' => 'required',
          'street_address' => 'required',
          'start_date' => 'required',
          'end_date' => 'required',
          'leave_days' => 'required',
          'accrual_rate' => 'required',
          'job_title' => 'required',
          'expiration' => 'required',
          'next_of_kin' => 'required',
          'relationship' => 'required',
          'contact' => 'required',
          'duration' => 'required',
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
    public function employeeNumber(){

        if (isset(Auth::user()->company)) {
            $str = Auth::user()->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }elseif (isset(Auth::user()->employee->company)) {
            $str = Auth::user()->employee->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }

            $employee = Employee::orderBy('id', 'desc')->first();

        if (!$employee) {
            $employee_number =  $initials .'E'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $employee->id + 1;
            $employee_number =  $initials .'E'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $employee_number;


    }

    public function driverNumber(){

        if (isset(Auth::user()->company)) {
            $str = Auth::user()->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }elseif (isset(Auth::user()->employee->company)) {
            $str = Auth::user()->employee->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }

            $driver = Driver::orderBy('id', 'desc')->first();

        if (!$driver) {
            $driver_number =  $initials .'D'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $driver->id + 1;
            $driver_number =  $initials .'D'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $driver_number;


    }

    public function updatedSelectedCountry($id){
        if (!is_null($id)) {
          $this->provinces = Province::where('country_id',$id)->orderBy('name','asc')->get();
        }
      }
      
    public function updatedSelectedDepartment($department)
    {
        if (!is_null($department) ) {
        $this->job_titles = JobTitle::where('department_id', $department)->get();
        }
    }

      public function store(){

      try {
      

      $pin = $this->generatePIN();

      $user = new User;
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
      $user->password = Hash::make($pin);
      $user->save();
      $user->roles()->sync($this->role_id);


      if (isset(Auth::user()->company)) {
        $company = Auth::user()->company;
    }elseif (isset(Auth::user()->employee->company)) {
        $company = Auth::user()->employee->company;
    }

    Mail::to($this->email)->send(new AccountCreationMail($user, $company,$pin));

      $employee = new Employee;
      if (isset(Auth::user()->company)) {
        $employee->company_id = Auth::user()->company->id;
      } else {
        $employee->company_id = Auth::user()->employee->company->id;
      }
      $employee->creator_id = Auth::user()->id;
      $employee->user_id = $user->id;
      $employee->employee_number = $this->employeeNumber();
      $employee->name = $this->name;
      $employee->middle_name = $this->middle_name;
      $employee->surname = $this->surname;
      $employee->phonenumber = $this->phonenumber;
      $employee->email = $this->email;
      $employee->pin = $pin;
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
      $employee->suburb = $this->suburb;
      $employee->salary = $this->salary;
      $employee->frequency = $this->frequency;
      $employee->currency_id = $this->currency_id;
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
      $employee->save();
      $employee->departments()->sync($this->selectedDepartment);
      $employee->ranks()->sync($this->rank_id);

      $rank = Rank::find($this->rank_id);
   
        $driver = new Driver;
        $driver->creator_id = Auth::user()->id;
        $driver->employee_id = $employee->id;
        $driver->user_id = $user->id;
        $driver->driver_number = $this->driverNumber();
        $driver->transporter_id = $this->transporter_id;
        $driver->license_number = $this->license_number;
        $driver->passport_number = $this->passport_number;
        $driver->class = $this->class;
        $driver->experience = $this->experience;
        $driver->reference = $this->reference;
        $driver->reference_phonenumber = $this->reference_phonenumber;
        $driver->status = 1;
        $driver->save();

        if (isset($this->file) && isset($this->title) && $this->file != "") {
            if ($this->file->count()>0) {
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
        }
      Session::flash('success','Driver created successfully');
      $this->resetInputFields();
      return redirect()->route('drivers.index');

        }
        catch(\Exception $e){
        // Set Flash Message
        $this->dispatchBrowserEvent('alert',[
            'type'=>'error',
            'message'=>"Something went wrong while creating driver!!"
        ]);
    }

      }
    public function render()
    {

        $this->departments = Department::orderBy('name','asc')->get();
        $this->branches = Branch::orderBy('name','asc')->get();
        $this->job_titles = JobTitle::latest()->get();
        $this->countries = Country::orderBy('name','asc')->get();

        return view('livewire.drivers.create',[
            'departments' => $this->departments,
            'branches' => $this->branches,
            'job_titles' => $this->job_titles,
            'countries' => $this->countries,
        ]);
    }
}
