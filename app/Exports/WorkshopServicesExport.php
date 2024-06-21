<?php

namespace App\Exports;

use App\Models\WorkshopService;
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

class WorkshopServicesExport implements  FromQuery,
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
        return WorkshopService::query();
    }
    public function map($workshop_service): array{

            $name =  $workshop_service->user ? $workshop_service->user->name : ""  ;
            $surname =  $workshop_service->user ? $workshop_service->user->surname : ""  ;
            $horse_reg = $workshop_service->horse->registration_number;
            $horse_fleet = $workshop_service->horse->fleet_number ? "(".$workshop_service->horse->fleet_number.")" : "";
            $trailer_reg = $workshop_service->trailer ? $workshop_service->trailer->registration_number : "";
            $trailer_fleet = $workshop_service->trailer->fleet_number ? "(".$workshop_service->trailer->fleet_number.")" : "";

            return   [
                $name." ".$surname,
                $workshop_service->account ? $workshop_service->account->name : '',
                $workshop_service->vendor ? $workshop_service->vendor->name : "",
                $workshop_service->transporter ? $workshop_service->transporter->name : "",
                $horse_reg." ".$horse_fleet,
                $trailer_reg." ".$trailer_fleet,
                $workshop_service->load_status,
                $workshop_service->days,
                $workshop_service->start_date,
                $workshop_service->end_date,
                $workshop_service->currency ? $workshop_service->currency->name : "",
                $workshop_service->amount,
                 ];


    }
    public function headings(): array{
            return[
                'CreatedBy',
                'Expense Account',
                'Vendor',
                'Transporter',
                'Horse',
                'Trailer',
                'Load Status',
                'Day(s)',
                'In',
                'Out',
                'Currency',
                'Amount',
            ];


    }
    public function registerEvents(): array{
        return[
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('A7:L7')->applyFromArray([
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
