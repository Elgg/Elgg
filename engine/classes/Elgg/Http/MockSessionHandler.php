<?php
namespace Elgg\Http;

/**
 * Mock session handler
 *
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Http
 */
class MockSessionHandler implements \Elgg\Http\SessionHandler {

	/**
	 * {@inheritDoc}
	 */
	public function open($save_path, $name) {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function read($session_id) {
		return '';
	}

	/**
	 * {@inheritDoc}
	 */
	public function write($session_id, $session_data) {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function close() {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function destroy($session_id) {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function gc($max_lifetime) {
		return true;
	}

}

