## About Vimeo Downloader

- For this you need to add client access token to config file.
- Then Open Cli command and run below command:

php artisan vimeo:all

This command will fetch all of videos available from that user account, and save their ids, and main target url into db as well.

Table name: videos_feed

- Second for downloading videos run following command.

php artisan vimeo:start

This command will download all videos in folder

/var/www/example/<client_id><video_id>

