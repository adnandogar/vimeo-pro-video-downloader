<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Support\Facades\Log;

class VimeoOpenThreads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vimeo:threads {file}';

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

    private function getExactlySourceQuality($latestRequest){
        $data = [];
        if(isset($latestRequest['body']['download'][0] ))
            foreach ($latestRequest['body']['download'] as $source){
                if($source['quality'] == 'source')
                {
                    $data['video_main_url'] = $source['link'];
                    $data['size'] = $source['size'];
                    $data['md5'] = $source['md5'];
                    $data['type'] = $source['type'];
                }
            }

        return $data;
    }

    private function findSourceVideo($video_id)
    {
        $latestRequest = Vimeo::request('/me/videos/' . $video_id, ['per_page' => 10], 'GET');
//        dd($latestRequest);
        $dataV = $this->getExactlySourceQuality($latestRequest);
        if(is_null($dataV)){
            $dataV['video_main_url'] = isset($latestRequest['body']['files'][0]['link']) ? $latestRequest['body']['download'][0]['link'] : $latestRequest['files']['download'][0][0]['link'];
            $dataV['size'] = isset($latestRequest['body']['files'][0]['size']) ? $latestRequest['body']['files'][0]['size'] : $latestRequest['body']['files'][0][0]['size'];
            $dataV['md5'] = isset($latestRequest['body']['files'][0]['md5']) ? $latestRequest['body']['files'][0]['md5'] : $latestRequest['body']['files'][0][0]['md5'];
            $dataV['type'] = isset($latestRequest['body']['files'][0]['type']) ? $latestRequest['body']['files'][0]['type'] : $latestRequest['body']['files'][0][0]['type'];
        }

        $video_extension = explode("/",$dataV['type']);
        $dataV['video_extension'] = isset($video_extension[1]) ? ($video_extension[1]) : 'mp4';
        $dataV['video_uri'] = $latestRequest['body']['uri'];
        $dataV['video_id'] = $video_id;
        $dataV['name'] = $latestRequest['body']['name'];
        $dataV['status'] = 2;
        $dataV['rateLimit'] = $latestRequest['headers'];
        return $dataV;
    }

    private function rateLimitSleep($header){
        $threshold = 1;
        if ($header['X-RateLimit-Remaining'] !== null && $header['X-RateLimit-Remaining'] <= $threshold) {
            $date = Carbon::parse($header['X-RateLimit-Reset'], 'UTC');

            if ($date->isFuture()) {
                $now = \Carbon\Carbon::now('UTC');
                $minutesToSleep = $now->diffInMinutes($date);

                Log::info('Now: ' . $now);
                Log::info('Resets: ' . $date);

                Log::info('Rate limit hit, SLEEPING for ' . ($minutesToSleep + 1) . ' min');
                sleep(($minutesToSleep + 1) * 60);
            }

            Log::info('Remaining Calls: ' . $header['X-RateLimit-Remaining']);

        }
    }

    private function getFromJson(){
        $path = storage_path() . "/json/video_feed.json"; // ie: /var/www/laravel/app/storage/json/filename.json
        $json = json_decode(file_get_contents($path), true);
        return $json;
    }

    public function handle()
    {
        $file_name = $this->argument('file');

        $gDisk = Storage::disk('gcs');
        $localDisk = Storage::disk('public');
        $video_ids = $this->getFromJson();
        foreach ($video_ids as $video) {
            $video_id = $video['VimeoID'];
            $client_id = $video['ClientID'];
            $targetUrl = $this->findSourceVideo($video_id);
            $this->rateLimitSleep($targetUrl['rateLimit']);
            $jsonArray = ($targetUrl);
            $jsonArray['client_id'] = $client_id;
            $jsonArray['time_started'] = Carbon::now();
            print_r($jsonArray);
            $fromUrl = $targetUrl['video_main_url'];
            try {
                // lets make a directory.
                try {
                    $bucket = $value = config('app.gcs_bucket');
                    $gcs_base_url = config('app.gcs_base_url');
                    $localDisk->makeDirectory($bucket . $client_id);
                } catch (\Exception $ex) {
                    //if already created
                    Log::info($ex->getMessage());
                }
                $local_base_url = $gcs_base_url . $bucket . $client_id;
//                echo "Base URL:".$local_base_url . "\n";
                // start downloading the exact video.
                if (!$gDisk->has($bucket . $client_id . "/" . $video_id . "." .$jsonArray['video_extension'])) {

                    echo "starting";
                    $output = shell_exec('wget "' . $fromUrl . '" -O ' . $local_base_url . "/" . $video_id . "." .$jsonArray['video_extension']);
                    Log::info($output);
                    Log::debug("Downloading video".$fromUrl);

                    //now time to put in gcloud storage

                    $gDisk->makeDirectory($bucket . $client_id);
                    echo "Now we need to upload on gcloucd!" . "\n";
                    $contents = $localDisk->get($bucket . $client_id . "/" . $video_id . ".mp4");

                    $gDisk->put($bucket . $client_id . "/" . $video_id . ".mp4", $contents);
                    echo "Gcloud uploaded!";

                    //now delete file from local
                    $localDisk->delete($bucket . $client_id . "/" . $video_id . ".mp4");

                    $ended_time = Carbon::now();
                    $jsonArray['ended_time'] = $ended_time;
                    // now time to update ended time and elapsed time.
                    $jsonArray['elapsed_time'] = $ended_time->diffInSeconds($jsonArray['time_started']);

                    //check size
                    $gSize = $gDisk->size($bucket.$client_id."/".$video_id.".mp4")."\n";
                    $jsonArray['size'];
                    if($gSize != $jsonArray['size']){
                        $jsonArray['size_error'] = 'error on transfer file size '.$gSize.' doesnt match with vimeo file size '.$jsonArray['size'];
                    }else{
                        $jsonArray['size_success'] = 'file size  on transfer file size '.$gSize.' matched with vimeo file size '.$jsonArray['size'];
                    }

                    if(!Storage::disk('public')->has('/json/'.$file_name))
                    {
                        $contents = [];
                        Storage::disk('public')->put('/json/'.$file_name,json_encode($contents));
                    }
                    $oldJsonData = Storage::disk('public')->get('/json/'.$file_name);
                    $oldJsonData = json_decode($oldJsonData);
                    $oldJsonData = ((array)$oldJsonData);

                    array_push($oldJsonData, $jsonArray);

                    $localDisk->put('/json/'.$file_name, json_encode($oldJsonData));
                    $gDisk->put($file_name, json_encode($oldJsonData));

                } else {
                    echo "already Exists!";
                }


            } catch (\Exception $ex) {
                //if already created
                Log::debug($ex->getMessage());
            }
        }
    }

}
