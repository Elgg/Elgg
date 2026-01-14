<?php

namespace Elgg\Email;

use Elgg\Exceptions\InvalidArgumentException;
use Symfony\Component\Mime\Part\DataPart;

/**
 * Email attachment
 */
class Attachment extends DataPart {
	
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
	 * @see \Symfony\Component\Mime\Part\DataPart
	 *
	 * @return Attachment return the attachment
	 * @throws InvalidArgumentException
	 */
	public static function factory(mixed $options): static {
		if ($options instanceof \ElggFile) {
			return self::fromElggFile($options);
		}
		
		if (!is_array($options)) {
			throw new InvalidArgumentException(__METHOD__ . ': $options needs to be an array');
		}
		
		if (!isset($options['content']) && !isset($options['filepath'])) {
			throw new InvalidArgumentException(__METHOD__ . ': $options "content" or "filepath" is required');
		}
		
		$content = elgg_extract('content', $options);
		unset($options['content']);
		if (!isset($content)) {
			$filepath = elgg_extract('filepath', $options);
			if (empty($filepath) || !is_file($filepath)) {
				throw new InvalidArgumentException(__METHOD__ . ': $options[filepath] didn\'t result in a valid file');
			}
			
			$content = file_get_contents($filepath);
			
			if (!isset($options['filename'])) {
				$options['filename'] = basename($filepath);
			}
			
			if (!isset($options['type'])) {
				$options['type'] = _elgg_services()->mimetype->getMimeType($filepath);
			}
		}
		
		$filename = $options['filename'] ?? null;
		$content_type = $options['type'] ?? null;
		$encoding = $options['encoding'] ?? null;
		
		$attachment = new self($content, $filename, $content_type, $encoding);
		
		if (isset($options['id'])) {
			$id = $options['id'];
			if (!str_contains($id, '@')) {
				$id .= '@elgg';
			}
			
			$attachment->setContentId($id);
		}
		
		return $attachment;
	}
	
	/**
	 * Create an attachment from an ElggFile
	 *
	 * @param \ElggFile $file the file
	 *
	 * @return Attachment
	 * @throws InvalidArgumentException
	 */
	public static function fromElggFile(\ElggFile $file): static {
		if (!$file->exists()) {
			throw new InvalidArgumentException(__METHOD__ . ': $file doesn\'t exist');
		}
		
		return self::factory([
			'content' => $file->grabFile(),
			'type' => $file->getMimeType(),
			'filename' => basename($file->getFilename()),
		]);
	}
}
