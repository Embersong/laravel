<?php

namespace App\Console\Commands;

use App\Models\Town;
use Illuminate\Console\Command;
use Exception;

class AddTowns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:town {town*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить города';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $towns = $this->argument('town');


        try {
            foreach ($towns as $value) {
                $town = new Town();
                $town->name = $value;
                $town->save();
            }
        } catch (Exception $exception) {
            $this->error('Ошибка добавления города!');
            $this->error($exception->getMessage());
            return Command::FAILURE;
        }


        $this->info('Города добавлены!');

        return Command::SUCCESS;
    }
}
