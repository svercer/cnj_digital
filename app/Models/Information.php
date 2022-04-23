<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

class Information extends Model
{
    protected $table = 'information';
    protected $fillable = [
        'date',
        'area',
        'average_price',
        'code',
        'houses_sold',
        'no_of_crimes',
        'borough_flag'
    ];
    use HasFactory;

    public static function calculate($file): array
    {
        $data = [];
        $content = file_get_contents($file);
        $rows = explode("\r\n", $content);

        $totalSalesAveragePrice = self::calculateTotalSalesAveragePrice($rows);
        $countOfAllHousesSold = self::calculateCountOfAllHousesSold($rows);
        $numberOfCrimesInYear = self::calculateNumberOfCrimesInYear($rows);
        $averagePricePerYearInLondon = self::calculateAveragePricePerYearInCity($rows);
        $data['totalSalesAveragePrice'] = $totalSalesAveragePrice;
        $data['countOfAllHousesSold'] = $countOfAllHousesSold;
        $data['numberOfCrimesInYear'] = $numberOfCrimesInYear;
        $data['averagePricePerYearInLondon'] = $averagePricePerYearInLondon;

        return $data;
    }

    private static function calculateTotalSalesAveragePrice($rows):int
    {
        $totalCount = 0;
        $totalSales = 0;
        foreach ($rows as $key => $row){
            if ($key == 0){
                continue;
            }
            $data = explode(',', $row);
            if ($data[2] != '' || $data[2] != null){
                $totalSales += $data[2];
                $totalCount++;
            }
        }
        return round($totalSales / $totalCount);
    }

    private static function calculateCountOfAllHousesSold($rows):int
    {
        $totalCount = 0;
        foreach ($rows as $key => $row){
            if ($key == 0){
                continue;
            }
            $data = explode(',', $row);
            if ($data[4] != '' || $data[4] != null){
                $totalCount += $data[4];
            }
        }
        return $totalCount;
    }

    private static function calculateNumberOfCrimesInYear($rows):int
    {
        $totalCount = 0;
        foreach ($rows as $key => $row){
            if ($key == 0){
                continue;
            }
            $data = explode(',', $row);
            if ($data[5] != '' || $data[5] != null){
                $totalCount += $data[5];
            }
        }
        return $totalCount;
    }

    private static function calculateAveragePricePerYearInCity($rows):array
    {
        $dataPerYear = [];
        foreach ($rows as $key => $row){
            if ($key == 0){
                continue;
            }
            list($datePerMonth,$city,$averagePerMonth) = explode(',', $row);
            if ($city == 'city of london'){
                $year = Carbon::createFromFormat('Y-m-d', $datePerMonth)->format('Y');
                if (isset($dataPerYear[$year])) {
                    $sum = $dataPerYear[$year]['total'] += $averagePerMonth;
                    $count = $dataPerYear[$year]['count'] +1;
                    $dataPerYear[$year]['total'] = $sum;
                    $dataPerYear[$year]['count'] = $count;
                    $dataPerYear[$year]['average'] = round($sum / $count);
                }else{
                   $dataPerYear[$year]['total'] = $averagePerMonth;
                   $dataPerYear[$year]['count'] = 1;
                   $dataPerYear[$year]['average'] = round($dataPerYear[$year]['total'] / $dataPerYear[$year]['count']);
                }
            }
        }
        return $dataPerYear;
    }
}
