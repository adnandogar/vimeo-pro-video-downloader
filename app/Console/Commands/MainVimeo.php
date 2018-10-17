<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class MainVimeo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vimeo:start {no?} {client_id?} {--video_ids=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    private function getFromJson(){
        $path = storage_path() . "/json/video_feed.json"; // ie: /var/www/laravel/app/storage/json/filename.json
        $json = json_decode(file_get_contents($path), true);
        return $json;
    }

    public function handle()
    {
        $no_of_commands = $this->argument('no',0);
        $client_id = $this->argument('client_id',Null);
        if($no_of_commands > 0){
            for($i=1;$i<=$no_of_commands;$i++){
                    call_in_background('vimeo:threads');
            }

        }else{

            $columns = $this->option('video_ids');
            $final_columns = [];
            foreach(explode(",", $columns) as $column) {
                $final_columns[] = $column;
            }
            $video_ids = $final_columns;
            foreach ($video_ids as $video_id){
                call_in_background('vimeo:download '.$client_id.' '.$video_id);
            }
        }
    }
}
