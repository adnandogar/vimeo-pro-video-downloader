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
    protected $signature = 'vimeo:start {no?}';

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
        //
        $no_of_commands = $this->argument('no');
        if($no_of_commands > 0){
            $video_ids = $this->getFromJson();
            foreach ($video_ids as $video_id){
//                print_r($video_id);
                call_in_background('vimeo:download '.$video_id['VimeoID']);

//                Artisan::call("vimeo:download", ['video_id' => $video_id['VimeoID'] ]);
            }
//            for($i=0;$i<=$no_of_commands;$i++)
//            {
//                Artisan::call("vimeo:download ".$video_id);
//            }

        }else{


            $columns = $this->option('video_ids');
            $final_columns = [];
            foreach(explode(",", $columns) as $column) {
                $final_columns[] = $column;
            }
            $video_ids = $final_columns;
            foreach ($video_ids as $video_id){
                Artisan::call("vimeo:download ".$video_id);
            }


        }



    }
}
