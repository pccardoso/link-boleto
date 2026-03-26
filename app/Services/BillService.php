<?php

    namespace App\Services;
    use App\Models\Bill;
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;

    class BillService{

        public function getAll(){
        
            return DB::table('bill_update')->get();

        }

        public function billsPorMes()
        {
            $year = Carbon::now()->year;

            $data = DB::table('bill_update')
                ->selectRaw('EXTRACT(MONTH FROM created_at) as mes, COUNT(*) as total')
                ->whereYear('created_at', $year)
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();

            $meses = [
                'JAN' => 0,
                'FEV' => 0,
                'MAR' => 0,
                'ABR' => 0,
                'MAI' => 0,
                'JUN' => 0,
                'JUL' => 0,
                'AGO' => 0,
                'SET' => 0,
                'OUT' => 0,
                'NOV' => 0,
                'DEZ' => 0,
            ];

            foreach ($data as $item) {

                $index = $item->mes - 1;
                $keys = array_keys($meses);

                $meses[$keys[$index]] = $item->total;

            }

            return response()->json($meses);
        }

    }