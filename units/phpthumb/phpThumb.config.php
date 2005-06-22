<?php

require("../../includes.php");

//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// See: phpthumb.readme.txt for usage instructions          //
//                                                         ///
//////////////////////////////////////////////////////////////

if (!file_exists('phpthumb.functions.php') || !include_once('phpthumb.functions.php')) {
	die('failed to include_once(phpthumb.functions.php) - realpath="'.realpath('phpthumb.functions.php').'"');
}

// START USER CONFIGURATION SECTION:

// * DocumentRoot configuration
// phpThumb() depends on $_SERVER['DOCUMENT_ROOT'] to resolve path/filenames. This value is almost always correct,
// but has been known to be broken on rare occasion. This value allows you to override the default value.
// Do not modify from the default value of $_SERVER['DOCUMENT_ROOT'] unless you are having problems.
//$PHPTHUMB_CONFIG['document_root'] = '/home/httpd/httpdocs';
//$PHPTHUMB_CONFIG['document_root'] = 'c:\\webroot\\example.com\\www';
//echo @$_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'].' = '.md5_file(@$_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF']).'<br>';
//echo realpath('.').'/'.basename($_SERVER['PHP_SELF']).' = '.md5_file(realpath('.').'/'.basename($_SERVER['PHP_SELF'])).'<br>';
//exit;
$PHPTHUMB_CONFIG['document_root'] = path;


// * Cache directory configuration (choose only one of these - leave the other lines commented-out):
// Note: this directory must be writable (usually chmod 777 is neccesary) for caching to work.
// If the directory is not writable no error will be generated but caching will be disabled.
$PHPTHUMB_CONFIG['cache_directory'] = path.'_files/cache/';                            // set the cache directory relative to the phpThumb() installation
//$PHPTHUMB_CONFIG['cache_directory'] = $PHPTHUMB_CONFIG['document_root'].'/phpthumb/cache/'; // set the cache directory to an absolute directory for all source images
//$PHPTHUMB_CONFIG['cache_directory'] = './cache/';                                           // set the cache directory relative to the source image - must start with '.' (will not work to cache URL- or database-sourced images, please use an absolute directory name)
//$PHPTHUMB_CONFIG['cache_directory'] = null;                                                 // disable thumbnail caching (not recommended)

$PHPTHUMB_CONFIG['cache_disable_warning'] = false; // If [cache_directory] is non-existant or not writable, and [cache_disable_warning] is false, an error image will be generated warning to either set the cache directory or disable the warning (to avoid people not knowing about the cache)

// * Cache culling: phpThumb can automatically limit the contents of the cache directory
// based on last-access date and/or number of files and/or total filesize.
$PHPTHUMB_CONFIG['cache_maxage'] = null;         // never delete cached thumbnails based on last-access time
//$PHPTHUMB_CONFIG['cache_maxage'] = 86400 * 30; // delete cached thumbnails that haven't been accessed in more than [30 days] (value is maximum time since last access in seconds to avoid deletion)

//$PHPTHUMB_CONFIG['cache_maxsize'] = null;   // never delete cached thumbnails based on byte size of cache directory
$PHPTHUMB_CONFIG['cache_maxsize'] = 1048576000; // delete least-recently-accessed cached thumbnails when more than [10MB] of cached files are present (value is maximum bytesize of all cached files)

$PHPTHUMB_CONFIG['cache_maxfiles'] = null;  // never delete cached thumbnails based on number of cached files
//$PHPTHUMB_CONFIG['cache_maxfiles'] = 500; // delete least-recently-accessed cached thumbnails when more than [500] cached files are present (value is maximum number of cached files to keep)


// * Source image cache configuration
$PHPTHUMB_CONFIG['cache_source_enabled']   = false;                               // if true, source images obtained via HTTP are cached to $PHPTHUMB_CONFIG['cache_source_directory']
$PHPTHUMB_CONFIG['cache_source_directory'] = dirname(__FILE__).'/cache/source/';  // set the cache directory for unprocessed source images


// * Temp directory configuration
// phpThumb() may need to create temp files. Usually the system temp dir is writable and can be used.
// Leave this value as NULL in most cases. If you get errors about "failed to open <filename> for writing"
// you should change this to a full pathname to a directory you do have write access to.
//$PHPTHUMB_CONFIG['temp_directory'] = '/tmp/';
$PHPTHUMB_CONFIG['temp_directory'] = null;


