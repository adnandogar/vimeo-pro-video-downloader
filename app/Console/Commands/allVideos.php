<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Vimeo\Laravel\Facades\Vimeo;

class allVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vimeo:all';

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
    public function handle()
    {
        //
        $videos = Vimeo::request('/me/videos/', ['per_page' => 10], 'GET');

        foreach ($videos['body']['data'] as $video) {

            $dataV['video_main_url'] = isset($video['download'][0]['link']) ? $video['download'][0]['link'] : $video['download'][0][0]['link'];
            $dataV['client_id'] = trim(str_replace("https://vimeo.com/user"," ",$video['user']['link']));
            $video_links = explode("/",$video['uri']);
            $dataV['video_id'] = $video_links[2];
            $dataV['name'] = $video['name'];
            $dataV['size'] =  isset($video['download'][0]['size']) ? $video['download'][0]['size'] : $video['download'][0][0]['size'];
            $dataV['status'] = 0;

            $alreadyAvilable = DB::table("videos_feed")
                ->where("video_id","=",$dataV['video_id'])
                ->first();
            if(is_null($alreadyAvilable)){
                DB::table("videos_feed")
                    ->insert($dataV);
            }
        }
//        print_r($videos);
    }
}
