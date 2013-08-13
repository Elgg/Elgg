Database
########

A thorough discussion of Elgg's data model design and motivation.

.. toctree::
   :maxdepth: 2

Overview
========

In Elgg, everything runs on a unified data model, based on atomic
units of data called entities.

Plugins are strongly discouraged from dealing with database issues
themselves, which makes for a more stable system that also has visible
benefits for the end user. Content created by different plugins can be
mixed together in consistent ways, which are programmed using generic
principles - in other words, plugins are faster to develop, and are at
the same time much more powerful.

Every entity in the system inherits the ``ElggEntity`` class. This class
controls access permissions, ownership and so on.

.. _thumb\|The Elgg data model diagramIn: image:Elgg_data_model.png

You can extend entities with extra information in two ways:

``Metadata``: This is information you can add to an object to
   describe it further. For example, tags, an ISBN number, a file
   location or language information would fall under metadata.
``Annotations``: Information generally added by third parties which
   adds to the information provided by the entity. For example, comments
   and ratings are both annotations.

Entities
========
ElggEntity is the base class for the Elgg data model.

Users, Objects, Groups, Sites
-----------------------------

``ElggEntity`` has four main specializations, which provide extra
properties and methods to more easily handle different kinds of data.

``ElggObject``: Usually content like blog posts, uploaded files and bookmarks
``ElggUser``: each user in the system
``ElggSite``: each site within an Elgg install
``ElggGroup``: multi-user collaborative systems, which were called
"Communities" in prior versions of Elgg

The benefit of such an approach is that, apart from modelling data with
greater ease, a common set of functions is available to handle objects,
regardless of their (sub)type.

Each of these have their own properties that they bring to the table:
ElggObjects have a title and description, ElggUsers have a username and
password, and so on. However, because they all inherit ElggEntity, they
each have a number of core properties and behaviours in common.

-  A numeric Globally Unique IDentifier.
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
GUID stands for Globally Unique IDentifier. This is a numeric ID value
that is unique not just across the scope of the type of entity we're
dealing with, but also across *all* types of entities.

If a blog post has a GUID of 7342, we know that no other entity in the
system has a GUID of 7342.

All entities have a GUID.

GUIDs are assigned automatically when the entity is saved to the
database and can never change once assigned.

ElggObject
==========

The ``ElggObject`` entity type represents arbitrary objects within an
Elgg install; things like blog posts, uploaded files, etc.

Beyond the standard ElggEntity properties, ElggObjects also support
the following. It is anticipated that most data will be stored via
metadata.

-  ``title`` The title of the object
-  ``description`` A plain text description

ElggUser
========

The ``ElggUser`` entity type represents users within an Elgg install.
These will be set to disabled until their accounts have been activated
(unless they were created from within the admin panel).

Beyond the standard ElggEntity properties, ElggUsers also support:

-  ``name`` The user's plain text name
-  ``username`` Their login name
-  ``password`` A hashed version of their password
-  ``salt`` The salt that their password has been hashed with
-  ``email`` Their email address
-  ``language`` Their default language
-  ``code`` Their session code
-  ``last_action`` The UNIX epoch timestamp of the last time they viewed
   a page
-  ``prev_last_action`` The UNIX epoch timestamp of the last time they
   viewed a page before ``last_action``
-  ``last_login`` The UNIX epoch timestamp of the last time they logged
   in
-  ``prev_last_login`` The UNIX epoch timestamp of the last time they
   logged in before ``last_login``

ElggSite
========

The ``ElggSite`` entity type represents sites within your Elgg install.
Most users will have exactly one of these.

Beyond the standard ElggEntity properties, ElggSites also support:

-  ``name`` The site name
-  ``description`` A description of the site
-  ``url`` The address of the site

The ``ElggGroup`` entity type represents groups within an Elgg install.

Introduction
------------

Beyond the standard ``ElggEntity`` properties, groups also support:

name: The group's plain text name
description: A description of the group

A group in Elgg is an entity that users can join, leave and post content
to.

methods provided by ``ElggGroup`` in order to manage
content and membership.

ElggGroup and default groups
----------------------------

