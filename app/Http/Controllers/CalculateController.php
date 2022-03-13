<?php

namespace App\Http\Controllers;

use App\Models\Overtimes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;

class CalculateController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'month' => 'date'
        ]);

        $result = DB::table('employees')
            ->select(
                'employees.id as id',
                'overtimes.employee_id as employee_id',
                'employees.name',
                'references.id as status_id',
                'references.name as status',
                'employees.salary',
                'overtimes.id as overtime_id',
                'overtimes.date',
                'overtimes.time_started',
                'overtimes.time_ended',
                
                // DB::raw('(CASE WHEN references.id = 3 THEN (subtime(overtimes.time_ended, overtimes.time_started)) ELSE (subtime(subtime(overtimes.time_ended, 10000), overtimes.time_started)) END) as overtimes_duration'),
                DB::raw('(CASE WHEN references.name = "Tetap" THEN (subtime(overtimes.time_ended, overtimes.time_started)) ELSE (subtime(date_sub(overtimes.time_ended, INTERVAL 1 HOUR), overtimes.time_started)) END) as overtimes_duration'),
                // DB::raw('(CASE WHEN references.name = "Tetap" THEN (subtime(overtimes.time_ended, overtimes.time_started)) ELSE (subtime(subtime(overtimes.time_ended, 10000), overtimes.time_started)) END) as total_overtimes_duration'),
                // DB::raw('(CASE WHEN references.name = "Tetap" THEN ' . DB::raw('(select sum(hour(subtime(overtimes.time_ended, overtimes.time_started) ) ) from overtimes where overtimes.employee_id = employees.id)') .' ELSE '. DB::raw('(select sum(hour(subtime(overtimes.time_ended, overtimes.time_started) ) ) from overtimes where overtimes.employee_id = employees.id)') .' END) as total_overtimes_duration'),
                DB::raw('(CASE WHEN references.name = "Tetap" THEN ' . DB::raw('(select @tetap := sum(hour(subtime(overtimes.time_ended, overtimes.time_started) ) ) from overtimes where overtimes.employee_id = employees.id)') .' ELSE '. DB::raw('(select @tetap := sum(hour(subtime(date_sub(overtimes.time_ended, INTERVAL 1 HOUR), overtimes.time_started) ) ) from overtimes where overtimes.employee_id = employees.id)') .' END) as total_overtimes_duration'),
                // DB::raw("SELECT (sum(overtimes.time_ended) from overtimes where overtimes.date like '%". $request['month'] ."%' as total_overtimes)")
                //upah
                DB::raw('(select CASE settings.value WHEN value = 1 THEN employees.salary / 173 * @tetap ELSE 10000 * @tetap END from settings) as upah')
            )
            ->from('employees')
            ->join('overtimes', 'overtimes.employee_id', '=', 'employees.id')
            ->join('references', 'employees.status_id', '=', 'references.id')
            ->where('overtimes.date', 'like', '%' . $request['month'] . '%')->get();

        return $result;

    }
}
