<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// See: phpthumb.changelog.txt for recent changes           //
// See: phpthumb.readme.txt for usage instructions          //
//                                                         ///
//////////////////////////////////////////////////////////////

error_reporting(E_ALL);
ini_set('display_errors', '1');
if (!@ini_get('safe_mode')) {
	set_time_limit(60);  // shouldn't take nearly this long in most cases, but with many filter and/or a slow server...
}

function SendSaveAsFileHeaderIfNeeded() {
	if (!empty($_GET['down'])) {
		$downloadfilename = ereg_replace('[/\\:\*\?"<>|]', '_', $_GET['down']);
		if (phpthumb_functions::version_compare_replacement(phpversion(), '4.1.0', '>=')) {
			$downloadfilename = trim($downloadfilename, '.');
		}
		if (!empty($downloadfilename)) {
			header('Content-Disposition: attachment; filename="'.$downloadfilename.'"');
		}
	}
	return true;
}

// this script relies on the superglobal arrays, fake it here for old PHP versions
if (phpversion() < '4.1.0') {
	$_SERVER = $HTTP_SERVER_VARS;
	$_GET    = $HTTP_GET_VARS;
}


if (file_exists('phpThumb.config.php')) {
	if (@include_once('phpThumb.config.php')) {
		// great
	} else {
		die('failed to include_once(phpThumb.config.php) - realpath="'.realpath('.').'/phpThumb.config.php"');
	}
} elseif (file_exists('phpThumb.config.php.default')) {
	die('Please rename "phpThumb.config.php.default" to "phpThumb.config.php"');
} else {
	die('failed to include_once(phpThumb.config.php) - realpath="'.realpath('.').'/phpThumb.config.php"');
}

if (!@$_SERVER['QUERY_STRING']) {
	die('$_SERVER[QUERY_STRING] is empty');
}
if (@$PHPTHUMB_CONFIG['high_security_enabled']) {
	if (!@$_GET['hash']) {
		die('ERROR: missing hash');
	}
	if (strlen($PHPTHUMB_CONFIG['high_security_password']) < 5) {
		die('ERROR: strlen($PHPTHUMB_CONFIG[high_security_password]) < 5');
	}
	if ($_GET['hash'] != md5(str_replace('&hash='.$_GET['hash'], '', $_SERVER['QUERY_STRING']).$PHPTHUMB_CONFIG['high_security_password'])) {
		die('ERROR: invalid hash');
	}
}

if (!function_exists('ImageJPEG') && !function_exists('ImagePNG') && !function_exists('ImageGIF')) {
	// base64-encoded error image in GIF format
	$ERROR_NOGD = 'R0lGODlhIAAgALMAAAAAABQUFCQkJDY2NkZGRldXV2ZmZnJycoaGhpSUlKWlpbe3t8XFxdXV1eTk5P7+/iwAAAAAIAAgAAAE/vDJSau9WILtTAACUinDNijZtAHfCojS4W5H+qxD8xibIDE9h0OwWaRWDIljJSkUJYsN4bihMB8th3IToAKs1VtYM75cyV8sZ8vygtOE5yMKmGbO4jRdICQCjHdlZzwzNW4qZSQmKDaNjhUMBX4BBAlmMywFSRWEmAI6b5gAlhNxokGhooAIK5o/pi9vEw4Lfj4OLTAUpj6IabMtCwlSFw0DCKBoFqwAB04AjI54PyZ+yY3TD0ss2YcVmN/gvpcu4TOyFivWqYJlbAHPpOntvxNAACcmGHjZzAZqzSzcq5fNjxFmAFw9iFRunD1epU6tsIPmFCAJnWYE0FURk7wJDA0MTKpEzoWAAskiAAA7';
	header('Content-type: image/gif');
	echo base64_decode($ERROR_NOGD);
	exit;
}

