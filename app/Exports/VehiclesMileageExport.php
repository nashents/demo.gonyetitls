<?php

namespace App\Exports;

use App\Models\Vehicle;
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

class VehiclesMileageExport implements  FromQuery,
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
    public function query()
    {
        return Vehicle::query();
    }
    public function map($vehicle): array{
        
        $make = $vehicle->vehicle_make? $vehicle->vehicle_make->name : "";
        $model = $vehicle->vehicle_model? $vehicle->vehicle_model->name : "";
        $fleet_number = $vehicle->fleet_number ? "(".$vehicle->fleet_number.")" : "";
        if ((isset($vehicle->mileage) && $vehicle->mileage > 0) && (isset($vehicle->next_service) && $vehicle->next_service > 0)) {
            if ($vehicle->mileage >= $vehicle->next_service) {
                $status = "Due for service";
            }elseif ($vehicle->mileage < $vehicle->next_service) {
                $status = "Fit for use";
            }
            $difference = $vehicle->next_service - $vehicle->mileage;
        }else {
            $difference = "";
            $status = "";
        }
      
       

        return   [
            $vehicle->transporter ? $vehicle->transporter->name : "",
            $make." ".$model." ".$vehicle->registration_number ." ". $fleet_number ,
            $vehicle->mileage ? $vehicle->mileage."Kms" : "" ,
            $vehicle->next_service ? $vehicle->next_service."Kms" : "" ,
            $difference ? $difference."Kms" : "",
            $status,
          
             ];


}
public function headings(): array{
        return[
            'Transporter',
            'Vehicle',
            'Current Mileage',
            'Next Service Mileage',
            'Mileage Difference',
            'Status',
        ];


}
    public function registerEvents(): array{
        return[
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('A7:H7')->applyFromArray([
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
