<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\HorseMake;
use App\Models\Inventory;
use App\Models\HorseModel;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\CategoryValue;
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

class InventoriesImport implements  ToCollection,
WithHeadingRow,
SkipsOnError,
WithValidation,
WithChunkReading
{
    use Importable, SkipsErrors;

  

    public function inventoryNumber(){

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

            $inventory = Inventory::orderBy('id', 'desc')->first();

        if (!$inventory) {
            $inventory_number =  $initials .'I'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $inventory->id + 1;
            $inventory_number =  $initials .'I'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $inventory_number;


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

        $product = Product::where('department','inventory')->orderBy('id','desc')->first();

        if (!$product) {
            $product_number =  $initials .'IP'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $product->id + 1;
            $product_number =  $initials .'IP'. str_pad($number, 5, "0", STR_PAD_LEFT);
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


              
        $store = Store::where('name', 'like', '%'.$row['store_name'].'%')->get()->first();
        $category = Category::where('name', '%'.$row['category'].'%')->get()->first();
        $sub_category = CategoryValue::where('name', '%'.$row['sub_category'].'%')->get()->first();
        $brand = Brand::where('name', 'like', '%'.$row['brand_name'].'%')->get()->first();
        $product = Product::where('name', 'like', '%'.$row['product_name'].'%')->get()->first();
        $horse_make = HorseMake::where('name', 'like', '%'.$row['horse_make'].'%')->get()->first();
        $horse_model = HorseModel::where('name', 'like', '%'.$row['horse_model'].'%')->get()->first();
        $vehicle_make = VehicleMake::where('name', 'like', '%'.$row['vehicle_make'].'%')->get()->first();
        $vehicle_model = VehicleModel::where('name', 'like', '%'.$row['vehicle_model'].'%')->get()->first();
        $currency = Currency::where('name', 'like', '%'.$row['currency'].'%')->get()->first();
        

     
       
            
            if (isset($row['quantity']) && $row['quantity'] > 0 ) {

               for ($i= 0; $i < $row['quantity']; $i++) { 
            
                if (isset($product)) {
                
                    $inventory = new Inventory;
                    $inventory->user_id = Auth::user()->id;
                    $inventory->inventory_number = $this->inventoryNumber();
                    if (isset($currency)) {
                        $inventory->currency_id = $currency->id;
                    }elseif(!isset($currency) && $row['currency'] != "") {
                        $currency = new Currency;
                        $currency->name = $row['currency'];
                        $currency->save();
                        $inventory->currency_id = $currency->id;
                    }
                    $inventory->product_id = $product->id;
                    $inventory->part_number = $row['part_number'];
                    $inventory->rate = $row['unit_price'];
                    $inventory->qty = 1;
                    $inventory->value = $row['unit_price'];
    
                    if (isset($horse_make)) {
                        $inventory->horse_make_id = $horse_make->id;
                    }elseif(!isset($horse_make) && $row['horse_make'] != "") {
                        $horse_make = new HorseMake;
                        $horse_make->name = $row['horse_make'];
                        $horse_make->save();
                        $inventory->horse_make_id = $horse_make->id;
                    }

                    if (isset($horse_model)) {
                        $inventory->horse_model_id = $horse_model->id;
                    }elseif(!isset($horse_model) && $row['horse_model'] != "") {
                        $horse_model = new HorseModel;
                        $horse_model->name = $row['horse_model'];
                        $horse_model->save();
                        $inventory->horse_model_id = $horse_model->id;
                    }
           
                    if (isset($vehicle_make)) {
                        $inventory->vehicle_make_id = $vehicle_make->id;
                    }elseif(!isset($vehicle_make) && $row['vehicle_make'] != "") {
                        $vehicle_make = new VehicleMake;
                        $vehicle_make->name = $row['vehicle_make'];
                        $vehicle_make->save();
                        $inventory->vehicle_make_id = $vehicle_make->id;
                    }
                    if (isset($vehicle_model)) {
                        $inventory->horse_model_id = $vehicle_model->id;
                    }elseif(!isset($vehicle_model) && $row['vehicle_model'] != "") {
                        $vehicle_model = new VehicleModel;
                        $vehicle_model->name = $row['vehicle_model'];
                        $vehicle_model->save();
                        $inventory->vehicle_model_id = $vehicle_model->id;
                    }
                    if (isset($store)) {
                        $inventory->store_id = $store->id;
                    }elseif(!isset($store) && $row['store_name'] != ""){
                        $store = new Store;
                        $store->name = $row['store_name'];
                        $store->save();
                        $inventory->store_id = $store->id;
                    }
                    $inventory->purchase_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['purchase_date']));
    
                    $inventory->weight = $row['item_contents'];
                    $inventory->balance = $row['balance'];
                    $inventory->measurement = $row['measurement'];
                    $inventory->status = 1;
                    $inventory->save();

            }else {
           
                    $product = new Product;
                    $product->user_id = Auth::user()->id;
                    if (isset($category)) {
                        $product->category_id = $category->id;
                    }elseif(!isset($category) && $row['category'] != ""){
                        $category = new Category;
                        $category->name = $row['category'];
                        $category->save();
                        $product->category_id = $category->id;
                    }
                    
                    if (isset($sub_category)) {
                        $product->category_value_id = $sub_category->id;
                    }elseif(!isset($sub_category) && $row['sub_category'] != ""){
                        $sub_category = new CategoryValue;
                        $sub_category->name = $row['sub_category'];
                        $sub_category->save();
                        $product->category_value_id = $sub_category->id;
                    }
                   
                    if (isset($brand)) {
                        $product->brand_id = $brand->id;
                    }elseif(!isset($brand) && $row['brand_name'] != ""){
                        $brand = new Brand;
                        $brand->name = $row['brand_name'];
                        $brand->save();
                        $product->brand_id = $brand->id;
                    }
                   
                    $product->name = $row['product_name'];
                    $product->product_number = $this->productNumber();
                    $product->department = 'inventory';
                    $product->manufacturer = $row['manufacturer'];
                    $product->description = $row['description'];
                    $product->status = '1';
                    $product->save(); 
                     
                    $inventory = new Inventory;
                    $inventory->user_id = Auth::user()->id;
                    $inventory->inventory_number = $this->inventoryNumber();
                    if (isset($currency)) {
                        $inventory->currency_id = $currency->id;
                    }elseif(!isset($currency) && $row['currency'] != "") {
                        $currency = new Currency;
                        $currency->name = $row['currency'];
                        $currency->save();
                        $inventory->currency_id = $currency->id;
                    }
                    $inventory->product_id = $product->id;
                    $inventory->part_number = $row['part_number'];
                    $inventory->rate = $row['unit_price'];
                    $inventory->qty = 1;
                    $inventory->value = $row['unit_price'];
    
                    if (isset($horse_make)) {
                        $inventory->horse_make_id = $horse_make->id;
                    }elseif(!isset($horse_make) && $row['horse_make'] != "") {
                        $horse_make = new HorseMake;
                        $horse_make->name = $row['horse_make'];
                        $horse_make->save();
                        $inventory->horse_make_id = $horse_make->id;
                    }

                    if (isset($horse_model)) {
                        $inventory->horse_model_id = $horse_model->id;
                    }elseif(!isset($horse_model) && $row['horse_model'] != "") {
                        $horse_model = new HorseModel;
                        $horse_model->name = $row['horse_model'];
                        $horse_model->save();
                        $inventory->horse_model_id = $horse_model->id;
                    }
           
                    if (isset($vehicle_make)) {
                        $inventory->vehicle_make_id = $vehicle_make->id;
                    }elseif(!isset($vehicle_make) && $row['vehicle_make'] != "") {
                        $vehicle_make = new VehicleMake;
                        $vehicle_make->name = $row['vehicle_make'];
                        $vehicle_make->save();
                        $inventory->vehicle_make_id = $vehicle_make->id;
                    }
                    if (isset($vehicle_model)) {
                        $inventory->horse_model_id = $vehicle_model->id;
                    }elseif(!isset($vehicle_model) && $row['vehicle_model'] != "") {
                        $vehicle_model = new VehicleModel;
                        $vehicle_model->name = $row['vehicle_model'];
                        $vehicle_model->save();
                        $inventory->vehicle_model_id = $vehicle_model->id;
                    }
                    if (isset($store)) {
                        $inventory->store_id = $store->id;
                    }elseif(!isset($store) && $row['store_name'] != ""){
                        $store = new Store;
                        $store->name = $row['store_name'];
                        $store->save();
                        $inventory->store_id = $store->id;
                    }
                    $inventory->purchase_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['purchase_date']));
    
                    $inventory->weight = $row['item_contents'];
                    $inventory->balance = $row['balance'];
                    $inventory->measurement = $row['measurement'];
                    $inventory->status = 1 ;
                    $inventory->save();
     
            }

               }
            }elseif(isset($row['quantity']) && $row['quantity'] == 0 ){
                if (isset($product)) {
                    $inventory = new Inventory;
                    $inventory->user_id = Auth::user()->id;
                    $inventory->inventory_number = $this->inventoryNumber();
                    if (isset($currency)) {
                        $inventory->currency_id = $currency->id;
                    }elseif(!isset($currency) && $row['currency'] != "") {
                        $currency = new Currency;
                        $currency->name = $row['currency'];
                        $currency->save();
                        $inventory->currency_id = $currency->id;
                    }
                    $inventory->product_id = $product->id;
                    $inventory->part_number = $row['part_number'];
                    $inventory->rate = $row['unit_price'];
                    $inventory->qty = 1;
                    $inventory->value = $row['unit_price'];
    
                    if (isset($horse_make)) {
                        $inventory->horse_make_id = $horse_make->id;
                    }elseif(!isset($horse_make) && $row['horse_make'] != "") {
                        $horse_make = new HorseMake;
                        $horse_make->name = $row['horse_make'];
                        $horse_make->save();
                        $inventory->horse_make_id = $horse_make->id;
                    }

                    if (isset($horse_model)) {
                        $inventory->horse_model_id = $horse_model->id;
                    }elseif(!isset($horse_model) && $row['horse_model'] != "") {
                        $horse_model = new HorseModel;
                        $horse_model->name = $row['horse_model'];
                        $horse_model->save();
                        $inventory->horse_model_id = $horse_model->id;
                    }
           
                    if (isset($vehicle_make)) {
                        $inventory->vehicle_make_id = $vehicle_make->id;
                    }elseif(!isset($vehicle_make) && $row['vehicle_make'] != "") {
                        $vehicle_make = new VehicleMake;
                        $vehicle_make->name = $row['vehicle_make'];
                        $vehicle_make->save();
                        $inventory->vehicle_make_id = $vehicle_make->id;
                    }
                    if (isset($vehicle_model)) {
                        $inventory->horse_model_id = $vehicle_model->id;
                    }elseif(!isset($vehicle_model) && $row['vehicle_model'] != "") {
                        $vehicle_model = new VehicleModel;
                        $vehicle_model->name = $row['vehicle_model'];
                        $vehicle_model->save();
                        $inventory->vehicle_model_id = $vehicle_model->id;
                    }
                    if (isset($store)) {
                        $inventory->store_id = $store->id;
                    }elseif(!isset($store) && $row['store_name'] != ""){
                        $store = new Store;
                        $store->name = $row['store_name'];
                        $store->save();
                        $inventory->store_id = $store->id;
                    }
                    $inventory->purchase_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['purchase_date']));
    
                    $inventory->weight = $row['item_contents'];
                    $inventory->balance = $row['balance'];
                    $inventory->measurement = $row['measurement'];
                    $inventory->status = 0;
                    $inventory->save();

            }else {
                    $product = new Product;
                    $product->user_id = Auth::user()->id;
                    if (isset($category)) {
                        $product->category_id = $category->id;
                    }elseif(!isset($category) && $row['category'] != ""){
                        $category = new Category;
                        $category->name = $row['category'];
                        $category->save();
                        $product->category_id = $category->id;
                    }
                    
                    if (isset($sub_category)) {
                        $product->category_value_id = $sub_category->id;
                    }elseif(!isset($sub_category) && $row['sub_category'] != ""){
                        $sub_category = new CategoryValue;
                        $sub_category->name = $row['sub_category'];
                        $sub_category->save();
                        $product->category_value_id = $sub_category->id;
                    }
                   
                    if (isset($brand)) {
                        $product->brand_id = $brand->id;
                    }elseif(!isset($brand) && $row['brand_name'] != ""){
                        $brand = new Brand;
                        $brand->name = $row['brand_name'];
                        $brand->save();
                        $product->brand_id = $brand->id;
                    }
                   
                    $product->name = $row['product_name'];
                    $product->product_number = $this->productNumber();
                    $product->department = 'inventory';
                    $product->manufacturer = $row['manufacturer'];
                    $product->description = $row['description'];
                    $product->status = '1';
                    $product->save(); 
                     
                    $inventory = new Inventory;
                    $inventory->user_id = Auth::user()->id;
                    $inventory->inventory_number = $this->inventoryNumber();
                    if (isset($currency)) {
                        $inventory->currency_id = $currency->id;
                    }elseif(!isset($currency) && $row['currency'] != "") {
                        $currency = new Currency;
                        $currency->name = $row['currency'];
                        $currency->save();
                        $inventory->currency_id = $currency->id;
                    }
                    $inventory->product_id = $product->id;
                    $inventory->part_number = $row['part_number'];
                    $inventory->rate = $row['unit_price'];
                    $inventory->qty = 1;
                    $inventory->value = $row['unit_price'];
    
                    if (isset($horse_make)) {
                        $inventory->horse_make_id = $horse_make->id;
                    }elseif(!isset($horse_make) && $row['horse_make'] != "") {
                        $horse_make = new HorseMake;
                        $horse_make->name = $row['horse_make'];
                        $horse_make->save();
                        $inventory->horse_make_id = $horse_make->id;
                    }

                    if (isset($horse_model)) {
                        $inventory->horse_model_id = $horse_model->id;
                    }elseif(!isset($horse_model) && $row['horse_model'] != "") {
                        $horse_model = new HorseModel;
                        $horse_model->name = $row['horse_model'];
                        $horse_model->save();
                        $inventory->horse_model_id = $horse_model->id;
                    }
           
                    if (isset($vehicle_make)) {
                        $inventory->vehicle_make_id = $vehicle_make->id;
                    }elseif(!isset($vehicle_make) && $row['vehicle_make'] != "") {
                        $vehicle_make = new VehicleMake;
                        $vehicle_make->name = $row['vehicle_make'];
                        $vehicle_make->save();
                        $inventory->vehicle_make_id = $vehicle_make->id;
                    }
                    if (isset($vehicle_model)) {
                        $inventory->horse_model_id = $vehicle_model->id;
                    }elseif(!isset($vehicle_model) && $row['vehicle_model'] != "") {
                        $vehicle_model = new VehicleModel;
                        $vehicle_model->name = $row['vehicle_model'];
                        $vehicle_model->save();
                        $inventory->vehicle_model_id = $vehicle_model->id;
                    }
                    if (isset($store)) {
                        $inventory->store_id = $store->id;
                    }elseif(!isset($store) && $row['store_name'] != ""){
                        $store = new Store;
                        $store->name = $row['store_name'];
                        $store->save();
                        $inventory->store_id = $store->id;
                    }
                    $inventory->purchase_date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['purchase_date']));
    
                    $inventory->weight = $row['item_contents'];
                    $inventory->balance = $row['balance'];
                    $inventory->measurement = $row['measurement'];
                    $inventory->status = 0;
                    $inventory->save();
     
            }
            }

 
        

    }
       }
    }

    public function rules(): array{
        return[
            '*.quantity' => ['required'],
        ];
    }




    public function chunkSize(): int
    {
        return 1000;
    }
}
