<?php
/**
 * This class defines the interface for all elgg data repositories.
 *
 * @package    Elgg.Core
 * @subpackage DataStorage
 * @class   ElggFilestore
 */
abstract class ElggFilestore {
	/**
	 * Attempt to open the file $file for storage or writing.
	 *
	 * @param ElggFile $file A file
	 * @param string   $mode "read", "write", "append"
	 *
	 * @return mixed A handle to the opened file or false on error.
	 */
	abstract public function open(ElggFile $file, $mode);

	/**
	 * Write data to a given file handle.
	 *
	 * @param mixed  $f    The file handle - exactly what this is depends on the file system
	 * @param string $data The binary string of data to write
	 *
	 * @return int Number of bytes written.
	 */
	abstract public function write($f, $data);

	/**
	 * Read data from a filestore.
	 *
	 * @param mixed $f      The file handle
	 * @param int   $length Length in bytes to read.
	 * @param int   $offset The optional offset.
	 *
	 * @return mixed String of data or false on error.
	 */
	abstract public function read($f, $length, $offset = 0);

	/**
	 * Seek a given position within a file handle.
	 *
	 * @param mixed $f        The file handle.
	 * @param int   $position The position.
	 *
	 * @return void
	 */
	abstract public function seek($f, $position);

	/**
	 * Return a whether the end of a file has been reached.
	 *
	 * @param mixed $f The file handle.
	 *
	 * @return boolean
	 */
	abstract public function eof($f);

	/**
	 * Return the current position in an open file.
	 *
	 * @param mixed $f The file handle.
	 *
	 * @return int
	 */
	abstract public function tell($f);

	/**
	 * Close a given file handle.
	 *
	 * @param mixed $f The file handle
	 *
	 * @return bool
	 */
	abstract public function close($f);

	/**
	 * Delete the file associated with a given file handle.
	 *
	 * @param ElggFile $file The file
	 *
	 * @return bool
	 */
	abstract public function delete(ElggFile $file);

	/**
	 * Return the size in bytes for a given file.
	 *
	 * @param ElggFile $file The file
	 *
	 * @return int
	 */
	abstract public function getFileSize(ElggFile $file);

	/**
	 * Return the filename of a given file as stored on the filestore.
	 *
	 * @param ElggFile $file The file
	 *
	 * @return string
	 */
	abstract public function getFilenameOnFilestore(ElggFile $file);

	/**
	 * Get the filestore's creation parameters as an associative array.
	 * Used for serialisation and for storing the creation details along side a file object.
	 *
	 * @return array
	 */
	abstract public function getParameters();

	/**
	 * Set the parameters from the associative array produced by $this->getParameters().
	 *
	 * @param array $parameters A list of parameters
	 *
	 * @return bool
	 */
	abstract public function setParameters(array $parameters);

	/**
	 * Get the contents of the whole file.
	 *
	 * @param mixed $file The file handle.
	 *
	 * @return mixed The file contents.
	 */
	abstract public function grabFile(ElggFile $file);

	/**
	 * Return whether a file physically exists or not.
	 *
	 * @param ElggFile $file The file
	 *
	 * @return bool
	 */
	abstract public function exists(ElggFile $file);
}
