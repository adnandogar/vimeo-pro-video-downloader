## About Vimeo Downloader

- For this you need to add client access token to config file.
- Then Open Cli command and run below command:


By using JSON:


php artisan vimeo:start 4

It will run 4 child process and get video feed from json file

Folder name
json/video_feeds.json


php artisan vimeo:start 0 <client_id> --video_ids='4445111,5455454545,4545455545'

It will run 0 child process, but will take client id and start sub process with each video id.

This command will download all videos in json/video_feeds.json


By Using Database.

php artisan vimeo:all

This command will fetch all of videos available from that user account, and save their ids, and main target url into db as well.

Table name: videos_feed

- Second for downloading videos run following command.
