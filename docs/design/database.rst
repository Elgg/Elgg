Database
########

A thorough discussion of Elgg's data model design and motivation.

.. contents:: Contents
   :local:
   :depth: 2

Overview
========

In Elgg, everything runs on a unified data model based on atomic
units of data called entities.

Plugins are discouraged from interacting directly with the database,
which creates a more stable system and a better user experience becase
content created by different plugins can be mixed together in
consistent ways. With this approach, plugins are faster to develop,
and are at the same time much more powerful.

Every entity in the system inherits the ``ElggEntity`` class. This class
controls access permissions, ownership

.. _thumb\|The Elgg data model diagramIn: image:Elgg_data_model.png

You can extend entities with extra information in two ways:

``Metadata``: This is information describing the entity, usually
   added by the author of the entity when the entity is created.
   For example, tags, an ISBN number, a file location, or
   source language is metadata.
``Annotations``: This is information about the entity, usually
   added by a third party after the entity is created. 
   For example, ratings, likes, and votes are annotations.
   (Comments were before 1.9.)

Datamodel
=========

.. figure:: images/data_model.png
   :figwidth: 650
   :align: center
   :alt: The Elgg data model diagram
   
   The Elgg data model diagram

Entities
========

ElggEntity is the base class for the Elgg data model.

Users, Objects, Groups, Sites
-----------------------------

``ElggEntity`` has four main specializations, which provide extra
properties and methods to more easily handle different kinds of data.

``ElggObject``: content like blog posts, uploaded files and bookmarks
``ElggUser``: a system user
``ElggSite``: each Elgg site within an Elgg installation
``ElggGroup``: multi-user collaborative systems (called "Communities"
in prior versions of Elgg)

The benefit of such an approach is that, apart from modelling data with
greater ease, a common set of functions is available to handle objects,
regardless of their (sub)type.

Each of these have their own properties that they bring to the table:
ElggObjects have a title and description, ElggUsers have a username and
password, and so on. However, because they all inherit ElggEntity, they
each have a number of core properties and behaviours in common.

