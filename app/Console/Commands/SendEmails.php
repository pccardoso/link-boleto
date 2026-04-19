<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\BillMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Bill;
use Carbon\Carbon;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    
        $start = Carbon::today()->setHour(1);
        $end = Carbon::today()->setHour(6);

        $boletos = Bill::whereNotNull('verified_paid_at')
            ->whereBetween('verified_paid_at', [$start, $end])
            ->get();

        Mail::to('supervisaoassistenciathais@gmail.com')->send(new BillMail($boletos));

    }
}
