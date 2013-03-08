<?php

/**
 * Session handler interface
 *
 * Use SessionHandlerInterface when minimum PHP version becomes 5.4
 */
interface Elgg_Http_SessionHandler {

	/**
	 * Re-initialize existing session, or creates a new one.
	 * Called when a session starts or when session_start() is invoked.
	 *
	 * @param string $save_path The path where to store/retrieve the session.
	 * @param string $name      The session name.
	 * @return boolean
	 */
	public function open($save_path, $name);

	/**
	 * Reads the session data from the session storage, and returns the results.
	 *
	 * @param string $session_id The session id.
	 * @return string Returns an encoded string of the read data
	 */
	public function read($session_id);

	/**
	 * Writes the session data to the session storage.
	 *
	 * @param string $session_id   The session id.
	 * @param string $session_data The encoded session data.
	 * @return boolean
	 */
	public function write($session_id, $session_data);

	/**
	 * Closes the current session.
	 *
	 * @return boolean
	 */
	public function close();

	/**
	 * Destroys a session.
	 *
	 * @param string $session_id The session id.
	 * @return boolean
	 */
	public function destroy($session_id);

	/**
	 * Cleans up expired sessions.
	 *
	 * @param int $max_lifetime Sessions not updated for max_lifetime seconds are removed.
	 * @return boolean
	 */
	public function gc($max_lifetime);
}
