<?php

namespace App\Http\Livewire\Companies;

use App\Models\Rank;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Transporter;
use App\Mail\AccountCreationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class Index extends Component
{
    public $name;
    public $email;
    public $selectedType;
    public $selectedPlan;
    public $license_currency_id;
    public $fee;
    public $role_id;
    public $rank_id;
    public $ranks;
    public $website;
    public $roles;
    public $phonenumber;
    public $country;
    public $city;
    public $status;
    public $noreply;
    public $suburb;
    public $street_address;
    public $companies;
    public $company_id;
    public $user_id;
    public $admin_id;

    public function mount(){
        if (Auth::user()->is_admin()) {
            $this->companies = Company::latest()->get();
        }else {
            $this->companies = Company::where('type','!=','admin')->get();
        }
      
        $this->roles = Role::all();
        $this->currencies = Currency::all();
        $this->noreply = 'noreply@gonyetitls.co.zw';
    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        
        'name' => 'required|unique:companies,name,NULL,id,deleted_at,NULL|string|min:2',
        'phonenumber' => 'required|unique:companies,phonenumber,NULL,id,deleted_at,NULL',
        'email' => 'required|unique:users,email,NULL,id,deleted_at,NULL',
        'selectedType' => 'required',
        'selectedPlan' => 'required',
        'fee' => 'required',
        'status' => 'required',
        'country' => 'required',
        'city' => 'required',
        'suburb' => 'required',
        'role_id' => 'required',
        'street_address' => 'required',
    ];

    private function resetInputFields(){
        $this->email = '';
        $this->phonenumber = '';
        $this->country = '';
        $this->city = '';
        $this->selectedType = '';
        $this->selectedPlan = '';
        $this->license_currency_id = '';
        $this->fee = '';
        $this->status = '';
        $this->website = '';
        $this->suburb = '';
        $this->street_address = '';
        $this->role_id = '';
        $this->name = '';
    }
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

    public function transporterNumber(){

        if (isset($this->company)) {
            $str =$this->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }elseif (isset($this->company)) {
            $str = $this->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }

            $transporter = Transporter::orderBy('id','desc')->first();

        if (!$transporter) {
            $transporter_number =  $initials .'T'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $transporter->id + 1;
            $transporter_number =  $initials .'T'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $transporter_number;


    }

    public function updatedSelectedPlan($plan){
        if (!is_null($plan)) {
            if ($plan == "10") {
                $this->fee = 50;
            }elseif ($plan == "40") {
                $this->fee = 100;
            }elseif ($plan == "100") {
                $this->fee = 150;
            }elseif ($plan == "150") {
                $this->fee = 200;
            }elseif ($plan == "200") {
                $this->fee = 250;
            }elseif ($plan == "500") {
                $this->fee = 300;
            }
        }
    }
    public function updatedSelectedType($type){
        if (!is_null($type)) {
            if ($type == "Broker") {
                $this->fee = 100;
            }
        }
    }

    public function store(){
        // try{
        $pin = $this->generatePIN();

        $user = new User;
        $user->name = $this->name;
        $user->category = 'company';
        $user->email = $this->email;
        $user->password = Hash::make($pin);
        $user->save();
        $user->roles()->attach($this->role_id);

        
        if (isset(Auth::user()->company)) {
            $company = Auth::user()->company;
        }elseif (isset(Auth::user()->employee->company)) {
            $company = Auth::user()->employee->company;
        }

        Mail::to($this->email)->send(new AccountCreationMail($user, $company,$pin));

        $company = new Company;
        $company->admin_id = Auth::user()->id;
        $company->user_id = $user->id;
        $company->type = $this->selectedType;
        $company->name = $this->name;
        $company->email = $this->email;
        $company->plan = $this->selectedPlan;
        $company->fee = $this->fee;
        $company->license_currency_id = $this->license_currency_id;
        $company->noreply = "noreply@gonyetitls.co.zw";
        $company->username = $this->email;
        $company->website = $this->website;
        $company->pin = $pin;
        $company->phonenumber = $this->phonenumber;
        $company->country = $this->country;
        $company->city = $this->city;
        $company->suburb = $this->suburb;
        $company->street_address = $this->street_address;
        $company->save();
        $this->company = $company;
        $this->company_id = $company->id;

        $pin = $this->generatePIN();

        $transporter_user = new User;
        $transporter_user->name = $this->name;
        $transporter_user->category = 'transporter';
        $transporter_user->email = $this->email;
        $transporter_user->password = Hash::make($pin);
        $transporter_user->save();

        Mail::to($this->email)->send(new AccountCreationMail($transporter_user, $company,$pin));

        $transporter = new Transporter;
        $transporter->creator_id = Auth::user()->id;
        $transporter->company_id = $company->id;
        $transporter->user_id = $transporter_user->id;
        $transporter->name = $this->name;
        $transporter->transporter_number = $this->transporterNumber();
        $transporter->email = $this->email;
        $transporter->pin = $pin;
        $transporter->phonenumber = $this->phonenumber;
        $transporter->country = $this->country;
        $transporter->city = $this->city;
        $transporter->suburb = $this->suburb;
        $transporter->street_address = $this->street_address;
        $transporter->status = 1;
        $transporter->save();

        $this->dispatchBrowserEvent('hide-companyModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Company Created Successfully!!"
        ]);

    //     }
    //     catch(\Exception $e){
    //     // Set Flash Message
    //     $this->dispatchBrowserEvent('alert',[
    //         'type'=>'error',
    //         'message'=>"Something goes wrong while creating company!!"
    //     ]);
    // }

    }

    public function edit($id){
        $company = Company::find($id);
        $this->user_id = $company->user_id;
        $this->admin_id = $company->admin_id;
        $this->name = $company->name;
        $this->selectedType = $company->type;
        $this->status = $company->status;
        $roles = $company->user->roles;
        
        if (isset($roles)) {
            foreach ($roles as $role) {
                $this->role_id[] = $role->id;
            }
          
        }
       
        $this->selectedPlan = $company->plan;
        $this->fee = $company->fee;
        $this->license_currency_id = $company->license_currency_id;
        $this->phonenumber = $company->phonenumber;
        $this->email = $company->email;
        $this->noreply = $company->noreply;
        $this->website = $company->website;
        $this->country = $company->country;
        $this->city = $company->city;
        $this->suburb = $company->suburb;
        $this->street_address = $company->street_address;
        $this->company_id = $company->id;
        $this->dispatchBrowserEvent('show-companyEditModal');

        }


        public function update()
        {
            if ($this->company_id) {
                try{
                $company = Company::find($this->company_id);
                
                $user = $company->user;
                $user->name = $this->name;
                $user->email = $this->email;
                $user->update();

                $company->user_id = $this->user_id;
                $company->admin_id = Auth::user()->id;
                $company->name = $this->name;
                $company->type = $this->selectedType;
                $company->phonenumber = $this->phonenumber;
                $company->email = $this->email;
                $company->license_currency_id = $this->license_currency_id;
                $company->status = $this->status;
                $company->plan = $this->selectedPlan;
                $company->fee = $this->fee;
                $company->noreply = $this->noreply;
                $company->website = $this->website;
                $company->username = $this->email;
                $company->country = $this->country;
                $company->city = $this->city;
                $company->suburb = $this->suburb;
                $company->street_address = $this->street_address;
                $company->update();

                $this->dispatchBrowserEvent('hide-companyEditModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Company Updated Successfully!!"
                ]);

                return redirect(request()->header('Referer'));
                }
                catch(\Exception $e){
                $this->dispatchBrowserEvent('hide-companyEditModal');
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'error',
                    'message'=>"Something goes wrong while creating company!!"
                ]);
              }
            }
        }

    public function render()
    {
        if (Auth::user()->is_admin()) {
            $this->companies = Company::latest()->get();
        }else {
            $this->companies = Company::where('type','!=','admin')->get();
        }
        return view('livewire.companies.index',[
            'companies' => $this->companies
        ]);
    }
}
