<?php
namespace Elgg\Filesystem;

/**
 * Detect the MIME type of a file
 */
class MimeTypeDetector {

	const DEFAULT_TYPE = 'application/octet-stream';

	/**
	 * @var callable[]
	 */
	public $strategies = [
		[__CLASS__, 'tryFinfo'],
		[__CLASS__, 'tryMimeContentType'],
		[__CLASS__, 'tryFile'],
		[__CLASS__, 'tryGetimagesize'],
	];

	/**
	 * @var bool
	 */
	public $use_extension = true;

	/**
	 * @var array
	 */
	public $extensions = [
		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',

		// images
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',

		// archives
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',

		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',

		// adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',

		// open office
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',

		// office
		'doc' => 'application/msword',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
		'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
		'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
		'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
		'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
		'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
		'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
		'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
		'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
		'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
		'sldm' => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
		'one' => 'application/msonenote',
		'onetoc2' => 'application/msonenote',
		'onetmp' => 'application/msonenote',
		'onepkg' => 'application/msonenote',
		'thmx' => 'application/vnd.ms-officetheme',
	];

	/**
	 * Sniff the MIME type
	 *
	 * @param string $file    File path
	 * @param string $default Default type to return on failure
	 * @return string MIME type
	 */
	public function getType($file, $default = self::DEFAULT_TYPE) {
		// Check only existing files
		if (!is_file($file) || !is_readable($file)) {
			return $default;
		}

		$ext = pathinfo($file, PATHINFO_EXTENSION);

		$type = $this->tryStrategies($file);

		if ($type) {
			return $this->fixDetectionErrors($type, $ext);
		}

		if ($this->use_extension && isset($this->extensions[$ext])) {
			return $this->extensions[$ext];
		}

		return $default;
	}

	/**
	 * Detect MIME type using various strategies
	 *
	 * @param string $file File path
	 * @return string Type detected. Empty string on failure
	 */
	public function tryStrategies($file) {
		$type = '';
		foreach ($this->strategies as $strategy) {
			$type = call_user_func($strategy, $file);
			if ($type) {
				break;
			}
		}
		return $type;
	}

	/**
	 * Fix common type detection errors
	 *
	 * @param string $type      MIME type detected
	 * @param string $extension Filename extensions
	 * @return string Fixed MIME type
	 */
	public function fixDetectionErrors($type, $extension) {
		if ($type === 'application/zip') {
			// hack for Microsoft zipped formats
			switch ($extension) {
				case 'docx':
					return "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
				case 'xlsx':
					return "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
				case 'pptx':
					return "application/vnd.openxmlformats-officedocument.presentationml.presentation";
			}
		}

		// check for bad ppt detection
		if ($type === "application/vnd.ms-office" && $extension === "ppt") {
			return "application/vnd.ms-powerpoint";
		}
		
		// try extension detection as a fallback for octet-stream
		if ($type === "application/octet-stream" && $this->use_extension && isset($this->extensions[$extension])) {
			return $this->extensions[$extension];
		}

		return $type;
	}

	/**
	 * Detect MIME type using finfo_open
	 *
	 * @param string $file File path
	 * @return string Type detected. Empty string on failure
	 */
	public static function tryFinfo($file) {
		if (!function_exists('finfo_open')) {
			return '';
		}

		$finfo = finfo_open(FILEINFO_MIME);
		$type = finfo_file($finfo, $file);
		finfo_close($finfo);
		// Mimetype can come in text/plain; charset=us-ascii form
		if (strpos($type, ';')) {
			list($type,) = explode(';', $type);
		}
		return $type;
	}

	/**
	 * Detect MIME type using mime_content_type
	 *
	 * @param string $file File path
	 * @return string Type detected. Empty string on failure
	 */
	public static function tryMimeContentType($file) {
		return function_exists('mime_content_type') ? mime_content_type($file) : '';
	}

	/**
	 * Detect MIME type using file(1)
	 *
	 * @param string $file File path
	 * @return string Type detected. Empty string on failure
	 */
	public static function tryFile($file) {
		if (DIRECTORY_SEPARATOR !== '/' || !function_exists('exec')) {
			return '';
		}
		$type = @exec("file -b --mime-type " . escapeshellarg($file));
		return $type ? $type : '';
	}

	/**
	 * Detect MIME type
	 *
	 * @param string $file File path
	 * @return string Type detected. Empty string on failure
	 */
	public static function tryGetimagesize($file) {
		$data = @getimagesize($file);
		return empty($data['mime']) ? '' : $data['mime'];
	}
}
