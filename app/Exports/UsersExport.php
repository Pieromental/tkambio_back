<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class UsersExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles,WithMapping,ShouldAutoSize
{
    public function __construct(private array $users) {}

    public function collection()
    {
        return collect($this->users);
    }

    public function headings(): array
    {
        return ['User ID', 'Name', 'Email', 'Birth Date', 'Active', 'Created By', 'Updated By', 'Created At', 'Updated At'];
    }
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD, 
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD, 
        ];
    }

    // Dar estilo a los encabezados
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '181818'], // Color del texto (blanco)
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'FFBE12'], // Color de fondo (verde)
                ],
                'alignment' => [
                    'horizontal' => 'center', // Centrar el texto
                ],
            ],
        ];
    }
    public function map($user): array
    {
        return [
            $user['user_id'],
            $user['name'],
            $user['email'],
            Carbon::parse($user['birth_date'])->format('Y-m-d'),
            $user['active'] ? 'VERDADERO' : 'FALSO',
            $user['created_by'],
            $user['updated_by'] ?? 'N/A', 
            Carbon::parse($user['created_at'])->format('Y-m-d H:i:s'),
            $user['updated_at'] ? Carbon::parse($user['updated_at'])->format('Y-m-d H:i:s') : 'N/A'  
        ];
    }

}
