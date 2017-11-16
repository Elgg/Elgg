<?php
/**
 * Private settings for entities
 * Private settings provide metadata like storage of settings for plugins
 * and users.
 *
 * @package Elgg.Core
 * @subpackage PrivateSettings
 */


/**
 * Gets a private setting for an entity.
 *
 * Plugin authors can set private data on entities.  By default
 * private data will not be searched or exported.
 *
 * @note Internal: Private data is used to store settings for plugins
 * and user settings.
 *
 * @param int    $entity_guid The entity GUID
 * @param string $name        The name of the setting
 *
 * @return mixed The setting value, or null if does not exist
 * @see set_private_setting()
 * @see get_all_private_settings()
 * @see remove_private_setting()
 * @see remove_all_private_settings()
 */
function get_private_setting($entity_guid, $name) {
	return _elgg_services()->privateSettings->get($entity_guid, $name);
}

/**
 * Return an array of all private settings.
 *
 * @param int $entity_guid The entity GUID
 *
 * @return string[] empty array if no settings
 * @see set_private_setting()
 * @see get_private_settings()
 * @see remove_private_setting()
 * @see remove_all_private_settings()
 */
function get_all_private_settings($entity_guid) {
	return _elgg_services()->privateSettings->getAll($entity_guid);
}

/**
 * Sets a private setting for an entity.
 *
 * @param int    $entity_guid The entity GUID
 * @param string $name        The name of the setting
 * @param string $value       The value of the setting
 *
 * @return bool
 * @see get_private_setting()
 * @see get_all_private_settings()
 * @see remove_private_setting()
 * @see remove_all_private_settings()
 */
function set_private_setting($entity_guid, $name, $value) {
	return _elgg_services()->privateSettings->set($entity_guid, $name, $value);
}

/**
 * Deletes a private setting for an entity.
 *
 * @param int    $entity_guid The Entity GUID
 * @param string $name        The name of the setting
 *
 * @return bool
 * @see get_private_setting()
 * @see get_all_private_settings()
 * @see set_private_setting()
 * @see remove_all_private_settings()
 */
function remove_private_setting($entity_guid, $name) {
	return _elgg_services()->privateSettings->remove($entity_guid, $name);
}

/**
 * Deletes all private settings for an entity.
 *
 * @param int $entity_guid The Entity GUID
 *
 * @return bool
 * @see get_private_setting()
 * @see get_all_private_settings()
 * @see set_private_setting()
 * @see remove_private_settings()
 */
function remove_all_private_settings($entity_guid) {
	return _elgg_services()->privateSettings->removeAllForEntity($entity_guid);
}
