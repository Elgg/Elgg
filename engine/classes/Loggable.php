<?php
/**
 * Interface that provides an interface which must be implemented by all objects wishing to be
 * recorded in the system log (and by extension the river).
 *
 * This interface defines a set of methods that permit the system log functions to
 * hook in and retrieve the necessary information and to identify what events can
 * actually be logged.
 *
 * To have events involving your object to be logged simply implement this interface.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Loggable
 */
interface Loggable {
	/**
	 * Return an identification for the object for storage in the system log.
	 * This id must be an integer.
	 *
	 * @return int
	 */
	public function getSystemLogID();

	/**
	 * Return the class name of the object.
	 * Added as a function because get_class causes errors for some reason.
	 *
	 * @return string
	 */
	public function getClassName();

	/**
	 * Return the type of the object - eg. object, group, user, relationship, metadata, annotation etc
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Return a subtype. For metadata & annotations this is the 'name' and for relationship this is the
	 * relationship type.
	 *
	 * @return string
	 */
	public function getSubtype();

	/**
	 * For a given ID, return the object associated with it.
	 * This is used by the river functionality primarily.
	 * This is useful for checking access permissions etc on objects.
	 *
	 * @param int $id GUID of an entity
	 *
	 * @return ElggEntity
	 */
	public function getObjectFromID($id);

	/**
	 * Return the GUID of the owner of this object.
	 *
	 * @return int
	 * @deprecated 1.8 Use getOwnerGUID() instead
	 */
	public function getObjectOwnerGUID();
}