-  A numeric Globally Unique IDentifier (See `GUIDs`_).
-  Access permissions. (When a plugin requests data, it never gets to
   touch data that the current user doesn't have permission to see.)
-  An arbitrary subtype. For example, a blog post is an ElggObject with
   a subtype of "blog". Subtypes aren't predefined; they can be any
   unique way to describe a particular kind of entity. "blog", "forum",
   "foo", "bar", "loafofbread" and "pyjamas" are all valid subtypes.
-  An owner.
-  The site that the entity belongs to.
-  A container, usually used to associate a group's content with the group.

GUIDs
-----

A GUID is an integer that uniquely identifies every entity in an Elgg
installation (a Globally Unique IDentifier). It's assigned automatically
when the entity is first saved and can never be changed.

Some Elgg API functions work with GUIDs instead of ``ElggEntity`` objects.

ElggObject
==========

The ``ElggObject`` entity type represents arbitrary content within an
Elgg install; things like blog posts, uploaded files, etc.

Beyond the standard ElggEntity properties, ElggObjects also support:

-  ``title`` The title of the object (HTML escaped text)
-  ``description`` A description of the object (HTML)

Most other data about the object is generally stored via metadata.

ElggUser
========

The ``ElggUser`` entity type represents users within an Elgg install.
These will be set to disabled until their accounts have been activated
(unless they were created from within the admin panel).

Beyond the standard ElggEntity properties, ElggUsers also support:

-  ``name`` The user's plain text name. e.g. "Hugh Jackman"
-  ``username`` Their login name. E.g. "hjackman"
-  ``password`` A hashed version of their password
-  ``salt`` The salt that their password has been hashed with
-  ``email`` Their email address
-  ``language`` Their default language code.
-  ``code`` Their session code (moved to a separate table in 1.9).
-  ``last_action`` The UNIX timestamp of the last time they loaded a page
-  ``prev_last_action`` The previous value of ``last_action``
-  ``last_login`` The UNIX timestamp of their last log in
-  ``prev_last_login`` the previous value of ``last_login``

ElggSite
========

The ``ElggSite`` entity type represents sites within your Elgg install.
Most installs will have only one.

Beyond the standard ElggEntity properties, ElggSites also support:

-  ``name`` The site name
-  ``description`` A description of the site
-  ``url`` The address of the site

ElggGroup
=========

The ``ElggGroup`` entity type represents an association of Elgg users.
Users can join, leave, and post content to groups.

Beyond the standard ElggEntity properties, ElggGroups also support:

-  ``name`` The group's name (HTML escaped text)
-  ``description`` A description of the group (HTML)

``ElggGroup`` has addition methods to manage content and membership.

The Groups plugin
-----------------

Not to be confused with the entity type ``ElggGroup``, Elgg comes with
a plugin called "Groups" that provides a default UI/UX for site users
to interact with groups. Each group is given a discussion forum and a
profile page linking users to content within the group.

You can alter the user experience via the traditional means of extending
plugins or completely replace the Groups plugin with your own.

Because ``ElggGroup`` can be subtyped like all other ElggEntities, you
can have multiple types of groups running on the same site.

Writing a group-aware plugin
----------------------------

Plugin owners need not worry too much about writing group-aware
functionality, but there are a few key points:

Adding content
~~~~~~~~~~~~~~

By passing along the group as ``container_guid`` via a hidden input field,
you can use a single form and action to add both user and group content.

Use
`can_write_to_container <http://reference.elgg.org/entities_8php.html#16a972909c7cb75f646cb707be001a6f>`_
to determine whether or not the current user has the right to
add content to a group.

Be aware that you will then need to pass the container GUID
or username to the page responsible for posting and the accompanying
value, so that this can then be stored in your form as a hidden input
field, for easy passing to your actions. Within a "create" action,
you'll need to take in this input field and save it as a property of
your new element (defaulting to the current user's container):

.. code:: php

    $user = elgg_get_logged_in_user_entity();
    $container_guid = (int)get_input('container_guid');
    if ($container_guid) {
        if (!can_write_to_container($user->guid, $container_guid)) {
            // register error and forward
        }
    } else {
        $container_guid = elgg_get_logged_in_user_guid();
    }

    $object = new ElggObject;
    $object->container_guid = $container_guid;

    ...

    $container = get_entity($container_guid);
    forward($container->getURL());

Usernames and page ownership
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Groups have a simulated username of the form *group:\ GUID*, which you
can get the value of by checking ``$group->username``. If you pass this
username to a page on the URL line as part of the ``username`` variable
(i.e., ``/yourpage?username=group:nnn``), Elgg will automatically
register that group as being the owner of the page (unless overridden).

Juggling users and groups
~~~~~~~~~~~~~~~~~~~~~~~~~

In fact, ``[[Engine/DataModel/Entities/ElggGroup|ElggGroup]]`` simulates
most of the methods of
``[[Engine/DataModel/Entities/ElggUser|ElggUser]]``. You can grab the
icon, name etc using the same calls, and if you ask for a group's
friends, you'll get its members. This has been designed specifically for
you to alternate between groups and users in your code easily.

Menu options
~~~~~~~~~~~~

***This section is deprecated as of Elgg 1.8***

The final piece of the puzzle, for default groups, is to add a link to
your functionality from the group's profile. Here we'll use the file
plugin as an example.

This involves creating a view within your plugin - in this case
file/menu - which will extend the group's menu. File/menu consists of a
link within paragraph tags that points to the file repository of the
page\_owner():

.. code:: php

    <p>
      <a href="<?php echo $vars['url']; ?>pg/file/<?php echo page_owner_entity()->username; ?>">
        <?php echo elgg_echo("file"); ?>
      </a>
    </p>

You can then extend the group's menu view with this one, within your
plugin's input function (in this case file\_init):

.. code:: php

    extend_view('groups/menu/links', 'file/menu');

Ownership
=========

Entities have a ``owner_guid`` GUID property, which defines its
owner. Typically this refers to the GUID of a user, although sites and
users themselves often have no owner (a value of 0).

The ownership of an entity dictates, in part, whether or not you can
access or edit that entity.

Containers
==========

In order to easily search content by group or by user, content is generally
set to be "contained" by either the user who posted it, or the group to which
the user posted. This means the new object's ``container_guid`` property
will be set to the GUID of the current ElggUser or the target ElggGroup.

E.g., three blog posts may be owned by different authors, but all be
contained by the group they were posted to.

Note: This is not always true. Comment entities are contained by the object
commented upon, and in some 3rd party plugins the container may be used
to model a parent-child relationship between entities (e.g. a "folder"
object containing a file object).

Annotations
===========

Annotations are pieces of data attached to an entity that allow users
to leave ratings, or other relevant feedback. A poll plugin might
register votes as annotations. Before Elgg 1.9, comments and group
discussion replies were stored as annotations.

Annotations are stored as instances of the ``ElggAnnotation`` class.

Each annotation has:

-  An internal annotation type (like *comment*)
-  A value (which can be a string or integer)
-  An access permission distinct from the entity it's attached to
-  An owner

Adding an annotation
--------------------

The easiest way to annotate is to use the ``annotate`` method on an
entity, which is defined as:

.. code:: php

    public function annotate(
        $name,           // The name of the annotation type (eg 'comment')
        $value,          // The value of the annotation
        $access_id = 0,  // The access level of the annotation
        $owner_id = 0,   // The annotation owner, defaults to current user
        $vartype = ""    // 'text' or 'integer'
    )

For example, to leave a rating on an entity, you might call:

.. code:: php

    $entity->annotate('rating', $rating_value, $entity->access_id);
    
Reading annotations
-------------------

To retrieve annotations on an object, you can call the following method:

.. code:: php

    $annotations = $entity->getAnnotations(
        $name,    // The type of annotation
        $limit,   // The number to return
        $offset,  // Any indexing offset
        $order,   // 'asc' or 'desc' (default 'asc')
    );

If your annotation type largely deals with integer values, a couple of
useful mathematical functions are provided:

.. code:: php

    $averagevalue = $entity->getAnnotationsAvg($name);  // Get the average value
    $total = $entity->getAnnotationsSum($name);         // Get the total value
    $minvalue = $entity->getAnnotationsMin($name);      // Get the minimum value
    $maxvalue = $entity->getAnnotationsMax($name);      // Get the maximum value
    
Useful helper functions
-----------------------

Comments
~~~~~~~~

If you want to provide comment functionality on your plugin objects, the
following function will provide the full listing, form and actions:

.. code:: php

    function elgg_view_comments(ElggEntity $entity)


Metadata
========

Metadata in Elgg allows you to store extra data on an ``entity`` beyond
the built-in fields that entity supports. For example, ``ElggObjects``
only support the basic entity fields plus title and description, but you
might want to include tags or an ISBN number. Similarly, you might want
users to be able to save a date of birth.

Under the hood, metadata is stored as an instance of the
``ElggMetadata`` class, but you don't need to worry about that in
practice (although if you're interested, see the ``ElggMetadata`` class
reference). What you need to know is:

-  Metadata has an owner and access ID, both of which may be different
   to the owner of the entity it's attached to
-  You can potentially have multiple items of each type of metadata
   attached to a single entity

The simple case
---------------

Adding metadata
~~~~~~~~~~~~~~~

To add a piece of metadata to an entity, just call:

.. code:: php

    $entity->metadata_name = $metadata_value;

For example, to add a date of birth to a user:

.. code:: php

    $user->dob = $dob_timestamp;

Or to add a couple of tags to an object:

.. code:: php

    $object->tags = array('tag one', 'tag two', 'tag three');

When adding metadata like this:

-  The owner is set to the currently logged-in user
-  Access permissions are inherited from the entity
-  Reassigning a piece of metadata will overwrite the old value

This is suitable for most purposes. Be careful to note which attributes
are metadata and which are built in to the entity type that you are
working with. You do not need to save an entity after adding or updating
metadata. You do need to save an entity if you have changed one of its
built in attributes. As an example, if you changed the access id of an
ElggObject, you need to save it or the change isn't pushed to the
database.

Reading metadata
~~~~~~~~~~~~~~~~

To retrieve metadata, treat it as a property of the entity:

.. code:: php

    $tags_value = $object->tags;

Note that this will return the absolute value of the metadata. To get
metadata as an ElggMetadata object, you will need to use the methods
described in the *finer control* section below.

If you stored multiple values in this piece of metadata (as in the
"tags" example above), you will get an array of all those values back.
If you stored only one value, you will get a string or integer back.
Storing an array with only one value will return a string back to you.
E.g.

.. code:: php

    $object->tags = array('tag');
    $tags = $object->tags;
    // $tags will be the string "tag", NOT array('tag')

To always get an array back, simply cast to an array;

.. code:: php

    $tags = (array)$object->tags;

Finer control
-------------

Adding metadata
~~~~~~~~~~~~~~~

If you need more control, for example to assign an access ID other than
the default, you can use the ``create_metadata`` function, which is
defined as follows:

.. code:: php

        function create_metadata(
            $entity_guid,           // The GUID of the parent entity
            $name,                  // The name of the metadata (eg 'tags')
            $value,                 // The metadata value
            $value_type,            // Currently either 'string' or 'integer'
            $owner_guid,            // The owner of the metadata
            $access_id = 0,         // The access restriction
            $allow_multiple = false // Do we have more than one value?
            )

For single values, you can therefore write metadata as follows (taking
the example of a date of birth attached to a user):

.. code:: php

    create_metadata($user_guid, 'dob', $dob_timestamp, 'integer', $_SESSION['guid'], $access_id);

For multiple values, you will need to iterate through and call
``create_metadata`` on each one. The following piece of code comes from
the profile save action:

.. code:: php

    $i = 0;
    foreach ($value as $interval) {
        $i++;
        $multiple = ($i != 1);
        create_metadata($user->guid, $shortname, $interval, 'text', $user->guid, $access_id, $multiple);
    }

Note that the *allow multiple* setting is set to *false* in the first
iteration and *true* thereafter.

Reading metadata
~~~~~~~~~~~~~~~~

``elgg_get_metadata`` is the best function for retrieving metadata as ElggMetadata
objects:

E.g., to retrieve a user's DOB

.. code:: php

    elgg_get_metadata(array(
        'metadata_name' => 'dob',
        'metadata_owner_guid' => $user_guid,
    ));

Or to get all metadata objects:

.. code:: php

    elgg_get_metadata(array(
        'metadata_owner_guid' => $user_guid,
        'limit' => 0,
    ));

.. complete list of metadata functions: http://reference.elgg.org/engine_2lib_2metadata_8php.html

Common mistakes
---------------

"Appending" metadata
~~~~~~~~~~~~~~~~~~~~

Note that you cannot "append" values to metadata arrays as if they were
normal php arrays. For example, the following will not do what it looks
like it should do.

.. code:: php

    $object->tags[] = "tag four";

Trying to store hashmaps
~~~~~~~~~~~~~~~~~~~~~~~~

Elgg does not support storing ordered maps (name/value pairs) in
metadata. For example, the following does not work as you might first
expect it to:

.. code:: php

    // Won't work!! Only the array values are stored
    $object->tags = array('one' => 'a', 'two' => 'b', 'three' => 'c');

You can instead store the information like so:

.. code:: php

    $object->one = 'a';
    $object->two = 'b';
    $object->three = 'c';
    
Storing GUIDs in metadata
~~~~~~~~~~~~~~~~~~~~~~~~~

Though there are some cases to store entity GUIDs in metadata,
`Relationships`_ are a much better construct for relating entities
to each other.

Relationships
=============

Relationships allow you to bind entities together. Examples: an
artist has fans, a user is a member of an organization, etc.

The class ``ElggRelationship`` models a directed relationship between
two entities, making the statement:

    "**{subject}** is a **{noun}** of **{target}**."

================  ===========     =========================================
API name          Models          Represents
================  ===========     =========================================
``guid_one``      The subject     Which entity is being bound
``relationship``  The noun        The type of relationship
``guid_two``      The target      The entity to which the subject is bound
================  ===========     =========================================

The type of relationship may alternately be a verb, making the statement:

    "**{subject}** **{verb}** **{target}**."

    E.g. User A "likes" blog post B

**Each relationship has direction.** Imagine an archer shoots
an arrow at a target; The arrow moves in one direction, binding
the subject (the archer) to the target.

**A relationship does not imply reciprocity**. **A** follows **B** does
not imply that **B** follows **A**.

**Relationships_ do not have access control.** They're never
hidden from view and can be edited with code at any privilege
level, with the caveat that *the entities* in a relationship
may be invisible due to access control!

Working with relationships
--------------------------

Creating a relationship
~~~~~~~~~~~~~~~~~~~~~~~

E.g. to establish that "**$user** is a **fan** of **$artist**"
(user is the subject, artist is the target):

.. code:: php

    // option 1
    $success = add_entity_relationship($user->guid, 'fan', $artist->guid);

    // option 2
    $success = $user->addRelationship($artist->guid, 'fan');

This triggers the event [create, relationship], passing in
the created ``ElggRelationship`` object. If a handler returns
``false``, the relationship will not be created and ``$success``
will be ``false``.

Verifying a relationship
~~~~~~~~~~~~~~~~~~~~~~~~

E.g. to verify that "**$user** is a **fan** of **$artist**":

.. code:: php

    if (check_entity_relationship($user->guid, 'fan', $artist->guid)) {
        // relationship exists
    }

Note that, if the relationship exists, ``check_entity_relationship()``
returns an ``ElggRelationship`` object:

.. code:: php

    $relationship = check_entity_relationship($user->guid, 'fan', $artist->guid);
    if ($relationship) {
        // use $relationship->id or $relationship->time_created
    }

Deleting a relationship
~~~~~~~~~~~~~~~~~~~~~~~

E.g. to be able to assert that "**$user** is no longer a **fan** of **$artist**":

.. code:: php

    $was_removed = remove_entity_relationship($user->guid, 'fan', $artist->guid);

This triggers the event [delete, relationship], passing in
the associated ``ElggRelationship`` object. If a handler returns
``false``, the relationship will remain, and ``$was_removed`` will
be ``false``.

Other useful functions:

- ``delete_relationship()`` : delete by ID
- ``remove_entity_relationships()`` : delete those relating to an entity (*note:* in versions before Elgg 1.9, this did not trigger delete events)

Finding relationships and related entities
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Below are a few functions to fetch relationship objects
and/or related entities. A few are listed below:

- ``get_entity_relationships()`` : fetch relationships by subject or target entity
- ``get_relationship()`` : get a relationship object by ID
- ``elgg_get_entities_from_relationship()`` : fetch entities in relationships in a
  variety of ways

E.g. retrieving users who joined your site in January 2014.

.. code:: php

    $entities = elgg_get_entities_from_relationship(array(
        'relationship' => 'member_of_site',
        'relationship_guid' => elgg_get_site_entity()->guid,
        'inverse_relationship' => true,

        'relationship_created_time_lower' => 1388534400, // January 1st 2014
        'relationship_created_time_upper' => 1391212800, // February 1st 2014
    ));

Access Control
==============

Granular access controls are one of the fundamental design principles in
Elgg, and a feature that has been at the centre of the system throughout
its development. The idea is simple: a user should have full control
over who sees an item of data he or she creates.

Access controls in the data model
---------------------------------

In order to achieve this, every entity, annotation and piece of
metadata contains an ``access_id`` property, which in turn corresponds
to one of the pre-defined access controls or an entry in the
``access_collections`` database table.

Pre-defined access controls
~~~~~~~~~~~~~~~~~~~~~~~~~~~

-  ``ACCESS_PRIVATE`` (value: 0) Private.
-  ``ACCESS_LOGGED_IN`` (value: 1) Logged in users.
-  ``ACCESS_PUBLIC`` (value: 2) Public data.
-  ``ACCESS_FRIENDS`` (value: -2) Owner and his/her friends.

User defined access controls
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You may define additional access groups and assign them to an entity,
annotation or metadata. A number of functions have been defined to
assist you; see the `access library reference`_ for more information.

How access affects data retrieval
---------------------------------

All data retrieval functions above the database layer - for example
``get_entities`` and its cousins - will only return items that the
current user has access to see. It is not possible to retrieve items
that the current user does not have access to. This makes it very hard
to create a security hole for retrieval.

.. _access library reference: http://reference.elgg.org/engine_2lib_2access_8php.html

Write access
------------

The following rules govern write access:

-  The owner of an entity can always edit it
-  The owner of a container can edit anything therein (note that this
   does not mean that the owner of a group can edit anything therein)
-  Admins can edit anything

You can override this behaviour using a :ref:`plugin hook <design/events#plugin-hooks>` called
``permissions_check``, which passes the entity in question to any
function that has announced it wants to be referenced. Returning
``true`` will allow write access; returning ``false`` will deny it. See
:ref:`the plugin hook reference for permissions\_check <guides/hooks-list#permission-hooks>` for more details.

.. seealso::

   `Access library reference`_

.. _Access library reference: http://reference.elgg.org/engine_2lib_2access_8php.html

Schema
======

The database contains a number of primary tables and secondary tables.
Its schema table is stored in ``/engine/schema/mysql.sql``.

Each table is prefixed by "prefix\_", this is replaced by the Elgg
framework during installation.

Main tables
-----------

This is a description of the main tables. Keep in mind that in a given
Elgg installation, the tables will have a prefix (typically "elgg\_").

Table: entities
~~~~~~~~~~~~~~~

This is the main `Entities`_ table containing Elgg users, sites,
objects and groups. When you first install Elgg this is automatically
populated with your first site.

It contains the following fields:

-  **guid** An auto-incrementing counter producing a GUID that uniquely
   identifies this entity in the system.
-  **type** The type of entity - object, user, group or site
-  **subtype** A link to the `entity_subtypes` table.
-  **owner\_guid** The GUID of the owner's entity.
-  **site\_guid** The site the entity belongs to.
-  **container\_guid** The GUID this entity is contained by - either a user or
   a group.
-  **access\_id** Access controls on this entity.
-  **time\_created** Unix timestamp of when the entity is created.
-  **time\_updated** Unix timestamp of when the entity was updated.
-  **enabled** If this is 'yes' an entity is accessible, if 'no' the entity
   has been disabled (Elgg treats it as if it were deleted without actually
   removing it from the database).

Table: entity\_subtypes
~~~~~~~~~~~~~~~~~~~~~~~

This table contains entity subtype information:

-  **id** A counter.
-  **type** The type of entity - object, user, group or site.
-  **subtype** The subtype name as a string.
-  **class** Optional class name if this subtype is linked with a class

Table: metadata
~~~~~~~~~~~~~~~

This table contains `Metadata`_, extra information attached to an entity.

-  **id** A counter.
-  **entity\_guid** The entity this is attached to.
-  **name\_id** A link to the metastrings table defining the name
   table.
-  **value\_id** A link to the metastrings table defining the value.
-  **value\_type** The value class, either text or an integer.
-  **owner\_guid** The owner GUID of the owner who set this item of
   metadata.
-  **access\_id** An Access controls on this item of metadata.
-  **time\_created** Unix timestamp of when the metadata is created.
-  **enabled** If this is 'yes' an item is accessible, if 'no' the item
   has been deleted.

Table: annotations
~~~~~~~~~~~~~~~~~~

This table contains `Annotations`_, this is distinct from `Metadata`_.

-  **id** A counter.
-  **entity\_guid** The entity this is attached to.
-  **name\_id** A link to the metastrings table defining the type of
   annotation.
-  **value\_id** A link to the metastrings table defining the value.
-  **value\_type** The value class, either text or an integer.
-  **owner\_guid** The owner GUID of the owner who set this item of
   metadata.
-  **access\_id** An Access controls on this item of metadata.
-  **time\_created** Unix timestamp of when the metadata is created.
-  **enabled** If this is 'yes' an item is accessible, if 'no' the item
   has been deleted.

Table: relationships
~~~~~~~~~~~~~~~~~~~~

This table defines `Relationships`_, these link one entity with another.

-  **guid\_one** The GUID of the subject entity.
-  **relationship** The type of the relationship.
-  **guid\_two** The GUID of the target entity.

Table: objects\_entity
~~~~~~~~~~~~~~~~~~~~~~

Extra information specifically relating to objects. These are split in
order to reduce load on the metadata table and make an obvious
difference between attributes and metadata.

Table: sites\_entity
~~~~~~~~~~~~~~~~~~~~

Extra information specifically relating to sites. These are split in
order to reduce load on the metadata table and make an obvious
difference between attributes and metadata.

Table: users\_entity
~~~~~~~~~~~~~~~~~~~~

Extra information specifically relating to users. These are split in
order to reduce load on the metadata table and make an obvious
difference between attributes and metadata.

Table: groups\_entity
~~~~~~~~~~~~~~~~~~~~~

Extra information specifically relating to groups. These are split in
order to reduce load on the metadata table and make an obvious
difference between attributes and metadata.

Table: metastrings
~~~~~~~~~~~~~~~~~~

Metastrings contain the actual string of metadata which is linked to by
the metadata and annotations tables.

This is to avoid duplicating strings, saving space and making database
lookups more efficient.

Core developers will place schema upgrades in
``/engine/schema/upgrades/*``.
