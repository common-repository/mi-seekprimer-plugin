<?php
/*
Plugin Name: Flv Player
Plugin URI: http://nexus.zteo.com/2007/07/01/flv-player/
Description: A filter for WordPress that displays Flash Streaming Videos using Geoff Stearns's player
Version: 1.0
Author: Chris Ravenscroft
Author URI: http://nexus.zteo.com/

Instructions

1. Download Geoff's player from http://blog.deconcept.com/swfobject/
2. Expand archive and copy flvplayer.swf and swfobject.js to wp-content/plugins/
3. Copy this file to wp-content/plugins/
4. Go to Administration > Plugins and activate this plugin.
5. In your posts, use this syntax:
   [flvplayer file|url[[width [height]]]
   eg:
   [flvplayer http://acme.com/video.flv]
   [flvplayer http://acme.com/video.flv 640 480]
*/

global $flvplayer_counter; // used to ensure uniqueness on the current page
$flvplayer_counter = 0;

function flvplayer_header()
{
	$output = <<<EOB
<script type="text/javascript" src="wp-content/plugins/swfobject.js"></script>
EOB;
	print $output;
}

function flvplayer_plugin_callback($match)
{
global $flvplayer_counter;

	$flvplayer_counter ++;
	// Default: Assume 'name' syntax
	$width  = 640;
	$height = 480;
	if(count($match)>2)
	{
		// Assume 'name width height' syntax
		if(!empty($match[2]))
			$width  = $match[2];
		if(!empty($match[3]))
			$height = $match[3];
	}

	$output = <<<EOB
<div id="player{$flvplayer_counter}"><a href="http://www.macromedia.com/go/getflashplayer">Get Flash</a> to see this player.</div>
<script type="text/javascript">
var so = new SWFObject('wp-content/plugins/flvplayer.swf', 'player', {$width}, {$height}, '7');
so.addParam("allowfullscreen","true");
so.addVariable("file","{$match[1]}");
so.write('player{$flvplayer_counter}');
</script>
EOB;
	return $output;
}

function flvplayer_plugin($content)
{
	return preg_replace_callback('/\[flvplayer ([A-Za-z0-9\-_\/\?\&\#\%\.\=@:;]+)(?:[ ]*)([A-Za-z0-9\-_\/\?\&\#\%\.\=@:;]*)(?:[ ]*)([A-Za-z0-9\-_\/\?\&\#\%\.\=@:;]*)\]/', 'flvplayer_plugin_callback', $content);
}

add_action('wp_head', 'flvplayer_header');
add_filter('the_content', 'flvplayer_plugin');
add_filter('comment_text', 'flvplayer_plugin');

?>
