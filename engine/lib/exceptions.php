<?php
/**
 * Exceptions.
 * Define some globally useful exception classes.
 *
 * @package Elgg
 * @subpackage Exceptions
 * @author Curverider Ltd <info@elgg.com>
 * @link http://elgg.org/
 */

// Top level //////////////////////////////////////////////////////////////////////////////

/**
 * IOException
 * An IO Exception, throw when an IO Exception occurs. Subclass for specific IO Exceptions.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class IOException extends Exception {}

/**
 * ClassException
 * A class Exception, throw when there is a class error.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class ClassException extends Exception {}

/**
 * ConfigurationException
 * There is a configuration error
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class ConfigurationException extends Exception {}

/**
 * SecurityException
 * An Security Exception, throw when a Security Exception occurs. Subclass for specific Security Execeptions (access problems etc)
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class SecurityException extends Exception {}

/**
 * ClassNotFoundException
 * An database exception, throw when a database exception happens, subclass if more detail is needed.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class DatabaseException extends Exception {}

/**
 * APIException
 * The API Exception class, thrown by the API layer when an API call has an issue.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class APIException extends Exception {}

/**
 * CallException
 * An exception thrown when there is a problem calling something.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class CallException extends Exception {}

/**
 * Data format exception
 * An exception thrown when there is a problem in the format of some data.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class DataFormatException extends Exception {}

// Class exceptions ///////////////////////////////////////////////////////////////////////

/**
 * InvalidClassException
 * An invalid class Exception, throw when a class is invalid.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class InvalidClassException extends ClassException {}

/**
 * ClassNotFoundException
 * An Class not found Exception, throw when an class can not be found occurs.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class ClassNotFoundException extends ClassException {}

// Configuration exceptions ///////////////////////////////////////////////////////////////

/**
 * InstallationException
 * Thrown when there is a major problem with the installation.
 *
 * @author Curverider Ltd <info@elgg.com>
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
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class NotImplementedException extends CallException {}

/**
 * InvalidParameterException
 * A parameter is invalid.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class InvalidParameterException extends CallException {}

// Installation exception /////////////////////////////////////////////////////////////////

/**
 * RegistrationException
 * Could not register a new user for whatever reason.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Exceptions
 */
class RegistrationException extends InstallationException {}