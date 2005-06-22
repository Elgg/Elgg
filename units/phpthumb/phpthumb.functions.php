<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// phpthumb.functions.php - general support functions       //
//                                                         ///
//////////////////////////////////////////////////////////////

class phpthumb_functions {

	function user_function_exists($functionname) {
		if (function_exists('get_defined_functions')) {
			static $get_defined_functions = array();
			if (empty($get_defined_functions)) {
				$get_defined_functions = get_defined_functions();
			}
			return in_array(strtolower($functionname), $get_defined_functions['user']);
		}
		return function_exists($functionname);
	}

	function builtin_function_exists($functionname) {
		if (function_exists('get_defined_functions')) {
			static $get_defined_functions = array();
			if (empty($get_defined_functions)) {
				$get_defined_functions = get_defined_functions();
			}
			return in_array(strtolower($functionname), $get_defined_functions['internal']);
		}
		return function_exists($functionname);
	}

	function version_compare_replacement_sub($version1, $version2, $operator='') {
		// If you specify the third optional operator argument, you can test for a particular relationship.
		// The possible operators are: <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne respectively.
		// Using this argument, the function will return 1 if the relationship is the one specified by the operator, 0 otherwise.

		// If a part contains special version strings these are handled in the following order: dev < (alpha = a) < (beta = b) < RC < pl
		static $versiontype_lookup = array();
		if (empty($versiontype_lookup)) {
			$versiontype_lookup['dev']   = 10001;
			$versiontype_lookup['a']     = 10002;
			$versiontype_lookup['alpha'] = 10002;
			$versiontype_lookup['b']     = 10003;
			$versiontype_lookup['beta']  = 10003;
			$versiontype_lookup['RC']    = 10004;
			$versiontype_lookup['pl']    = 10005;
		}
		if (isset($versiontype_lookup[$version1])) {
			$version1 = $versiontype_lookup[$version1];
		}
		if (isset($versiontype_lookup[$version2])) {
			$version2 = $versiontype_lookup[$version2];
		}

		switch ($operator) {
			case '<':
			case 'lt':
				return intval($version1 < $version2);
				break;
			case '<=':
			case 'le':
				return intval($version1 <= $version2);
				break;
			case '>':
			case 'gt':
				return intval($version1 > $version2);
				break;
			case '>=':
			case 'ge':
				return intval($version1 >= $version2);
				break;
			case '==':
			case '=':
			case 'eq':
				return intval($version1 == $version2);
				break;
			case '!=':
			case '<>':
			case 'ne':
				return intval($version1 != $version2);
				break;
		}
		if ($version1 == $version2) {
			return 0;
		} elseif ($version1 < $version2) {
			return -1;
		}
		return 1;
	}

	function version_compare_replacement($version1, $version2, $operator='') {
		if (function_exists('version_compare')) {
			// built into PHP v4.1.0+
			return version_compare($version1, $version2, $operator);
		}

		// The function first replaces _, - and + with a dot . in the version strings
		$version1 = strtr($version1, '_-+', '...');
		$version2 = strtr($version2, '_-+', '...');

		// and also inserts dots . before and after any non number so that for example '4.3.2RC1' becomes '4.3.2.RC.1'.
		// Then it splits the results like if you were using explode('.',$ver). Then it compares the parts starting from left to right.
		$version1 = eregi_replace('([0-9]+)([A-Z]+)([0-9]+)', '\\1.\\2.\\3', $version1);
		$version2 = eregi_replace('([0-9]+)([A-Z]+)([0-9]+)', '\\1.\\2.\\3', $version2);

		$parts1 = explode('.', $version1);
		$parts2 = explode('.', $version1);
		$parts_count = max(count($parts1), count($parts2));
		for ($i = 0; $i < $parts_count; $i++) {
			$comparison = phpthumb_functions::version_compare_replacement_sub($version1, $version2, $operator);
			if ($comparison != 0) {
				return $comparison;
			}
		}
		return 0;
	}

	function phpinfo_array() {
		static $phpinfo_array = array();
		if (empty($phpinfo_array)) {
			ob_start();
			phpinfo();
			$phpinfo = ob_get_contents();
			ob_end_clean();
			$phpinfo_array = explode("\n", $phpinfo);
		}
		return $phpinfo_array;
	}

	function exif_info() {
		static $exif_info = array();
		if (empty($exif_info)) {
			// based on code by johnschaefer at gmx dot de
			// from PHP help on gd_info()
			$exif_info = array(
				'EXIF Support'           => '',
				'EXIF Version'           => '',
				'Supported EXIF Version' => '',
				'Supported filetypes'    => ''
			);
			$phpinfo_array = phpthumb_functions::phpinfo_array();
			foreach ($phpinfo_array as $line) {
				$line = trim(strip_tags($line));
				foreach ($exif_info as $key => $value) {
					if (strpos($line, $key) === 0) {
						$newvalue = trim(str_replace($key, '', $line));
						$exif_info[$key] = $newvalue;
					}
				}
			}
		}
		return $exif_info;
	}

