<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Support\Facades\Log;

class VimeoCustomStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vimeo:download {video_id}';

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

    private function findSourceVideo($video_id){
        $latestRequest = Vimeo::request('/me/videos/'.$video_id, ['per_page' => 10], 'GET');
//        dd($latestRequest);
                $dataV['video_main_url'] = isset($latestRequest['body']['download'][0]['link']) ? $latestRequest['body']['download'][0]['link'] : $latestRequest['body']['download'][0][0]['link'];
                $dataV['video_uri'] = $latestRequest['body']['uri'];
                $dataV['video_id'] = $video_id;
                $dataV['name'] = $latestRequest['body']['name'];
                $dataV['size'] =  isset($latestRequest['body']['download'][0]['size']) ? $latestRequest['body']['download'][0]['size'] : $latestRequest['body']['download'][0][0]['size'];
                $dataV['md5'] =  isset($latestRequest['body']['download'][0]['md5']) ? $latestRequest['body']['download'][0]['md5'] : $latestRequest['body']['download'][0][0]['md5'];
                $dataV['status'] = 0;
                return $dataV;
    }

    public function handle()
    {
        //

        echo $video_id = $this->argument('video_id');
        $targetUrl = $this->findSourceVideo($video_id);
        $jsonArray = ($targetUrl);
        $oldJsonData = Storage::disk('public')->get('/json/video_targets.json');
        $oldJsonData = json_decode($oldJsonData);
        print_r((array)$oldJsonData);
        print_r($jsonArray);

        $oldJsonData = ((array)$oldJsonData);
        array_push($oldJsonData,$jsonArray);

        Storage::disk('public')->put('/json/video_targets.json', json_encode($oldJsonData));

//        file_put_contents(storage_path('/json/video_targets.json'), stripslashes($jsonArray));
//        print_r($targetUrl);

    }
}