// maximum number of pixels in source image to attempt to process entire image.
// If this is zero then no limit on source image dimensions.
// If this is nonzero then this is the maximum number of pixels the source image
// can have to be processed normally, otherwise the embedded EXIF thumbnail will
// be used (if available) or an "image too large" notice will be displayed.
// This is to be used for large source images (> 1600x1200) and low PHP memory
// limits. If PHP runs out of memory the script will usually just die with no output.
// To calculate this number, multiply the dimensions of the largest image
// you can process with your memory limitation (e.g. 1600 * 1200 = 1920000)
// As a general guideline, this number will be about 20% of your PHP memory
// configuration, so 8M = 1,677,722; 16M = 3,355,443; 32M = 6,710,886; etc.
if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.2', '>=') && !defined('memory_get_usage') && !@ini_get('memory_limit')) {
	// memory_get_usage() will only be defined if your PHP is compiled with the --enable-memory-limit configuration option.
	$PHPTHUMB_CONFIG['max_source_pixels'] = 0;         // no memory limit
} else {
	// calculate default max_source_pixels as 20% of memory limit configuration
	$PHPTHUMB_CONFIG['max_source_pixels'] = round(max(intval(ini_get('memory_limit')), intval(get_cfg_var('memory_limit'))) * 1048576 * 0.20);
	//$PHPTHUMB_CONFIG['max_source_pixels'] = 0;       // no memory limit
	//$PHPTHUMB_CONFIG['max_source_pixels'] = 1920000; // allow 1600x1200 images (2Mpx), no larger (about 10MB memory required)
	//$PHPTHUMB_CONFIG['max_source_pixels'] = 3355443; // 16MB memory limit
	//$PHPTHUMB_CONFIG['max_source_pixels'] = 3871488; // allow 2272x1704 images (4Mpx), no larger (about 16MB memory required)
}


// ImageMagick configuration
// If source image is larger than available memory limits as defined above in
// 'max_source_pixels' AND ImageMagick's "convert" program is available, phpThumb()
// will call ImageMagick to perform the thumbnailing of the source image to bypass
// the memory limitation. Leaving the value as NULL will cause phpThumb() to
// attempt to detect ImageMagick's presence with `which`
if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
	// Windows: set absolute pathname
	$PHPTHUMB_CONFIG['imagemagick_path'] = 'C:\\Program Files\\ImageMagick-6.0.6-Q16\\convert.exe';
} else {
	// *nix: set absolute pathname to "convert", or leave as null if "convert" is in the path
	//$PHPTHUMB_CONFIG['imagemagick_path'] = '/usr/local/bin/convert';
	$PHPTHUMB_CONFIG['imagemagick_path'] = null;
}


// * Default output configuration:
$PHPTHUMB_CONFIG['output_format']    = 'jpeg'; // default output format ('jpeg', 'png' or 'gif') - thumbnail will be output in this format (if available in your version of GD). This is always overridden by ?f=___ GETstring parameter
$PHPTHUMB_CONFIG['output_maxwidth']  = 100;      // default maximum thumbnail width.  If this is zero then default width  is the width  of the source image. This is always overridden by ?w=___ GETstring parameter
$PHPTHUMB_CONFIG['output_maxheight'] = 100;      // default maximum thumbnail height. If this is zero then default height is the height of the source image. This is always overridden by ?h=___ GETstring parameter
$PHPTHUMB_CONFIG['output_interlace'] = true;   // if true: interlaced output for GIF/PNG, progressive output for JPEG; if false: non-interlaced for GIF/PNG, baseline for JPEG.

// * Error message configuration
$PHPTHUMB_CONFIG['error_image_width']           = 100;      // default width for error images
$PHPTHUMB_CONFIG['error_image_height']          = 100;      // default height for error images
$PHPTHUMB_CONFIG['error_message_image_default'] = '';       // Set this to the name of a generic error image (e.g. '/images/error.png') that you want displayed in place of any error message that may occur. This setting is overridden by the 'err' parameter, which does the same thing.
$PHPTHUMB_CONFIG['error_bgcolor']               = 'CCCCFF'; // background color of error message images
$PHPTHUMB_CONFIG['error_textcolor']             = 'FF0000'; // color of text in error messages
$PHPTHUMB_CONFIG['error_fontsize']              = 1;        // size of text in error messages, from 1 (smallest) to 5 (largest)
$PHPTHUMB_CONFIG['error_die_on_error']          = true;     // die with error message on any fatal error (recommended with standalone phpThumb.php)
$PHPTHUMB_CONFIG['error_silent_die_on_error']   = false;    // simply die with no output of any kind on fatal errors (not recommended)
$PHPTHUMB_CONFIG['error_die_on_source_failure'] = false;    // die with error message if source image cannot be processed by phpThumb() (usually because source image is corrupt in some way). If false (default) the source image will be passed through unprocessed, if true an error message will be displayed.

