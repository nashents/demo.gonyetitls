<?php

namespace App\Exports;

use App\Models\Allocation;
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

class AllocationsExport implements FromQuery,
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
        return Allocation::query();
    }
    public function map($allocation): array{
        $status = $allocation->status == 1 ? "active" : "expired";
            return   [
                $allocation->allocation_number,
                $allocation->allocation_type,
                $allocation->vehicle ? $allocation->vehicle->make : "" . ' ' . $allocation->vehicle ? $allocation->vehicle->model : "" . ' ' . $allocation->vehicle ? $allocation->vehicle->registration_number : ""  ,
                $allocation->employee ? $allocation->employee->name : "" .' '. $allocation->employee ? $allocation->employee->surname : "" ,
                $allocation->container ? $allocation->container->name : "",
                $allocation->quantity,
                $allocation->rate,
                $allocation->fuel_type,
                $allocation->amount,
                $allocation->balance,
                $status,
                $allocation->expiry_date,
                 ];


    }
    public function headings(): array{
            return[
                'Allocation #',
                'Allocation Type',
                'Vehicle',
                'Employee',
                'Container',
                'Quantity',
                'Rate',
                'Fuel Type',
                'Amount',
                'Balance',
                'Status',
                'Expiry',

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