	function ImageTypeToMIMEtype($imagetype) {
		if (function_exists('image_type_to_mime_type')) {
			return image_type_to_mime_type($imagetype);
		}
		static $image_type_to_mime_type = array(
			1  => 'image/gif',                     // IMAGETYPE_GIF
			2  => 'image/jpeg',                    // IMAGETYPE_JPEG
			3  => 'image/png',                     // IMAGETYPE_PNG
			4  => 'application/x-shockwave-flash', // IMAGETYPE_SWF
			5  => 'image/psd',                     // IMAGETYPE_PSD
			6  => 'image/bmp',                     // IMAGETYPE_BMP
			7  => 'image/tiff',                    // IMAGETYPE_TIFF_II (intel byte order)
			8  => 'image/tiff',                    // IMAGETYPE_TIFF_MM (motorola byte order)
			9  => 'application/octet-stream',      // IMAGETYPE_JPC
			10 => 'image/jp2',                     // IMAGETYPE_JP2
			11 => 'application/octet-stream',      // IMAGETYPE_JPX
			12 => 'application/octet-stream',      // IMAGETYPE_JB2
			13 => 'application/x-shockwave-flash', // IMAGETYPE_SWC
			14 => 'image/iff',                     // IMAGETYPE_IFF
			15 => 'image/vnd.wap.wbmp',            // IMAGETYPE_WBMP
			16 => 'image/xbm');                    // IMAGETYPE_XBM

		return (isset($image_type_to_mime_type[$imagetype]) ? $image_type_to_mime_type[$imagetype] : false);
	}

	function HexCharDisplay($string) {
		$len = strlen($string);
		$output = '';
		for ($i = 0; $i < $len; $i++) {
			$output .= ' 0x'.str_pad(dechex(ord($string{$i})), 2, '0', STR_PAD_LEFT);
		}
		return $output;
	}

	function IsHexColor($HexColorString) {
		return eregi('^[0-9A-F]{6}$', $HexColorString);
	}

