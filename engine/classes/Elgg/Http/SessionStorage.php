<?php
/**
 * Based on Symfony2's SessionStorageInterface and AttributeBagInterface.
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
 * Interface for session storage
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Http
 */
interface Elgg_Http_SessionStorage {

	/**
	 * Starts the session.
	 *
	 * @return boolean True if started.
	 * @throws RuntimeException If something goes wrong starting the session.
	 */
	public function start();

	/**
	 * Checks if the session is started.
	 *
	 * @return boolean True if started, false otherwise.
	 */
	public function isStarted();

	/**
	 * Returns the session ID
	 *
	 * @return string The session ID or empty.
	 */
	public function getId();

	/**
	 * Sets the session ID
	 *
	 * @param string $id Session string
	 * @return void
	 */
	public function setId($id);

	/**
	 * Returns the session name
	 *
	 * @return string The session name.
	 */
	public function getName();

	/**
	 * Sets the session name
	 *
	 * @param string $name Session name.
	 * @return void
	 */
	public function setName($name);

	/**
	 * Regenerates id that represents this storage.
	 *
	 * This method must invoke session_regenerate_id($destroy) unless
	 * this interface is used for a storage object designed for unit
	 * or functional testing where a real PHP session would interfere
	 * with testing.
	 *
	 * Note regenerate+destroy should not clear the session data in memory
	 * only delete the session data from persistent storage.
	 *
	 * @param boolean $destroy  Destroy session when regenerating?
	 * @param integer $lifetime Sets the cookie lifetime for the session cookie. A null value
	 *                          will leave the system settings unchanged, 0 sets the cookie
	 *                          to expire with browser session. Time is in seconds, and is
	 *                          not a Unix timestamp.
	 *
	 * @return boolean True if session regenerated, false if error
	 *
	 * @throws RuntimeException If an error occurs while regenerating this storage
	 */
	public function regenerate($destroy = false, $lifetime = null);

	/**
	 * Force the session to be saved and closed.
	 *
	 * This method must invoke session_write_close() unless this interface is
	 * used for a storage object design for unit or functional testing where
	 * a real PHP session would interfere with testing, in which case it
	 * it should actually persist the session data if required.
	 *
	 * @return void
	 * @throws RuntimeException If the session is saved without being started,
	 *                          or if the session is already closed.
	 */
	public function save();

	/**
	 * Checks if an attribute is defined.
	 *
	 * @param string $name The attribute name
	 *
	 * @return boolean
	 */
	public function has($name);

	/**
	 * Returns an attribute.
	 *
	 * @param string $name    The attribute name
	 * @param mixed  $default The default value if not found.
	 *
	 * @return mixed
	 */
	public function get($name, $default = null);

	/**
	 * Sets an attribute.
	 *
	 * @param string $name  Attribute name
	 * @param mixed  $value Attribute value
	 * @return void
	 */
	public function set($name, $value);

	/**
	 * Returns all attributes.
	 *
	 * @return array Attributes
	 */
	public function all();

	/**
	 * Replaces all attributes
	 *
	 * @param array $attributes Attributes
	 * @return void
	 */
	public function replace(array $attributes);

	/**
	 * Removes an attribute.
	 *
	 * @param string $name Attribute name
	 * @return mixed The removed value
	 */
	public function remove($name);

	/**
	 * Clears all attributes.
	 *
	 * @return void
	 */
	public function clear();
}
