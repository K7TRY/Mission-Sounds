The "Audio Response" web application is a small web application that allows a user to submit an audio or video file response with additional form data. There is an unprotected admin page that will list the submissions and allow you to play an MP3 version of the audio or video file that was uploaded. 

This app runs on PHP as part of a Apache2 HTTPD server. The server it was developed on is an Ubuntu 12.04.5 LTS machine. Besides Apache2 HTTPD and PHP, the app uses ffmpeg to convert audio and video files to MP3. Because it uses the MP3 file format additional codecs need to be installed.

Tested Server: Ubuntu 12.04.5 LTS
Tested PHP Version: PHP 5.5.27
Tested ffmpeg Version: 0.8.17-4:0.8.17-0ubuntu0.12.04.1

Install ffmpeg and additional needed codecs with this command: sudo apt-get install ffmpeg libavcodec-extra-53
Note the libavcodec-extra version number may be higher or lower depending on the version and distribution of server. 

Since the app uploads and converts videos it is important to make sure that the web server user has permission to write to yt3 directory.

There is no need for a database server. The requirements called for a light load, so the app uses a text file to store comma separated values. The text file is read, parsed and displayed by the admin script. 
