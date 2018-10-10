<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Vimeo\Laravel\Facades\Vimeo;
use GuzzleHttp\Stream;

class VimeoController extends Controller
{
    //
    public function getVideo(Request $request)
    {
//        $id = $request->input('id');
//        $videos = Vimeo::request('/me/videos/'.$id, ['per_page' => 10], 'GET');
//        Vimeo::request('https://player.vimeo.com/play/1116961057?s=293961513_1539206530_565bc528076308fc5651983e4ad15b1e&loc=external&context=Vimeo%5CController%5CApi%5CResources%5CUser%5CVideoController.&download=1&filename=VID_20181002_180643source.mp4');
//        dd($videos);

        $toFile = '/var/www/example/';

        $fromUrl = 'https://player.vimeo.com/play/1116961057?s=293961513_1539206530_565bc528076308fc5651983e4ad15b1e&loc=external&context=Vimeo%5CController%5CApi%5CResources%5CUser%5CVideoController.&download=1&filename=VID_20181002_180643source.mp4';
//
        exec('wget  "https://player.vimeo.com/play/1116961057?s=293961513_1539206530_565bc528076308fc5651983e4ad15b1e&loc=external&context=Vimeo%5CController%5CApi%5CResources%5CUser%5CVideoController.&download=1&filename=VID_20181002_180643source.mp4" -O /var/www/example/abc2.mp4',$output);
        echo implode('<br />', $output);





//        $file = Storage::get($fromUrl);
//

//        $state = "wget --no-clobber --convert-links --random-wait -S –header=”Accept-Encoding: gzip, deflate” -O -p -P wget '$url'";
//        exec($state, $output, $return);
//        if ($return) {
//            echo "<br/>Download Complete!<br/>";
//            $website = new Website;
//            $website->web_url = $url;
//            $website->web_complete = '0';
//            $website->verification_number = $newVerificationNumber;
//            $website->save();
//            echo json_encode($website->toArray()) . '<br/>';
//            $progress = 1;
//        } else {
//            echo "<br/>Error!<br/>";
//        }

        $client = new Client();
        $response = $client->get($fromUrl); // in case your resource is under protection

//        $original = Stream::create(fopen('https://player.vimeo.com/play/1116961057?s=293961513_1539206530_565bc528076308fc5651983e4ad15b1e&loc=external&context=Vimeo%5CController%5CApi%5CResources%5CUser%5CVideoController.&download=1&filename=VID_20181002_180643source.mp4', 'r'));
//        $local = Stream::create(fopen('/var/www/example/abc.file', 'w'));
//        $local->write($original->getContents());

//        $dl = new Download('https://vimeo.com/294192918', 'mp4');

//        $url = "https://vimeo.com/294192918";
//        $contents = file_get_contents($url);
//        $name = substr($url, strrpos($url, '/') + 1);
//        Storage::put('/home/adnan/', $contents);

        //Saves the file to specified directory
//        $dl->download();






//        $latestRequest = $this->vimeoRequest('/videos/' . $id . '?fields=uri,duration,download,name,files');
//        $latestRequest = $videos;
//
//        if (!isset($latestRequest['body']['download'])) {
//            throw new \Exception('No Download links');
//        }
//
//        if ($latestRequest['body']['duration'] === 0) {
//            throw new \Exception('No Duration');
//        }
//
//        $source = null;
//
//        foreach ($latestRequest['body']['download'] as $index => $videoData) {
//            if ($videoData['type'] !== 'source') {
//                continue;
//            }
//
//            $videoData['name'] = $latestRequest['body']['name'];
//            $source = $videoData;
//        }
//
//        if ($source === null) {
//            throw new \Exception('Cannot find source');
////            $source = $this->selectSource($latestRequest);
//        }




//        return $source;


    }


    public function getMyVideo(Request $request)
    {
        $videos = Vimeo::request('/me/videos' ,['per_page' => 10], 'GET');
        dd($videos);

    }


}
