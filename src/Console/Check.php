<?php

namespace Sunhill\Basic\Console;

use Illuminate\Console\Command;
use Sunhill\Basic\Facades\Checks;

class Check extends Command
{
    protected $signature = 'sunhill:check';
    
    protected $description = 'Checks the consistency of the database and other structures';
    
    public function handle()
    {
        $this->info('Performing checks...');
        $result = Checks::Check();
        foreach ($result as $single_result) {
            if ($single_resuls->result == 'OK') {
                $this->info($single_result->name.'... OK');
            } else {
                $this->error($single_result->name.'... FAILED:');
                $this->error('  '.$single_result->error);
            }
        }
        $this->info('Checks finished');
    }
}
