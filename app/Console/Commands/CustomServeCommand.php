<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Foundation\Console\ServeCommand;
use Illuminate\Support\Facades\Artisan;

class CustomServeCommand extends ServeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve {--host=127.0.0.1} {--port=8000} {--tries=1} {--no-reload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application with monthly recap check';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->runMonthlyRecapIfNeeded();

        parent::handle();
    }

    protected function runMonthlyRecapIfNeeded()
    {
        $lastRecapDate = cache("last_recap_date");
        $currentMonth = Carbon::now()->startOfMonth();

        if (!$lastRecapDate || $currentMonth->greaterThan($lastRecapDate)) {
            Artisan::call("recap:monthly");
            cache(["last_recap_date" => $currentMonth], now()->addMonths(1));
        }
    }
}
