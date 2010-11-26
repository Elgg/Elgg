<?php
/**
 * Exceptions.
 * Define some globally useful exception classes.
 *
 * @package Elgg
 * @subpackage Exceptions
 */

// Top level //////////////////////////////////////////////////////////////////////////////

/**
 * IOException
 * An IO Exception, throw when an IO Exception occurs. Subclass for specific IO Exceptions.
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class IOException extends Exception {}

/**
 * ClassException
 * A class Exception, throw when there is a class error.
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class ClassException extends Exception {}

/**
 * ConfigurationException
 * There is a configuration error
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class ConfigurationException extends Exception {}

/**
 * SecurityException
 * An Security Exception, throw when a Security Exception occurs. Subclass for specific Security Execeptions (access problems etc)
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class SecurityException extends Exception {}

/**
 * ClassNotFoundException
 * An database exception, throw when a database exception happens, subclass if more detail is needed.
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class DatabaseException extends Exception {}

/**
 * APIException
 * The API Exception class, thrown by the API layer when an API call has an issue.
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class APIException extends Exception {}

/**
 * CallException
 * An exception thrown when there is a problem calling something.
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class CallException extends Exception {}

/**
 * Data format exception
 * An exception thrown when there is a problem in the format of some data.
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class DataFormatException extends Exception {}

// Class exceptions ///////////////////////////////////////////////////////////////////////

/**
 * InvalidClassException
 * An invalid class Exception, throw when a class is invalid.
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class InvalidClassException extends ClassException {}

/**
 * ClassNotFoundException
 * An Class not found Exception, throw when an class can not be found occurs.
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class ClassNotFoundException extends ClassException {}

// Configuration exceptions ///////////////////////////////////////////////////////////////

/**
 * InstallationException
 * Thrown when there is a major problem with the installation.
 *
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
 * @package Elgg
 * @subpackage Exceptions
 */
class NotImplementedException extends CallException {}

/**
 * InvalidParameterException
 * A parameter is invalid.
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class InvalidParameterException extends CallException {}

// Installation exception /////////////////////////////////////////////////////////////////

/**
 * RegistrationException
 * Could not register a new user for whatever reason.
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class RegistrationException extends InstallationException {}