<?php

namespace App\Exports;

use App\Models\Fuel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class FuelsExport implements  FromQuery,
ShouldAutoSize,
WithMapping,
WithHeadings,
WithEvents,
WithDrawings,
WithCustomStartCell
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    public $from;
    public $to;
    public $fuel_filter;
    public $container_id;
   

    public function __construct($from, $to, $fuel_filter, $container_id)
    {
            $this->from = $from;
            $this->to = $to;
            $this->fuel_filter = $fuel_filter;
            $this->container_id = $container_id;
           
    }
    public function query()
    {
        if (isset($this->from) && isset($this->to)) {
            if (isset($this->container_id)) {
                return Fuel::query()->with(['container','horse',
                'horse.horse_make','horse.horse_model'])->where('container_id',$this->container_id)->whereBetween($this->fuel_filter,[$this->from, $this->to] )->orderBy('created_at','desc');
            }else{
                return Fuel::query()->with(['container','horse',
                'horse.horse_make','horse.horse_model'])->whereBetween($this->fuel_filter,[$this->from, $this->to] )->orderBy('created_at','desc');
            }
           
        }elseif(isset($this->container_id)){
            return Fuel::query()->with(['container','horse',
            'horse.horse_make','horse.horse_model'])->where('container_id',$this->container_id)->whereMonth($this->fuel_filter, date('m'))
            ->whereYear($this->fuel_filter, date('Y'))->orderBy('created_at','desc');
        }else {
            return Fuel::query()->with(['container','horse',
            'horse.horse_make','horse.horse_model'])->whereMonth($this->fuel_filter, date('m'))
            ->whereYear($this->fuel_filter, date('Y'))->orderBy('created_at','desc');
        }
    }
    public function map($fuel): array{
                 
        if ( $fuel->horse) {
            $horse_make =  $fuel->horse->horse_make ? $fuel->horse->horse_make->name : "";
            $horse_model = $fuel->horse->horse_model ? $fuel->horse->horse_model->name : "";
            $horse_registration_number = $fuel->horse->registration_number;
           
            }else {
                $horse_make = "";
                $horse_model = "";
                $horse_registration_number = "";
            }
        if ( $fuel->vehicle) {
            $vehicle_make =  $fuel->vehicle->vehicle_make ? $fuel->vehicle->vehicle_make->name : "";
            $vehicle_model = $fuel->vehicle->vehicle_model ? $fuel->vehicle->vehicle_model->name : "";
            $vehicle_registration_number = $fuel->vehicle->registration_number;
           
            }else {
                $vehicle_make = "";
                $vehicle_model = "";
                $vehicle_registration_number = "";
            }
        if ( $fuel->asset) {
            $product = $fuel->asset->product ? $fuel->asset->product->name : "";
           
            }else {
                $product = "";
                
            }
            $authorized_by_name = User::find($fuel->authorized_by_id) ? User::find($fuel->authorized_by_id)->name : "";
            $authorized_by_surname = User::find($fuel->authorized_by_id) ? User::find($fuel->authorized_by_id)->surname : "";
            $created_by_name =  $fuel->user ? $fuel->user->name : "" ;
            $created_by_surname = $fuel->user ? $fuel->user->name : "" ;
            $symbol = $fuel->currency ? $fuel->currency->symbol : "";

            if ( $fuel->type == "Horse" || $fuel->type == "Trip") {
                return   [
                $fuel->order_number,
                $created_by_name ." ".$created_by_surname,
                $fuel->authorization,
                $authorized_by_name ." ".$authorized_by_surname,
                $fuel->container ? $fuel->container->name : "",
                $fuel->date,
                $fuel->type,
                $fuel->trip ? "Trip#: ".$fuel->trip->trip_number : "" .' '.$horse_registration_number .' '.$horse_make .' '. $horse_model,
                $fuel->odometer . 'Kms',
                $fuel->fillup == 1 ? "initial" : "top up",
                $fuel->container ? $fuel->container->fuel_type : "",
                $fuel->quantity. 'L',
                $fuel->currency ? $fuel->currency->name : "",
                $symbol. $fuel->amount,
                $fuel->comments,
                 ];
                }
                elseif ($fuel->type == "Vehicle" || $fuel->type == "Trip") {
                    return   [
                        $fuel->order_number,
                        $created_by_name ." ".$created_by_surname,
                        $fuel->authorization,
                        $authorized_by_name ." ".$authorized_by_surname,
                        $fuel->container ? $fuel->container->name : "",
                        $fuel->date,
                        $fuel->type,
                        $fuel->trip ? "Trip#: ".$fuel->trip->trip_number : "" .' '. $vehicle_registration_number . ' ' .$vehicle_make .' '. $vehicle_model ,
                        $fuel->odometer . 'Kms',
                        $fuel->fillup == 1 ? "initial" : "top up",
                        $fuel->container ? $fuel->container->fuel_type : "",
                        $fuel->quantity. 'L',
                        $fuel->currency ? $fuel->currency->name : "",
                        $symbol. $fuel->amount,
                        $fuel->comments,
                         ];
                }elseif ($fuel->type == "Asset") {
                    return   [
                        $fuel->order_number,
                        $created_by_name ." ".$created_by_surname,
                        $fuel->authorization,
                        $authorized_by_name ." ".$authorized_by_surname,
                        $fuel->container ? $fuel->container->name : "",
                        $fuel->date,
                        $fuel->type,
                        $product ,
                        "",
                        $fuel->fillup == 1 ? "initial" : "top up",
                        $fuel->container ? $fuel->container->fuel_type : "",
                        $fuel->quantity. 'L',
                        $fuel->currency ? $fuel->currency->name : "",
                        $symbol. $fuel->amount,
                        $fuel->comments,
                         ];
                }elseif ($fuel->type == "Other") {
                    return   [
                        $fuel->order_number,
                        $created_by_name ." ".$created_by_surname,
                        $fuel->authorization,
                        $authorized_by_name ." ".$authorized_by_surname,
                        $fuel->container ? $fuel->container->name : "",
                        $fuel->date,
                        $fuel->type,
                        "",
                        "",
                        $fuel->fillup == 1 ? "initial" : "top up",
                        $fuel->container ? $fuel->container->fuel_type : "",
                        $fuel->quantity. 'L',
                        $fuel->currency ? $fuel->currency->name : "",
                        $symbol. $fuel->amount,
                        $fuel->comments,
                         ];
                }


    }
    public function headings(): array{
            return[
                'Order#',
                'CreatedBy',
                'Auth Status',
                'AuthorizedBy',
                'Fueling Station',
                'Date ',
                'Fuel Order For',
                'Category',
                'Mileage',
                'Fuel Order Type',
                'Fuel Type',
                'Quantity',
                'Currency',
                'Amount',
                'Comments',
               
            ];


    }
    public function registerEvents(): array{
        return[
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('A7:O7')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ],
                    ]
                ]);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        if (isset(Auth::user()->employee->company)) {
            $drawing->setName(Auth::user()->employee->company->name);
            $drawing->setDescription(Auth::user()->employee->company->name . 'Logo');
            $drawing->setPath(public_path('/images/uploads/'.Auth::user()->employee->company->logo));
            } elseif (Auth::user()->company) {
            $drawing->setName(Auth::user()->company->name);
            $drawing->setDescription(Auth::user()->company->name. 'Logo');
            $drawing->setPath(public_path('/images/uploads/'.Auth::user()->company->logo));
            }
        $drawing->setHeight(90);
        $drawing->setCoordinates('A2');

        return $drawing;
    }

    public function startCell(): string{
        return 'A7';
    }
}
