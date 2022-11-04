<?php

namespace Elgg\Http;

/**
 * Download response builder
 *
 * @since 5.0
 */
class DownloadResponse extends OkResponse {
	
	/**
	 * {@inheritDoc}
	 */
	public function getHeaders() {
		$headers = parent::getHeaders();
		
		if (!isset($headers['Content-Type'])) {
			$headers['Content-Type'] = 'application/octet-stream; charset=utf-8';
		}
		
		if (!isset($headers['Cache-Control'])) {
			$headers['Cache-Control'] = 'no-store';
		}
		
		if (!isset($headers['Content-Disposition'])) {
			$headers['Content-Disposition'] = 'attachment';
		}
		
		if (!empty($this->content) && !isset($headers['Content-Length'])) {
			$headers['Content-Length'] = strlen((string) $this->content);
		}
		
		return $headers;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function setForwardURL(string $forward_url = REFERRER) {
		return $this;
	}
	
	/**
	 * Set the filename for the download
	 *
	 * This will only be applied if the 'Content-Disposition' header isn't already set
	 *
	 * @param string $filename The filename when downloaded
	 * @param bool   $inline   Is this an inline download (default: false, determines the 'Content-Disposition' header)
	 *
	 * @return self
	 */
	public function setFilename(string $filename = '', bool $inline = false): static {
		if (isset($this->headers['Content-Disposition'])) {
			return $this;
		}
		
		$disposition = $inline ? 'inline' : 'attachment';
		
		if (!elgg_is_empty($filename)) {
			$disposition .= "; filename=\"{$filename}\"";
		}
		
		$this->headers['Content-Disposition'] = $disposition;
		
		return $this;
	}
}
