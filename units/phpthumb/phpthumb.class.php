<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// See: phpthumb.readme.txt for usage instructions          //
//                                                         ///
//////////////////////////////////////////////////////////////

if (!include_once('phpthumb.functions.php')) {
	die('failed to include_once("'.realpath('phpthumb.functions.php').'")');
}

class phpthumb {

	// public:
	// START PARAMETERS (for object mode and phpThumb.php)
	// See phpthumb.readme.txt for descriptions of what each of these values are
	var $src  = null;    // SouRCe filename
	var $new  = null;    // NEW image (phpThumb.php only)
	var $w    = null;    // Width
	var $h    = null;    // Height
	var $f    = 'jpeg';  // Format
	var $q    = 75;      // Quality
	var $sx   = null;    // Source crop top-left X position
	var $sy   = null;    // Source crop top-left Y position
	var $sw   = null;    // Source crop Width
	var $sh   = null;    // Source crop Height
	var $zc   = null;    // Zoom Crop
	var $bc   = null;    // Border Color
	var $bg   = null;    // BackGround color
	var $bgt  = null;    // BackGround Transparent
	var $fltr = array(); // FiLTeRs
	var $file = null;    // render-to FILEname
	var $goto = null;    // GO TO url after processing
	var $err  = null;    // default ERRor image filename
	var $xto  = null;    // extract eXif Thumbnail Only
	var $ra   = null;    // Rotate by Angle
	var $ar   = null;    // Auto Rotate
	var $aoe  = null;    // Allow Output Enlargement
	var $far  = null;    // Fixed Aspect Ratio
	var $iar  = null;    // Ignore Aspect Ratio
	var $maxb = null;    // Maximum Bytes
	var $down = null;    // DOWNload thumbnail

	var $phpThumbDebug = null;
	// END PARAMETERS


	// public:
	// START CONFIGURATION OPTIONS (for object mode only)
	// See phpThumb.config.php for descriptions of what each of these settings do

	// * Directory Configuration
	var $config_cache_directory              = null;
	var $config_cache_disable_warning        = true;
	var $config_cache_source_enabled         = false;
	var $config_cache_source_directory       = null;
	var $config_temp_directory               = null;
	var $config_document_root                = null;

	// * Default output configuration:
	var $config_output_format                = 'jpeg';
	var $config_output_maxwidth              = 0;
	var $config_output_maxheight             = 0;
	var $config_output_interlace             = true;

	// * Error message configuration
	var $config_error_image_width            = 400;
	var $config_error_image_height           = 100;
	var $config_error_message_image_default  = '';
	var $config_error_bgcolor                = 'CCCCFF';
	var $config_error_textcolor              = 'FF0000';
	var $config_error_fontsize               = 1;
	var $config_error_die_on_error           = true;
	var $config_error_silent_die_on_error    = false;
	var $config_error_die_on_source_failure  = true;

	// * Anti-Hotlink Configuration:
	var $config_nohotlink_enabled            = true;
	var $config_nohotlink_valid_domains      = array();
	var $config_nohotlink_erase_image        = true;
	var $config_nohotlink_text_message       = 'Off-server thumbnailing is not allowed';
	// * Off-server Linking Configuration:
	var $config_nooffsitelink_enabled        = false;
	var $config_nooffsitelink_valid_domains  = array();
	var $config_nooffsitelink_require_refer  = false;
	var $config_nooffsitelink_erase_image    = true;
	var $config_nooffsitelink_text_message   = 'Off-server linking is not allowed';

	// * Border & Background default colors
	var $config_border_hexcolor              = '000000';
	var $config_background_hexcolor          = 'FFFFFF';

	// * TrueType Fonts
	var $config_ttf_directory                = '.';

	var $config_max_source_pixels            = 0;
	var $config_use_exif_thumbnail_for_speed = true;
	var $config_output_allow_enlarging       = false;

	var $config_imagemagick_path             = null;

	var $config_cache_maxage                 = null;
	var $config_cache_maxsize                = null;
	var $config_cache_maxfiles               = null;

	var $config_disable_debug                = false;
	// END CONFIGURATION OPTIONS


	// public: error messages (read-only)
	var $debugmessages = array();
	var $fatalerror    = null;


	// private: (should not be modified directly)
	var $thumbnailQuality = 75;
	var $thumbnailFormat  = null;

	var $sourceFilename = null;
	var $rawImageData   = null;

	var $gdimg_output     = null;
	var $gdimg_source     = null;

	var $getimagesizeinfo = null;

	var $source_width  = null;
	var $source_height = null;

	var $thumbnailCropX = null;
	var $thumbnailCropY = null;
	var $thumbnailCropW = null;
	var $thumbnailCropH = null;

	var $exif_thumbnail_width  = null;
	var $exif_thumbnail_height = null;
	var $exif_thumbnail_type   = null;
	var $exif_thumbnail_data   = null;

	var $thumbnail_width        = null;
	var $thumbnail_height       = null;
	var $thumbnail_image_width  = null;
	var $thumbnail_image_height = null;

	var $cache_filename         = null;

	var $is_alpha = false;

	var $iswindows = null;
	var $osslash   = null;

	var $phpthumb_version = '1.5.2-200504161013';

	//////////////////////////////////////////////////////////////////////

