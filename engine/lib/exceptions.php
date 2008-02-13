<?php
	/**
	 * Exceptions.
	 * Define some globally useful exception classes.
	 * 
	 * @package Elgg
	 * @subpackage Exceptions
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Top level //////////////////////////////////////////////////////////////////////////////

	/**
	 * @class IOException 
	 * An IO Exception, throw when an IO Exception occurs. Subclass for specific IO Exceptions.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class IOException extends Exception {}

	/**
	 * @class ClassException 
	 * A class Exception, throw when there is a class error.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class ClassException extends Exception {}

	/**
	 * @class ConfigurationException 
	 * There is a configuration error
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class ConfigurationException extends Exception {}

	/**
	 * @class SecurityException 
	 * An Security Exception, throw when a Security Exception occurs. Subclass for specific Security Execeptions (access problems etc)
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class SecurityException extends Exception {}

	/**
	 * @class ClassNotFoundException 
	 * An database exception, throw when a database exception happens, subclass if more detail is needed.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class DatabaseException extends Exception {}

	/**
	 * @class APIException
	 * The API Exception class, thrown by the API layer when an API call has an issue.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class APIException extends Exception {}
	
	// Class exceptions ///////////////////////////////////////////////////////////////////////

	/**
	 * @class InvalidClassException 
	 * An invalid class Exception, throw when a class is invalid.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class InvalidClassException extends ClassException {}

	/**
	 * @class ClassNotFoundException 
	 * An Class not found Exception, throw when an class can not be found occurs.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class ClassNotFoundException extends ClassException {}
	
	// Configuration exceptions ///////////////////////////////////////////////////////////////

	/**
	 * @class InstallationException
	 * Thrown when there is a major problem with the installation.
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 */
	class InstallationException extends ConfigurationException {}
?>