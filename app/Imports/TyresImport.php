<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Tyre;
use App\Models\Brand;
use App\Models\Horse;
use App\Models\Store;
use App\Models\Product;
use App\Models\Trailer;
use App\Models\Vehicle;
use App\Models\Category;
use App\Models\Currency;
use App\Models\TyreDispatch;
use App\Models\TyreAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TyresImport implements  ToCollection,
WithHeadingRow,
SkipsOnError,
WithValidation,
WithChunkReading
{
    use Importable, SkipsErrors;

    public $horse;
    public $trailer;
    public $vehicle;
    public $tyre;
    public $product;
    public $category;
    public $store;
    public $brand;
    public $currency;



    public function tyreNumber(){

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

            $tyre = Tyre::orderBy('id', 'desc')->first();

        if (!$tyre) {
            $tyre_number =  $initials .'T'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $tyre->id + 1;
            $tyre_number =  $initials .'T'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $tyre_number;


    }

    
    public function productNumber(){

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

        $product = Product::where('department','tyre')->orderBy('id','desc')->first();

        if (!$product) {
            $product_number =  $initials .'TP'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $product->id + 1;
            $product_number =  $initials .'TP'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $product_number;


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

        $this->tyre = Tyre::where('serial_number',$row['serial_number'])->get()->first();  
        $this->horse = Horse::where('registration_number',$row['horse_reg_number'])->get()->first();  
        $this->vehicle = Vehicle::where('registration_number', $row['vehicle_reg_number'])->get()->first();
        $this->trailer = Trailer::where('registration_number', $row['trailer_reg_number'])->get()->first();
        $this->store = Store::where('name', $row['store_name'])->get()->first();
        $this->category = Category::where('name', $row['category'])->get()->first();
        $this->brand = Brand::where('name', $row['brand_name'])->get()->first();
        $this->product = Product::where('name', $row['product_name'])->get()->first();
        $this->currency = Currency::where('name',$row['currency'])->get()->first();
        

        if (isset($this->tyre)) {

            if (isset($this->product)) {

                $tyre = Tyre::find($this->tyre->id);

                if (isset($this->currency)) {
                    $tyre->currency_id = $this->currency->id;
                }elseif(!isset($this->currency) && $row['currency'] != "") {
                    $currency = new Currency;
                    $currency->name = $row['currency'];
                    $currency->save();
                    $tyre->currency_id = $currency->id;
                }
                if (isset($this->store)) {
                    $tyre->store_id = $this->store->id;
                }elseif(!isset($this->store) && $row['store_name'] != ""){
                    $store = new Store;
                    $store->name = $row['store_name'];
                    $store->save();
                    $tyre->store_id = $store->id;
                }
                $tyre->product_id = $this->product->id;
                $tyre->serial_number = $row['serial_number'];
                $tyre->rate = $row['unit_price'];
                $tyre->type = $row['type'];
                $tyre->width = $row['width'];
                $tyre->aspect_ratio = $row['aspect_ratio'];
                $tyre->diameter = $row['diameter'];
                $tyre->qty = 1;
                if (isset($row['purchase_date'])) {
                    $tyre->purchase_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['purchase_date']));
                }
                
                $tyre->update();

                if (isset($row['horse_reg_number']) || isset($row['vehicle_reg_number']) || isset($row['trailer_reg_number'])) {

                    $tyre_assignment = $this->tyre->tyre_assignment;

                    if (isset($tyre_assignment)) {  
                    $assignment = TyreAssignment::find($tyre_assignment->id);
                    $assignment->tyre_id = $this->tyre->id;
                    if(isset($row['horse_reg_number'])){
                        $assignment->type = "Horse";
                        if (isset($this->horse)) {
                            $assignment->horse_id = $this->horse->id;
                        }
                    }elseif(isset($row['vehicle_reg_number'])){
                        $assignment->type = "Vehicle";
                        if (isset($this->vehicle)) {
                            $assignment->vehicle_id = $this->vehicle->id;
                        }
                    }elseif(isset($row['trailer_reg_number'])){
                        $assignment->type = "Trailer";
                        if (isset($this->trailer)) {
                            $assignment->trailer_id = $this->trailer->id;
                        }
                    }
                    $assignment->starting_odometer = $row['starting_mileage'];
                    $assignment->position = $row['position'];
                    $assignment->axle = $row['axle'];
                    $assignment->status = 1;
                    $assignment->update();

                    if (isset($assignment)) {
                        $tyre_dispatch = $assignment->tyre_dispatch;

                        if (isset($tyre_dispatch)) {
                            $dispatch = TyreDispatch::find($tyre_dispatch->id);
                            $dispatch->tyre_assignment_id = $assignment->id;
                            $dispatch->tyre_id = $this->tyre->id;
                            $dispatch->tyre_number = $this->tyre->tyre_number;
                            $dispatch->serial_number = $this->tyre->serial_number;
                            $dispatch->width = $this->tyre->width;
                            $dispatch->aspect_ratio = $this->tyre->aspect_ratio;
                            $dispatch->diameter =  $this->tyre->diameter;
                            if (isset($this->horse)) {
                                $dispatch->horse_id = $this->horse->id;
                            }elseif(isset($this->vehicle)){
                                $dispatch->vehicle_id = $this->vehicle->id;
                            }elseif(isset($this->trailer)){
                                $dispatch->trailer_id = $this->trailer->id;
                            }
                            $dispatch->update();
                        }else {
                            $dispatch = new TyreDispatch;
                            $dispatch->tyre_assignment_id = $assignment->id;
                            $dispatch->tyre_id = $this->tyre->id;
                            $dispatch->tyre_number = $this->tyre->tyre_number;
                            $dispatch->serial_number = $this->tyre->serial_number;
                            $dispatch->width = $this->tyre->width;
                            $dispatch->aspect_ratio = $this->tyre->aspect_ratio;
                            $dispatch->diameter =  $this->tyre->diameter;
                            if (isset($this->horse)) {
                                $dispatch->horse_id = $this->horse->id;
                            }elseif(isset($this->vehicle)){
                                $dispatch->vehicle_id = $this->vehicle->id;
                            }elseif(isset($this->trailer)){
                                $dispatch->trailer_id = $this->trailer->id;
                            }
                            $dispatch->save();
                        }
                   
                    }
  
                    $tyre = Tyre::find($this->tyre->id);
                    $tyre->status = 0;
                    $tyre->update();

                    }else {
                    

                    $assignment = new TyreAssignment;
                    $assignment->user_id = Auth::user()->id;
                    $assignment->tyre_id = $this->tyre->id;

                    if(isset($row['horse_reg_number'])){
                        $assignment->type = "Horse";
                        if (isset($this->horse)) {
                            $assignment->horse_id = $this->horse->id;
                        }
                    }elseif(isset($row['vehicle_reg_number'])){
                        $assignment->type = "Vehicle";
                        if (isset($this->vehicle)) {
                            $assignment->vehicle_id = $this->vehicle->id;
                        }
                    }elseif(isset($row['trailer_reg_number'])){
                        $assignment->type = "Trailer";
                        if (isset($this->trailer)) {
                            $assignment->trailer_id = $this->trailer->id;
                        }
                    }

                    $assignment->starting_odometer = $row['starting_mileage'];
                    $assignment->position = $row['position'];
                    $assignment->axle = $row['axle'];
                    $assignment->status = 1;
                    $assignment->save();

                    if (isset($assignment)) {

                        $dispatch = new TyreDispatch;
                        $dispatch->tyre_assignment_id = $assignment->id;
                        $dispatch->tyre_id = $this->tyre->id;
                        $dispatch->tyre_number = $this->tyre->tyre_number;
                        $dispatch->serial_number = $this->tyre->serial_number;
                        $dispatch->width = $this->tyre->width;
                        $dispatch->aspect_ratio = $this->tyre->aspect_ratio;
                        $dispatch->diameter =  $this->tyre->diameter;

                        if (isset($this->horse)) {
                            $dispatch->horse_id = $this->horse->id;
                        }elseif(isset($this->vehicle)){
                            $dispatch->vehicle_id = $this->vehicle->id;
                        }elseif(isset($this->trailer)){
                            $dispatch->trailer_id = $this->trailer->id;
                        }
                        $dispatch->save();
                    }
  
            
                    $tyre = Tyre::find($tyre->id);
                    $tyre->status = 0;
                    $tyre->update();

                    }

                }

            }
            // new product of exsting tyre
            else {
       
                $product = new Product;
                $product->user_id = Auth::user()->id;
                $product->product_number = $this->productNumber();
                if (isset($this->category)) {
                    $product->category_id = $this->category->id;
                }elseif(!isset($this->category) && $row['category'] != ""){
                    $category = new Category;
                    $category->name = $row['category'];
                    $category->save();
                    $product->category_id = $category->id;
                }

                if (isset($this->brand)) {
                    $product->brand_id = $this->brand->id;
                }elseif(!isset($this->brand) && $row['brand_name'] != ""){
                    $brand = new Brand;
                    $brand->name = $row['brand_name'];
                    $brand->status = 1;
                    $brand->save();
                    $product->brand_id = $brand->id;
                }
               
                $product->name = $row['product_name'];
                $product->department = 'tyre';
                $product->status = '1';
                $product->save(); 
                 
                $tyre = Tyre::find($this->tyre->id);

                if (isset($this->currency)) {
                    $tyre->currency_id = $this->currency->id;
                }elseif(!isset($this->currency) && $row['currency'] != "") {
                    $currency = new Currency;
                    $currency->name = $row['currency'];
                    $currency->save();
                    $tyre->currency_id = $currency->id;
                }
                if (isset($this->store)) {
                    $tyre->store_id = $this->store->id;
                }elseif(!isset($this->store) && $row['store_name'] != ""){
                    $store = new Store;
                    $store->name = $row['store_name'];
                    $store->save();
                    $tyre->store_id = $store->id;
                }
                $tyre->product_id = $product->id;
                $tyre->serial_number = $row['serial_number'];
                $tyre->rate = $row['unit_price'];
                $tyre->type = $row['type'];
                $tyre->width = $row['width'];
                $tyre->aspect_ratio = $row['aspect_ratio'];
                $tyre->diameter = $row['diameter'];
                $tyre->qty = 1;
                if (isset($row['purchase_date'])) {
                    $tyre->purchase_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['purchase_date']));
                }
                $tyre->status = '1';
                $tyre->update();

                    if (isset($row['horse_reg_number']) || isset($row['vehicle_reg_number']) || isset($row['trailer_reg_number'])) {

                            $tyre_assignment = $this->tyre->tyre_assignment;
                            
                            if (isset($tyre_assignment)) {
                            
                            $assignment = TyreAssignment::find($tyre_assignment->id);
                            $assignment->tyre_id = $this->tyre->id;

                            if(isset($row['horse_reg_number'])){
                                $assignment->type = "Horse";
                                if (isset($this->horse)) {
                                    $assignment->horse_id = $this->horse->id;
                                }
                            }elseif(isset($row['vehicle_reg_number'])){
                                $assignment->type = "Vehicle";
                                if (isset($this->vehicle)) {
                                    $assignment->vehicle_id = $this->vehicle->id;
                                }
                            }elseif(isset($row['trailer_reg_number'])){
                                $assignment->type = "Trailer";
                                if (isset($this->trailer)) {
                                    $assignment->trailer_id = $this->trailer->id;
                                }
                            }

                            $assignment->starting_odometer = $row['starting_mileage'];
                            $assignment->position = $row['position'];
                            $assignment->axle = $row['axle'];
                            $assignment->status = 1;
                            $assignment->update();

                            if (isset($assignment)) {
                                $tyre_dispatch = $assignment->tyre_dispatch;

                                if (isset($tyre_dispatch)) {
                                    $dispatch = TyreDispatch::find($tyre_dispatch->id);
                                    $dispatch->tyre_assignment_id = $assignment->id;
                                    $dispatch->tyre_id = $this->tyre->id;
                                    $dispatch->tyre_number = $this->tyre->tyre_number;
                                    $dispatch->serial_number = $this->tyre->serial_number;
                                    $dispatch->width = $this->tyre->width;
                                    $dispatch->aspect_ratio = $this->tyre->aspect_ratio;
                                    $dispatch->diameter =  $this->tyre->diameter;
                                    if (isset($this->horse)) {
                                        $dispatch->horse_id = $this->horse->id;
                                    }elseif(isset($this->vehicle)){
                                        $dispatch->vehicle_id = $this->vehicle->id;
                                    }elseif(isset($this->trailer)){
                                        $dispatch->trailer_id = $this->trailer->id;
                                    }
                                    $dispatch->update();
                                }else{
                                    $dispatch =  new TyreDispatch;
                                    $dispatch->tyre_assignment_id = $assignment->id;
                                    $dispatch->tyre_id = $this->tyre->id;
                                    $dispatch->tyre_number = $this->tyre->tyre_number;
                                    $dispatch->serial_number = $this->tyre->serial_number;
                                    $dispatch->width = $this->tyre->width;
                                    $dispatch->aspect_ratio = $this->tyre->aspect_ratio;
                                    $dispatch->diameter =  $this->tyre->diameter;
                                    if (isset($this->horse)) {
                                        $dispatch->horse_id = $this->horse->id;
                                    }elseif(isset($this->vehicle)){
                                        $dispatch->vehicle_id = $this->vehicle->id;
                                    }elseif(isset($this->trailer)){
                                        $dispatch->trailer_id = $this->trailer->id;
                                    }
                                    $dispatch->save();
                                }
                        
                            }
        
                            $tyre = Tyre::find($this->tyre->id);
                            $tyre->status = 0;
                            $tyre->update();

                            }else {
                            
                            $assignment = new TyreAssignment;
                            $assignment->user_id = Auth::user()->id;
                            $assignment->tyre_id = $this->tyre->id;

                            if(isset($row['horse_reg_number'])){
                                $assignment->type = "Horse";
                                if (isset($this->horse)) {
                                    $assignment->horse_id = $this->horse->id;
                                }
                            }elseif(isset($row['vehicle_reg_number'])){
                                $assignment->type = "Vehicle";
                                if (isset($this->vehicle)) {
                                    $assignment->vehicle_id = $this->vehicle->id;
                                }
                            }elseif(isset($row['trailer_reg_number'])){
                                $assignment->type = "Trailer";
                                if (isset($this->trailer)) {
                                    $assignment->trailer_id = $this->trailer->id;
                                }
                            }

                            $assignment->starting_odometer = $row['starting_mileage'];
                            $assignment->position = $row['position'];
                            $assignment->axle = $row['axle'];
                            $assignment->status = 1;
                            $assignment->save();

                            if (isset($assignment)) {
                                $dispatch = new TyreDispatch;
                                $dispatch->tyre_assignment_id = $assignment->id;
                                $dispatch->tyre_id = $this->tyre->id;
                                $dispatch->tyre_number = $this->tyre->tyre_number;
                                $dispatch->serial_number = $this->tyre->serial_number;
                                $dispatch->width = $this->tyre->width;
                                $dispatch->aspect_ratio = $this->tyre->aspect_ratio;
                                $dispatch->diameter =  $this->tyre->diameter;

                                if (isset($this->horse)) {
                                    $dispatch->horse_id = $this->horse->id;
                                }elseif(isset($this->vehicle)){
                                    $dispatch->vehicle_id = $this->vehicle->id;
                                }elseif(isset($this->trailer)){
                                    $dispatch->trailer_id = $this->trailer->id;
                                }
                                $dispatch->save();
                            }
        
                    
                            $tyre = Tyre::find($tyre->id);
                            $tyre->status = 0;
                            $tyre->update();

                            }

                    }
 
            }

        }
        // new tyre
        else {

            if (isset($this->product)) {
                $tyre = new Tyre;
                $tyre->user_id = Auth::user()->id;
                $tyre->tyre_number = $this->tyreNumber();

                if (isset($this->currency)) {
                    $tyre->currency_id = $this->currency->id;
                }elseif(!isset($this->currency) && $row['currency'] != "") {
                    $currency = new Currency;
                    $currency->name = $row['currency'];
                    $currency->save();
                    $tyre->currency_id = $currency->id;
                }
                if (isset($this->store)) {
                    $tyre->store_id = $this->store->id;
                }elseif(!isset($this->store) && $row['store_name'] != ""){
                    $store = new Store;
                    $store->name = $row['store_name'];
                    $store->save();
                    $tyre->store_id = $store->id;
                }
                $tyre->product_id = $this->product->id;
                $tyre->serial_number = $row['serial_number'];
                $tyre->rate = $row['unit_price'];
                $tyre->type = $row['type'];
                $tyre->width = $row['width'];
                $tyre->aspect_ratio = $row['aspect_ratio'];
                $tyre->diameter = $row['diameter'];
                $tyre->qty = 1;
                if (isset($row['purchase_date'])) {
                    $tyre->purchase_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['purchase_date']));
                }
                $tyre->status = '1';
                $tyre->save();

                if (isset($row['horse_reg_number']) || isset($row['vehicle_reg_number']) || isset($row['trailer_reg_number'])) {

                    $assignment = new TyreAssignment;
                    $assignment->user_id = Auth::user()->id;
                    $assignment->tyre_id = $tyre->id;

                    if(isset($row['horse_reg_number'])){
                        $assignment->type = "Horse";
                        if (isset($this->horse)) {
                            $assignment->horse_id = $this->horse->id;
                        }
                    }elseif(isset($row['vehicle_reg_number'])){
                        $assignment->type = "Vehicle";
                        if (isset($this->vehicle)) {
                            $assignment->vehicle_id = $this->vehicle->id;
                        }
                    }elseif(isset($row['trailer_reg_number'])){
                        $assignment->type = "Trailer";
                        if (isset($this->trailer)) {
                            $assignment->trailer_id = $this->trailer->id;
                        }
                    }

                    $assignment->starting_odometer = $row['starting_mileage'];
                    $assignment->position = $row['position'];
                    $assignment->axle = $row['axle'];
                    $assignment->status = 1;
                    $assignment->save();

                    if (isset($assignment)) {
                        $tyre = Tyre::find($tyre->id);
                        $dispatch = new TyreDispatch;
                        $dispatch->tyre_assignment_id = $assignment->id;
                        $dispatch->tyre_id = $tyre->id;
                        $dispatch->tyre_number = $tyre->tyre_number;
                        $dispatch->serial_number = $tyre->serial_number;
                        $dispatch->width = $tyre->width;
                        $dispatch->aspect_ratio = $tyre->aspect_ratio;
                        $dispatch->diameter =  $tyre->diameter;

                        if (isset($this->horse)) {
                            $dispatch->horse_id = $this->horse->id;
                        }elseif(isset($this->vehicle)){
                            $dispatch->vehicle_id = $this->vehicle->id;
                        }elseif(isset($this->trailer)){
                            $dispatch->trailer_id = $this->trailer->id;
                        }
                       
                        $dispatch->save();
                    }
  
            
                    $tyre = Tyre::find($tyre->id);
                    $tyre->status = 0;
                    $tyre->update();
                }

            }else {
       
                $product = new Product;
                $product->user_id = Auth::user()->id;
                $product->product_number = $this->productNumber();
                if (isset($this->category)) {
                    $product->category_id = $this->category->id;
                }elseif(!isset($this->category) && $row['category'] != ""){
                    $category = new Category;
                    $category->name = $row['category'];
                    $category->save();
                    $product->category_id = $category->id;
                }

                if (isset($this->brand)) {
                    $product->brand_id = $this->brand->id;
                }elseif(!isset($this->brand) && $row['brand_name'] != ""){
                    $brand = new Brand;
                    $brand->name = $row['brand_name'];
                    $brand->status = 1;
                    $brand->save();
                    $product->brand_id = $brand->id;
                }
               
                $product->name = $row['product_name'];
                $product->department = 'tyre';
                $product->status = '1';
                $product->save(); 
                 
              
                $tyre = new Tyre;
                $tyre->user_id = Auth::user()->id;
                $tyre->tyre_number = $this->tyreNumber();

                if (isset($this->currency)) {
                    $tyre->currency_id = $this->currency->id;
                }elseif(!isset($this->currency) && $row['currency'] != "") {
                    $currency = new Currency;
                    $currency->name = $row['currency'];
                    $currency->save();
                    $tyre->currency_id = $currency->id;
                }

                if (isset($this->store)) {
                    $tyre->store_id = $this->store->id;
                }elseif(!isset($this->store) && $row['store_name'] != ""){
                    $store = new Store;
                    $store->name = $row['store_name'];
                    $store->save();
                    $tyre->store_id = $store->id;
                }

                $tyre->product_id = $product->id;
                $tyre->serial_number = $row['serial_number'];
                $tyre->rate = $row['unit_price'];
                $tyre->type = $row['type'];
                $tyre->width = $row['width'];
                $tyre->aspect_ratio = $row['aspect_ratio'];
                $tyre->diameter = $row['diameter'];
                $tyre->qty = 1;
                if (isset($row['purchase_date'])) {
                    $tyre->purchase_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['purchase_date']));
                }
                $tyre->status = '1';
                $tyre->save();

                if (isset($row['horse_reg_number']) || isset($row['vehicle_reg_number']) || isset($row['trailer_reg_number'])) {

                    $assignment = new TyreAssignment;
                    $assignment->user_id = Auth::user()->id;
                    $assignment->tyre_id = $tyre->id;

                    if(isset($row['horse_reg_number'])){
                        $assignment->type = "Horse";
                        if (isset($this->horse)) {
                            $assignment->horse_id = $this->horse->id;
                        }
                    }elseif(isset($row['vehicle_reg_number'])){
                        $assignment->type = "Vehicle";
                        if (isset($this->vehicle)) {
                            $assignment->vehicle_id = $this->vehicle->id;
                        }
                    }elseif(isset($row['trailer_reg_number'])){
                        $assignment->type = "Trailer";
                        if (isset($this->trailer)) {
                            $assignment->trailer_id = $this->trailer->id;
                        }
                    }

                    $assignment->starting_odometer = $row['starting_mileage'];
                    $assignment->position = $row['position'];
                    $assignment->axle = $row['axle'];
                    $assignment->status = 1;
                    $assignment->save();
            
                    $tyre = Tyre::find($tyre->id);
                    
                    $dispatch = new TyreDispatch;
                    $dispatch->tyre_assignment_id = $assignment->id;
                    $dispatch->tyre_id = $tyre->id;
                    $dispatch->tyre_number = $tyre->tyre_number;
                    $dispatch->serial_number = $tyre->serial_number;
                    $dispatch->width = $tyre->width;
                    $dispatch->aspect_ratio = $tyre->aspect_ratio;
                    $dispatch->diameter =  $tyre->diameter;

                    if (isset($this->horse)) {
                        $dispatch->horse_id = $this->horse->id;
                    }elseif(isset($this->vehicle)){
                        $dispatch->vehicle_id = $this->vehicle->id;
                    }elseif(isset($this->trailer)){
                        $dispatch->trailer_id = $this->trailer->id;
                    }
                   
                    $dispatch->save();
            
                    $tyre = Tyre::find($tyre->id);
                    $tyre->status = 0;
                    $tyre->update();
                }
 
            }
        }
    
    }
       }
    }

    public function rules(): array{
        return[
             '*.serial_number' => ['required'],
             '*.aspect_ratio' => ['required'],
             '*.diameter' => ['required'],
        ];
    }




    public function chunkSize(): int
    {
        return 1000;
    }
}