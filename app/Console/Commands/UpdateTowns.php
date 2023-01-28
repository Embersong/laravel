<?php

namespace App\Console\Commands;

use App\Models\Okrug;
use App\Models\Region;
use App\Models\Town;
use Exception;
use Illuminate\Console\Command;

class UpdateTowns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:towns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверить область и округ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $towns = Town::all();
            foreach ($towns as $town) {
                if (is_null($town->id_region)) {

                    $token = env('GEO_TOKEN');
                    $secret = env('GEO_SECRET');
                    $dadata = new \Dadata\DadataClient($token, $secret);

                    $response = $dadata->clean("address", $town->name);

                    $region = Region::firstOrCreate([
                        'name' => $response['region']
                    ]);

                    $town->id_region = $region->id;
                    $town->save();

                    Okrug::firstOrCreate([
                        'name' => $response['federal_district']
                    ]);

                    $region->id_okrug = $region->id;
                    $region->save();

                }
            }
        } catch (Exception $exception) {
            $this->error('Ошибка обновления!');
            $this->error($exception->getMessage());
            return Command::FAILURE;
        }

        $this->info("Регионы и области успешно обновлены.");
        return Command::SUCCESS;
    }
}
