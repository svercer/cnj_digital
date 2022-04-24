<?php

namespace App\Imports;

use App\Models\Information;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class InformationImport implements ToModel, WithHeadingRow, WithBatchInserts, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|Information|null
    {
        return new Information([
            'date' => $row['date'],
            'area' => $row['area'],
            'average_price' => $row['average_price'],
            'code' => $row['code'],
            'houses_sold' => $row['houses_sold'],
            'no_of_crimes' => $row['no_of_crimes'],
            'borough_flag' => $row['borough_flag'],
        ]);
    }

    public function batchSize(): int
    {
        return 300;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'area' => 'required|string',
            'average_price' => 'required|integer',
            'code' => 'required|string',
            'houses_sold' => 'nullable|integer',
            'no_of_crimes' => 'nullable|integer',
            'borough_flag' => 'required|integer',
        ];
    }
}