	function ImageColorAllocateAlphaSafe(&$gdimg_hexcolorallocate, $R, $G, $B, $alpha=false) {
		if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.2', '>=') && ($alpha !== false)) {
			return ImageColorAllocateAlpha($gdimg_hexcolorallocate, $R, $G, $B, $alpha);
		} else {
			return ImageColorAllocate($gdimg_hexcolorallocate, $R, $G, $B);
		}
	}

	function ImageHexColorAllocate(&$gdimg_hexcolorallocate, $HexColorString, $dieOnInvalid=false, $alpha=false) {
		if (!is_resource($gdimg_hexcolorallocate)) {
			die('$gdimg_hexcolorallocate is not a GD resource in ImageHexColorAllocate()');
		}
		if (phpthumb_functions::IsHexColor($HexColorString)) {
			$R = hexdec(substr($HexColorString, 0, 2));
			$G = hexdec(substr($HexColorString, 2, 2));
			$B = hexdec(substr($HexColorString, 4, 2));
			return phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_hexcolorallocate, $R, $G, $B, $alpha);
		}
		if ($dieOnInvalid) {
			die('Invalid hex color string: "'.$HexColorString.'"');
		}
		return ImageColorAllocate($gdimg_hexcolorallocate, 0x00, 0x00, 0x00);
	}

	function HexColorXOR($hexcolor) {
		return strtoupper(str_pad(dechex(~hexdec($hexcolor) & 0xFFFFFF), 6, '0', STR_PAD_LEFT));
	}

	function GetPixelColor(&$img, $x, $y) {
		return @ImageColorsForIndex($img, @ImageColorAt($img, $x, $y));
	}

	function GrayscalePixel($OriginalPixel) {
		$gray = round(($OriginalPixel['red'] * 0.30) + ($OriginalPixel['green'] * 0.59) + ($OriginalPixel['blue'] * 0.11));
		return array('red'=>$gray, 'green'=>$gray, 'blue'=>$gray);
	}

	function ImageResizeFunction(&$dst_im, &$src_im, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH) {
		if (phpthumb_functions::gd_version() >= 2.0) {
			return ImageCopyResampled($dst_im, $src_im, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
		}
		return ImageCopyResized($dst_im, $src_im, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
	}

	function ImageCreateFunction($x_size, $y_size) {
		$ImageCreateFunction = 'ImageCreate';
		if (phpthumb_functions::gd_version() >= 2.0) {
			$ImageCreateFunction = 'ImageCreateTrueColor';
		}
		if (!function_exists($ImageCreateFunction)) {
			return phpthumb::ErrorImage($ImageCreateFunction.'() does not exist - no GD support?');
		}
		if (($x_size <= 0) || ($y_size <= 0)) {
			return phpthumb::ErrorImage('Invalid image dimensions: '.$ImageCreateFunction.'('.$x_size.', '.$y_size.')');
		}
		return $ImageCreateFunction($x_size, $y_size);
	}

	function ImageCopyRespectAlpha(&$dst_im, &$src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct=100) {
		for ($x = $src_x; $x < $src_w; $x++) {
			for ($y = $src_y; $y < $src_h; $y++) {
				$RealPixel    = phpthumb_functions::GetPixelColor($dst_im, $dst_x + $x, $dst_y + $y);
				$OverlayPixel = phpthumb_functions::GetPixelColor($src_im, $x, $y);
				$alphapct = $OverlayPixel['alpha'] / 127;
				$opacipct = $pct / 100;
				$overlaypct = (1 - $alphapct) * $opacipct;

				$newcolor = phpthumb_functions::ImageColorAllocateAlphaSafe(
					$dst_im,
					round($RealPixel['red']   * (1 - $overlaypct)) + ($OverlayPixel['red']   * $overlaypct),
					round($RealPixel['green'] * (1 - $overlaypct)) + ($OverlayPixel['green'] * $overlaypct),
					round($RealPixel['blue']  * (1 - $overlaypct)) + ($OverlayPixel['blue']  * $overlaypct),
					//$RealPixel['alpha']);
					0);

				ImageSetPixel($dst_im, $dst_x + $x, $dst_y + $y, $newcolor);
			}
		}
		return true;
	}

	function ProportionalResize($old_width, $old_height, $new_width=false, $new_height=false) {
		$old_aspect_ratio = $old_width / $old_height;
		if (($new_width === false) && ($new_height === false)) {
			return false;
		} elseif ($new_width === false) {
			$new_width = $new_height * $old_aspect_ratio;
		} elseif ($new_height === false) {
			$new_height = $new_width / $old_aspect_ratio;
		}
		$new_aspect_ratio = $new_width / $new_height;
		if ($new_aspect_ratio == $old_aspect_ratio) {
			// great, done
		} elseif ($new_aspect_ratio < $old_aspect_ratio) {
			// limited by width
			$new_height = $new_width / $old_aspect_ratio;
		} elseif ($new_aspect_ratio > $old_aspect_ratio) {
			// limited by height
			$new_width = $new_height * $old_aspect_ratio;
		}
		return array(round($new_width), round($new_height));
	}

	function SafeBackTick($command) {
		static $BacktickDisabled = null;
		if (is_null($BacktickDisabled)) {
			$disable_functions = explode(',', @ini_get('disable_functions'));
			if (@ini_get('safe_mode')) {
				$BacktickDisabled = true;
			} else if (in_array('shell_exec', $disable_functions) || in_array('exec', $disable_functions) || in_array('system', $disable_functions)) {
				$BacktickDisabled = true;
			} else {
				$BacktickDisabled = false;
			}
		}
		if ($BacktickDisabled) {
			return '';
		}
		return `$command`;
	}

	function ApacheLookupURIarray($filename) {
		// apache_lookup_uri() only works when PHP is installed as an Apache module.
		if (php_sapi_name() == 'apache') {
			$keys = array('status', 'the_request', 'status_line', 'method', 'content_type', 'handler', 'uri', 'filename', 'path_info', 'args', 'boundary', 'no_cache', 'no_local_copy', 'allowed', 'send_bodyct', 'bytes_sent', 'byterange', 'clength', 'unparsed_uri', 'mtime', 'request_time');
			if ($apacheLookupURIobject = @apache_lookup_uri($filename)) {
				$apacheLookupURIarray = array();
				foreach ($keys as $key) {
					$apacheLookupURIarray[$key] = @$apacheLookupURIobject->$key;
				}
				return $apacheLookupURIarray;
			}
		}
		return false;
	}

	function gd_version($fullstring=false) {
		static $cache_gd_version = array();
		if (empty($cache_gd_version)) {
			$gd_info = phpthumb_functions::gd_info();
			if (substr($gd_info['GD Version'], 0, strlen('bundled (')) == 'bundled (') {
				$cache_gd_version[1] = $gd_info['GD Version'];                                         // e.g. "bundled (2.0.15 compatible)"
				$cache_gd_version[0] = (float) substr($gd_info['GD Version'], strlen('bundled ('), 3); // e.g. "2.0" (not "bundled (2.0.15 compatible)")
			} else {
				$cache_gd_version[1] = $gd_info['GD Version'];                       // e.g. "1.6.2 or higher"
				$cache_gd_version[0] = (float) substr($gd_info['GD Version'], 0, 3); // e.g. "1.6" (not "1.6.2 or higher")
			}
		}
		return $cache_gd_version[intval($fullstring)];
	}

	function gd_info() {
		if (function_exists('gd_info')) {
			// built into PHP v4.3.0+ (with bundled GD2 library)
			return gd_info();
		}

		static $gd_info = array();
		if (empty($gd_info)) {
			// based on code by johnschaefer at gmx dot de
			// from PHP help on gd_info()
			$gd_info = array(
				'GD Version'         => '',
				'FreeType Support'   => false,
				'FreeType Linkage'   => '',
				'T1Lib Support'      => false,
				'GIF Read Support'   => false,
				'GIF Create Support' => false,
				'JPG Support'        => false,
				'PNG Support'        => false,
				'WBMP Support'       => false,
				'XBM Support'        => false
			);
			$phpinfo_array = phpthumb_functions::phpinfo_array();
			foreach ($phpinfo_array as $line) {
				$line = trim(strip_tags($line));
				foreach ($gd_info as $key => $value) {
					//if (strpos($line, $key) !== false) {
					if (strpos($line, $key) === 0) {
						$newvalue = trim(str_replace($key, '', $line));
						$gd_info[$key] = $newvalue;
					}
				}
			}
			if (empty($gd_info['GD Version'])) {
				// probable cause: "phpinfo() disabled for security reasons"
				if (function_exists('ImageTypes')) {
					$imagetypes = ImageTypes();
					if ($imagetypes & IMG_PNG) {
						$gd_info['PNG Support'] = true;
					}
					if ($imagetypes & IMG_GIF) {
						$gd_info['GIF Create Support'] = true;
					}
					if ($imagetypes & IMG_JPG) {
						$gd_info['JPG Support'] = true;
					}
					if ($imagetypes & IMG_WBMP) {
						$gd_info['WBMP Support'] = true;
					}
				}
				// to determine capability of GIF creation, try to use ImageCreateFromGIF on a 1px GIF
				if (function_exists('ImageCreateFromGIF')) {
					if ($tempfilename = phpthumb::phpThumb_tempnam()) {
						if ($fp_tempfile = @fopen($tempfilename, 'wb')) {
							fwrite($fp_tempfile, base64_decode('R0lGODlhAQABAIAAAH//AP///ywAAAAAAQABAAACAUQAOw==')); // very simple 1px GIF file base64-encoded as string
							fclose($fp_tempfile);

							// if we can convert the GIF file to a GD image then GIF create support must be enabled, otherwise it's not
							$gd_info['GIF Read Support'] = (bool) @ImageCreateFromGIF($tempfilename);
						}
						unlink($tempfilename);
					}
				}
				if (function_exists('ImageCreateTrueColor') && @ImageCreateTrueColor(1, 1)) {
					$gd_info['GD Version'] = '2.0.1 or higher (assumed)';
				} elseif (function_exists('ImageCreate') && @ImageCreate(1, 1)) {
					$gd_info['GD Version'] = '1.6.0 or higher (assumed)';
				}
			}
		}
		return $gd_info;
	}

	function filesize_remote($remotefile, $timeout=10) {
		$size = false;
		$url = parse_url($remotefile);
		if ($fp = @fsockopen($url['host'], ($url['port'] ? $url['port'] : 80), $errno, $errstr, $timeout)) {
			fwrite($fp, 'HEAD '.@$url['path'].@$url['query'].' HTTP/1.0'."\r\n".'Host: '.@$url['host']."\r\n\r\n");
			stream_set_timeout($fp, $timeout);
			while (!feof($fp)) {
				$headerline = fgets($fp, 4096);
				if (eregi('^Content-Length: (.*)', $headerline, $matches)) {
					$size = intval($matches[1]);
					break;
				}
			}
			fclose ($fp);
		}
		return $size;
	}

	function filedate_remote($remotefile, $timeout=10) {
		$date = false;
		$url = parse_url($remotefile);
		if ($fp = @fsockopen($url['host'], ($url['port'] ? $url['port'] : 80), $errno, $errstr, $timeout)) {
			fwrite($fp, 'HEAD '.@$url['path'].@$url['query'].' HTTP/1.0'."\r\n".'Host: '.@$url['host']."\r\n\r\n");
			stream_set_timeout($fp, $timeout);
			while (!feof($fp)) {
				$headerline = fgets($fp, 4096);
				if (eregi('^Last-Modified: (.*)', $headerline, $matches)) {
					$date = strtotime($matches[1]) - date('Z');
					break;
				}
			}
			fclose ($fp);
		}
		return $date;
	}

}

?>