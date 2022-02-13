<?php

namespace Sunhill\Basic\Console;

use Illuminate\Console\Command;
use Sunhill\Basic\Facades\Checks;

class Check extends Command
{
    protected $signature = 'sunhill:check {--repair}';
    
    protected $description = 'Checks the consistency of the database and other structures';
    
    public function handle()
    {
        $this->info(__('Performing checks...',[]));
        $result = Checks::Check($this->option('repair'));
        foreach ($result as $single_result) {
            if ($single_result->result == 'OK') {
                $this->info($single_result->name.'... OK');
            } else {
                $this->error($single_result->name.'... FAILED:');
                $this->error('  '.$single_result->error);
            }
        }
        $this->info(__('Checks finished',[]));
    }
}
