<?PHP // $Id: filter_poodll.php ,v 1.3 2012/05/16 12:47:13 Justin Hunt Exp $ 
      // PoodLl Filter
$string['filtername'] = 'PoodLL Filter';

$string['settings'] = 'PoodLL Filter Settings';
$string['activate'] = 'Activate PoodLL?';

//headings
$string['filter_poodll_network_heading'] = 'PoodLL Network Settings';
$string['filter_poodll_audioplayer_heading'] = 'Audio Player Settings';
$string['filter_poodll_mic_heading'] = 'Microphone Settings';
$string['filter_poodll_videoplayer_heading'] = 'Video Player Settings';
$string['filter_poodll_camera_heading'] = 'Web Camera Settings';
$string['filter_poodll_videogallery_heading'] = 'Video Gallery Settings';
$string['filter_poodll_whiteboard_heading'] = 'Whiteboard Settings';
$string['filter_poodll_legacy_heading'] = 'PoodLL Legacy Settings';
$string['filter_poodll_playertypes_heading'] = 'Default Player Types';
$string['filter_poodll_intercept_heading'] = 'Filetypes PoodLL Handles by Default';
$string['filter_poodll_flowplayer_heading'] = 'Flowplayer Settings'; 

     
$string['defaultplayer'] = 'Default A/V Player';
$string['html5controls'] = 'HTML5 Controls';
$string['handleflv'] = 'Handle FLV Files';
$string['handlemp4'] = 'Handle MP4 Files';
$string['handlemov'] = 'Handle MOV Files';
$string['handlemp3'] = 'Handle MP3 Files';


$string['videowidth'] = 'Video Player Width';
$string['videoheight'] = 'Video Player Height';
$string['videosplash'] = 'Show Simple Video Splash';
$string['videosplashdetails'] = 'Splash screen is shown for Flowplayer only.';
$string['thumbnailsplash'] = 'Use Preview as Splash';
$string['thumbnailsplashdetails'] = 'Preview splash uses first frame of video as the splash image. Only use this when using server tokyo.poodll.com.';
$string['audiowidth'] = 'Audio Player Width';
$string['audioheight'] = 'Audio Player Height';
$string['audiosplash'] = 'Show Simple Audio Splash';
$string['audiosplashdetails'] = 'Splash screen is shown for Flowplayer only.';
$string['miniplayerwidth'] = 'Mini Player Width';
$string['wordplayerfontsize'] = 'Word Player Fontsize';


$string['talkbackwidth'] = 'Talkback Player Width';
$string['talkbackheight'] = 'Talkback Player Height';
$string['showwidth'] = 'Screencast Player Width';
$string['showheight'] = 'Screencast Player Height';

$string['datadir'] = 'PoodLL Data Dir';
$string['datadirdetails'] = 'A sub directory of Moodle dir, to allow some components Moodle 1.9 style file access to media resources. Should only be used for non sensitive media resources. PoodLL will not create, or manage access rights for, this folder';

$string['forum_recording'] = 'PoodLL Forum: AV Recording Enabled?';
$string['forum_audio'] = 'PoodLL Forum: Audio?';
$string['forum_video'] = 'PoodLL Forum: Video?';

$string['journal_recording'] = 'PoodLL Journal: AV Recording Enabled?';
$string['journal_audio'] = 'PoodLL Journal: Audio?';
$string['journal_video'] = 'PoodLL Journal: Video?';

$string['servername'] = 'PoodLL Host Address';
$string['serverid'] = 'PoodLL Server Id';
$string['serverport'] = 'PoodLL Server Port (RTMP)';
$string['serverhttpport'] = 'PoodLL Server Port (HTTP)';
$string['autotryports'] = 'Try diff. ports if cannot connect';

//$string['useproxy'] = 'Use Moodle Proxy?';

$string['usecourseid'] = 'Use Course ID?';
$string['filename'] = 'Default Filename';
$string['overwrite'] = 'Overwrite Same?';

$string['screencapturedevice'] = 'Screencast Capture Device Name';

$string['nopoodllresource'] = '--- Select PoodLL Resource ---';

$string['biggallwidth'] = 'Vid. Gallery (big) Width';
$string['biggallheight'] = 'Vid. Gallery (big) Height';

$string['smallgallwidth'] = 'Vid. Gallery (small) Width';
$string['smallgallheight'] = 'Vid. Gallery (small) Height';

$string['newpairwidth'] = 'Pairwork Widget Width ';
$string['newpairheight'] = 'Pairwork Widget Height';

$string['wboardwidth'] = 'Whiteboard Width ';
$string['wboardheight'] = 'Whiteboard Height';

//video capture settings
$string['capturewidth'] = 'Video Recorder Capture Size';
$string['captureheight'] = 'Video Recorder Capture Height';
$string['capturefps'] = 'Video Recorder Capture FPS';
$string['studentcam'] = 'Preferred device name for camera';
$string['bandwidth'] = 'Student connection. bytes/second. Affects webcam qual. ';
$string['picqual'] = 'Target webcam qual. 1 - 10 ';



//audio capture settings 
$string['studentmic'] = 'Preferred  device name for microphone';
$string['micrate'] = 'Mic. Rate';
$string['micgain'] = 'Mic. Gain';
$string['micsilencelevel'] = 'Mic. Silence Level';
$string['micecho'] = 'Mic. Echo';
$string['micloopback'] = 'Mic. Loopback';


//fpembedtype
$string['fpembedtype'] = 'Flowplayer Embed Method';
$string['fp_embedtypedescr'] = 'SWF Object is the most reliable. Flowplayer JS handles preview splash images better. If you use Flowplayer JS consider turning off Multimedia Plugins filter MP3/FLV/MP4 handling to avoid double-filtering. ';
$string['fp_bgcolor'] = 'Flowplayer Color';
$string['fp_enableplaylist'] = 'Enable Flowplayer Audiolist';
$string['fp_enableplaylistdescr'] = 'This requires the JQuery javascript library and adds about 100kb to the page download size. Moodle will cache it though, so there should be no noticeable slowdown. You should also set the Flowplayer embed setting to Flowplayer js. Purge the cache after changing this or any flowplayer config setting.';

//html5 settings
$string['html5use_heading'] ='When to use HTML5';
$string['html5rec'] ='HTML5 Recording';
$string['html5play'] ='HTML5 Playback';
$string['html5widgets'] ='HTML5 PoodLL Widgets';

//transcode settings
$string['transcode_heading'] ='Audio/Video File Conversion Settings';
$string['videotranscode'] = 'Auto Conv. to MP4';
$string['videotranscodedetails'] = 'Convert recorded/uploaded video files to MP4 format before storing in Moodle. This works for recordings made on tokyo.poodll.com, or uploaded recordings if using FFMPEG';
$string['audiotranscode'] = 'Auto Conv. to MP3';
$string['audiotranscodedetails'] = 'Convert recorded/uploaded audio file to MP3 format before storing in Moodle. This works for recordings made on tokyo.poodll.com, or uploaded recordings if using FFMPEG';
$string['ffmpeg'] ='Convert uploaded media with FFMPEG';
$string['ffmpeg_details'] ='FFMPEG must be installed on your Moodle Server and on the system path. It will need to support converting to mp3, so try it out first on the command line, eg ffmpeg -i somefile.flv somefile.mp3 . This is still *experimental*';
?>
