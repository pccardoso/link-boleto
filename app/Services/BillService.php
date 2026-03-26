<?php

    namespace App\Services;
    use App\Models\Bill;
    use Illuminate\Support\Facades\DB;

    class BillService{

        public function getAll(){
        
            return DB::table('bill_update')->get();

        }

    }