// * Off-server Thumbnailing Configuration:
$PHPTHUMB_CONFIG['nohotlink_enabled']           = true;                                     // If false will allow thumbnailing from any source domain
$PHPTHUMB_CONFIG['nohotlink_valid_domains']     = array(@$_SERVER['HTTP_HOST']);            // This is the list of domains for which thumbnails are allowed to be created. The default value of the current domain should be fine in most cases, but if neccesary you can add more domains in here, in the format 'www.example.com'
$PHPTHUMB_CONFIG['nohotlink_erase_image']       = true;                                     // if true thumbnail is covered up with $PHPTHUMB_CONFIG['nohotlink_fill_color'] before text is applied, if false text is written over top of thumbnail
$PHPTHUMB_CONFIG['nohotlink_text_message']      = 'Off-server thumbnailing is not allowed'; // text of error message
// * Off-server Linking Configuration:
$PHPTHUMB_CONFIG['nooffsitelink_enabled']       = true;                                       // If false will allow thumbnails to be linked to from any domain, if true only domains listed below in 'nooffsitelink_valid_domains' will be allowed.
$PHPTHUMB_CONFIG['nooffsitelink_valid_domains'] = array(@$_SERVER['HTTP_HOST']);              // This is the list of domains for which thumbnails are allowed to be created. The default value of the current domain should be fine in most cases, but if neccesary you can add more domains in here, in the format 'www.example.com'
$PHPTHUMB_CONFIG['nooffsitelink_require_refer'] = false;                                      // If false will allow standalone calls to phpThumb(). If true then only requests with a $_SERVER['HTTP_REFERER'] value in 'nooffsitelink_valid_domains' are allowed.
$PHPTHUMB_CONFIG['nooffsitelink_erase_image']   = true;                                       // if true thumbnail is covered up with $PHPTHUMB_CONFIG['nohotlink_fill_color'] before text is applied, if false text is written over top of thumbnail
$PHPTHUMB_CONFIG['nooffsitelink_text_message']  = 'Image taken from '.@$_SERVER['HTTP_HOST']; // text of error message

// * Border & Background default colors
$PHPTHUMB_CONFIG['border_hexcolor']     = '000000'; // Default border color - usual HTML-style hex color notation (overidden with 'bc' parameter)
$PHPTHUMB_CONFIG['background_hexcolor'] = 'FFFFFF'; // Default background color when thumbnail aspect ratio does not match fixed-dimension box - usual HTML-style hex color notation (overridden with 'bg' parameter)

// * Watermark configuration
$PHPTHUMB_CONFIG['ttf_directory'] = '.'; // Base directory for TTF font files
//$PHPTHUMB_CONFIG['ttf_directory'] = 'c:/windows/fonts';


$PHPTHUMB_CONFIG['high_security_enabled']  = false; // if enabled, requires 'high_security_password' set to at least 5 characters, and requires the use of phpThumbURL() function (at the bottom of phpThumb.config.php) to generate hashed URLs
$PHPTHUMB_CONFIG['high_security_password'] = '';    // required if 'high_security_enabled' is true, must be at least 5 characters long
$PHPTHUMB_CONFIG['disable_debug']          = false; // Prevent phpThumb from displaying any information about your system. If true, phpThumbDebug and error messages will be disabled


$PHPTHUMB_CONFIG['use_exif_thumbnail_for_speed'] = true; // If true, and EXIF thumbnail is available, and is larger or equal to output image dimensions, use EXIF thumbnail rather than actual source image for generating thumbnail. Benefit is only speed, avoiding resizing large image.

// if true, and source image is smaller than 'w' & 'h' parameters or $PHPTHUMB_CONFIG['output_maxheight'] / $PHPTHUMB_CONFIG['output_maxwidth']
// will be enlarged to that size. If false then small images will not be enlarged beyond their original dimensions
$PHPTHUMB_CONFIG['output_allow_enlarging'] = (isset($_REQUEST['aoe']) ? (bool) $_REQUEST['aoe'] : false);

// END USER CONFIGURATION SECTION

///////////////////////////////////////////////////////////////////////////////

// START DEFAULT PARAMETERS SECTION
// If any parameters are constant across ALL images, you can set them here

// If true, any parameters in the URL will override the defaults set here
// If false, any parameters set here cannot be overridden in the URL
$PHPTHUMB_DEFAULTS_GETSTRINGOVERRIDE = true;

//$PHPTHUMB_DEFAULTS['w']    = 200;
//$PHPTHUMB_DEFAULTS['fltr'] = array('wmi|/images/watermark.png');
//$PHPTHUMB_DEFAULTS['q']    =  90;


// END DEFAULT PARAMETERS SECTION



///////////////////////////////////////////////////////////////////////////////
// function for generating hashed calls to phpThumb if 'high_security_enabled'
// echo '<img src="'.phpThumbURL('src=pic.jpg&w=50').'">';

function phpThumbURL($ParameterString) {
	global $PHPTHUMB_CONFIG;
	return 'phpThumb.php?'.$ParameterString.'&hash='.md5($ParameterString.$PHPTHUMB_CONFIG['high_security_password']);
}

///////////////////////////////////////////////////////////////////////////////

s?>