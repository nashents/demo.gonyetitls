<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Employee;
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

class EmployeesAgeExport implements  FromQuery,
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
        return Employee::query();
    }
    public function map($employee): array{
        
            $pattern = '/^\d{4}-\d{2}-\d{2}$/';
            $today = Carbon::today();
            if ((preg_match($pattern, $employee->start_date)) ){
                $start_date = Carbon::parse($employee->start_date);
                $yearsDifference = $start_date->diffInYears($today);
            }else {
                $yearsDifference = "";
            }
            if ((preg_match($pattern, $employee->end_date)) ){
                $end_date = Carbon::parse($employee->end_date);
                $yearsOfEmployeementDifference = $start_date->diffInYears($end_date);
            }else {
                $yearsOfEmployeementDifference = "";
            }
           
        

            return   [
                $employee->name ." ". $employee->surname,
                $employee->start_date,
                $yearsDifference ? $yearsDifference." Year(s)" : "",
                $employee->end_date,
                $yearsOfEmployeementDifference ? $yearsOfEmployeementDifference." Year(s)" : "",
                 ];


    }
    public function headings(): array{
            return[
                'Name',
                'Date of Employement',
                'Total Years of Employment',
                'End of Employement',
                'Years Worked',
            ];


    }
    public function registerEvents(): array{
        return[
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('A7:E7')->applyFromArray([
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
