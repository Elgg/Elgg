Access Control Lists
####################

An Access Control List (or ACL) can grant one or more users access to an entity or annotation in the database.

.. contents:: Contents
	:local:
	:depth: 1

.. seealso::

	:ref:`Database Access Control <database-access-control>`

Creating an ACL
===============

An access collection can be create by using the function `create_access_collection()`.

.. code-block:: php

	$owner_guid = elgg_get_logged_in_user_guid();

	$acl = create_access_collection("Sample name", $owner_guid, 'collection_subtype');

ACL subtypes
============

ACLs can have a subtype, this is to help differentiate between the usage of the ACL. It's higly recommended to set a subtype
for an ACL.

Elgg core has three examples of subtype usage

- ``group_acl`` an ACL owned by an ``ElggGroup`` which grants group members access to content shared with the group
- ``friends`` an ACL owned by an ``ElggUser`` which grant friends of a user access to content shared with friends
- ``friends_collection`` an ACL owned by an ``ElggUser`` which grant specific friends access to content shared with the ACL

Adding users to an ACL
======================

If you have an ACL you still need to add users to it in order to grant those users access to content with
the `access_id` of the ACLs `id`.

.. code-block:: php

	// creating an ACL
	$owner_guid = elgg_get_logged_in_user_guid();

	$acl_id = create_access_collection("Sample name", $owner_guid, 'collection_subtype');
	
	// add other user (procedural style)
	add_user_to_access_collection($some_user_guid, $acl_id);
	
	// add other user (object oriented style)
	/* @var $acl ElggAccessCollection */
	$acl = get_access_collection($acl_id);
	
	$acl->addMember($some_other_user_guid);

Removing users from an ACL
==========================

If you no longer wish to allow access for a given user in an ACL you can easily remove that user from the list.

.. code-block:: php

	// remove a user from an ACL (procedural style)
	remove_user_from_access_collection($user_guid_to_be_removed, $acl_id);
	
	// remove a user from an ACL (object oriented style)
	/* @var $acl ElggAccessCollection */
	$acl = get_access_collection($acl_id);
	
	$acl->removeMember(user_guid_to_be_removed);

Retrieving an ACL
=================

In order to manage an ACL, or add the ID of an ACL to an access list there are several functions available to 
retrieve an ACL from the database.

.. code-block:: php

	// get ACL based on known id
	$acl = get_access_collection($acl_id);
	
	// get all ACLs of an owner (procedural style)
	$acls = elgg_get_access_collections([
		'owner_guid' => $some_owner_guid,
	]);
	
	// get all ACLs of an owner (object oriented style)
	$acls = $some_owner_entity->getOwnedAccessCollections();
	
	// add a filter for ACL subtype
	// get all ACLs of an owner (procedural style)
	$acls = elgg_get_access_collections([
		'owner_guid' => $some_owner_guid,
		'subtype' => 'some_subtype',
	]);
	
	// get all ACLs of an owner (object oriented style)
	$acls = $some_owner_entity->getOwnedAccessCollections([
		'subtype' => 'some_subtype',
	]);
	
	// get one ACL of an owner (object oriented style)
	// for example the group_acl of an ElggGroup
	// Returns the first ACL owned by the entity with a given subtype
	$acl = $group_entity->getOwnedAccessCollection('group_acl');
	
Read access
===========

The access system of Elgg automaticly adds all the ACLs a user is a member of to the access checks. For example a 
user is a member of a group and is friends with 3 other users, all the corresponding ACLs are added in order to check 
access to entities when retrieving them (eg. listing all blogs).
