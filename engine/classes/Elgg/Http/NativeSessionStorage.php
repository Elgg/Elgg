<?php

/**
 * Based on Symfony2's NativeSessionStorage.
 *
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
 */

/**
 * PHP Session Storage
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Http
 */
class Elgg_Http_NativeSessionStorage implements Elgg_Http_SessionStorage {

	/** @var boolean */
	protected $started = false;

	/** @var boolean */
	protected $closed = false;

	/**
	 * Constructor
	 *
	 * List of options for $options array with their defaults.
	 * @see http://php.net/session.configuration for options
	 * but we omit 'session.' from the beginning of the keys for convenience.
	 *
	 * ("auto_start", is not supported as it tells PHP to start a session before
	 * PHP starts to execute user-land code. Setting during runtime has no effect).
	 *
	 * cache_limiter, "nocache" (use "0" to prevent headers from being sent entirely).
	 * cookie_domain, ""
	 * cookie_httponly, ""
	 * cookie_lifetime, "0"
	 * cookie_path, "/"
	 * cookie_secure, ""
	 * entropy_file, ""
	 * entropy_length, "0"
	 * gc_divisor, "100"
	 * gc_maxlifetime, "1440"
	 * gc_probability, "1"
	 * hash_bits_per_character, "4"
	 * hash_function, "0"
	 * referer_check, ""
	 * serialize_handler, "php"
	 * use_cookies, "1"
	 * use_only_cookies, "1"
	 * use_trans_sid, "0"
	 * upload_progress.enabled, "1"
	 * upload_progress.cleanup, "1"
	 * upload_progress.prefix, "upload_progress_"
	 * upload_progress.name, "PHP_SESSION_UPLOAD_PROGRESS"
	 * upload_progress.freq, "1%"
	 * upload_progress.min-freq, "1"
	 *
	 * @param array                    $options Session config options
	 * @param Elgg_Http_SessionHandler $handler Session handler
	 */
	public function __construct(array $options = array(), Elgg_Http_SessionHandler $handler = null) {
		$this->setOptions($options);
		$this->setHandler($handler);
	}

	/**
	 * {@inheritdoc}
	 */
	public function start() {
		if ($this->started && !$this->closed) {
			return true;
		}

		if (!session_start()) {
			throw new RuntimeException('Failed to start the session');
		}

		$this->started = true;
		$this->closed = false;

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function regenerate($destroy = false, $lifetime = null) {
		if (null !== $lifetime) {
			ini_set('session.cookie_lifetime', $lifetime);
		}

		return session_regenerate_id($destroy);
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {
		session_write_close();

		$this->closed = true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isStarted() {
		return $this->started;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId() {
		if (!$this->started) {
			return ''; // returning empty is consistent with session_id() behavior
		}

		return session_id();
	}

	/**
	 * {@inheritdoc}
	 */
	public function setId($id) {
		if ($this->started) {
			throw new RuntimeException('Cannot change the ID of an active session');
		}

		session_id($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return session_name();
	}

	/**
	 * {@inheritdoc}
	 */
	public function setName($name) {
		session_name($name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function has($name) {
		if (!$this->started) {
			$this->start();
		}

		return array_key_exists($name, $_SESSION);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($name, $default = null) {
		if (!$this->started) {
			$this->start();
		}
		return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : $default;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set($name, $value) {
		if (!$this->started) {
			$this->start();
		}
		$_SESSION[$name] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function all() {
		if (!$this->started) {
			$this->start();
		}
		return $_SESSION;
	}

	/**
	 * {@inheritdoc}
	 */
	public function replace(array $attributes) {
		if (!$this->started) {
			$this->start();
		}
		$_SESSION = array();
		foreach ($attributes as $key => $value) {
			$this->set($key, $value);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function remove($name) {
		if (!$this->started) {
			$this->start();
		}
		$retval = null;
		if (array_key_exists($name, $_SESSION)) {
			$retval = $_SESSION[$name];
			unset($_SESSION[$name]);
		}

		return $retval;
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear() {
		if (!$this->started) {
			$this->start();
		}
		$_SESSION = array();
	}

	/**
	 * Sets session.* ini variables.
	 *
	 * For convenience we omit 'session.' from the beginning of the keys.
	 * Explicitly ignores other ini keys.
	 *
	 * @param array $options Session ini directives array(key => value).
	 * @return void
	 * @see http://php.net/session.configuration
	 */
	protected function setOptions(array $options) {
		$validOptions = array_flip(array(
			'cache_limiter', 'cookie_domain', 'cookie_httponly',
			'cookie_lifetime', 'cookie_path', 'cookie_secure',
			'entropy_file', 'entropy_length', 'gc_divisor',
			'gc_maxlifetime', 'gc_probability', 'hash_bits_per_character',
			'hash_function', 'name', 'referer_check',
			'serialize_handler', 'use_cookies',
			'use_only_cookies', 'use_trans_sid', 'upload_progress.enabled',
			'upload_progress.cleanup', 'upload_progress.prefix', 'upload_progress.name',
			'upload_progress.freq', 'upload_progress.min-freq', 'url_rewriter.tags',
		));

		foreach ($options as $key => $value) {
			if (isset($validOptions[$key])) {
				ini_set('session.' . $key, $value);
			}
		}
	}

	/**
	 * Set the session handler class with PHP
	 *
	 * @param Elgg_Http_SessionHandler $handler Handler object
	 * @return void
	 */
	protected function setHandler($handler) {
		session_set_save_handler(
			array($handler, 'open'),
			array($handler, 'close'),
			array($handler, 'read'),
			array($handler, 'write'),
			array($handler, 'destroy'),
			array($handler, 'gc'));
	}

}
