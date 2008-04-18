<?php
	/**
	 * Elgg filestore.
	 * This file contains classes, interfaces and functions for saving and retrieving data to various file 
	 * stores.
	 * 
	 * @package Elgg
	 * @subpackage API
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	include_once("objects.php");
	
	/**
	 * @class ElggFilestore
	 * This class defines the interface for all elgg data repositories.
	 * @author Marcus Povey
	 */
	abstract class ElggFilestore
	{
		/**
		 * Attempt to open the file $file for storage or writing.
		 *
		 * @param ElggFile $file
		 * @param string $mode "read", "write", "append"
		 * @return mixed A handle to the opened file or false on error.
		 */
		abstract public function open(ElggFile $file, $mode);
		
		/**
		 * Write data to a given file handle.
		 *
		 * @param mixed $f The file handle - exactly what this is depends on the file system
		 * @param string $data The binary string of data to write
		 * @return int Number of bytes written.
		 */
		abstract public function write($f, $data);
		
		/**
		 * Read data from a filestore.
		 *
		 * @param mixed $f The file handle
		 * @param int $length Length in bytes to read, or 0 for the entire file.
		 * @param int $offset The optional offset.
		 * @return mixed String of data or false on error.
		 */
		abstract public function read($f, $length = 0, $offset = 0);
		
		/**
		 * Seek a given position within a file handle.
		 * 
		 * @param mixed $f The file handle.
		 * @param int $position The position.
		 */
		abstract public function seek($f, $position);
		
		/**
		 * Close a given file handle.
		 *
		 * @param mixed $f
		 */
		abstract public function close($f);
		
		/**
		 * Delete the file associated with a given file handle.
		 *
		 * @param ElggFile $file
		 */
		abstract public function delete(ElggFile $file);
		
		/**
		 * Get the filestore's creation parameters as an associative array.
		 * Used for serialisation and for storing the creation details along side a file object.
		 * 
		 * @return array
		 */
		abstract public function getParameters();
		
		/**
		 * Set the parameters from the associative array produced by $this->getParameters().
		 */
		abstract public function setParameters(array $parameters);

	}
	
	/**
	 * @class ElggDiskFilestore
	 * This class uses disk storage to save data.
	 * @author Marcus Povey
	 */
	class ElggDiskFilestore extends ElggFilestore
	{
		/**
		 * Directory root.
		 */
		private $dir_root;
		
		/**
		 * Default depth of file directory matrix
		 */
		private $matrix_depth = 5;
		
		/**
		 * Construct a disk filestore using the given directory root.
		 *
		 * @param string $directory_root Root directory, must end in "/"
		 */
		public function __construct($directory_root)
		{
			$this->dir_root = $directory_root;
		}
		
		public function open(ElggFile $file, $mode)
		{
			// Try and create the directory
			try { $this->make_directory_root($this->dir_root); } catch (Exception $e){}
			
			$name = $file->getFilename();
			$matrix = $this->make_file_matrix($name);
			
			switch ($mode)
			{
				case "read" : $mode = "r+b"; break;
				case "write" : $mode = "w+b"; break;
				case "append" : $mode = "a+b"; break;
				default: throw new InvalidParameterException("Unrecognised file mode '$mode'");
			}
			
			return fopen($this->dir_root . $matrix . $name, $mode);
		}
		
		public function write($f, $data)
		{
			return fwrite($f, $data);
		}
		
		public function read($f, $length = 0, $offset = 0)
		{
			if ($offset)
				$this->seek($f, $offset);
				
			return fread($f, $length);
		}
		
		public function close($f)
		{
			return fclose($f);
		}
		
		public function delete(ElggFile $file)
		{
			$name = $file->getFilename();
			$matrix = $this->make_file_matrix($name);
			
			$unlink = unlink($this->dir_root . $matrix . $name);
			if ($unlink)
				return $file->delete();
	
			return false;
		}
		
		public function seek($f, $position)
		{
			return fseek($f, $position);
		}
		
		/**
		 * Make the directory root.
		 *
		 * @param string $dirroot
		 */
		protected function make_directory_root($dirroot)
		{
			if (!mkdir($dir, 0700, true)) 
				throw new IOException("Could not make $dirroot");
				
			return true;
		}
		
		/**
		 * Construct the filename matrix.
		 *
		 * @param string $filename
		 */
		protected function make_file_matrix($filename)
		{
			$matrix = "";
			
			$len = strlen($ident);
			if ($len>$this->matrix_depth)
				$len = $this->matrix_depth;
			
			for ($n = 0; $n < strlen($ident); $n++)
				$matrix .= $ident[$n] . "/";	
	
			return $matrix;
		}
		
		public function getParameters()
		{
			return array("dir_root" => $this->dir_root);
		}
		
		public function setParameters(array $parameters)
		{
			if (isset($parameters['dir_root']))
			{
				$this->dir_root = $parameters['dir_root'];
				return true;
			}
			
			return false;
		}
	}
	
	/**
	 * @class ElggFile
	 * This class represents a physical file.
	 * @author Marcus Povey
	 */
	class ElggFile extends ElggObject
	{
		protected function initialise_attributes()
		{
			parent::initialise_attributes();
			
			$this->attributes['subtype'] = "file";
		}
		
		function __construct($guid = null) 
		{			
			parent::__construct($guid);
			
		}
		
		// TODO: Save filestore & filestore parameters - getparameters, save them as name/value with type "$datastoreclassname"

		// TODO: Set name and optional description
		
		
		//get datastore (save with object as meta/ load from create)
		
		// constrcut

		// initialise (set subtype to elggfile)

		// set name
	
		
		
		// read / write / open / close / delete

		// Get name


		
		// getFilestore
			
			
			// if $filestore is blank, try and get from meta
			// if meta not found or guid is null then get from default
		
	}
	

	/// Variable holding the default datastore
	$DEFAULT_FILE_STORE = NULL;
	
	/**
	 * Return the default filestore.
	 *
	 * @return ElggFilestore
	 */
	function get_default_filestore()
	{
		global $DEFAULT_FILE_STORE;
		
		return $DEFAULT_FILE_STORE;
	}
	
	/**
	 * Set the default filestore for the system.
	 */
	function set_default_filestore(ElggFilestore $filestore)
	{
		global $DEFAULT_FILE_STORE;
		
		$DEFAULT_FILE_STORE = $filestore;
		
		return true;
	}
	
	
	// Now register a default filestore
	set_default_filestore(new ElggDiskFilestore($CONFIG->dataroot));
?>