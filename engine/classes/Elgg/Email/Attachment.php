<?php

namespace Elgg\Email;

use Laminas\Mime\Part;
use Laminas\Mime\Mime;

/**
 * Email attachment
 */
class Attachment extends Part {
	
	/**
	 * {@inheritDoc}
	 */
	public function __construct($content = '') {
		parent::__construct($content);
		
		$this->disposition = 'attachment';
		$this->setId(uniqid('attachment'));
	}
	
	/**
	 * Create an attachment
	 *
	 * @param mixed $options an array or an ElggFile, supported array keys are:
	 * 		                 - content:     (string) the file contents of the attachment
	 *		                 - filepath:    (string) if content isn't provided, a filepath can be given to fetch the content from
	 *		                 - filename:    (string) the name of the attachment
	 *		                 - type:        (string) the mimetype
	 *		                 - encoding:    (string) the content encoding
	 *		                 - disposition: (string) the attachment disposition (default: attachment)
	 *		                 - charset:     (string) the charset
	 *
	 * @see \Laminas\Mime\Part
	 *
	 * @return false|\Elgg\Email\Attachment return the attachment or false on error
	 */
	public static function factory($options) {
		
		if ($options instanceof \ElggFile) {
			return self::fromElggFile($options);
		}
		
		if (!is_array($options)) {
			elgg_log(__METHOD__ . ': $options needs to be an array', \Psr\Log\LogLevel::ERROR);
			return false;
		}
		
		if (!isset($options['content']) && !isset($options['filepath'])) {
			elgg_log(__METHOD__ . ': $options "content" or "filepath" is required', \Psr\Log\LogLevel::ERROR);
			return false;
		}
		
		$content = elgg_extract('content', $options);
		unset($options['content']);
		if (!isset($content)) {
			$filepath = elgg_extract('filepath', $options);
			if (empty($filepath) || !is_file($filepath)) {
				elgg_log(__METHOD__ . ': $options[filepath] didn\'t result in a valid file', \Psr\Log\LogLevel::ERROR);
				return false;
			}
			
			$content = file_get_contents($filepath);
			
			$options['encoding'] = Mime::ENCODING_BASE64;
			
			if (!isset($options['filename'])) {
				$options['filename'] = basename($filepath);
			}
			
			if (!isset($options['type'])) {
				$options['type'] = _elgg_services()->mimetype->getMimeType($filepath);
			}
		}
		
		unset($options['filepath']);
		
		$attachment = new self($content);
		
		foreach ($options as $key => $value) {
			$attachment->$key = $value;
		}
		
		return $attachment;
	}
	
	/**
	 * Create an attachment from an ElggFile
	 *
	 * @param \ElggFile $file the file
	 *
	 * @return false|\Elgg\Email\Attachment
	 */
	public static function fromElggFile(\ElggFile $file) {
		
		if (!$file->exists()) {
			return false;
		}
		
		$options = [
			'content' => $file->grabFile(),
			'type' => $file->getMimeType(),
			'filename' => basename($file->getFilename()),
			'encoding' => Mime::ENCODING_BASE64,
		];
		
		return self::factory($options);
	}
}
