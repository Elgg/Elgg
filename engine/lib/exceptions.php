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
	 * IOException 
	 * An IO Exception, throw when an IO Exception occurs. Subclass for specific IO Exceptions.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class IOException extends Exception {}

	/**
	 * ClassException 
	 * A class Exception, throw when there is a class error.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class ClassException extends Exception {}

	/**
	 * ConfigurationException 
	 * There is a configuration error
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class ConfigurationException extends Exception {}

	/**
	 * SecurityException 
	 * An Security Exception, throw when a Security Exception occurs. Subclass for specific Security Execeptions (access problems etc)
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class SecurityException extends Exception {}

	/**
	 * ClassNotFoundException 
	 * An database exception, throw when a database exception happens, subclass if more detail is needed.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class DatabaseException extends Exception {}

	/**
	 * APIException
	 * The API Exception class, thrown by the API layer when an API call has an issue.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class APIException extends Exception {}
	
	/**
	 * CallException
	 * An exception thrown when there is a problem calling something.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class CallException extends Exception {}
	
	/**
	 * Data format exception
	 * An exception thrown when there is a problem in the format of some data.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class DataFormatException extends Exception {}
	
	// Class exceptions ///////////////////////////////////////////////////////////////////////

	/**
	 * InvalidClassException 
	 * An invalid class Exception, throw when a class is invalid.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class InvalidClassException extends ClassException {}

	/**
	 * ClassNotFoundException 
	 * An Class not found Exception, throw when an class can not be found occurs.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class ClassNotFoundException extends ClassException {}
	
	// Configuration exceptions ///////////////////////////////////////////////////////////////

	/**
	 * InstallationException
	 * Thrown when there is a major problem with the installation.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class InstallationException extends ConfigurationException {}
	
	// Call exceptions ////////////////////////////////////////////////////////////////////////

	/**
	 * NotImplementedException
	 * Thrown when a method or function has not been implemented, primarily used in development... you should
	 * not see these!
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class NotImplementedException extends CallException {}
	
	/**
	 * InvalidParameterException
	 * A parameter is invalid.
	 * 
	 * @author Marcus Povey <marcus@dushka.co.uk>
	 * @package Elgg
	 * @subpackage Exceptions
	 */
	class InvalidParameterException extends CallException {}
?>