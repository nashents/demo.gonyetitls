<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Count;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Transporter;
use App\Imports\EmployeesImport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DriversImport implements  ToCollection,
WithHeadingRow,
SkipsOnError,
WithValidation,
WithChunkReading
{
    use Importable, SkipsErrors;
    public $transporter;
    public $transporter_id;


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
   

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
       foreach($rows as $row){
        if($row->filter()->isNotEmpty()){
        $pin =  $this->generatePIN();
        
        $transporter = Transporter::where('transporter_number',$row['transporter_number'])->get()->first();
      
        if (isset($transporter)) {
            $transporter_id = $transporter->id;
        }    

        $user = User::where('name',$row['name'])
                     ->where('surname',$row['surname'])->first();
       
        $employee = Employee::where('name',$row['name'])
                            ->where('surname',$row['surname'])->first();

        if ($employee) {
            $driver = Driver::where('employee_id',$employee->id)
                            ->where('license_number',$row['license_number'])->first();
        }
       
       
        if (isset($user) && isset($employee) && isset($driver)) {
       
        $user->name = $row['name'];
        $user->surname = $row['surname'];
        $user->category  = 'driver';
        $user->is_admin = '0';
        $user->active = '1';
        $user->email = $row['email'];
        $user->update();
        $user->roles()->attach([3]);

      
        $employee->name    = $row['name'];
        $employee->surname     = $row['surname'];
        $employee->gender     = $row['gender'];
        $employee->dob     = $row['dob'];
        $employee->email    = $row['email'];
        $employee->phonenumber    = $row['phonenumber'];
        $employee->idnumber    = $row['idnumber'];
        $employee->country    = $row['country'];
        $employee->city    = $row['city'];
        $employee->suburb    = $row['suburb'];
        $employee->duration    = $row['contract_duration'];
        $employee->start_date    = $row['start_date'];
        $employee->expiration    = $row['expiry_date'];
        $employee->street_address    = $row['streetaddress'];
        $employee->next_of_kin    = $row['nextofkin'];
        $employee->relationship    = $row['relationship'];
        $employee->contact    = $row['contact'];
        $employee->update();
        $employee->ranks()->attach([4]);

     
       if (isset($transporter_id) && $transporter_id != "") {
        $driver->transporter_id     = $transporter_id;
      }
     
       $driver->license_number    = $row['license_number'];
       $driver->passport_number    = $row['passport_number'];
       $driver->experience     = $row['experience'];
       $driver->class     = $row['class'];
       $driver->reference    = $row['reference'];
       $driver->reference_phonenumber    = $row['reference_phonenumber'];
       $driver->update();
       $transporter_id = "";
 
        } else {
           
        $user = User::create([
            'name'     => $row['name'],
            'category'     => 'employee',
            'surname'     => $row['surname'],
            'is_admin'     => '0',
            'active'     => '1',
            'email'     => $row['email'],
            'password'    => Hash::make($pin),

        ]);
        $user->roles()->attach([3]);

       $employee = new Employee;
       $employee->company_id     = Auth::user()->employee->company_id;
       $employee->creator_id     = Auth::user()->id;
       $employee->user_id     = $user->id;
       $employee->employee_number   = $this->employeeNumber();
       $employee->name    = $row['name'];
       $employee->surname     = $row['surname'];
       $employee->gender     = $row['gender'];
       $employee->dob     = $row['dob'];
       $employee->email    = $row['email'];
       $employee->pin    = $pin;
       $employee->phonenumber    = $row['phonenumber'];
       $employee->idnumber    = $row['idnumber'];
       $employee->country    = $row['country'];
       $employee->city    = $row['city'];
       $employee->suburb    = $row['suburb'];
       $employee->duration    = $row['contract_duration'];
       $employee->start_date    = $row['start_date'];
       $employee->expiration    = $row['expiry_date'];
       $employee->street_address    = $row['streetaddress'];
       $employee->next_of_kin    = $row['nextofkin'];
       $employee->relationship    = $row['relationship'];
       $employee->contact    = $row['contact'];
       $employee->save();
       $employee->ranks()->attach([4]);

       $driver = new Driver;
       if (isset($transporter_id) && $transporter_id != "") {
        $driver->transporter_id     = $transporter_id;
      }
       $driver->company_id     = Auth::user()->employee->company_id;
       $driver->creator_id     = Auth::user()->id;
       $driver->user_id     = $user->id;
       $driver->employee_id     = $employee->id;
       $driver->driver_number   = $this->driverNumber();
       $driver->license_number    = $row['license_number'];
       $driver->passport_number    = $row['passport_number'];
       $driver->experience     = $row['experience'];
       $driver->class     = $row['class'];
       $driver->reference    = $row['reference'];
       $driver->reference_phonenumber    = $row['reference_phonenumber'];
       $driver->save();
       $transporter_id = "";
        }
        


    }
       }

       
    }

    public function rules(): array{
        return[
            '*.transporter_number' => ['required'],
            // '*.license_number' => ['nullable','unique:drivers,license_number,NULL,id,deleted_at,NULL'],
            // '*.passport_number' => ['nullable','unique:drivers,passport_number,NULL,id,deleted_at,NULL'],
        ];
    }




    public function chunkSize(): int
    {
        return 1000;
    }
}
