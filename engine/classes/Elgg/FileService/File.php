<?php

namespace Elgg\FileService;

use Elgg\Security\Base64Url;

/**
 * File service
 *
 * @access private
 */
class File {

	const INLINE = 'inline';
	const ATTACHMENT = 'attachment';

	/**
	 * @var \ElggFile
	 */
	private $file;

	/**
	 * @var int
	 */
	private $expires;

	/**
	 * @var string
	 */
	private $disposition;

	/**
	 * @var bool
	 */
	private $use_cookie = true;

	/**
	 * Set file object
	 *
	 * @param \ElggFile $file File object
	 * @return void
	 */
	public function setFile(\ElggFile $file) {
		$this->file = $file;
	}

	/**
	 * Returns file object
	 * @return \ElggFile|null
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Sets URL expiration
	 *
	 * @param int $expires String suitable for strtotime()
	 * @return void
	 */
	public function setExpires($expires = '+2 hours') {
		$this->expires = strtotime($expires);
	}

	/**
	 * Sets content disposition
	 *
	 * @param string $disposition Content disposition ('inline' or 'attachment')
	 * @return void
	 */
	public function setDisposition($disposition = self::ATTACHMENT) {
		if (!in_array($disposition, [self::ATTACHMENT, self::INLINE])) {
			throw new \InvalidArgumentException("Disposition $disposition is not supported in " . __CLASS__);
		}
		$this->disposition = $disposition;
	}

	/**
	 * Bind URL to current user session
	 *
	 * @param bool $use_cookie Use cookie
	 * @return void
	 */
	public function bindSession($use_cookie = true) {
		$this->use_cookie = $use_cookie;
	}

	/**
	 * Returns publicly accessible URL
	 * @return string|false
	 */
	public function getURL() {

		if (!$this->file instanceof \ElggFile || !$this->file->exists()) {
			elgg_log("Unable to resolve resource URL for a file that does not exist on filestore");
			return false;
		}

		$relative_path = '';
		$root_prefix = _elgg_config()->dataroot;
		$path = $this->file->getFilenameOnFilestore();
		if (substr($path, 0, strlen($root_prefix)) == $root_prefix) {
			$relative_path = substr($path, strlen($root_prefix));
		}

		if (!$relative_path) {
			elgg_log("Unable to resolve relative path of the file on the filestore");
			return false;
		}

		if (preg_match('~[^a-zA-Z0-9_\./ ]~', $relative_path)) {
			// Filenames may contain special characters that result in malformatted URLs
			// and/or HMAC mismatches. We want to avoid that by encoding the path.
			$relative_path = ':' . Base64Url::encode($relative_path);
		}

		$data = [
			'expires' => isset($this->expires) ? $this->expires : 0,
			'last_updated' => filemtime($this->file->getFilenameOnFilestore()),
			'disposition' => $this->disposition == self::INLINE ? 'i' : 'a',
			'path' => $relative_path,
		];

		if ($this->use_cookie) {
			$data['cookie'] = _elgg_services()->session->getId();
			if (empty($data['cookie'])) {
				return false;
			}
			$data['use_cookie'] = 1;
		} else {
			$data['use_cookie'] = 0;
		}

		ksort($data);
		$mac = _elgg_services()->hmac->getHmac($data)->getToken();

		$url_segments = [
			'serve-file',
			"e{$data['expires']}",
			"l{$data['last_updated']}",
			"d{$data['disposition']}",
			"c{$data['use_cookie']}",
			$mac,
			$relative_path,
		];

		return elgg_normalize_url(implode('/', $url_segments));
	}
}
