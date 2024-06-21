<?php

namespace App\Exports;

use App\Models\Customer;
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

class CustomersAgeExport implements  FromQuery,
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
        return Customer::query();
    }

    public function map($customer): array{
        
        $contract = $customer->contracts->where('status',1)->first();
        $duration = $contract ? $contract->duration : "";
    

        return   [
            $customer->name ,
            $contract ? $contract->start_date : "",
            $contract ? $contract->end_date : "",
            $duration ? $duration." Months" : "",
          
             ];


}
public function headings(): array{
        return[
            'Customer',
            'Start of Contract',
            'End of Contract',
            'Contract Duration',
        ];


}
    public function registerEvents(): array{
        return[
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('A7:D7')->applyFromArray([
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
