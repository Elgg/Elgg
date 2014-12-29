<?php
namespace Elgg\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpFoundation\HeaderBag;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Represents an HTTP request.
 *
 * Some methods were pulled from Symfony. They are
 * Copyright (c) 2004-2013 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * @package    Elgg.Core
 * @subpackage Http
 * @since      1.9.0
 * @access private
 */
class Request extends SymfonyRequest {

	/**
	 * {@inheritDoc}
	 */
	public function initialize(array $query = array(), array $request = array(), array $attributes = array(),
			array $cookies = array(), array $files = array(), array $server = array(), $content = null) {
		$this->request = new ParameterBag($this->stripSlashesIfMagicQuotes($request));
		$this->query = new ParameterBag($this->stripSlashesIfMagicQuotes($query));
		$this->attributes = new ParameterBag($attributes);
		$this->cookies = new ParameterBag($this->stripSlashesIfMagicQuotes($cookies));
		$this->files = new FileBag($files);
		$this->server = new ServerBag($server);
		$this->headers = new HeaderBag($this->server->getHeaders());

		$this->content = $content;
		$this->languages = null;
		$this->charsets = null;
		$this->encodings = null;
		$this->acceptableContentTypes = null;
		$this->pathInfo = null;
		$this->requestUri = null;
		$this->baseUrl = null;
		$this->basePath = null;
		$this->method = null;
		$this->format = null;
	}

	/**
	 * Get URL segments from the path info
	 *
	 * @see \Elgg\Http\Request::getPathInfo()
	 *
	 * @return array
	 */
	public function getUrlSegments() {
		$path = trim($this->query->get('__elgg_uri'), '/');
		if (!$path) {
			return array();
		}

		return explode('/', $path);
	}

	/**
	 * Get first URL segment from the path info
	 *
	 * @see \Elgg\Http\Request::getUrlSegments()
	 *
	 * @return string
	 */
	public function getFirstUrlSegment() {
		$segments = $this->getUrlSegments();
		if ($segments) {
			return array_shift($segments);
		} else {
			return '';
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getClientIp() {
		$ip = parent::getClientIp();

		if ($ip == $this->server->get('REMOTE_ADDR')) {
			// try one more
			$ip_addresses = $this->server->get('HTTP_X_REAL_IP');
			if ($ip_addresses) {
				return array_pop(explode(',', $ip_addresses));
			}
		}

		return $ip;
	}

	/**
	 * Strip slashes if magic quotes is on
	 *
	 * @param mixed $data Data to strip slashes from
	 * @return mixed
	 */
	protected function stripSlashesIfMagicQuotes($data) {
		if (get_magic_quotes_gpc()) {
			return _elgg_stripslashes_deep($data);
		} else {
			return $data;
		}
	}
}
