<?php

namespace Elgg\Filesystem;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\PluginHooksService;

/**
 * Public service related to MIME type detection
 *
 * @since 3.3
 */
class MimeTypeService {

	protected $hooks;
	
	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks Plugin hooks service
	 */
	public function __construct(PluginHooksService $hooks) {
		$this->hooks = $hooks;
	}
	
	/**
	 * Get the mimetype for a given filename
	 *
	 * @param string $filename Filename to check
	 * @param string $default  Default mimetype if not detected (default: application/octet-stream)
	 *
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public function getMimeType(string $filename, string $default = MimeTypeDetector::DEFAULT_TYPE): string {
		if (!is_file($filename) || !is_readable($filename)) {
			throw new InvalidArgumentException("The file '{$filename}' is not a valid file or is not readable");
		}
		
		$detector = new MimeTypeDetector();
		
		$mime = $detector->getType($filename, $default);
		
		$params = [
			'filename' => $filename,
			'original_filename' => basename($filename),
			'default' => $default,
		];
		
		return $this->hooks->trigger('mime_type', 'file', $params, $mime);
	}
	
	/**
	 * Returns the category of a file from its MIME type
	 *
	 * @param string $mimetype The MIME type
	 * @param string $default  Default MIME type if detection fails (default: general)
	 *
	 * @return string
	 */
	public function getSimpleType(string $mimetype, string $default = 'general'): string {
		$result = $default;
		
		switch ($mimetype) {
			case 'application/msword':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'application/pdf':
				$result = 'document';
				break;
			case 'application/ogg':
				$result = 'audio';
				break;
		}
		
		$matches = [];
		if (preg_match('~^(audio|image|video)/~', $mimetype, $matches)) {
			$result = $matches[1];
		}
		
		if (0 === strpos($mimetype, 'text/') || false !== strpos($mimetype, 'opendocument')) {
			$result = 'document';
		}
		
		$params = [
			'mime_type' => $mimetype,
		];
		return $this->hooks->trigger('simple_type', 'file', $params, $result);
	}
	
	/**
	 * Returns the category of a file from a filename
	 *
	 * @param string $filename The filename to check
	 * @param string $default  Default MIME type if detection fails (default: general)
	 *
	 * @return string
	 */
	public function getSimpleTypeFromFile(string $filename, string $default = 'general'): string {
		$mimetype = $this->getMimeType($filename);
		
		return $this->getSimpleType($mimetype, $default);
	}
}