// returned the fixed string if the evil "magic_quotes_gpc" setting is on
if (get_magic_quotes_gpc()) {
	$RequestVarsToStripSlashes = array('src', 'wmf', 'file', 'err', 'goto', 'down');
	foreach ($RequestVarsToStripSlashes as $key) {
		if (isset($_GET[$key])) {
			$_GET[$key] = stripslashes($_GET[$key]);
		}
	}
}

// instantiate a new phpThumb() object
if (!include_once('phpthumb.class.php')) {
	die('failed to include_once("'.realpath('phpthumb.class.php').'")');
}
$phpThumb = new phpThumb();

foreach ($PHPTHUMB_CONFIG as $key => $value) {
	$keyname = 'config_'.$key;
	$phpThumb->$keyname = $value;
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
if (@$_GET['phpThumbDebug'] == '1') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

$parsed_url_referer = parse_url(@$_SERVER['HTTP_REFERER']);
if ($phpThumb->config_nooffsitelink_require_refer && !in_array(@$parsed_url_referer['host'], $phpThumb->config_nohotlink_valid_domains)) {
	$phpThumb->ErrorImage('config_nooffsitelink_require_refer enabled and '.(@$parsed_url_referer['host'] ? '"'.$parsed_url_referer['host'].'" is not an allowed referer' : 'no HTTP_REFERER exists'));
}
$parsed_url_src = parse_url(@$_GET['src']);
if ($phpThumb->config_nohotlink_enabled && $phpThumb->config_nohotlink_erase_image && eregi('^(f|ht)tp[s]?://', @$_GET['src']) && !in_array(@$parsed_url_src['host'], $phpThumb->config_nohotlink_valid_domains)) {
	$phpThumb->ErrorImage($phpThumb->config_nohotlink_text_message);
}


////////////////////////////////////////////////////////////////
// You may want to pull data from a database rather than a physical file
// If so, uncomment the following $SQLquery line (modified to suit your database)
// Note: this must be the actual binary data of the image, not a URL or filename
// see http://www.billy-corgan.com/blog/archive/000143.php for a brief tutorial on this section

//$SQLquery = 'SELECT `picture` FROM `products` WHERE (`id` = \''.mysql_escape_string(@$_GET['id']).'\')';
if (@$SQLquery) {

	// change this information to match your server
	$hostname = 'localhost';
	$username = 'username';
	$password = 'password';
	$database = 'database';
	if ($cid = @mysql_connect($hostname, $username, $password)) {
		if (@mysql_select_db($database, $cid)) {
			if ($result = @mysql_query($SQLquery, $cid)) {
				if ($row = @mysql_fetch_array($result)) {

					mysql_free_result($result);
					mysql_close($cid);
					$phpThumb->setSourceData($row[0]);
					unset($row);

				} else {
					mysql_free_result($result);
					mysql_close($cid);
					$phpThumb->ErrorImage('no matching data in database.');
					//$phpThumb->ErrorImage('no matching data in database. MySQL said: "'.mysql_error($cid).'"');
				}
			} else {
				mysql_close($cid);
				$phpThumb->ErrorImage('Error in MySQL query: "'.mysql_error($cid).'"');
			}
		} else {
			mysql_close($cid);
			$phpThumb->ErrorImage('cannot select MySQL database: "'.mysql_error($cid).'"');
		}
	} else {
		$phpThumb->ErrorImage('cannot connect to MySQL server');
	}
	unset($_GET['id']);
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
if (@$_GET['phpThumbDebug'] == '2') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

$allowedGETparameters = array('src', 'new', 'w', 'h', 'f', 'q', 'sx', 'sy', 'sw', 'sh', 'zc', 'bc', 'bg', 'bgt', 'fltr', 'file', 'goto', 'err', 'xto', 'ra', 'ar', 'aoe', 'far', 'iar', 'maxb', 'down', 'phpThumbDebug', 'hash');
foreach ($_GET as $key => $value) {
	if (in_array($key, $allowedGETparameters)) {
		$phpThumb->$key = $value;
	} else {
		$phpThumb->ErrorImage('Forbidden parameter: '.$key);
	}
}

if (!empty($PHPTHUMB_DEFAULTS)) {
	foreach ($PHPTHUMB_DEFAULTS as $key => $value) {
		if ($PHPTHUMB_DEFAULTS_GETSTRINGOVERRIDE || !isset($_GET[$key])) {
			$phpThumb->$key = $value;
		}
	}
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
if (@$_GET['phpThumbDebug'] == '3') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////


// check to see if file can be output from source with no processing or caching
$CanPassThroughDirectly = true;
if (!empty($phpThumb->rawImageData)) {
	// data from SQL, should be fine
} elseif (!@is_file(@$_GET['src']) || !@is_readable(@$_GET['src'])) {
	$CanPassThroughDirectly = false;
}
foreach ($_GET as $key => $value) {
	switch ($key) {
		case 'src':
			// allowed
			break;

		default:
			// all other parameters will cause some processing,
			// therefore cannot pass through original image unmodified
			$CanPassThroughDirectly = false;
			$phpThumb->DebugMessage('Cannot pass through directly because $_GET['.$key.'] is set', __FILE__, __LINE__);
			break 2;
	}
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
if (@$_GET['phpThumbDebug'] == '4') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

if ($CanPassThroughDirectly && $phpThumb->src) {
	// no parameters set, passthru
	$SourceFilename = $phpThumb->ResolveFilenameToAbsolute($phpThumb->src);
	if (@$_GET['phpThumbDebug']) {
		$phpThumb->DebugMessage('Would have passed "'.$SourceFilename.'" through directly, but skipping due to phpThumbDebug', __FILE__, __LINE__);
	} else {
		SendSaveAsFileHeaderIfNeeded();
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', @filemtime($SourceFilename)).' GMT');
		if ($getimagesize = @GetImageSize($SourceFilename)) {
			header('Content-type: '.phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]));
		}
		@readfile($SourceFilename);
		exit;
	}
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
if (@$_GET['phpThumbDebug'] == '5') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

// check to see if file already exists in cache, and output it with no processing if it does
$phpThumb->SetCacheFilename();
if (is_file($phpThumb->cache_filename)) {
	$parsed_url = @parse_url(@$_SERVER['HTTP_REFERER']);
	if ($phpThumb->config_nooffsitelink_enabled && @$_SERVER['HTTP_REFERER'] && !in_array(@$parsed_url['host'], $phpThumb->config_nooffsitelink_valid_domains)) {
		$phpThumb->DebugMessage('Would have used cached (image/'.$phpThumb->thumbnailFormat.') file "'.$phpThumb->cache_filename.'" (Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($phpThumb->cache_filename)).' GMT), but skipping because $_SERVER[HTTP_REFERER] ('.@$_SERVER['HTTP_REFERER'].') is not in $phpThumb->config_nooffsitelink_valid_domains ('.implode(';', $phpThumb->config_nooffsitelink_valid_domains).')', __FILE__, __LINE__);
	} elseif (@$_GET['phpThumbDebug']) {
		$phpThumb->DebugMessage('Would have used cached (image/'.$phpThumb->thumbnailFormat.') file "'.$phpThumb->cache_filename.'" (Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($phpThumb->cache_filename)).' GMT), but skipping due to phpThumbDebug', __FILE__, __LINE__);
	} else {
		SendSaveAsFileHeaderIfNeeded();
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($phpThumb->cache_filename)).' GMT');
		if ($getimagesize = @GetImageSize($phpThumb->cache_filename)) {
			header('Content-type: '.phpthumb_functions::ImageTypeToMIMEtype($getimagesize[2]));
		}
		@readfile($phpThumb->cache_filename);
		exit;
	}
} else {
	$phpThumb->DebugMessage('Cached file "'.$phpThumb->cache_filename.'" does not exist, processing as normal', __FILE__, __LINE__);
}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
if (@$_GET['phpThumbDebug'] == '6') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

if ($phpThumb->rawImageData) {

	// great

} elseif (!empty($_GET['new'])) {

	// generate a blank image resource of the specified size/background color/opacity
	if (($phpThumb->w <= 0) || ($phpThumb->h <= 0)) {
		$phpThumb->ErrorImage('"w" and "h" parameters required for "new"');
	}
	@list($bghexcolor, $opacity) = explode('|', $_GET['new']);
	if (!phpthumb_functions::IsHexColor($bghexcolor)) {
		$phpThumb->ErrorImage('BGcolor parameter for "new" is not valid');
	}
	$opacity = (strlen($opacity) ? $opacity : 100);
	if ($phpThumb->gdimg_source = phpthumb_functions::ImageCreateFunction($phpThumb->w, $phpThumb->h)) {
		$alpha = (100 - min(100, max(0, $opacity))) * 1.27;
		if ($alpha) {
			$phpThumb->is_alpha = true;
			ImageAlphaBlending($phpThumb->gdimg_source, false);
			ImageSaveAlpha($phpThumb->gdimg_source, true);
		}
		$new_background_color = phpthumb_functions::ImageHexColorAllocate($phpThumb->gdimg_source, $bghexcolor, false, $alpha);
		ImageFilledRectangle($phpThumb->gdimg_source, 0, 0, $phpThumb->w, $phpThumb->h, $new_background_color);
	} else {
		$phpThumb->ErrorImage('failed to create "new" image ('.$phpThumb->w.'x'.$phpThumb->h.')');
	}

} elseif (!$phpThumb->src) {

	$phpThumb->ErrorImage('Usage: '.$_SERVER['PHP_SELF'].'?src=/path/and/filename.jpg'."\n".'read Usage comments for details');

} elseif (substr(strtolower($phpThumb->src), 0, 7) == 'http://') {

	ob_start();
	$HTTPurl = strtr($phpThumb->src, array(' '=>'%20'));
	if ($fp = fopen($HTTPurl, 'rb')) {

		$rawImageData = '';
		do {
			$buffer = fread($fp, 8192);
			if (strlen($buffer) == 0) {
				break;
			}
			$rawImageData .= $buffer;
		} while (true);
		fclose($fp);
		$phpThumb->setSourceData($rawImageData, urlencode($phpThumb->src));

	} else {

		$fopen_error = strip_tags(ob_get_contents());
		ob_end_clean();
		if (ini_get('allow_url_fopen')) {
			$phpThumb->ErrorImage('cannot open "'.$HTTPurl.'" - fopen() said: "'.$fopen_error.'"');
		} else {
			$phpThumb->ErrorImage('"allow_url_fopen" disabled');
		}

	}
	ob_end_clean();

}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
if (@$_GET['phpThumbDebug'] == '7') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

$phpThumb->GenerateThumbnail();

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
if (@$_GET['phpThumbDebug'] == '8') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

if ($phpThumb->file) {

	$phpThumb->RenderToFile($phpThumb->ResolveFilenameToAbsolute($phpThumb->file));
	if ($phpThumb->goto && (substr(strtolower($phpThumb->goto), 0, strlen('http://')) == 'http://')) {
		// redirect to another URL after image has been rendered to file
		header('Location: '.$phpThumb->goto);
		exit;
	}

} else {

	if ((file_exists($phpThumb->cache_filename) && is_writable($phpThumb->cache_filename)) || is_writable(dirname($phpThumb->cache_filename))) {

		$phpThumb->CleanUpCacheDirectory();
		$phpThumb->RenderToFile($phpThumb->cache_filename);

	} else {

		$phpThumb->DebugMessage('Cannot write to $phpThumb->cache_filename ('.$phpThumb->cache_filename.') because that directory ('.dirname($phpThumb->cache_filename).') is not writable', __FILE__, __LINE__);

	}

}

////////////////////////////////////////////////////////////////
// Debug output, to try and help me diagnose problems
if (@$_GET['phpThumbDebug'] == '9') {
	$phpThumb->phpThumbDebug();
}
////////////////////////////////////////////////////////////////

$phpThumb->OutputThumbnail();

?>