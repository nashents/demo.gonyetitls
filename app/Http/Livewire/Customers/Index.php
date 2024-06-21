<?php

namespace App\Http\Livewire\Customers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Contact;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Document;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Livewire\WithFileUploads;
use App\Exports\CustomersExport;
use App\Mail\AccountCreationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Ixudra\Curl\Facades\Curl;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    private $customers;
    public $contact_name;
    public $contact_surname;
    public $contact_email;
    public $contact_phonenumber;
    public $department;
    public $name;
    public $initials;
    public $phonenumber;
    public $currencies;
    public $currency_id;
    public $worknumber;
    public $email;
    public $country;
    public $tin_number;
    public $vat_number;
    public $town;
    public $suburb;
    public $street_address;

    public $search;
    protected $queryString = ['search'];

    public $customer_id;
    public $user_id;


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

    
    public $contacts_inputs = [];
    public $o = 1;
    public $m = 1;

    public function contactsAdd($o)
    {
        $o = $o + 1;
        $this->o = $o;
        array_push($this->contacts_inputs ,$o);
    }

    public function contactsRemove($o)
    {
        unset($this->contacts_inputs[$o]);
    }

    
    public function exportCustomersCSV(Excel $excel){

        return $excel->download(new CustomersExport, 'customers.csv', Excel::CSV);
    }
    public function exportCustomersPDF(Excel $excel){

        return $excel->download(new CustomersExport, 'customers.pdf', Excel::DOMPDF);
    }
    public function exportCustomersExcel(Excel $excel){
        return $excel->download(new CustomersExport, 'customers.xlsx');
    }


    public function mount(){
        $this->resetPage();
        $this->currencies = Currency::orderBy('name','asc')->get();
    }

    public function customerNumber(){
       
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

            $customer = Customer::orderBy('id', 'desc')->first();

        if (!$customer) {
            $customer_number =  $initials .'C'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $customer->id + 1;
            $customer_number =  $initials .'C'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $customer_number;


    }

    private function resetInputFields(){
        $this->customers = "";
        $this->contact_name = "";
        $this->contact_surname = "";
        $this->contact_email = "";
        $this->contact_phonenumber = "";
        $this->department = "";
        $this->name = "";
        $this->initials = "";
        $this->vat_number = "";
        $this->tin_number = "";
        $this->currency_id = "";
        $this->phonenumber = "";
        $this->worknumber = "";
        $this->email = "";
        $this->title = "";
        $this->file = "";
        $this->expires_at = "";
        $this->country = "";
        $this->town = "";
        $this->suburb = "";
        $this->street_address = "";
    }
    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'name' => 'required|unique:customers,name,NULL,id,deleted_at,NULL|string|min:2',
        // 'contact_name.0' => 'required|string|min:2',
        // 'contact_surname.0' => 'required|string|min:2',
        // 'contact_email.0' => 'required|email',
        // 'contact_phonenumber.0' => 'required|string|min:2',
        // 'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
        // 'phonenumber' => 'required',
        // 'department' => 'required',
        // 'worknumber' => 'required',
        // 'country' => 'required|string|min:2',
        // 'city' => 'required|string|min:2',
        // 'suburb' => 'required|string|min:2',
        // 'street_address' => 'required|string|min:2',

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


    public function store(){
        // try{
        $pin = $this->generatePIN();

        $user = new User;
        $user->name = $this->name;
        $user->category = 'customer';
        $user->email = $this->email;
        $user->password = Hash::make($pin);
        $user->save();

        if (isset(Auth::user()->company)) {
            $company = Auth::user()->company;
        }elseif (isset(Auth::user()->employee->company)) {
            $company = Auth::user()->employee->company;
        }

        Mail::to($this->email)->send(new AccountCreationMail($user, $company,$pin));

        $customer = new Customer;
        $customer->creator_id = Auth::user()->id;
        $customer->company_id = Auth::user()->employee->company->id;
        $customer->user_id = $user->id;
        $customer->currency_id = $this->currency_id ? $this->currency_id : NULL;
        $customer->name = $this->name;
        $customer->initials = $this->initials;
        $customer->customer_number = $this->customerNumber();
        $customer->email = $this->email;
        $customer->pin = $pin;
        $customer->phonenumber = $this->phonenumber;
        $customer->vat_number = $this->vat_number;
        $customer->tin_number = $this->tin_number;
        $customer->worknumber = $this->worknumber;
        $customer->country = $this->country;
        $customer->city = $this->town;
        $customer->suburb = $this->suburb;
        $customer->street_address = $this->street_address;
        $customer->status = 1;
        $customer->save();

        if (isset($this->contact_name)) {
            foreach ($this->contact_name as $key => $value) {
               $contact = new Contact;
               $contact->customer_id = $customer->id;
               $contact->category = 'customer';
               if (isset($this->contact_name[$key])) {
                $contact->name = $this->contact_name[$key];
               }
               if (isset($this->contact_surname[$key])) {
                $contact->surname = $this->contact_surname[$key];
               }
                if (isset($this->contact_phonenumber[$key])) {
                    $contact->phonenumber = $this->contact_phonenumber[$key];
                }
                if (isset($this->contact_email[$key])) {
                    $contact->email = $this->contact_email[$key];
                }
                if (isset($this->department[$key])) {
                    $contact->department = $this->department[$key];
                }
              
               $contact->save();
            }
        }

        if (isset($this->file) && isset($this->title) && $this->file != "") {
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
              $document->customer_id = $customer->id;
              $document->category = 'customer';
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

        $this->dispatchBrowserEvent('hide-customerModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Customer Created Successfully!!"
        ]);

        return redirect(request()->header('Referer'));

        // }
        //     catch(\Exception $e){
        //     // Set Flash Message
        //     $this->dispatchBrowserEvent('alert',[
        //         'type'=>'error',
        //         'message'=>"Something went wrong while creating customer!!"
        //     ]);
        // }
    }

    public function edit($id){
    $customer = Customer::find($id);
    $this->user_id = $customer->user_id;
    $this->name = $customer->name;
    $this->email = $customer->email;
    $this->phonenumber = $customer->phonenumber;
    $this->worknumber = $customer->worknumber;
    $this->country = $customer->country;
    $this->vat_number = $customer->vat_number;
    $this->tin_number = $customer->tin_number;
    $this->initials = $customer->initials;
    $this->city = $customer->city;
    $this->currency_id = $customer->currency_id;
    $this->suburb = $customer->suburb;
    $this->street_address = $customer->street_address;
    $this->customer_id = $customer->id;
    $this->dispatchBrowserEvent('show-customerEditModal');

    }

    public function update()
    {
        if ($this->customer_id) {
            try{
            $customer = Customer::find($this->customer_id);
            $customer->user_id = Auth::user()->id;
            $customer->name = $this->name;
            $customer->initials = $this->initials;
            $customer->phonenumber = $this->phonenumber;
            $customer->worknumber = $this->worknumber;
            $customer->vat_number = $this->vat_number;
            $customer->tin_number = $this->tin_number;
            $customer->email = $this->email;
            $customer->currency_id = $this->currency_id;
            $customer->country = $this->country;
            $customer->city = $this->town;
            $customer->suburb = $this->suburb;
            $customer->street_address = $this->street_address;
            $customer->update();

            $this->dispatchBrowserEvent('hide-customerEditModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Customer Updated Successfully!!"
            ]);
            return redirect(request()->header('Referer'));
            }
                catch(\Exception $e){
                // Set Flash Message
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'error',
                    'message'=>"Something went wrong while updating customer!!"
                ]);
            }

        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        
             // sleep(1);

             $departments = Auth::user()->employee->departments;
             foreach($departments as $department){
                 $department_names[] = $department->name;
             }
             $roles = Auth::user()->roles;
             foreach($roles as $role){
                 $role_names[] = $role->name;
             }
             $ranks = Auth::user()->employee->ranks;
             foreach($ranks as $rank){
                 $rank_names[] = $rank->name;
             }
             if (in_array('Admin', $role_names) || in_array('Super Admin', $role_names)) {
                 
                 if (isset($this->search)) {
                    
                     return view('livewire.customers.index',[
                         'customers' => Customer::query()->with(['invoices'])
                         ->where('customer_number','like', '%'.$this->search.'%')
                         ->orWhere('name','like', '%'.$this->search.'%')
                         ->orWhere('email','like', '%'.$this->search.'%')
                         ->orWhere('vat_number','like', '%'.$this->search.'%')
                         ->orWhere('tin_number','like', '%'.$this->search.'%')
                         ->orderBy('customer_number','desc')->paginate(10),
                     ]);
                 }
                 else {
                    
                     return view('livewire.customers.index',[
                        'customers' => Customer::query()->with(['invoices'])->orderBy('customer_number','desc')->paginate(10),
                        
                     ]);
                   
                 }
             }else {
              
       
                 if (isset($this->search)) {
                     return view('livewire.customers.index',[
                        'customers' => Customer::query()->with(['invoices'])
                        ->where('user_id',Auth::user()->id)
                        ->where('customer_number','like', '%'.$this->search.'%')
                        ->orWhere('name','like', '%'.$this->search.'%')
                        ->orWhere('email','like', '%'.$this->search.'%')
                        ->orWhere('vat_number','like', '%'.$this->search.'%')
                        ->orWhere('tin_number','like', '%'.$this->search.'%')
                        ->orderBy('customer_number','desc')->paginate(10),
                     ]);
                 }
                 else {
                     
                     return view('livewire.customers.index',[
                        'customers' => Customer::query()->with(['invoices'])->where('user_id',Auth::user()->id)->orderBy('customer_number','desc')->paginate(10),
                     ]);
 
                 }
     
             }
        
 
       
    }
}
