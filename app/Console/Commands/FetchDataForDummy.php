<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Dashboard\Models\Tile;

class FetchDataForDummy extends Command
{
    protected $signature = 'dashboard:fetch-data-for-dummy';

    protected $description = 'Fetch data for dummy component';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
            //$data = Http::get(<some-fancy-api-endpoint>)->json();
            $data = [
                "favourite-colour" => "green",
                "spelling" => "the Queen's English"
            ];

            // this will store your data in the database
            Tile::firstOrCreateForName('dummy')->putData('my_data', $data);
    }
}
