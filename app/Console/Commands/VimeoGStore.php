<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;


class VimeoGStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vimeo:gstore {client_id} {video_id}';

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

        $client_id = $this->argument('client_id');
        $video_id = $this->argument('video_id');

        $bucket = $value = config('app.gcs_bucket');
        $gcs_base_url = config('app.gcs_base_url');

        echo $local_base_url = $gcs_base_url.$bucket.$client_id;
        $gDisk = Storage::disk('gcs');

        $localDisk = Storage::disk('public');

//        $gDisk->makeDirectory('/test');
//
//
//        $disk = Storage::disk('gcs');
        echo "Now we need to upload on gcloucd!"."\n";

        $contents = $localDisk->get('vimeo/839394989384/294366428.mp4');
        if(!$gDisk->has($bucket.$client_id."/".$video_id.".mp4", $contents)){
            $gDisk->put($bucket.$client_id."/".$video_id.".mp4", $contents);
        }else{
            echo "not uploaded";

        }

//        $contents = $localDisk->get('294366471.mp4');


//        $gDisk->put('293961513.mp4', $contents);

    }
}