It's important to draw a distinction between the default groups supplied
in the Elgg distribution - which have profile pages, forums and draw
content to the front - and
``[[Engine/DataModel/Entities/ElggGroup|ElggGroup]]``, the
``[[Engine/DataModel/Entities|ElggEntity]]`` specialization class that
provides group-level functionality.

The default group as provided by Elgg is an example. You can certainly
use it effectively, but you also have the ability to write very
different group functionality using the same underlying methods. Other
examples might include an event, a class or a cause. Because
``ElggGroup`` can be subtyped like all other ElggEntities, you can have
multiple types of group running on the same site.

Writing a group-aware plugin
----------------------------

Plugin owners need not worry too much about writing group-aware
functionality, but there are a few key points:

Uploading content
~~~~~~~~~~~~~~~~~

Use
``[http://reference.elgg.org/entities_8php.html#16a972909c7cb75f646cb707be001a6f can_write_to_container]``
to determine whether or not the current user has the right to
upload/post. Be aware that you will then need to pass the container GUID
or username to the page responsible for posting and the accompanying
value, so that this can then be stored in your form as a hidden input
field, for easy passing to your actions. Within a "create" action,
you'll need to take in this input field and save it as a property of
your new element (defaulting to the current user's container):

.. code:: php

    $container_guid = get_input('container_guid', $_SESSION['user']->getGUID());
    $new_element = new ElggObject;
    $new_element->container_guid = $container_guid;

When it comes time to redirect to a page, once your action has finished,
you'll need to get the container's username:

.. code:: php

    $container_entity = get_entity($container_guid);
    forward($CONFIG->wwwroot . 'pg/file/' . $container_entity->username);

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

Entities have a ``container_guid`` GUID property, which defines its
owner. Typically this refers to the GUID of a user, although sites and
users themselves often have no owner (a value of 0).

The ownership of an entity dictates, in part, whether or not you can
access or edit that entity.

Containers
==========

Normally, an entity is owned by another entity (typically an
``ElggUser``). However, you may find that you need to loosely connect
entities as being under the umbrella of a particular item, while having
different specific owners. For example, you might have a list of three
blog posts, all owned by different people, that you want to be part of a
single group blog. This kind of activity is what containers are for.

Each entity has a ``container_guid`` property, which is a numeric GUID
reference and is usually set to refer to its owner entity.

When you call functions like
``[http://reference.elgg.org/engine_2lib_2users_8php.html#364fd1280ee221f105c4a4f257076ae2 get_user_objects]``,
Elgg uses the ``container_guid`` property to retrieve entities rather
than the ``owner_guid``. That way, any entities assigned to a user's
*container* will also be retrieved.

Assigning containers
--------------------

To assign an entity to a container, set its ``container_guid`` property
to be the entity GUID of your choice. Note that you will not be able to
save your entity if the current user doesn't have permission to write to
that container, so you may want to test using
``[http://reference.elgg.org/entities_8php.html#16a972909c7cb75f646cb707be001a6f can_write_to_container]``
first.

Annotations
===========

Annotations are pieces of data attached to an entity that allow users
to leave comments, ratings, or other relevant feedback. In the group
forum plugin, each post is an annotation on a topic. A poll plugin might
register votes as annotations.

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

    function annotate(
                      $name,           // The name of the annotation type (eg 'comment')
                      $value,          // The value of the annotation
                      $access_id = 0,  // The access level of the annotation
                      $owner_id = 0,   // The annotation owner, defaults to current user
                      $vartype = ""    // 'text' or 'integer'
                     ) 

For example, to leave a comment on an entity, you might call:

.. code:: php

    $entity->annotate('comment', $comment_text, $entity->access_id);
    
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
    $tags = $object->tags; //$tags == "tag" NOT array('tag')

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
    foreach($value as $interval) {
        $i++;
        if ($i == 1) { $multiple = false; } else { $multiple = true; }
        create_metadata($user->guid, $shortname, $interval, 'text', $user->guid, $access_id, $multiple);
    }

Note that the *allow multiple* setting is set to *false* in the first
iteration and *true* thereafter.

Reading metadata
~~~~~~~~~~~~~~~~

A number of functions are available to retrieve metadata as ElggMetadata
objects:

By name
^^^^^^^

.. code:: php

    function get_metadata_byname (
                         $entity_guid,
                         $meta_name  
                         )

The above retrieves a piece of metadata by name. For example:

.. code:: php

    $dob = get_metadata_byname($user_guid, 'dob');

Get all metadata for an entity
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code:: php

    function get_metadata_for_entity (
                         $entity_guid
                         )

This will return an array containing all of the accessible metadata for
a specified entity.

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

    // Won't work!!
    $object->tags = array('one' => 'a', 'two' => 'b', 'three' => 'c');

You can instead store the information like so:

.. code:: php

    $object->one = 'a';
    $object->two = 'b';
    $object->three = 'c';
    
Storing GUIDs in metadata
~~~~~~~~~~~~~~~~~~~~~~~~~

Though this is not technically incorrect, it is inadvisable to use
metadata to store guids. Relationships are a much better construct for
this purpose. Yes, there are core plugins that break this principle --
we are working on fixing them!

For example, instead of:

.. code:: php

    $object->example_guid = $guid;

do:

.. code:: php

    $object->addRelationship('example', $guid);

Relationships
=============

TODO

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

-  **0** Private.
-  **1** Logged in users.
-  **2** Public data.

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

You can override this behaviour using a `plugin hook`_ called
``permissions_check``, which passes the entity in question to any
function that has announced it wants to be referenced. Returning
``true`` will allow write access; returning ``false`` will deny it. See
`the plugin hook reference for permissions\_check`_ for more details.

Also see
--------

-  `Engine reference`_
-  `Access library reference`_

.. _plugin hook: PluginHooks
.. _the plugin hook reference for permissions\_check: PluginHooks#permissions_check
.. _Engine reference : Engine
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

entities
~~~~~~~~

This is the main Elgg table containing Elgg users, sites,
objects and groups. When you first install Elgg this is automatically
populated with your first site.

It contains the following fields:

guid: An auto-incrementing counter producing a GUID that uniquely
identifies this entity in the system.
type: The type of entity - object, user, group or site
subtype: A link to the `entity_subtypes` table.
owner\_guid: The GUID of the owner's entity.
site\_guid: The site the entity belongs to.
container\_guid: The GUID this entity is contained by - either a user or
a group.
access\_id: Access controls on this entity.
time\_created: Unix timestamp of when the entity is created.
time\_updated: Unix timestamp of when the entity was updated.
enabled: If this is 'yes' an entity is accessible, if 'no' the entity
has been disabled (Elgg treats it as if it were deleted without actually
removing it from the database).

entity\_subtypes
~~~~~~~~~~~~~~~~

This table contains entity subtype information:

-  **id** A counter.
-  **type** The type of entity - object, user, group or site.
-  **subtype** The subtype name as a string.
-  **class** Optional class name if this subtype is linked with a class

metadata
~~~~~~~~

This table contains extra information attached to an entity.

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

annotations
~~~~~~~~~~~

This table contains annotations, this is distinct from metadata.

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

relationships
~~~~~~~~~~~~~

This table defines relationships, these link one entity with another.

-  **guid\_one** entity number one.
-  **relationship** Relationship string.
-  **guid\_two** entity number two.

objects\_entity
~~~~~~~~~~~~~~~

Extra information specifically relating to objects. These are split in
order to reduce load on the metadata table and make an obvious
difference between attributes and metadata.

sites\_entity
~~~~~~~~~~~~~

Extra information specifically relating to sites. These are split in
order to reduce load on the metadata table and make an obvious
difference between attributes and metadata.

users\_entity
~~~~~~~~~~~~~

Extra information specifically relating to users. These are split in
order to reduce load on the metadata table and make an obvious
difference between attributes and metadata.

groups\_entity
~~~~~~~~~~~~~~

Extra information specifically relating to groups. These are split in
order to reduce load on the metadata table and make an obvious
difference between attributes and metadata.

metastrings
~~~~~~~~~~~

Metastrings contain the actual string of metadata which is linked to by
the metadata and annotations tables.

This is to avoid duplicating strings, saving space and making database
lookups more efficient.

Core developers will place schema upgrades in
``/engine/schema/upgrades/*``.

