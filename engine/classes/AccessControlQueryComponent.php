<?php
/**
 * The access control component of the query class.
 *
 * @todo probably remove.
 * @access private
 * @package Elgg.Core
 * @subpackage Unimplemented
 */
class AccessControlQueryComponent extends QueryComponent {
	/**
	 * Construct the ACL.
	 *
	 * @param string $acl_table The table where the access control field is.
	 * @param string $acl_field The field containing the access control.
	 * @param string $object_owner_table The table containing the owner information for the stuff you're retrieving.
	 * @param string $object_owner_id_field The field in $object_owner_table containing the owner information
	 */
	function __construct($acl_table = "entities", $acl_field = "access_id", $object_owner_table = "entities", $object_owner_id_field = "owner_guid") {
		global $CONFIG;

		$this->acl_table = $CONFIG->dbprefix . sanitise_string($acl_table);
		$this->acl_field = sanitise_string($acl_field);
		$this->object_owner_table = $CONFIG->dbprefix . sanitise_string($object_owner_table);
		$this->object_owner_id_field = sanitise_string($object_owner_id_field);
	}

	function __toString() {
		//$access = get_access_list();
		// KJ - changed to use get_access_sql_suffix
		// Note: currently get_access_sql_suffix is hardwired to use
		// $acl_field = "access_id", $object_owner_table = $acl_table, and
		// $object_owner_id_field = "owner_guid"
		// @todo recode get_access_sql_suffix to make it possible to specify alternate field names
		return "and ".get_access_sql_suffix($this->acl_table); // Add access controls

		//return "and ({$this->acl_table}.{$this->acl_field} in {$access} or ({$this->acl_table}.{$this->acl_field} = 0 and {$this->object_owner_table}.{$this->object_owner_id_field} = {$_SESSION['id']}))";
	}
}