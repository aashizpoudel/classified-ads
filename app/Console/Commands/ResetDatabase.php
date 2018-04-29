<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classified:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $reset_database;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HomeController $homeController)
    {
        parent::__construct();
        $this->reset_database = $homeController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->reset_database->resetDatabase();
    }
}