	// public: constructor
	function phpThumb() {
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
			$this->iswindows = true;
			$this->osslash   = '\\';
		} else {
			$this->iswindows = false;
			$this->osslash   = '/';
		}
		if (!empty($_SERVER['DOCUMENT_ROOT'])) {
			$this->config_document_root = $_SERVER['DOCUMENT_ROOT'];
		}
	}

	// public:
	function setSourceFilename($sourceFilename) {
		$this->rawImageData   = null;
		$this->sourceFilename = $sourceFilename;
		return true;
	}

	// public:
	function setSourceData($rawImageData, $sourceFilename='') {
		$this->sourceFilename = null;
		$this->rawImageData   = $rawImageData;
		if ($this->config_cache_source_enabled) {
			$sourceFilename = ($sourceFilename ? $sourceFilename : md5($rawImageData));
			if (!is_dir($this->config_cache_source_directory)) {
				$this->ErrorImage('$this->config_cache_source_directory ('.$this->config_cache_source_directory.') is not a directory');
			} elseif (!is_writable($this->config_cache_source_directory)) {
				$this->ErrorImage('$this->config_cache_source_directory ('.$this->config_cache_source_directory.') is not writable');
			}
			$this->DebugMessage('setSourceData() attempting to save source image to "'.$this->config_cache_source_directory.'/'.urlencode($sourceFilename).'"', __FILE__, __LINE__);
			if ($fp = @fopen($this->config_cache_source_directory.'/'.urlencode($sourceFilename), 'wb')) {
				fwrite($fp, $rawImageData);
				fclose($fp);
			} else {
				$this->ErrorImage('setSourceData() failed to write to source cache ('.$this->config_cache_source_directory.'/'.urlencode($sourceFilename).')');
			}
		}
		return true;
	}

	// public:
	function setSourceImageResource($gdimg) {
		$this->gdimg_source = $gdimg;
		return true;
	}



	// public:
	function GenerateThumbnail() {

		if (empty($this->thumbnailFormat)) {
			$this->setOutputFormat();
		}
		$this->ResolveSource();
		$this->SetCacheFilename();
		$this->ExtractEXIFgetImageSize();
		if (!$this->SourceImageToGD()) {
			return false;
		}
		$this->Rotate();
		$this->CreateGDoutput();

		// copy/resize image to appropriate dimensions (either nearest-neighbor or resample, depending on GD version)
		phpthumb_functions::ImageResizeFunction(
			$this->gdimg_output,
			$this->gdimg_source,
			round(($this->thumbnail_width  - $this->thumbnail_image_width)  / 2),
			round(($this->thumbnail_height - $this->thumbnail_image_height) / 2),
			$this->thumbnailCropX,
			$this->thumbnailCropY,
			$this->thumbnail_image_width,
			$this->thumbnail_image_height,
			$this->thumbnailCropW,
			$this->thumbnailCropH
		);


		$this->AntiOffsiteLinking();
		$this->ApplyFilters();
		$this->AlphaChannelFlatten();
		$this->MaxFileSize();

		return true;
	}


	// public:
	function RenderToFile($filename) {
		if (!is_resource($this->gdimg_output)) {
			$this->DebugMessage('RenderToFile('.$filename.') failed because !is_resource($this->gdimg_output)', __FILE__, __LINE__);
			return false;
		}
		// render thumbnail to this file only, do not cache, do not output to browser
		$ImageOutFunction = 'image'.$this->thumbnailFormat;
		//$renderfilename = $this->ResolveFilenameToAbsolute(dirname($filename)).'/'.basename($filename);
		$renderfilename = $filename;
		if (($filename{0} != '/') && ($filename{0} != '\\') && ($filename{1} != ':')) {
			$renderfilename = $this->ResolveFilenameToAbsolute($filename);
		}
		$this->DebugMessage('RenderToFile('.$filename.') attempting to render to file "'.$renderfilename.'"', __FILE__, __LINE__);
		ob_start();
		switch ($this->thumbnailFormat) {
			case 'jpeg':
				$ImageOutFunction($this->gdimg_output, $renderfilename, $this->thumbnailQuality);
				break;

			case 'png':
			case 'gif':
				$ImageOutFunction($this->gdimg_output, $renderfilename);
				break;
		}
		$errormessage = strip_tags(ob_get_contents());
		ob_end_clean();
		if ($errormessage) {
			$this->DebugMessage('RenderToFile ['.$ImageOutFunction.'('.$renderfilename.')] failed with message "'.$errormessage.'"', __FILE__, __LINE__);
			return false;
		}
		return true;
	}


	// public:
	function OutputThumbnail() {
		if (!is_resource($this->gdimg_output)) {
			$this->DebugMessage('OutputThumbnail() failed because !is_resource($this->gdimg_output)', __FILE__, __LINE__);
			return false;
		}
		if (headers_sent()) {
			return $this->ErrorImage('OutputThumbnail() failed - headers already sent');
			exit;
		}

		if (!empty($this->down)) {
			$downloadfilename = ereg_replace('[/\\:\*\?"<>|]', '_', $this->down);
			if (phpthumb_functions::version_compare_replacement(phpversion(), '4.1.0', '>=')) {
				$downloadfilename = trim($downloadfilename, '.');
			}
			if ($downloadfilename != $this->down) {
				$this->DebugMessage('renaming output file for "down" from "'.$this->down.'" to "'.$downloadfilename.'"', __FILE__, __LINE__);
			}
			if ($downloadfilename) {
				header('Content-Disposition: attachment; filename="'.$downloadfilename.'"');
			} else {
				$this->DebugMessage('failed to send Content-Disposition header because $downloadfilename is empty', __FILE__, __LINE__);
			}
		}

		ImageInterlace($this->gdimg_output, intval($this->config_output_interlace));
		$ImageOutFunction = 'image'.$this->thumbnailFormat;
		switch ($this->thumbnailFormat) {
			case 'jpeg':
				header('Content-type: image/'.$this->thumbnailFormat);
				@$ImageOutFunction($this->gdimg_output, '', $this->thumbnailQuality);
				break;

			case 'png':
			case 'gif':
				header('Content-type: image/'.$this->thumbnailFormat);
				@$ImageOutFunction($this->gdimg_output);
				break;
		}
		ImageDestroy($this->gdimg_output);
		return true;
	}


	// public:
	function CleanUpCacheDirectory() {
		if (($this->config_cache_maxage > 0) || ($this->config_cache_maxsize > 0) || ($this->config_cache_maxfiles > 0)) {
			$CacheDirOldFilesAge  = array();
			$CacheDirOldFilesSize = array();
			if ($dirhandle = opendir($this->config_cache_directory)) {
				while ($oldcachefile = readdir($dirhandle)) {
					if (eregi('^phpThumb_cache_', $oldcachefile)) {
						$CacheDirOldFilesAge[$oldcachefile] = fileatime($this->config_cache_directory.'/'.$oldcachefile);
						if ($CacheDirOldFilesAge[$oldcachefile] == 0) {
							$CacheDirOldFilesAge[$oldcachefile] = filemtime($this->config_cache_directory.'/'.$oldcachefile);
						}

						$CacheDirOldFilesSize[$oldcachefile] = filesize($this->config_cache_directory.'/'.$oldcachefile);
					}
				}
			}
			asort($CacheDirOldFilesAge);

			if ($this->config_cache_maxfiles > 0) {
				$TotalCachedFiles = count($CacheDirOldFilesAge);
				$DeletedKeys = array();
				foreach ($CacheDirOldFilesAge as $oldcachefile => $filedate) {
					if ($TotalCachedFiles > $this->config_cache_maxfiles) {
						$TotalCachedFiles--;
						if (@unlink($this->config_cache_directory.'/'.$oldcachefile)) {
							$DeletedKeys[] = $oldcachefile;
						}
					} else {
						// there are few enough files to keep the rest
						break;
					}
				}
				foreach ($DeletedKeys as $oldcachefile) {
					unset($CacheDirOldFilesAge[$oldcachefile]);
					unset($CacheDirOldFilesSize[$oldcachefile]);
				}
			}

			if ($this->config_cache_maxage > 0) {
				$mindate = time() - $this->config_cache_maxage;
				$DeletedKeys = array();
				foreach ($CacheDirOldFilesAge as $oldcachefile => $filedate) {
					if ($filedate > 0) {
						if ($filedate < $mindate) {
							if (@unlink($this->config_cache_directory.'/'.$oldcachefile)) {
								$DeletedKeys[] = $oldcachefile;
							}
						} else {
							// the rest of the files are new enough to keep
							break;
						}
					}
				}
				foreach ($DeletedKeys as $oldcachefile) {
					unset($CacheDirOldFilesAge[$oldcachefile]);
					unset($CacheDirOldFilesSize[$oldcachefile]);
				}
			}

			if ($this->config_cache_maxsize > 0) {
				$TotalCachedFileSize = array_sum($CacheDirOldFilesSize);
				$DeletedKeys = array();
				foreach ($CacheDirOldFilesAge as $oldcachefile => $filedate) {
					if ($TotalCachedFileSize > $this->config_cache_maxsize) {
						$TotalCachedFileSize -= $CacheDirOldFilesSize[$oldcachefile];
						if (@unlink($this->config_cache_directory.'/'.$oldcachefile)) {
							$DeletedKeys[] = $oldcachefile;
						}
					} else {
						// the total filesizes are small enough to keep the rest of the files
						break;
					}
				}
				foreach ($DeletedKeys as $oldcachefile) {
					unset($CacheDirOldFilesAge[$oldcachefile]);
					unset($CacheDirOldFilesSize[$oldcachefile]);
				}
			}

		}
		return true;
	}

	//////////////////////////////////////////////////////////////////////

	function ResolveSource() {
		if (is_resource($this->gdimg_source)) {
			return true;
		}
		if (empty($this->sourceFilename) && empty($this->rawImageData)) {
			$this->sourceFilename = $this->ResolveFilenameToAbsolute($this->src);
		}
		return true;
	}

	function setOutputFormat() {
		$AvailableImageOutputFormats = array();
		$AvailableImageOutputFormats[] = 'text';
		$this->thumbnailFormat         = 'text';

		// Set default output format based on what image types are available
		if (!function_exists('ImageTypes')) {
			return $this->ErrorImage('ImageTypes() does not exist - GD support might not be enabled?');
		}
		$imagetypes = ImageTypes();
		if ($imagetypes & IMG_WBMP) {
			$this->thumbnailFormat         = 'wbmp';
			$AvailableImageOutputFormats[] = 'wbmp';
		}
		if ($imagetypes & IMG_GIF) {
			$this->thumbnailFormat         = 'gif';
			$AvailableImageOutputFormats[] = 'gif';
		}
		if ($imagetypes & IMG_PNG) {
			$this->thumbnailFormat         = 'png';
			$AvailableImageOutputFormats[] = 'png';
		}
		if ($imagetypes & IMG_JPG) {
			$this->thumbnailFormat         = 'jpeg';
			$AvailableImageOutputFormats[] = 'jpeg';
		}
		if (in_array($this->config_output_format, $AvailableImageOutputFormats)) {
			// set output format to config default if that format is available
			$this->thumbnailFormat = $this->config_output_format;
		}
		if ($this->f == 'jpg') {
			$this->f == 'jpeg';
		}
		if (!empty($this->f) && (in_array($this->f, $AvailableImageOutputFormats))) {
			// override output format if $this->f is set and that format is available
			$this->thumbnailFormat = $this->f;
		}

		// for JPEG images, quality 0 (worst) to 100 (best)
		// quality < 25 is nasty, with not much size savings - not recommended
		// problems with 100 - invalid JPEG?
		$this->thumbnailQuality = max(1, min(95, (!empty($this->q) ? $this->q : 75)));

		return true;
	}

	function setCacheDirectory() {
		// resolve cache directory to absolute pathname
		if (substr($this->config_cache_directory, 0, 1) == '.') {
			if (eregi('^(f|ht)tp[s]?://', $this->src)) {
				if (!$this->config_cache_disable_warning && !$this->phpThumbDebug) {
					$this->ErrorImage('$this->config_cache_directory ('.$this->config_cache_directory.') cannot be used for remote images. Adjust "cache_directory" or "cache_disable_warning" in phpThumb.config.php');
				}
			} elseif ($this->src) {
				// resolve relative cache directory to source image
				$this->config_cache_directory = dirname($this->ResolveFilenameToAbsolute($this->src)).'/'.$this->config_cache_directory;
			} else {
				// $this->new is probably set
			}
		}
		if (substr($this->config_cache_directory, -1) == '/') {
			$this->config_cache_directory = substr($this->config_cache_directory, 0, -1);
		}
		if ($this->iswindows) {
			$this->config_cache_directory = str_replace('/', $this->osslash, $this->config_cache_directory);
		}
		if (!empty($this->config_cache_directory)) {
			$real_cache_path = realpath($this->config_cache_directory);
			if (!$real_cache_path) {
				$this->DebugMessage('realpath($this->config_cache_directory) failed for "'.$this->config_cache_directory.'"', __FILE__, __LINE__);
				if (!is_dir($this->config_cache_directory)) {
					$this->DebugMessage('!is_dir('.$this->config_cache_directory.')', __FILE__, __LINE__);
				}
			}
			$this->config_cache_directory = $real_cache_path;
		}
		if (!is_dir($this->config_cache_directory)) {
			if (!$this->config_cache_disable_warning && !$this->phpThumbDebug) {
				$this->ErrorImage('$this->config_cache_directory ('.$this->config_cache_directory.') does not exist. Adjust "cache_directory" or "cache_disable_warning" in phpThumb.config.php');
			}
			$this->DebugMessage('$this->config_cache_directory ('.$this->config_cache_directory.') is not a directory', __FILE__, __LINE__);
			$this->config_cache_directory = null;
		} elseif (!is_writable($this->config_cache_directory)) {
			$this->DebugMessage('$this->config_cache_directory is not writable ('.$this->config_cache_directory.')', __FILE__, __LINE__);
		}
		return true;
	}


	function ResolveFilenameToAbsolute($filename) {
		if (eregi('^(f|ht)tp[s]?://', $filename)) {

			// URL
			$AbsoluteFilename = $filename;

		} elseif ($this->iswindows && ($filename{1} == ':')) {

			// absolute pathname (Windows)
			$AbsoluteFilename = $filename;

		} elseif ($this->iswindows && ((substr($filename, 0, 2) == '//') || (substr($filename, 0, 2) == '\\\\'))) {

			// absolute pathname (Windows)
			$AbsoluteFilename = $filename;

		} elseif ($filename{0} == '/') {

			if (@is_readable($filename) && !@is_readable($this->config_document_root.$filename)) {
				// absolute filename (*nix)
				$AbsoluteFilename = $filename;
			} elseif ($filename{1} == '~') {
				// /~user/path
				if ($ApacheLookupURIarray = phpthumb_functions::ApacheLookupURIarray($filename)) {
					$AbsoluteFilename = $ApacheLookupURIarray['filename'];
				} else {
					$AbsoluteFilename = realpath($filename);
					if (@is_readable($AbsoluteFilename)) {
						$this->DebugMessage('phpthumb_functions::ApacheLookupURIarray() failed for "'.$filename.'", but the correct filename ('.$AbsoluteFilename.') seems to have been resolved with realpath($filename)', __FILE__, __LINE__);
					} else {
						return $this->ErrorImage('phpthumb_functions::ApacheLookupURIarray() failed for "'.$filename.'". This has been known to fail on Apache2 - try using the absolute filename for the source image');
					}
				}
			} else {
				// relative filename (any OS)
				$AbsoluteFilename = $this->config_document_root.$filename;
			}

		} else {

			// relative to current directory (any OS)
			$AbsoluteFilename = $this->config_document_root.dirname(@$_SERVER['PHP_SELF']).'/'.$filename;
			//if (!file_exists($AbsoluteFilename) && file_exists(realpath($this->DotPadRelativeDirectoryPath($filename)))) {
			//	$AbsoluteFilename = realpath($this->DotPadRelativeDirectoryPath($filename));
			//}

			if (substr(dirname(@$_SERVER['PHP_SELF']), 0, 2) == '/~') {
				if ($ApacheLookupURIarray = phpthumb_functions::ApacheLookupURIarray(dirname(@$_SERVER['PHP_SELF']))) {
					$AbsoluteFilename = $ApacheLookupURIarray['filename'].'/'.$filename;
				} else {
					$AbsoluteFilename = realpath('.').'/'.$filename;
					if (@is_readable($AbsoluteFilename)) {
						$this->DebugMessage('phpthumb_functions::ApacheLookupURIarray() failed for "'.dirname(@$_SERVER['PHP_SELF']).'", but the correct filename ('.$AbsoluteFilename.') seems to have been resolved with realpath(.)/$filename', __FILE__, __LINE__);
					} else {
						return $this->ErrorImage('phpthumb_functions::ApacheLookupURIarray() failed for "'.dirname(@$_SERVER['PHP_SELF']).'". This has been known to fail on Apache2 - try using the absolute filename for the source image');
					}
				}
			}

		}
		return $AbsoluteFilename;
	}

	function ImageMagickCommandlineBase() {
		static $commandline = null;
		if (is_null($commandline)) {
			$commandline = '';

			$which_convert = trim(phpthumb_functions::SafeBackTick('which convert'));

			if ($this->config_imagemagick_path && ($this->config_imagemagick_path != realpath($this->config_imagemagick_path))) {
				$this->DebugMessage('Changing $this->config_imagemagick_path ('.$this->config_imagemagick_path.') to realpath($this->config_imagemagick_path) ('.realpath($this->config_imagemagick_path).')', __FILE__, __LINE__);
				$this->config_imagemagick_path = realpath($this->config_imagemagick_path);
			}
			if (file_exists($this->config_imagemagick_path)) {

				$this->DebugMessage('using ImageMagick path from $this->config_imagemagick_path ('.$this->config_imagemagick_path.')', __FILE__, __LINE__);
				if ($this->iswindows) {
					$commandline = substr($this->config_imagemagick_path, 0, 2).' && cd "'.substr(dirname($this->config_imagemagick_path), 2).'" && '.basename($this->config_imagemagick_path);
				} else {
					$commandline = '"'.$this->config_imagemagick_path.'"';
				}

			} elseif ($which_convert && ($which_convert{0} == '/') && @file_exists($which_convert)) {

				// `which convert` *should* return the path if "convert" exist, or nothing if it doesn't
				// other things *may* get returned, like "sh: convert: not found" or "no convert in /usr/local/bin /usr/sbin /usr/bin /usr/ccs/bin"
				// so only do this if the value returned exists as a file
				$this->DebugMessage('using ImageMagick path from `which convert` ('.$which_convert.')', __FILE__, __LINE__);
				$commandline = 'convert';

			} else {

				$this->DebugMessage('ImageMagickThumbnailToGD() aborting because cannot find convert in $this->config_imagemagick_path ('.$this->config_imagemagick_path.'), and `which convert` returned ('.$which_convert.')', __FILE__, __LINE__);

			}
		}
		return $commandline;
	}

	function ImageMagickVersion() {
		$commandline = $this->ImageMagickCommandlineBase();
		if (!empty($commandline)) {
			$commandline .= ' -version';
			$versionstring = phpthumb_functions::SafeBackTick($commandline);
			if (eregi('^Version: (.*) http', $versionstring, $matches)) {
				return $matches[1];
			}
			$this->DebugMessage('ImageMagick did not return recognized version string ('.$versionstring.')', __FILE__, __LINE__);
			return $versionstring;
		}
		return false;
	}

	function ImageMagickThumbnailToGD() {
		// http://freealter.org/doc_distrib/ImageMagick-5.1.1/www/convert.html
		if (ini_get('safe_mode')) {
			$this->DebugMessage('ImageMagickThumbnailToGD() aborting because safe_mode is enabled', __FILE__, __LINE__);
			return false;
		}
		if (!function_exists('ImageCreateFromPNG')) {
			// ImageMagickThumbnailToGD() depends on ImageCreateFromPNG()
			$this->DebugMessage('ImageMagickThumbnailToGD() aborting because ImageCreateFromPNG() is not available', __FILE__, __LINE__);
			return false;
		}

		$commandline = $this->ImageMagickCommandlineBase();
		if (!empty($commandline)) {
			if ($IMtempfilename = $this->phpThumb_tempnam()) {

				$IMtempfilename = realpath($IMtempfilename);
				$IMwidth  = ((intval($this->w) > 0) ? intval($this->w) : 640);
				$IMheight = ((intval($this->h) > 0) ? intval($this->h) : 480);
				if (!$this->aoe && !$this->iar && ($getimagesize = @GetImageSize($this->sourceFilename))) {
					// limit output size to input size unless AllowOutputEnlargement is enabled
					$IMwidth  = min($IMwidth,  $getimagesize[0]);
					$IMheight = min($IMheight, $getimagesize[1]);
				}
				//$commandline .= ' -resize '.$IMwidth.'x'.$IMheight; // behaves badly with IM v5.3.x
				$commandline .= ' -geometry '.$IMwidth.'x'.$IMheight;
				if (!empty($this->iar) && (intval($this->w) > 0) && (intval($this->h) > 0)) {
					$commandline .= '!';
				}
				$commandline .= ' "'.str_replace('/', $this->osslash, $this->sourceFilename).'"';
				$commandline .= ' png:'.$IMtempfilename;
				$commandline .= ' 2>&1';

				$IMresult = phpthumb_functions::SafeBackTick($commandline);
				if (!empty($IMresult)) {

					//return $this->ErrorImage('ImageMagick was called as:'."\n".$commandline."\n\n".'but failed with message:'."\n".$IMresult);
					$this->DebugMessage('ImageMagick was called as ('.$commandline.') but failed with message ('.$IMresult.')', __FILE__, __LINE__);

				} elseif ($this->gdimg_source = @ImageCreateFromPNG($IMtempfilename)) {

					unlink($IMtempfilename);
					$this->source_width  = ImageSX($this->gdimg_source);
					$this->source_height = ImageSY($this->gdimg_source);
					$this->DebugMessage('ImageMagickThumbnailToGD() succeeded, $this->gdimg_source is now ('.$this->source_width.'x'.$this->source_height.')', __FILE__, __LINE__);
					return true;

				}
				unlink($IMtempfilename);

			} else {
				$this->DebugMessage('ImageMagickThumbnailToGD() aborting, phpThumb_tempnam() failed', __FILE__, __LINE__);
			}
		}
		$this->DebugMessage('ImageMagickThumbnailToGD() aborting because ImageMagickCommandlineBase() failed', __FILE__, __LINE__);
		return false;
	}


	function Rotate() {
		if (!empty($this->ra) || !empty($this->ar)) {
			if (!function_exists('ImageRotate')) {
				$this->DebugMessage('!function_exists(ImageRotate)', __FILE__, __LINE__);
				return false;
			}
			if (!include_once('phpthumb.filters.php')) {
				$this->DebugMessage('Error including "phpthumb.filters.php" which is required for applying filters ('.implode(';', $this->fltr).')', __FILE__, __LINE__);
				return false;
			}

			$this->config_background_hexcolor = (!empty($this->bg) ? $this->bg : $this->config_background_hexcolor);
			if (!phpthumb_functions::IsHexColor($this->config_background_hexcolor)) {
				return $this->ErrorImage('Invalid hex color string "'.$this->config_background_hexcolor.'" for parameter "bg"');
			}

			$rotate_angle = 0;
			if (!empty($this->ra)) {

				$rotate_angle = floatval($this->ra);

			} else {

				if ($this->ar == 'x') {
					if (phpthumb_functions::version_compare_replacement(phpversion(), '4.2.0', '>=')) {
						if ($this->sourceFilename) {
							if (function_exists('exif_read_data')) {
								if ($exif_data = @exif_read_data($this->sourceFilename, 'IFD0')) {
									// http://sylvana.net/jpegcrop/exif_orientation.html
									switch (@$exif_data['Orientation']) {
										case 1:
											$rotate_angle = 0;
											break;
										case 3:
											$rotate_angle = 180;
											break;
										case 6:
											$rotate_angle = 270;
											break;
										case 8:
											$rotate_angle = 90;
											break;

										default:
											$this->DebugMessage('EXIF auto-rotate failed because unknown $exif_data[Orientation] "'.@$exif_data['Orientation'].'"', __FILE__, __LINE__);
											return false;
											break;
									}
									$this->DebugMessage('EXIF auto-rotate set to '.$rotate_angle.' degrees ($exif_data[Orientation] = "'.@$exif_data['Orientation'].'")', __FILE__, __LINE__);
								} else {
									$this->DebugMessage('failed: exif_read_data('.$this->sourceFilename.')', __FILE__, __LINE__);
									return false;
								}
							} else {
								$this->DebugMessage('!function_exists(exif_read_data)', __FILE__, __LINE__);
								return false;
							}
						} else {
							$this->DebugMessage('Cannot auto-rotate from EXIF data because $this->sourceFilename is empty', __FILE__, __LINE__);
							return false;
						}
					} else {
						$this->DebugMessage('Cannot auto-rotate from EXIF data because PHP is less than v4.2.0 ('.phpversion().')', __FILE__, __LINE__);
						return false;
					}
				} elseif (($this->ar == 'l') && ($this->source_height > $this->source_width)) {
					$rotate_angle = 270;
				} elseif (($this->ar == 'L') && ($this->source_height > $this->source_width)) {
					$rotate_angle = 90;
				} elseif (($this->ar == 'p') && ($this->source_width > $this->source_height)) {
					$rotate_angle = 90;
				} elseif (($this->ar == 'P') && ($this->source_width > $this->source_height)) {
					$rotate_angle = 270;
				}

			}
			while ($rotate_angle < 0) {
				$rotate_angle += 360;
			}
			$rotate_angle = $rotate_angle % 360;
			if ($rotate_angle != 0) {

				$background_color = phpthumb_functions::ImageHexColorAllocate($this->gdimg_source, $this->config_background_hexcolor);

				if ((phpthumb_functions::gd_version() >= 2) && ($this->thumbnailFormat == 'png') && !$this->bg && ($rotate_angle % 90)) {

					if ($gdimg_rotate_mask = phpthumb_functions::ImageCreateFunction(ImageSX($this->gdimg_source), ImageSY($this->gdimg_source))) {

						$this->gdimg_source = ImageRotate($this->gdimg_source, $rotate_angle, $background_color);
						$color_mask_opaque      = ImageColorAllocate($gdimg_rotate_mask, 0xFF, 0xFF, 0xFF);
						$color_mask_transparent = ImageColorAllocate($gdimg_rotate_mask, 0x00, 0x00, 0x00);
						ImageFilledRectangle($gdimg_rotate_mask, 0, 0, ImageSX($gdimg_rotate_mask), ImageSY($gdimg_rotate_mask), $color_mask_opaque);
						$gdimg_rotate_mask = ImageRotate($gdimg_rotate_mask, $rotate_angle, $color_mask_transparent);

						ImageAlphaBlending($this->gdimg_source, false);
						if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.2', '>=')) {
							ImageSaveAlpha($this->gdimg_source, true);
						}
						$this->is_alpha = true;
						phpthumb_filters::ApplyMask($gdimg_rotate_mask, $this->gdimg_source);

						ImageDestroy($gdimg_rotate_mask);
						$this->source_width  = ImageSX($this->gdimg_source);
						$this->source_height = ImageSY($this->gdimg_source);

					} else {
						$this->DebugMessage('ImageCreateFromStringReplacement() failed for "'.$MaskFilename.'"', __FILE__, __LINE__);
					}

				} else {

					if (phpthumb_functions::gd_version() >= 2) {
						$this->DebugMessage('Using non-alpha rotate because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
					} else {
						$this->DebugMessage('Using non-alpha rotate because $this->thumbnailFormat is "'.$this->thumbnailFormat.'"', __FILE__, __LINE__);
					}

					if (ImageColorTransparent($this->gdimg_source) >= 0) {
						// ImageRotate() forgets all about an image's transparency and sets the transparent color to black
						// To compensate, flood-fill the transparent color of the source image with the specified background color first
						// then rotate and the colors should match

						if (!function_exists('ImageIsTrueColor') || !ImageIsTrueColor($this->gdimg_source)) {
							// convert paletted image to true-color before rotating to prevent nasty aliasing artifacts

							$this->source_width  = ImageSX($this->gdimg_source);
							$this->source_height = ImageSY($this->gdimg_source);
							$gdimg_newsrc = phpthumb_functions::ImageCreateFunction($this->source_width, $this->source_height);
							$background_color = phpthumb_functions::ImageHexColorAllocate($gdimg_newsrc, $this->config_background_hexcolor);
							ImageFilledRectangle($gdimg_newsrc, 0, 0, $this->source_width, $this->source_height, phpthumb_functions::ImageHexColorAllocate($gdimg_newsrc, $this->config_background_hexcolor));
							ImageCopy($gdimg_newsrc, $this->gdimg_source, 0, 0, 0, 0, $this->source_width, $this->source_height);
							ImageDestroy($this->gdimg_source);
							unset($this->gdimg_source);
							$this->gdimg_source = $gdimg_newsrc;
							unset($gdimg_newsrc);

						} else {

							ImageColorSet(
								$this->gdimg_source,
								ImageColorTransparent($this->gdimg_source),
								hexdec(substr($this->config_background_hexcolor, 0, 2)),
								hexdec(substr($this->config_background_hexcolor, 2, 2)),
								hexdec(substr($this->config_background_hexcolor, 4, 2)));

							ImageColorTransparent($this->gdimg_source, -1);

						}
					}

					$this->gdimg_source = ImageRotate($this->gdimg_source, $rotate_angle, $background_color);
					$this->source_width  = ImageSX($this->gdimg_source);
					$this->source_height = ImageSY($this->gdimg_source);

				}
			}
		}
		return true;
	}


	function FixedAspectRatio() {
		// optional fixed-dimension images (regardless of aspect ratio)
		if (isset($this->far)) {
			$this->is_alpha;
			if ($this->thumbnail_image_width >= $this->thumbnail_width) {
				if (isset($this->w)) {
					$aspectratio = $this->thumbnail_image_height / $this->thumbnail_image_width;
					$this->thumbnail_image_height = round($this->thumbnail_image_width * $aspectratio);
					if (!isset($this->h)) {
						$this->thumbnail_height = $this->thumbnail_image_height;
					}
				} elseif ($this->thumbnail_image_height < $this->thumbnail_height) {
					$this->thumbnail_image_height = $this->thumbnail_height;
					$this->thumbnail_image_width  = round($this->thumbnail_image_height / $aspectratio);
				}
			} else {
				if (isset($this->h)) {
					$aspectratio = $this->thumbnail_image_width / $this->thumbnail_image_height;
					$this->thumbnail_image_width = round($this->thumbnail_image_height * $aspectratio);
				} elseif ($this->thumbnail_image_width < $this->thumbnail_width) {
					$this->thumbnail_image_width = $this->thumbnail_width;
					$this->thumbnail_image_height  = round($this->thumbnail_image_width / $aspectratio);
				}
			}
		}
		return true;
	}


	function AntiOffsiteLinking() {
		// Optional anti-offsite hijacking of the thumbnail script
		$allow = true;
		if ($allow && $this->config_nooffsitelink_enabled && $this->config_nooffsitelink_require_refer) {
			$this->DebugMessage('AntiOffsiteLinking() checking $_SERVER[HTTP_REFERER] "'.@$_SERVER['HTTP_REFERER'].'"', __FILE__, __LINE__);
			$parsed_url = parse_url(@$_SERVER['HTTP_REFERER']);
			if (!in_array(@$parsed_url['host'], $this->config_nooffsitelink_valid_domains)) {
				$allow = false;
				$erase   = $this->config_nooffsitelink_erase_image;
				$message = $this->config_nooffsitelink_text_message;
				$this->DebugMessage('AntiOffsiteLinking() - "'.@$parsed_url['host'].'" is NOT in $this->config_nooffsitelink_valid_domains ('.implode(';', $this->config_nooffsitelink_valid_domains).')', __FILE__, __LINE__);
			} else {
				$this->DebugMessage('AntiOffsiteLinking() - "'.@$parsed_url['host'].'" is in $this->config_nooffsitelink_valid_domains ('.implode(';', $this->config_nooffsitelink_valid_domains).')', __FILE__, __LINE__);
			}
		}

		if ($allow && $this->config_nohotlink_enabled && eregi('^(f|ht)tp[s]?://', $this->src)) {
			$parsed_url = parse_url($this->src);
			if (!in_array(@$parsed_url['host'], $this->config_nohotlink_valid_domains)) {
				// This domain is not allowed
				$allow = false;
				$erase   = $this->config_nohotlink_erase_image;
				$message = $this->config_nohotlink_text_message;
				$this->DebugMessage('AntiOffsiteLinking() - "'.$parsed_url['host'].'" is NOT in $this->config_nohotlink_valid_domains ('.implode(';', $this->config_nohotlink_valid_domains).')', __FILE__, __LINE__);
			} else {
				$this->DebugMessage('AntiOffsiteLinking() - "'.$parsed_url['host'].'" is in $this->config_nohotlink_valid_domains ('.implode(';', $this->config_nohotlink_valid_domains).')', __FILE__, __LINE__);
			}
		}

		if ($allow) {
			$this->DebugMessage('AntiOffsiteLinking() says this is allowed', __FILE__, __LINE__);
			return true;
		}

		if (!phpthumb_functions::IsHexColor($this->config_error_bgcolor)) {
			return $this->ErrorImage('Invalid hex color string "'.$this->config_error_bgcolor.'" for $this->config_error_bgcolor');
		}
		if (!phpthumb_functions::IsHexColor($this->config_error_textcolor)) {
			return $this->ErrorImage('Invalid hex color string "'.$this->config_error_textcolor.'" for $this->config_error_textcolor');
		}
		if ($erase) {

			return $this->ErrorImage($message, $this->thumbnail_width, $this->thumbnail_height, $this->config_error_bgcolor, $this->config_error_textcolor, $this->config_error_fontsize);

		} else {

			$nohotlink_text_array = explode("\n", wordwrap($message, floor($this->thumbnail_width / ImageFontWidth($this->config_error_fontsize)), "\n"));
			$nohotlink_text_color = phpthumb_functions::ImageHexColorAllocate($this->gdimg_output, $this->config_error_textcolor);

			$topoffset = round(($this->thumbnail_height - (count($nohotlink_text_array) * ImageFontHeight($this->config_error_fontsize))) / 2);

			$rowcounter = 0;
			$this->DebugMessage('AntiOffsiteLinking() writing '.count($nohotlink_text_array).' lines of text "'.$message.'" (in #'.$this->config_error_textcolor.') on top of image', __FILE__, __LINE__);
			foreach ($nohotlink_text_array as $textline) {
				$leftoffset = max(0, round(($this->thumbnail_width - (strlen($textline) * ImageFontWidth($this->config_error_fontsize))) / 2));
				ImageString($this->gdimg_output, $this->config_error_fontsize, $leftoffset, $topoffset + ($rowcounter++ * ImageFontHeight($this->config_error_fontsize)), $textline, $nohotlink_text_color);
			}

		}
		return true;
	}


	function AlphaChannelFlatten() {
		if (!$this->is_alpha) {
			// image doesn't have alpha transparency, no need to flatten
			$this->DebugMessage('skipping AlphaChannelFlatten() because !$this->is_alpha', __FILE__, __LINE__);
			return false;
		}
		if ($this->thumbnailFormat == 'png') {

			// image has alpha transparency, but output as PNG which can handle it
			$this->DebugMessage('skipping AlphaChannelFlatten() because ($this->thumbnailFormat == "'.$this->thumbnailFormat.'")', __FILE__, __LINE__);
			return false;

		} elseif ($this->thumbnailFormat == 'gif') {

			// image has alpha transparency, but output as GIF which can handle only single-color transparency
			$CurrentImageColorTransparent = ImageColorTransparent($this->gdimg_output);
			if ($CurrentImageColorTransparent == -1) {
				// no transparent color defined

				if (phpthumb_functions::gd_version() < 2.0) {
					$this->DebugMessage('AlphaChannelFlatten() failed because GD version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
					return false;
				}

				if ($img_alpha_mixdown_dither = @ImageCreateTrueColor(ImageSX($this->gdimg_output), ImageSY($this->gdimg_output))) {

					for ($i = 0; $i <= 255; $i++) {
						$dither_color[$i] = ImageColorAllocate($img_alpha_mixdown_dither, $i, $i, $i);
					}

					// scan through current truecolor image copy alpha channel to temp image as grayscale
					for ($x = 0; $x < $this->thumbnail_width; $x++) {
						for ($y = 0; $y < $this->thumbnail_height; $y++) {
							$PixelColor = phpthumb_functions::GetPixelColor($this->gdimg_output, $x, $y);
							ImageSetPixel($img_alpha_mixdown_dither, $x, $y, $dither_color[($PixelColor['alpha'] * 2)]);
						}
					}

					// dither alpha channel grayscale version down to 2 colors
					ImageTrueColorToPalette($img_alpha_mixdown_dither, true, 2);

					// reduce color palette to 256-1 colors (leave one palette position for transparent color)
					ImageTrueColorToPalette($this->gdimg_output, true, 255);

					// allocate a new color for transparent color index
					$TransparentColor = ImageColorAllocate($this->gdimg_output, 1, 254, 253);
					ImageColorTransparent($this->gdimg_output, $TransparentColor);

					// scan through alpha channel image and note pixels with >50% transparency
					$TransparentPixels = array();
					for ($x = 0; $x < $this->thumbnail_width; $x++) {
						for ($y = 0; $y < $this->thumbnail_height; $y++) {
							$AlphaChannelPixel = phpthumb_functions::GetPixelColor($img_alpha_mixdown_dither, $x, $y);
							if ($AlphaChannelPixel['red'] > 127) {
								ImageSetPixel($this->gdimg_output, $x, $y, $TransparentColor);
							}
						}
					}
					ImageDestroy($img_alpha_mixdown_dither);

					$this->DebugMessage('AlphaChannelFlatten() set image to 255+1 colors with transparency for GIF output', __FILE__, __LINE__);
					return true;

				} else {
					$this->DebugMessage('AlphaChannelFlatten() failed ImageCreate('.ImageSX($this->gdimg_output).', '.ImageSY($this->gdimg_output).')', __FILE__, __LINE__);
					return false;
				}

			} else {
				// a single transparent color already defined, leave as-is
				$this->DebugMessage('skipping AlphaChannelFlatten() because ($this->thumbnailFormat == "'.$this->thumbnailFormat.'") and ImageColorTransparent returned "'.$CurrentImageColorTransparent.'"', __FILE__, __LINE__);
				return true;
			}

		}
		$this->DebugMessage('continuing AlphaChannelFlatten() for output format "'.$this->thumbnailFormat.'"', __FILE__, __LINE__);

		// image has alpha transparency, and is being output in a format that doesn't support it -- flatten
		if ($gdimg_flatten_temp = phpthumb_functions::ImageCreateFunction($this->thumbnail_width, $this->thumbnail_height)) {

			$this->config_background_hexcolor = (!empty($this->bg) ? $this->bg : $this->config_background_hexcolor);
			if (!phpthumb_functions::IsHexColor($this->config_background_hexcolor)) {
				return $this->ErrorImage('Invalid hex color string "'.$this->config_background_hexcolor.'" for parameter "bg"');
			}
			$background_color = phpthumb_functions::ImageHexColorAllocate($this->gdimg_output, $this->config_background_hexcolor);
			ImageFilledRectangle($gdimg_flatten_temp, 0, 0, $this->thumbnail_width, $this->thumbnail_height, $background_color);
			ImageCopy($gdimg_flatten_temp, $this->gdimg_output, 0, 0, 0, 0, $this->thumbnail_width, $this->thumbnail_height);

			ImageAlphaBlending($this->gdimg_output, true);
			if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.2', '>=')) {
				ImageSaveAlpha($this->gdimg_output, false);
			}
			ImageColorTransparent($this->gdimg_output, -1);
			ImageCopy($this->gdimg_output, $gdimg_flatten_temp, 0, 0, 0, 0, $this->thumbnail_width, $this->thumbnail_height);

			ImageDestroy($gdimg_flatten_temp);
			return true;

		} else {
			$this->DebugMessage('ImageCreateFunction() failed', __FILE__, __LINE__);
		}
		return false;
	}


	function ApplyFilters() {
		if (!empty($this->fltr) && is_array($this->fltr)) {
			if (!include_once('phpthumb.filters.php')) {
				$this->DebugMessage('Error including "phpthumb.filters.php" which is required for applying filters ('.implode(';', $this->fltr).')', __FILE__, __LINE__);
				return false;
			}
			foreach ($this->fltr as $filtercommand) {
				@list($command, $parameter) = explode('|', $filtercommand, 2);
				$this->DebugMessage('Attempting to process filter command "'.$command.'"', __FILE__, __LINE__);
				switch ($command) {
					case 'ds':
						phpthumb_filters::Desaturate($this->gdimg_output, $parameter, '');
						break;

					case 'gray':
						phpthumb_filters::Desaturate($this->gdimg_output, 100, '');
						break;

					case 'clr':
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping Colorize() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							break;
						}
						@list($amount, $color) = explode('|', $parameter);
						phpthumb_filters::Colorize($this->gdimg_output, $amount, $color);
						break;

					case 'sep':
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping Sepia() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							break;
						}
						@list($amount, $color) = explode('|', $parameter);
						phpthumb_filters::Sepia($this->gdimg_output, $amount, $color);
						break;

					case 'gam':
						phpthumb_filters::Gamma($this->gdimg_output, $parameter);
						break;

					case 'neg':
						phpthumb_filters::Negative($this->gdimg_output);
						break;

					case 'th':
						phpthumb_filters::Threshold($this->gdimg_output, $parameter);
						break;

					case 'flip':
						phpthumb_filters::Flip($this->gdimg_output, (strpos(strtolower($parameter), 'x') !== false), (strpos(strtolower($parameter), 'y') !== false));
						break;

					case 'bvl':
						@list($width, $color1, $color2) = explode('|', $parameter);
						phpthumb_filters::Bevel($this->gdimg_output, $width, $color1, $color2);
						break;

					case 'lvl':
						@list($band, $min, $max) = explode('|', $parameter);
						$band = ($band ? $band : '*');
						$min  = ((strlen($min) > 0) ? $min : '-1');
						$max  = ((strlen($max) > 0) ? $max : '-1');
						phpthumb_filters::HistogramStretch($this->gdimg_output, $band, $min, $max);
						break;

					case 'hist':
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping HistogramOverlay() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							break;
						}
						@list($bands, $colors, $width, $height, $alignment, $opacity, $margin) = explode('|', $parameter);
						$bands     = ($bands     ? $bands     :  '*');
						$colors    = ($colors    ? $colors    :   '');
						$width     = ($width     ? $width     : 0.25);
						$height    = ($height    ? $height    : 0.25);
						$alignment = ($alignment ? $alignment : 'BR');
						$opacity   = ($opacity   ? $opacity   :   50);
						$margin    = ($margin    ? $margin    :    5);
						phpthumb_filters::HistogramOverlay($this->gdimg_output, $bands, $colors, $width, $height, $alignment, $opacity, $margin);
						break;

					case 'fram':
						@list($frame_width, $edge_width, $color_frame, $color1, $color2) = explode('|', $parameter);
						phpthumb_filters::Frame($this->gdimg_output, $frame_width, $edge_width, $color_frame, $color1, $color2);
						break;

					case 'drop':
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping DropShadow() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						$this->is_alpha = true;
						@list($distance, $width, $color, $angle, $fade) = explode('|', $parameter);
						phpthumb_filters::DropShadow($this->gdimg_output, $distance, $width, $color, $angle, $fade);
						break;

					case 'mask':
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping Mask() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						$mask_filename = $this->ResolveFilenameToAbsolute($parameter);
						if (@is_readable($mask_filename) && ($fp_mask = @fopen($mask_filename, 'rb'))) {
							$MaskImageData = fread($fp_mask, filesize($mask_filename));
							fclose($fp_mask);
							if ($gdimg_mask = $this->ImageCreateFromStringReplacement($MaskImageData)) {
								$this->is_alpha = true;
								phpthumb_filters::ApplyMask($gdimg_mask, $this->gdimg_output);
								ImageDestroy($gdimg_mask);
							} else {
								$this->DebugMessage('ImageCreateFromStringReplacement() failed for "'.$mask_filename.'"', __FILE__, __LINE__);
							}
						} else {
							$this->DebugMessage('Cannot open mask file "'.$mask_filename.'"', __FILE__, __LINE__);
						}
						break;

					case 'elip':
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping Elipse() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						$this->is_alpha = true;
						phpthumb_filters::Elipse($this->gdimg_output);
						break;

					case 'ric':
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping RoundedImageCorners() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						@list($radius_x, $radius_y) = explode('|', $parameter);
						if (($radius_x < 1) || ($radius_y < 1)) {
							$this->DebugMessage('Skipping RoundedImageCorners('.$radius_x.', '.$radius_y.') because x/y radius is less than 1', __FILE__, __LINE__);
							break;
						}
						$this->is_alpha = true;
						phpthumb_filters::RoundedImageCorners($this->gdimg_output, $radius_x, $radius_y);
						break;

					case 'bord':
						@list($border_width, $radius_x, $radius_y, $hexcolor_border) = explode('|', $parameter);
						$this->is_alpha = true;
						phpthumb_filters::ImageBorder($this->gdimg_output, $border_width, $radius_x, $radius_y, $hexcolor_border);
						break;

					case 'over':
						@list($filename, $underlay, $margin, $opacity) = explode('|', $parameter);
						$underlay = ($underlay              ? (bool) $underlay : false);
						$margin   = ((strlen($margin)  > 0) ?        $margin   :   0.1);
						$opacity  = ((strlen($opacity) > 0) ?        $opacity  :   100);

						$filename = $this->ResolveFilenameToAbsolute($filename);
						if (@is_readable($filename) && ($fp_watermark = @fopen($filename, 'rb'))) {
							$WatermarkImageData = fread($fp_watermark, filesize($filename));
							fclose($fp_watermark);
							if ($img_watermark = $this->ImageCreateFromStringReplacement($WatermarkImageData)) {
								if ($margin < 1) {
									$resized_x = ImageSX($this->gdimg_output) - round(2 * (ImageSX($this->gdimg_output) * $margin));
									$resized_y = ImageSY($this->gdimg_output) - round(2 * (ImageSY($this->gdimg_output) * $margin));
								} else {
									$resized_x = ImageSX($this->gdimg_output) - round(2 * $margin);
									$resized_y = ImageSY($this->gdimg_output) - round(2 * $margin);
								}

								if ($img_watermark_resized = phpthumb_functions::ImageCreateFunction(ImageSX($this->gdimg_output), ImageSY($this->gdimg_output))) {

									if ($underlay) {

										ImageAlphaBlending($img_watermark_resized, false);
										ImageSaveAlpha($img_watermark_resized, true);
										phpthumb_functions::ImageResizeFunction($img_watermark_resized, $img_watermark, 0, 0, 0, 0, ImageSX($img_watermark_resized), ImageSY($img_watermark_resized), ImageSX($img_watermark), ImageSY($img_watermark));
										if ($img_source_resized = phpthumb_functions::ImageCreateFunction($resized_x, $resized_y)) {
											ImageAlphaBlending($img_source_resized, false);
											ImageSaveAlpha($img_source_resized, true);
											phpthumb_functions::ImageResizeFunction($img_source_resized, $this->gdimg_output, 0, 0, 0, 0, ImageSX($img_source_resized), ImageSY($img_source_resized), ImageSX($this->gdimg_output), ImageSY($this->gdimg_output));
											phpthumb_filters::WatermarkOverlay($img_watermark_resized, $img_source_resized, 'C', $opacity, $margin);
											ImageCopy($this->gdimg_output, $img_watermark_resized, 0, 0, 0, 0, ImageSX($this->gdimg_output), ImageSY($this->gdimg_output));
										} else {
											$this->DebugMessage('phpthumb_functions::ImageCreateFunction('.$resized_x.', '.$resized_y.')', __FILE__, __LINE__);
										}

									} else { // overlay

										ImageAlphaBlending($img_watermark_resized, false);
										ImageSaveAlpha($img_watermark_resized, true);
										phpthumb_functions::ImageResizeFunction($img_watermark_resized, $img_watermark, 0, 0, 0, 0, ImageSX($img_watermark_resized), ImageSY($img_watermark_resized), ImageSX($img_watermark), ImageSY($img_watermark));
										phpthumb_filters::WatermarkOverlay($this->gdimg_output, $img_watermark_resized, 'C', $opacity, $margin);

									}

								} else {
									$this->DebugMessage('phpthumb_functions::ImageCreateFunction('.ImageSX($this->gdimg_output).', '.ImageSY($this->gdimg_output).')', __FILE__, __LINE__);
								}
								ImageDestroy($img_watermark_resized);
								ImageDestroy($img_watermark);
							} else {
								$this->DebugMessage('ImageCreateFromStringReplacement() failed for "'.$filename.'"', __FILE__, __LINE__);
							}
						} else {
							$this->DebugMessage('Cannot open overlay file "'.$filename.'"', __FILE__, __LINE__);
						}
						break;

					case 'wmi':
						@list($filename, $alignment, $opacity, $margin) = explode('|', $parameter);
						$alignment = ($alignment      ? $alignment : 'BR');
						$opacity   = (isset($opacity) ? $opacity   : 50);
						$margin    = (isset($margin)  ? $margin    : 5);

						$filename = $this->ResolveFilenameToAbsolute($filename);
						if (@is_readable($filename) && ($fp_watermark = @fopen($filename, 'rb'))) {
							$WatermarkImageData = fread($fp_watermark, filesize($filename));
							fclose($fp_watermark);
							if ($img_watermark = $this->ImageCreateFromStringReplacement($WatermarkImageData)) {
								// great
								phpthumb_filters::WatermarkOverlay($this->gdimg_output, $img_watermark, $alignment, $opacity, $margin);
								ImageDestroy($img_watermark);
							} else {
								$this->DebugMessage('ImageCreateFromStringReplacement() failed for "'.$filename.'"', __FILE__, __LINE__);
							}
						} else {
							$this->DebugMessage('Cannot open watermark file "'.$filename.'"', __FILE__, __LINE__);
						}
						break;

					case 'wmt':
						@list($text, $size, $alignment, $hex_color, $ttffont, $opacity, $margin, $angle) = explode('|', $parameter);
						$text      = ($text           ? $text      : '');
						$size      = ($size           ? $size      : 3);
						$alignment = ($alignment      ? $alignment : 'BR');
						$hex_color = ($hex_color      ? $hex_color : '000000');
						$ttffont   = ($ttffont        ? $ttffont   : '');
						$opacity   = (isset($opacity) ? $opacity   : 50);
						$margin    = (isset($margin)  ? $margin    : 5);
						$angle     = (isset($angle)   ? $angle     : 0);
						phpthumb_filters::WatermarkText($this->gdimg_output, $text, $size, $alignment, $hex_color, realpath($this->config_ttf_directory.'/'.$ttffont), $opacity, $margin, $angle);
						break;

					case 'blur':
						@list($radius) = explode('|', $parameter);
						$radius = ($radius ? $radius : 1);
						if (phpthumb_functions::gd_version() < 2) {
							$this->DebugMessage('Skipping Blur() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						phpthumb_filters::Blur($this->gdimg_output, $radius);
						break;

					case 'usm':
						@list($amount, $radius, $threshold) = explode('|', $parameter);
						$amount    = ($amount           ? $amount    : 80);
						$radius    = ($radius           ? $radius    : 0.5);
						$threshold = (isset($threshold) ? $threshold : 3);
						if (phpthumb_functions::gd_version() >= 2.0) {
							if (@include_once('phpthumb.unsharp.php')) {
								phpUnsharpMask::applyUnsharpMask($this->gdimg_output, $amount, $radius, $threshold);
							} else {
								$this->DebugMessage('Error including "phpthumb.unsharp.php" which is required for unsharp masking', __FILE__, __LINE__);
								return false;
							}
						} else {
							$this->DebugMessage('Skipping unsharp mask because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
							return false;
						}
						break;
				}
			}
		}
		return true;
	}


	function MaxFileSize() {
		if (phpthumb_functions::gd_version() < 2) {
			$this->DebugMessage('Skipping MaxFileSize() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
			return false;
		}
		if (!empty($this->maxb) && ($this->maxb > 0)) {
			switch ($this->thumbnailFormat) {
				case 'png':
				case 'gif':
					$imgRenderFunction = 'image'.$this->thumbnailFormat;

					ob_start();
					$imgRenderFunction($this->gdimg_output);
					$imgdata = ob_get_contents();
					ob_end_clean();

					if (strlen($imgdata) > $this->maxb) {
						for ($i = 8; $i >= 1; $i--) {
							$tempIMG = ImageCreateTrueColor(ImageSX($this->gdimg_output), ImageSY($this->gdimg_output));
							ImageCopy($tempIMG, $this->gdimg_output, 0, 0, 0, 0, ImageSX($this->gdimg_output), ImageSY($this->gdimg_output));
							ImageTrueColorToPalette($tempIMG, true, pow(2, $i));
							ob_start();
							$imgRenderFunction($tempIMG);
							$imgdata = ob_get_contents();
							ob_end_clean();

							if (strlen($imgdata) <= $this->maxb) {
								ImageTrueColorToPalette($this->gdimg_output, true, pow(2, $i));
								break;
							}
						}
					}
					if (strlen($imgdata) > $this->maxb) {
						ImageTrueColorToPalette($this->gdimg_output, true, pow(2, $i));
						return false;
					}
					break;

				case 'jpeg':
					ob_start();
					ImageJPEG($this->gdimg_output);
					$imgdata = ob_get_contents();
					ob_end_clean();

					$OriginalJPEGquality = $this->thumbnailQuality;
					if (strlen($imgdata) > $this->maxb) {
						for ($i = 3; $i < 20; $i++) {
							$q = round(100 * (1 - log10($i / 2)));
							ob_start();
							ImageJPEG($this->gdimg_output, '', $q);
							$imgdata = ob_get_contents();
							ob_end_clean();

							$this->thumbnailQuality = $q;
							if (strlen($imgdata) <= $this->maxb) {
								break;
							}
						}
					}
					if (strlen($imgdata) > $this->maxb) {
						return false;
					}
					break;

				default:
					return false;
					break;
			}
		}
		return true;
	}


	function CalculateThumbnailDimensions() {

		$this->thumbnailCropX = (!empty($this->sx) ? (($this->sx >= 1) ? $this->sx : round($this->sx * $this->source_width))  : 0);
		$this->thumbnailCropY = (!empty($this->sy) ? (($this->sy >= 1) ? $this->sy : round($this->sy * $this->source_height)) : 0);
		$this->thumbnailCropW = (!empty($this->sw) ? (($this->sw >= 1) ? $this->sw : round($this->sw * $this->source_width))  : $this->source_width);
		$this->thumbnailCropH = (!empty($this->sh) ? (($this->sh >= 1) ? $this->sh : round($this->sh * $this->source_height)) : $this->source_height);

		// limit source area to original image area
		$this->thumbnailCropW = max(1, min($this->thumbnailCropW, $this->source_width  - $this->thumbnailCropX));
		$this->thumbnailCropH = max(1, min($this->thumbnailCropH, $this->source_height - $this->thumbnailCropY));

		$this->DebugMessage('CalculateThumbnailDimensions() [x,y,w,h] initially set to ['.$this->thumbnailCropX.','.$this->thumbnailCropY.','.$this->thumbnailCropW.','.$this->thumbnailCropH.']', __FILE__, __LINE__);



		if (!empty($this->zc) && !empty($this->w) && !empty($this->h)) {
			// Zoom Crop
			// retain proportional resizing we did above, but crop off larger dimension so smaller
			// dimension fully fits available space

			$scaling_X = $this->source_width  / $this->w;
			$scaling_Y = $this->source_height / $this->h;
			if ($scaling_X > $scaling_Y) {
				// some of the width will need to be cropped
				$allowable_width = $this->source_width / $scaling_X * $scaling_Y;
				$this->thumbnailCropW = round($allowable_width);
				$this->thumbnailCropX = round(($this->source_width - $allowable_width) / 2);

			} elseif ($scaling_Y > $scaling_X) {
				// some of the height will need to be cropped
				$allowable_height = $this->source_height / $scaling_Y * $scaling_X;
				$this->thumbnailCropH = round($allowable_height);
				$this->thumbnailCropY = round(($this->source_height - $allowable_height) / 2);

			} else {
				// image fits perfectly, no cropping needed
			}

		}


		if (!empty($this->iar) && !empty($this->w) && !empty($this->h)) {

			// Ignore Aspect Ratio
			// forget all the careful proportional resizing we did above, stretch image to fit 'w' && 'h'
			$this->thumbnail_width  = $this->w;
			$this->thumbnail_height = $this->h;
			$this->thumbnail_image_width  = $this->thumbnail_width;
			$this->thumbnail_image_height = $this->thumbnail_height;

		} else {

			// default new width and height to source area
			$this->thumbnail_image_width  = $this->thumbnailCropW;
			$this->thumbnail_image_height = $this->thumbnailCropH;
			if (($this->config_output_maxwidth > 0) && ($this->thumbnail_image_width > $this->config_output_maxwidth)) {
				if (($this->config_output_maxwidth < $this->thumbnailCropW) || $this->config_output_allow_enlarging) {
					$maxwidth = $this->config_output_maxwidth;
					$this->thumbnail_image_width = $maxwidth;
					$this->thumbnail_image_height = round($this->thumbnailCropH * ($this->thumbnail_image_width / $this->thumbnailCropW));
				}
			}

			// if user sets width, save as max width
			// and compute new height based on source area aspect ratio
			if (!empty($this->w)) {
				if (($this->w < $this->thumbnailCropW) || $this->config_output_allow_enlarging) {
					$maxwidth = $this->w;
					$this->thumbnail_image_width = $this->w;
					$this->thumbnail_image_height = round($this->thumbnailCropH * $this->w / $this->thumbnailCropW);
				}
			}

			// if user sets height, save as max height
			// if the max width has already been set and the new image is too tall,
			// compute new width based on source area aspect ratio
			// otherwise, use max height and compute new width
			if (!empty($this->h) || ($this->config_output_maxheight > 0)) {
				$maxheight = (!empty($this->h) ? $this->h : $this->config_output_maxheight);
				if (($maxheight < $this->thumbnailCropH) || $this->config_output_allow_enlarging) {
					if (isset($maxwidth)) {
						if ($this->thumbnail_image_height > $maxheight) {
							$this->thumbnail_image_width  = round($this->thumbnailCropW * $maxheight / $this->thumbnailCropH);
							$this->thumbnail_image_height = $maxheight;
						}
					} else {
						$this->thumbnail_image_height = $maxheight;
						$this->thumbnail_image_width  = round($this->thumbnailCropW * $this->thumbnail_image_height / $this->thumbnailCropH);
					}
				}
			}

			$this->thumbnail_width  = $this->thumbnail_image_width;
			$this->thumbnail_height = $this->thumbnail_image_height;
			if (!empty($this->w) && !empty($this->h) && isset($this->far)) {
				$this->thumbnail_width  = $this->w;
				$this->thumbnail_height = $this->h;
			}

			$this->FixedAspectRatio();

		}
		return true;
	}


	function CreateGDoutput() {

		$this->CalculateThumbnailDimensions();

		// Create the GD image (either true-color or 256-color, depending on GD version)
		$this->gdimg_output = phpthumb_functions::ImageCreateFunction($this->thumbnail_width, $this->thumbnail_height);

		// Images that have transparency must have the background filled with the configured 'bg' color
		// otherwise the transparent color will appear as black
		if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.2', '>=')) {
			ImageSaveAlpha($this->gdimg_output, true);
		}
		if ($this->is_alpha && phpthumb_functions::gd_version() >= 2) {

			ImageAlphaBlending($this->gdimg_output, false);
			$output_full_alpha = ImageColorAllocateAlpha($this->gdimg_output, 255, 255, 255, 127);
			ImageFilledRectangle($this->gdimg_output, 0, 0, $this->thumbnail_width, $this->thumbnail_height, $output_full_alpha);

		} else {

			$current_transparent_color = ImageColorTransparent($this->gdimg_source);
			if ($this->bg || (@$current_transparent_color >= 0)) {

				$this->config_background_hexcolor = ($this->bg ? $this->bg : $this->config_background_hexcolor);
				if (!phpthumb_functions::IsHexColor($this->config_background_hexcolor)) {
					return $this->ErrorImage('Invalid hex color string "'.$this->config_background_hexcolor.'" for parameter "bg"');
				}
				$background_color = phpthumb_functions::ImageHexColorAllocate($this->gdimg_output, $this->config_background_hexcolor);
				ImageFilledRectangle($this->gdimg_output, 0, 0, $this->thumbnail_width, $this->thumbnail_height, $background_color);

			}

		}
		return true;
	}


	function ExtractEXIFgetImageSize() {

		if (is_resource($this->gdimg_source)) {

			$this->source_width  = ImageSX($this->gdimg_source);
			$this->source_height = ImageSY($this->gdimg_source);

		} elseif ($this->rawImageData && !$this->sourceFilename) {

			$this->DebugMessage('bypassing EXIF and GetImageSize sections because $this->rawImageData is set and $this->sourceFilename is not', __FILE__, __LINE__);

		} elseif ($this->getimagesizeinfo = @GetImageSize($this->sourceFilename)) {

			$this->source_width  = $this->getimagesizeinfo[0];
			$this->source_height = $this->getimagesizeinfo[1];

			if (function_exists('exif_thumbnail') && ($this->getimagesizeinfo[2] == 2)) {
				// Extract EXIF info from JPEGs

				$this->exif_thumbnail_width  = '';
				$this->exif_thumbnail_height = '';
				$this->exif_thumbnail_type   = '';

				// The parameters width, height and imagetype are available since PHP v4.3.0
				if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.0', '>=')) {

					$this->exif_thumbnail_data = @exif_thumbnail($this->sourceFilename, $this->exif_thumbnail_width, $this->exif_thumbnail_height, $this->exif_thumbnail_type);

				} else {

					// older versions of exif_thumbnail output an error message but NOT return false on failure
					ob_start();
					$this->exif_thumbnail_data = exif_thumbnail($this->sourceFilename);
					$exit_thumbnail_error = ob_get_contents();
					ob_end_clean();
					if (empty($exit_thumbnail_error) && !empty($this->exif_thumbnail_data)) {

						if ($gdimg_exif_temp = $this->ImageCreateFromStringReplacement($this->exif_thumbnail_data, false)) {
							$this->exif_thumbnail_width  = ImageSX($gdimg_exif_temp);
							$this->exif_thumbnail_height = ImageSY($gdimg_exif_temp);
							$this->exif_thumbnail_type   = 2; // (2 == JPEG) before PHP v4.3.0 only JPEG format EXIF thumbnails are returned
							unset($gdimg_exif_temp);
						} else {
							return $this->ErrorImage('Failed - $this->ImageCreateFromStringReplacement($this->exif_thumbnail_data) in '.__FILE__.' on line '.__LINE__);
						}

					}

				}

			} elseif (!function_exists('exif_thumbnail')) {

				$this->DebugMessage('exif_thumbnail() does not exist, cannot extract EXIF thumbnail', __FILE__, __LINE__);

			}

			// see if EXIF thumbnail can be used directly with no processing
			if (!empty($this->exif_thumbnail_data)) {
				while (true) {
					if (empty($this->xto)) {
						if (isset($this->w) && ($this->w != $this->exif_thumbnail_width)) {
							break;
						}
						if (isset($this->h) && ($this->h != $this->exif_thumbnail_height)) {
							break;
						}
						$CannotBeSetParameters = array('sx', 'sy', 'sh', 'sw', 'far', 'bg', 'bc', 'fltr', 'phpThumbDebug');
						foreach ($CannotBeSetParameters as $parameter) {
							if (!empty($this->$parameter)) {
								break 2;
							}
						}
					}

					// EXIF thumbnail can be used directly for these parameters - write cached file
					$ImageTypesLookup = array(2=>'jpeg'); // EXIF thumbnails are (currently?) only availble from JPEG source images
					if (is_dir($this->config_cache_directory) && is_writable($this->config_cache_directory) && isset($ImageTypesLookup[$this->exif_thumbnail_type])) {
						if ($fp_cached = @fopen($this->cache_filename, 'wb')) {
							fwrite($fp_cached, $this->exif_thumbnail_data);
							fclose($fp_cached);
						} else {
							$this->DebugMessage('failed to fopen $this->cache_filename ('.$this->cache_filename.')', __FILE__, __LINE__);
						}
					} else {
						$this->DebugMessage('!is_dir($this->config_cache_directory), or !is_writable($this->config_cache_directory) ('.$this->cache_filename.'); or !isset($ImageTypesLookup['.$this->exif_thumbnail_type.'])', __FILE__, __LINE__);
					}

					if ($mime_type = phpthumb_functions::ImageTypeToMIMEtype($this->exif_thumbnail_type)) {
						header('Content-type: '.$mime_type);
						echo $this->exif_thumbnail_data;
						exit;
					} else {
						return $this->ErrorImage('phpthumb_functions::ImageTypeToMIMEtype('.$this->exif_thumbnail_type.') failed in '.__FILE__.' on line '.__LINE__);
					}
					break;
				}
			}

			if (($this->config_max_source_pixels > 0) && (($this->source_width * $this->source_height) > $this->config_max_source_pixels)) {
				// Source image is larger than would fit in available PHP memory.
				// If ImageMagick is installed, use it to generate the thumbnail.
				// Else, if an EXIF thumbnail is available, use that as the source image.
				// Otherwise, no choice but to fail with an error message
				$this->DebugMessage('image is '.$this->source_width.'x'.$this->source_height.' and therefore contains more pixels ('.($this->source_width * $this->source_height).') than $this->config_max_source_pixels setting ('.$this->config_max_source_pixels.')', __FILE__, __LINE__);

				if ($this->ImageMagickThumbnailToGD()) {

					// excellent, we have a thumbnailed source image

				} elseif (!empty($this->exif_thumbnail_data)) {

					$this->DebugMessage('ImageMagickThumbnailToGD() failed, but $this->exif_thumbnail_data is usable', __FILE__, __LINE__);

					// EXIF thumbnail exists, and will be use as source image
					$this->gdimg_source  = $this->ImageCreateFromStringReplacement($this->exif_thumbnail_data);
					$this->source_width  = $this->exif_thumbnail_width;
					$this->source_height = $this->exif_thumbnail_height;

					// override allow-enlarging setting if EXIF thumbnail is the only source available
					// otherwise thumbnails larger than the EXIF thumbnail will be created at EXIF size
					$this->config_output_allow_enlarging = true;

				} else {

					return $this->ErrorImage('Source image is ('.$this->source_width.'x'.$this->source_height.') which equals '.sprintf('%1.1f', $this->source_width * $this->source_height).' megapixels, which is more than the allowed '.sprintf('%1.1f', ($this->config_max_source_pixels / 1000000)).' megapixels -- insufficient memory.'."\n".'EXIF thumbnail unavailable.');

				}
			}

		} else {

			$this->DebugMessage('GetImageSize("'.$this->sourceFilename.'") failed', __FILE__, __LINE__);

		}
		return true;
	}


	function SetCacheFilename() {
		if (!is_null($this->cache_filename)) {
			// $this->cache_filename already set, no need to re-set it
			return true;
		}
		$this->setOutputFormat();
		$this->setCacheDirectory();
		if (empty($this->config_cache_directory)) {
			$this->DebugMessage('SetCacheFilename() failed because $this->config_cache_directory is empty', __FILE__, __LINE__);
			return false;
		}

		if (empty($this->sourceFilename) && empty($this->rawImageData) && !empty($this->src)) {
			$this->sourceFilename = $this->ResolveFilenameToAbsolute($this->src);
		}

		$this->cache_filename = $this->config_cache_directory.'/phpThumb_cache';
		if ($this->new) {
			$this->cache_filename .= '_new'.$this->new;
		} elseif (!$this->src && $this->rawImageData) {
			$this->cache_filename .= '_'.strtolower(md5($this->rawImageData));
		} else {
			$this->cache_filename .= '_'.urlencode($this->src);
		}
		if (!empty($_SERVER['HTTP_REFERER'])) {
			$parsed_url1 = @parse_url(@$_SERVER['HTTP_REFERER']);
			$parsed_url2 = @parse_url('http://'.@$_SERVER['HTTP_HOST']);
			if (@$parsed_url1['host'] && @$parsed_url2['host'] && ($parsed_url1['host'] != $parsed_url2['host'])) {
				// include refering domain only if it doesn't match the domain of the current server
				$this->cache_filename .= '_httpreferer'.urlencode(@$parsed_url2['host']);
			}
		}
		if (!empty($_REQUEST['fltr']) && is_array($_REQUEST['fltr'])) {
			$this->cache_filename .= '_fltr'.urlencode(str_replace('|', '¦', implode('_fltr', $_REQUEST['fltr'])));
		}
		$FilenameParameters = array('h', 'w', 'sx', 'sy', 'sw', 'sh', 'far', 'bg', 'bgt', 'bc', 'xto', 'ra', 'ar', 'iar', 'maxb');
		foreach ($FilenameParameters as $key) {
			if (isset($this->$key)) {
				if ($this->$key === true) {
					$this->cache_filename .= '_'.$key.'1';
				} else {
					$this->cache_filename .= '_'.$key.$this->$key;
				}
			}
		}
		if (eregi('^(f|ht)tp[s]?://', $this->src)) {
			$this->cache_filename .= '_'.intval(phpthumb_functions::filedate_remote($this->src));
		} elseif ($this->src && !$this->rawImageData) {
			$this->cache_filename .= '_'.intval(@filemtime($this->sourceFilename));
		}
		$this->cache_filename .= '_q'.$this->thumbnailQuality;
		$this->cache_filename .= '_'.$this->thumbnailFormat;

		return true;
	}


	function SourceImageToGD() {
		if (is_resource($this->gdimg_source)) {
			$this->DebugMessage('skipping SourceImageToGD() because $this->gdimg_source is already a resource', __FILE__, __LINE__);
			return true;
		}
		$this->DebugMessage('starting SourceImageToGD()', __FILE__, __LINE__);
		if ($this->config_use_exif_thumbnail_for_speed && !empty($this->exif_thumbnail_data)) {
			if (($this->exif_thumbnail_width  >= $this->thumbnail_image_width) &&
				($this->exif_thumbnail_height >= $this->thumbnail_image_height) &&
				($this->thumbnailCropX == 0) &&
				($this->thumbnailCropY == 0) &&
				($this->thumbnailCropW > 0) &&
				($this->thumbnailCropH > 0) &&
				($this->source_width  >= $this->thumbnailCropW) &&
				($this->source_height >= $this->thumbnailCropH)) {
					// EXIF thumbnail exists, and is equal to or larger than destination thumbnail, and will be use as source image
					// Only benefit here is greater speed, not lower memory usage
					$this->DebugMessage('Trying to use EXIF thumbnail as source image', __FILE__, __LINE__);

					if ($gdimg_exif_temp = $this->ImageCreateFromStringReplacement($this->exif_thumbnail_data, false)) {

						$this->DebugMessage('Successfully using EXIF thumbnail as source image', __FILE__, __LINE__);

						$this->gdimg_source = $gdimg_exif_temp;
						$this->source_width  = $this->exif_thumbnail_width;
						$this->source_height = $this->exif_thumbnail_height;
						$this->thumbnailCropW = $this->source_width;
						$this->thumbnailCropH = $this->source_height;

						return true;

					} else {
						$this->DebugMessage('$this->ImageCreateFromStringReplacement($this->exif_thumbnail_data, false) failed', __FILE__, __LINE__);
					}

			} else {
				$this->DebugMessage('Not using EXIF thumbnail data because EXIF thumbnail is too small', __FILE__, __LINE__);
			}

		} else {

			if (!$this->config_use_exif_thumbnail_for_speed) {
				$this->DebugMessage('Not using EXIF thumbnail data because $this->config_use_exif_thumbnail_for_speed is FALSE', __FILE__, __LINE__);
			} elseif ($this->getimagesizeinfo[2] == 2) {
				$this->DebugMessage('Not using EXIF thumbnail data because EXIF thumbnail is unavailable', __FILE__, __LINE__);
			} elseif (is_array($this->getimagesizeinfo)) {
				$this->DebugMessage('Not using EXIF thumbnail data because source image is not JPEG, therefore no EXIF thumbnail available', __FILE__, __LINE__);
			//} else {
			//	$this->DebugMessage('Not using EXIF thumbnail data because GetImageSize failed on source image', __FILE__, __LINE__);
			}

		}

		if (empty($this->gdimg_source)) {
			// try to create GD image source directly via GD, if possible,
			// rather than buffering to memory and creating with ImageCreateFromString
			$ImageCreateWasAttempted = false;

			if (!empty($this->rawImageData)) {
				// fine
			} elseif ($this->iswindows && ((substr($this->sourceFilename, 0, 2) == '//') || (substr($this->sourceFilename, 0, 2) == '\\\\'))) {
				// Windows \\share\filename.ext
			} elseif (eregi('^(f|ht)tp[s]?://', $this->sourceFilename)) {
				// URL
			} elseif (!file_exists($this->sourceFilename)) {
				return $this->ErrorImage('"'.$this->sourceFilename.'" does not exist');
			} elseif (!is_file($this->sourceFilename)) {
				return $this->ErrorImage('"'.$this->sourceFilename.'" is not a file');
			}

			$ImageCreateFromFunction = array(
				1  => 'ImageCreateFromGIF',
				2  => 'ImageCreateFromJPEG',
				3  => 'ImageCreateFromPNG',
				15 => 'ImageCreateFromWBMP',
			);
			switch (@$this->getimagesizeinfo[2]) {
				case 1:  // GIF
				case 2:  // JPEG
				case 3:  // PNG
				case 15: // WBMP
					$ImageCreateFromFunctionName = $ImageCreateFromFunction[$this->getimagesizeinfo[2]];
					if (function_exists($ImageCreateFromFunctionName)) {
						$this->DebugMessage('Calling '.$ImageCreateFromFunctionName.'('.$this->sourceFilename.')', __FILE__, __LINE__);
						$ImageCreateWasAttempted = true;
						$this->gdimg_source = @$ImageCreateFromFunctionName($this->sourceFilename);
						switch ($this->getimagesizeinfo[2]) {
							case 1:
							case 3:
								// GIF or PNG input file may have transparency
								$this->is_alpha = true;
								break;
						}
					} else {
						$this->DebugMessage('NOT calling '.$ImageCreateFromFunctionName.'('.$this->sourceFilename.') because !function_exists('.$ImageCreateFromFunctionName.')', __FILE__, __LINE__);
					}
					break;

				case 4:  // SWF
				case 5:  // PSD
				case 6:  // BMP
				case 7:  // TIFF (LE)
				case 8:  // TIFF (BE)
				case 9:  // JPC
				case 10: // JP2
				case 11: // JPX
				case 12: // JB2
				case 13: // SWC
				case 14: // IFF
				case 16: // XBM
					$this->DebugMessage('No built-in image creation function for image type "'.@$this->getimagesizeinfo[2].'" ($this->getimagesizeinfo[2])', __FILE__, __LINE__);
					break;

				case '':
					// no source file, source image was probably set by setSourceData()
					break;

				default:
					$this->DebugMessage('Unknown value for $this->getimagesizeinfo[2]: "'.@$this->getimagesizeinfo[2].'"', __FILE__, __LINE__);
					break;
			}
			if (empty($this->gdimg_source)) {
				// cannot create from filename, attempt to create source image with ImageCreateFromString, if possible
				if ($ImageCreateWasAttempted) {
					$this->DebugMessage(@$ImageCreateFromFunctionName.'() was attempted but FAILED', __FILE__, __LINE__);
				}
				if (empty($this->rawImageData)) {
					$this->DebugMessage('Populating $this->rawImageData and attempting ImageCreateFromStringReplacement()', __FILE__, __LINE__);
					if ($fp = @fopen($this->sourceFilename, 'rb')) {

						$this->rawImageData = '';
						$filesize = filesize($this->sourceFilename);
						$blocksize = 16384;
						$blockreads = ceil($filesize / $blocksize);
						for ($i = 0; $i < $blockreads; $i++) {
							$this->rawImageData .= fread($fp, $blocksize);
						}
						fclose($fp);

					} else {
						return $this->ErrorImage('cannot fopen("'.$this->sourceFilename.'") on line '.__LINE__.' of '.__FILE__);
					}
				}
				$this->gdimg_source = $this->ImageCreateFromStringReplacement($this->rawImageData, true);
			}

			if (empty($this->gdimg_source)) {
				$this->DebugMessage('$this->gdimg_source is still empty', __FILE__, __LINE__);

				if ($this->ImageMagickThumbnailToGD()) {

					// excellent, we have a thumbnailed source image
					$this->DebugMessage('ImageMagickThumbnailToGD() succeeded', __FILE__, __LINE__);

				} else {

					$this->DebugMessage('ImageMagickThumbnailToGD() failed', __FILE__, __LINE__);

					$imageHeader = '';
					switch (substr($this->rawImageData, 0, 3)) {
						case 'GIF':
							$imageHeader = 'Content-type: image/gif';
							break;
						case "\xFF\xD8\xFF":
							$imageHeader = 'Content-type: image/jpeg';
							break;
						case "\x89".'PN':
							$imageHeader = 'Content-type: image/png';
							break;
					}
					if ($imageHeader) {
						// cannot create image for whatever reason (maybe ImageCreateFromJPEG et al are not available?)
						// and ImageMagick is not available either, no choice but to output original (not resized/modified) data and exit
						if ($this->config_error_die_on_source_failure) {
							$this->ErrorImage('All attempts to create GD image source failed (source image probably corrupt), cannot generate thumbnail');
						} else {
							$this->DebugMessage('All attempts to create GD image source failed, outputing raw image', __FILE__, __LINE__);
							header($imageHeader);
							echo $this->rawImageData;
							exit;
						}
					}

					switch (substr($this->rawImageData, 0, 2)) {
						case 'BM':
							if (@include_once('phpthumb.bmp.php')) {
								$phpthumb_bmp = new phpthumb_bmp;
								if ($this->gdimg_source = $phpthumb_bmp->phpthumb_bmp2gd($this->rawImageData, (phpthumb_functions::gd_version() >= 2.0))) {
									$this->DebugMessage('$phpthumb_bmp->phpthumb_bmp2gd() succeeded', __FILE__, __LINE__);
									break;
								} else {
									return $this->ErrorImage('phpthumb_bmp2db failed');
								}
							} else {
								return $this->ErrorImage('include_once(phpthumb.bmp.php) failed');
							}
							return $this->ErrorImage('ImageMagick is unavailable and phpThumb() does not support BMP source images without it');
							break;
					}


					switch (substr($this->rawImageData, 0, 4)) {
						case 'II'."\x2A\x00":
						case 'MM'."\x00\x2A":
							return $this->ErrorImage('ImageMagick is unavailable and phpThumb() does not support TIFF source images without it');
							break;

						case "\xD7\xCD\xC6\x9A":
							return $this->ErrorImage('ImageMagick is unavailable and phpThumb() does not support WMF source images without it');
							break;
					}

					if (empty($this->gdimg_source)) {
						return $this->ErrorImage('Unknown image type identified by "'.substr($this->rawImageData, 0, 4).'" ('.phpthumb_functions::HexCharDisplay(substr($this->rawImageData, 0, 4)).') in SourceImageToGD()');
					}

				}
			}

		}
		$this->source_width  = ImageSX($this->gdimg_source);
		$this->source_height = ImageSY($this->gdimg_source);
		return true;
	}




	function phpThumbDebugVarDump($var) {
		if (is_null($var)) {
			return 'null';
		} elseif (is_bool($var)) {
			return ($var ? 'TRUE' : 'FALSE');
		} elseif (is_string($var)) {
			return 'string('.strlen($var).')'.str_repeat(' ', max(0, 3 - strlen(strlen($var)))).' "'.$var.'"';
		} elseif (is_int($var)) {
			return 'integer     '.$var;
		} elseif (is_float($var)) {
			return 'float       '.$var;
		} elseif (is_array($var)) {
			ob_start();
			var_dump($var);
			$vardumpoutput = ob_get_contents();
			ob_end_clean();
			return strtr($vardumpoutput, "\n\r\t", '   ');
		}
		return gettype($var);
	}

	function phpThumbDebug() {
		if ($this->config_disable_debug) {
			return $this->ErrorImage('phpThumbDebug disabled');
		}

		$FunctionsExistance = array('exif_thumbnail', 'gd_info', 'image_type_to_mime_type', 'ImageCopyResampled', 'ImageCopyResized', 'ImageCreate', 'ImageCreateFromString', 'ImageCreateTrueColor', 'ImageIsTrueColor', 'ImageRotate', 'ImageTypes', 'version_compare', 'ImageCreateFromGIF', 'ImageCreateFromJPEG', 'ImageCreateFromPNG', 'ImageCreateFromWBMP', 'ImageCreateFromXBM', 'ImageCreateFromXPM', 'ImageCreateFromString', 'ImageCreateFromGD', 'ImageCreateFromGD2', 'ImageCreateFromGD2Part', 'ImageJPEG', 'ImageGIF', 'ImagePNG', 'ImageWBMP');
		$ParameterNames     = array('src', 'new', 'w', 'h', 'f', 'q', 'sx', 'sy', 'sw', 'sh', 'far', 'bg', 'bgt', 'bc', 'file', 'goto', 'err', 'xto', 'ra', 'ar', 'aoe', 'iar', 'maxb');
		$OtherVariableNames = array('phpThumbDebug', 'thumbnailQuality', 'thumbnailFormat', 'gdimg_output', 'gdimg_source', 'sourceFilename', 'source_width', 'source_height', 'thumbnailCropX', 'thumbnailCropY', 'thumbnailCropW', 'thumbnailCropH', 'exif_thumbnail_width', 'exif_thumbnail_height', 'exif_thumbnail_type', 'thumbnail_width', 'thumbnail_height', 'thumbnail_image_width', 'thumbnail_image_height');

		$DebugOutput = array();
		$DebugOutput[] = 'phpThumb() version          = '.$this->phpthumb_version;
		$DebugOutput[] = 'phpversion()                = '.@phpversion();
		$DebugOutput[] = 'PHP_OS                      = '.PHP_OS;
		$DebugOutput[] = '$_SERVER[PHP_SELF]          = '.@$_SERVER['PHP_SELF'];
		$DebugOutput[] = '$_SERVER[DOCUMENT_ROOT]     = '.@$_SERVER['DOCUMENT_ROOT'];
		$DebugOutput[] = '$_SERVER[HTTP_REFERER]      = '.@$_SERVER['HTTP_REFERER'];
		$DebugOutput[] = '$_SERVER[QUERY_STRING]      = '.@$_SERVER['QUERY_STRING'];
		$DebugOutput[] = 'realpath(.)                 = '.@realpath('.');
		$DebugOutput[] = '';

		$DebugOutput[] = 'get_magic_quotes_gpc()      = '.$this->phpThumbDebugVarDump(@get_magic_quotes_gpc());
		$DebugOutput[] = 'get_magic_quotes_runtime()  = '.$this->phpThumbDebugVarDump(@get_magic_quotes_runtime());
		$DebugOutput[] = 'error_reporting()           = '.$this->phpThumbDebugVarDump(error_reporting());
		$DebugOutput[] = 'ini_get(error_reporting)    = '.$this->phpThumbDebugVarDump(@ini_get('error_reporting'));
		$DebugOutput[] = 'ini_get(display_errors)     = '.$this->phpThumbDebugVarDump(@ini_get('display_errors'));
		$DebugOutput[] = 'ini_get(allow_url_fopen)    = '.$this->phpThumbDebugVarDump(@ini_get('allow_url_fopen'));
		$DebugOutput[] = 'ini_get(disable_functions)  = '.$this->phpThumbDebugVarDump(@ini_get('disable_functions'));
		$DebugOutput[] = 'ini_get(safe_mode)          = '.$this->phpThumbDebugVarDump(@ini_get('safe_mode'));
		$DebugOutput[] = 'ini_get(open_basedir)       = '.$this->phpThumbDebugVarDump(@ini_get('open_basedir'));
		$DebugOutput[] = 'ini_get(memory_limit)       = '.$this->phpThumbDebugVarDump(@ini_get('memory_limit'));
		$DebugOutput[] = 'ini_get(max_execution_time) = '.$this->phpThumbDebugVarDump(@ini_get('max_execution_time'));
		$DebugOutput[] = 'get_cfg_var(memory_limit)   = '.$this->phpThumbDebugVarDump(@get_cfg_var('memory_limit'));
		$DebugOutput[] = 'memory_get_usage()          = '.(function_exists('memory_get_usage') ? $this->phpThumbDebugVarDump(@memory_get_usage()) : 'n/a');
		$DebugOutput[] = '';

		$DebugOutput[] = '$this->config_imagemagick_path              = '.$this->phpThumbDebugVarDump($this->config_imagemagick_path);
		$DebugOutput[] = 'SafeBackTick(which convert)                 = '.trim(phpthumb_functions::SafeBackTick('which convert'));
		$IMpathUsed = ($this->config_imagemagick_path ? $this->config_imagemagick_path : trim(phpthumb_functions::SafeBackTick('which convert')));
		$DebugOutput[] = '[actual ImageMagick path used]              = '.$this->phpThumbDebugVarDump($IMpathUsed);
		$DebugOutput[] = 'file_exists([actual ImageMagick path used]) = '.$this->phpThumbDebugVarDump(file_exists($IMpathUsed));
		$DebugOutput[] = 'ImageMagickVersion()                        = '.$this->ImageMagickVersion();
		$DebugOutput[] = '';

		$DebugOutput[] = '$this->config_cache_directory               = '.$this->phpThumbDebugVarDump($this->config_cache_directory);
		$DebugOutput[] = '$this->config_cache_disable_warning         = '.$this->phpThumbDebugVarDump($this->config_cache_disable_warning);
		$DebugOutput[] = '$this->config_cache_maxage                  = '.$this->phpThumbDebugVarDump($this->config_cache_maxage);
		$DebugOutput[] = '$this->config_cache_maxsize                 = '.$this->phpThumbDebugVarDump($this->config_cache_maxsize);
		$DebugOutput[] = '$this->config_cache_maxfiles                = '.$this->phpThumbDebugVarDump($this->config_cache_maxfiles);
		$DebugOutput[] = '$this->cache_filename                       = '.$this->phpThumbDebugVarDump($this->cache_filename);
		$DebugOutput[] = 'is_writable($this->config_cache_directory)  = '.$this->phpThumbDebugVarDump(is_writable($this->config_cache_directory));
		$DebugOutput[] = 'is_writable($this->cache_filename)          = '.(file_exists($this->cache_filename) ? $this->phpThumbDebugVarDump(is_writable($this->cache_filename)) : 'n/a');
		$DebugOutput[] = '';

		$DebugOutput[] = '$this->config_document_root                 = '.$this->phpThumbDebugVarDump($this->config_document_root);
		$DebugOutput[] = '$this->config_temp_directory                = '.$this->phpThumbDebugVarDump($this->config_temp_directory);
		$DebugOutput[] = '';
		$DebugOutput[] = '$this->config_output_format                 = '.$this->phpThumbDebugVarDump($this->config_output_format);
		$DebugOutput[] = '$this->config_output_maxwidth               = '.$this->phpThumbDebugVarDump($this->config_output_maxwidth);
		$DebugOutput[] = '$this->config_output_maxheight              = '.$this->phpThumbDebugVarDump($this->config_output_maxheight);
		$DebugOutput[] = '';
		$DebugOutput[] = '$this->config_error_message_image_default   = '.$this->phpThumbDebugVarDump($this->config_error_message_image_default);
		$DebugOutput[] = '$this->config_error_bgcolor                 = '.$this->phpThumbDebugVarDump($this->config_error_bgcolor);
		$DebugOutput[] = '$this->config_error_textcolor               = '.$this->phpThumbDebugVarDump($this->config_error_textcolor);
		$DebugOutput[] = '$this->config_error_fontsize                = '.$this->phpThumbDebugVarDump($this->config_error_fontsize);
		$DebugOutput[] = '$this->config_error_die_on_error            = '.$this->phpThumbDebugVarDump($this->config_error_die_on_error);
		$DebugOutput[] = '$this->config_error_die_on_error            = '.$this->phpThumbDebugVarDump($this->config_error_die_on_error);
		$DebugOutput[] = '$this->config_error_silent_die_on_error     = '.$this->phpThumbDebugVarDump($this->config_error_silent_die_on_error);
		$DebugOutput[] = '$this->config_error_die_on_source_failure   = '.$this->phpThumbDebugVarDump($this->config_error_die_on_source_failure);
		$DebugOutput[] = '';
		$DebugOutput[] = '$this->config_nohotlink_enabled             = '.$this->phpThumbDebugVarDump($this->config_nohotlink_enabled);
		$DebugOutput[] = '$this->config_nohotlink_valid_domains       = '.$this->phpThumbDebugVarDump($this->config_nohotlink_valid_domains);
		$DebugOutput[] = '$this->config_nohotlink_erase_image         = '.$this->phpThumbDebugVarDump($this->config_nohotlink_erase_image);
		$DebugOutput[] = '$this->config_nohotlink_text_message        = '.$this->phpThumbDebugVarDump($this->config_nohotlink_text_message);
		$DebugOutput[] = '';
		$DebugOutput[] = '$this->config_nooffsitelink_enabled         = '.$this->phpThumbDebugVarDump($this->config_nooffsitelink_enabled);
		$DebugOutput[] = '$this->config_nooffsitelink_valid_domains   = '.$this->phpThumbDebugVarDump($this->config_nooffsitelink_valid_domains);
		$DebugOutput[] = '$this->config_nooffsitelink_require_refer   = '.$this->phpThumbDebugVarDump($this->config_nooffsitelink_require_refer);
		$DebugOutput[] = '$this->config_nooffsitelink_erase_image     = '.$this->phpThumbDebugVarDump($this->config_nooffsitelink_erase_image);
		$DebugOutput[] = '$this->config_nooffsitelink_text_message    = '.$this->phpThumbDebugVarDump($this->config_nooffsitelink_text_message);
		$DebugOutput[] = '';
		$DebugOutput[] = '$this->config_max_source_pixels             = '.$this->phpThumbDebugVarDump($this->config_max_source_pixels);
		$DebugOutput[] = '$this->config_use_exif_thumbnail_for_speed  = '.$this->phpThumbDebugVarDump($this->config_use_exif_thumbnail_for_speed);
		$DebugOutput[] = '$this->config_output_allow_enlarging        = '.$this->phpThumbDebugVarDump($this->config_output_allow_enlarging);
		$DebugOutput[] = '$this->config_border_hexcolor               = '.$this->phpThumbDebugVarDump($this->config_border_hexcolor);
		$DebugOutput[] = '$this->config_background_hexcolor           = '.$this->phpThumbDebugVarDump($this->config_background_hexcolor);
		$DebugOutput[] = '$this->config_ttf_directory                 = '.$this->phpThumbDebugVarDump($this->config_ttf_directory);
		$DebugOutput[] = '';

		foreach ($OtherVariableNames as $varname) {
			$value = $this->$varname;
			$DebugOutput[] = '$this->'.str_pad($varname, 27, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
		}
		$DebugOutput[] = 'strlen($this->rawImageData)        = '.strlen(@$this->rawImageData);
		$DebugOutput[] = 'strlen($this->exif_thumbnail_data) = '.strlen(@$this->exif_thumbnail_data);
		$DebugOutput[] = '';

		foreach ($ParameterNames as $varname) {
			$value = $this->$varname;
			$DebugOutput[] = '$this->'.str_pad($varname, 4, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
		}
		$DebugOutput[] = '';

		foreach ($FunctionsExistance as $functionname) {
			$DebugOutput[] = 'builtin_function_exists('.$functionname.')'.str_repeat(' ', 23 - strlen($functionname)).' = '.$this->phpThumbDebugVarDump(phpthumb_functions::builtin_function_exists($functionname));
		}
		$DebugOutput[] = '';

		$gd_info = phpthumb_functions::gd_info();
		foreach ($gd_info as $key => $value) {
			$DebugOutput[] = 'gd_info.'.str_pad($key, 34, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
		}
		$DebugOutput[] = '';

		$exif_info = phpthumb_functions::exif_info();
		foreach ($exif_info as $key => $value) {
			$DebugOutput[] = 'exif_info.'.str_pad($key, 26, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
		}
		$DebugOutput[] = '';

		if ($ApacheLookupURIarray = phpthumb_functions::ApacheLookupURIarray(dirname(@$_SERVER['PHP_SELF']))) {
			foreach ($ApacheLookupURIarray as $key => $value) {
				$DebugOutput[] = 'ApacheLookupURIarray.'.str_pad($key, 15, ' ', STR_PAD_RIGHT).' = '.$this->phpThumbDebugVarDump($value);
			}
		} else {
				$DebugOutput[] = 'ApacheLookupURIarray() -- FAILED';
		}
		$DebugOutput[] = '';

		if (isset($_GET) && is_array($_GET)) {
			foreach ($_GET as $key => $value) {
				$DebugOutput[] = '$_GET['.$key.']'.str_repeat(' ', 30 - strlen($key)).'= '.$this->phpThumbDebugVarDump($value);
			}
		}
		if (isset($_POST) && is_array($_POST)) {
			foreach ($_POST as $key => $value) {
				$DebugOutput[] = '$_POST['.$key.']'.str_repeat(' ', 29 - strlen($key)).'= '.$this->phpThumbDebugVarDump($value);
			}
		}
		$DebugOutput[] = '';

		$DebugOutput[] = '$this->debugmessages:';
		foreach ($this->debugmessages as $errorstring) {
			$DebugOutput[] = '  * '.$errorstring;
		}

		return $this->ErrorImage(implode("\n", $DebugOutput), 700, 500);
	}

	function ErrorImage($text, $width=0, $height=0) {
		$width  = ($width  ? $width  : $this->config_error_image_width);
		$height = ($height ? $height : $this->config_error_image_height);

		if ($this->config_disable_debug) {
			$text = 'Error messages disabled';
		}

		$this->DebugMessage($text);
		if (!$this->config_error_die_on_error) {
			$this->fatalerror = $text;
			return false;
		}
		if ($this->config_error_silent_die_on_error) {
			exit;
		}
		if (!empty($this->err) || !empty($this->config_error_message_image_default)) {
			// Show generic custom error image instead of error message
			// for use on production sites where you don't want debug messages
			if (@$this->err == 'showerror') {
				// fall through and actually show error message even if default error image is set
			} else {
				header('Location: '.(!empty($this->err) ? $this->err : $this->config_error_message_image_default));
				exit;
			}
		}
		if (@$this->f == 'text') {
			// bypass all GD functions and output text error message
			die('<PRE>'.$text.'</PRE>');
		}

		$FontWidth  = ImageFontWidth($this->config_error_fontsize);
		$FontHeight = ImageFontHeight($this->config_error_fontsize);

		$LinesOfText = explode("\n", @wordwrap($text, floor($width / $FontWidth), "\n", true));
		$height = max($height, count($LinesOfText) * $FontHeight);

		if (headers_sent()) {

			echo "\n".'**Headers already sent, dumping error message as text:**<br><pre>'."\n\n".$text."\n".'</pre>';

		} elseif ($gdimg_error = ImageCreate($width, $height)) {

			$background_color = phpthumb_functions::ImageHexColorAllocate($gdimg_error, $this->config_error_bgcolor,   true);
			$text_color       = phpthumb_functions::ImageHexColorAllocate($gdimg_error, $this->config_error_textcolor, true);
			ImageFilledRectangle($gdimg_error, 0, 0, $width, $height, $background_color);
			$lineYoffset = 0;
			foreach ($LinesOfText as $line) {
				ImageString($gdimg_error, $this->config_error_fontsize, 2, $lineYoffset, $line, $text_color);
				$lineYoffset += $FontHeight;
			}
			if (function_exists('ImageTypes')) {
				$imagetypes = ImageTypes();
				if ($imagetypes & IMG_PNG) {
					header('Content-type: image/png');
					ImagePNG($gdimg_error);
				} elseif ($imagetypes & IMG_GIF) {
					header('Content-type: image/gif');
					ImageGIF($gdimg_error);
				} elseif ($imagetypes & IMG_JPG) {
					header('Content-type: image/jpeg');
					ImageJPEG($gdimg_error);
				} elseif ($imagetypes & IMG_WBMP) {
					header('Content-type: image/wbmp');
					ImageWBMP($gdimg_error);
				}
			}
			ImageDestroy($gdimg_error);

		}
		if (!headers_sent()) {
			echo "\n".'**Failed to send graphical error image, dumping error message as text:**<br>'."\n\n".$text;
		}
		exit;
		return true;
	}

	function ImageCreateFromStringReplacement(&$RawImageData, $DieOnErrors=false) {
		// there are serious bugs in the non-bundled versions of GD which may cause
		// PHP to segfault when calling ImageCreateFromString() - avoid if at all possible
		// when not using a bundled version of GD2
		$gd_info = phpthumb_functions::gd_info();
		if (strpos($gd_info['GD Version'], 'bundled') !== false) {
			return @ImageCreateFromString($RawImageData);
		}

		switch (substr($RawImageData, 0, 3)) {
			case 'GIF':
				$ICFSreplacementFunctionName = 'ImageCreateFromGIF';
				break;
			case "\xFF\xD8\xFF":
				$ICFSreplacementFunctionName = 'ImageCreateFromJPEG';
				break;
			case "\x89".'PN':
				$ICFSreplacementFunctionName = 'ImageCreateFromPNG';
				break;
			default:
				//if ($DieOnErrors) {
				//	return $this->ErrorImage('Unknown image type identified by "'.substr($RawImageData, 0, 3).'" ('.phpthumb_functions::HexCharDisplay(substr($this->rawImageData, 0, 3)).') in ImageCreateFromStringReplacement()');
				//}
				return false;
				break;
		}
		if ($tempnam = $this->phpThumb_tempnam()) {
			if ($fp_tempnam = @fopen($tempnam, 'wb')) {
				fwrite($fp_tempnam, $RawImageData);
				fclose($fp_tempnam);
				if (($ICFSreplacementFunctionName == 'ImageCreateFromGIF') && !function_exists($ICFSreplacementFunctionName)) {

					// Need to create from GIF file, but ImageCreateFromGIF does not exist
					if (@include_once('phpthumb.gif.php')) {
						// gif_loadFileToGDimageResource() cannot read from raw data, write to file first
						if ($tempfilename = $this->phpThumb_tempnam()) {
							if ($fp_tempfile = @fopen($tempfilename, 'wb')) {
								fwrite($fp_tempfile, $RawImageData);
								fclose($fp_tempfile);
								$gdimg_source = gif_loadFileToGDimageResource($tempfilename);
								unlink($tempfilename);
								return $gdimg_source;
								break;
							} else {
								$ErrorMessage = 'Failed to open tempfile in '.__FILE__.' on line '.__LINE__;
							}
						} else {
							$ErrorMessage = 'Failed to open generate tempfile name in '.__FILE__.' on line '.__LINE__;
						}
					} else {
						$ErrorMessage = 'Failed to include required file "phpthumb.gif.php" in '.__FILE__.' on line '.__LINE__;
					}

				} elseif (function_exists($ICFSreplacementFunctionName) && ($gdimg_source = $ICFSreplacementFunctionName($tempnam))) {

					// great
					unlink($tempnam);
					return $gdimg_source;

				} else { // GD functions not available

					// base64-encoded error image in GIF format
					$ERROR_NOGD = 'R0lGODlhIAAgALMAAAAAABQUFCQkJDY2NkZGRldXV2ZmZnJycoaGhpSUlKWlpbe3t8XFxdXV1eTk5P7+/iwAAAAAIAAgAAAE/vDJSau9WILtTAACUinDNijZtAHfCojS4W5H+qxD8xibIDE9h0OwWaRWDIljJSkUJYsN4bihMB8th3IToAKs1VtYM75cyV8sZ8vygtOE5yMKmGbO4jRdICQCjHdlZzwzNW4qZSQmKDaNjhUMBX4BBAlmMywFSRWEmAI6b5gAlhNxokGhooAIK5o/pi9vEw4Lfj4OLTAUpj6IabMtCwlSFw0DCKBoFqwAB04AjI54PyZ+yY3TD0ss2YcVmN/gvpcu4TOyFivWqYJlbAHPpOntvxNAACcmGHjZzAZqzSzcq5fNjxFmAFw9iFRunD1epU6tsIPmFCAJnWYE0FURk7wJDA0MTKpEzoWAAskiAAA7';
					header('Content-type: image/gif');
					echo base64_decode($ERROR_NOGD);
					exit;

				}
			} else {
				$ErrorMessage = 'Failed to fopen('.$tempnam.', "wb") in '.__FILE__.' on line '.__LINE__."\n".'You may need to set $PHPTHUMB_CONFIG[temp_directory] in phpThumb.config.php';
			}
			unlink($tempnam);
		} else {
			$ErrorMessage = 'Failed to generate phpThumb_tempnam() in '.__FILE__.' on line '.__LINE__."\n".'You may need to set $PHPTHUMB_CONFIG[temp_directory] in phpThumb.config.php';
		}
		if ($DieOnErrors && !empty($ErrorMessage)) {
			return $this->ErrorImage($ErrorMessage);
		}
		return false;
	}

	function phpThumb_tempnam() {
		return tempnam($this->config_temp_directory, 'pThumb');
	}

	function DebugMessage($message, $file='', $line='') {
		$this->debugmessages[] = $message.($file ? ' in file "'.(basename($file) ? basename($file) : $file).'"' : '').($line ? ' on line '.$line : '');
		return true;
	}

}

?>