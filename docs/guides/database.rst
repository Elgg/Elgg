Database
########

Persist user-generated content and settings with Elgg's generic storage API.

.. contents:: Contents
   :local:
   :depth: 2

Entities
========

Creating an object
------------------

To create an object in your code, you need to instantiate an
``ElggObject``. Setting data is simply a matter of adding instance
variables or properties. The built-in properties are:

-  **``guid``** The entity's GUID; set automatically
-  **``owner_guid``** The owning user's GUID
-  **``subtype``** A single-word arbitrary string that defines what kind
   of object it is, for example ``blog``
-  **``access_id``** An integer representing the access level of the
   object
-  **``title``** The title of the object
-  **``description``** The description of the object

The object subtype is a special property. This is an arbitrary string
that describes what the object is. For example, if you were writing a
blog plugin, your subtype string might be *blog*. It's a good idea to
make this unique, so that other plugins don't accidentally try and use
the same subtype. For the purposes of this document, let's assume we're
building a simple forum. Therefore, the subtype will be *forum*:

.. code-block:: php

    $object = new ElggObject();
    $object->subtype = "forum";
    $object->access_id = 2;
    $object->save();
    
``access_id`` is another important property. If you don't set this, your
object will be private, and only the creator user will be able to see
it. Elgg defines constants for the special values of ``access_id``:

-  **ACCESS_PRIVATE** Only the owner can see it
-  **ACCESS_LOGGED_IN** Any logged in user can see it
-  **ACCESS_PUBLIC** Even visitors not logged in can see it

Saving the object will automatically populate the ``$object->guid``
property if successful. If you change any more base properties, you can
call ``$object->save()`` again, and it will update the database for you.

You can set metadata on an object just like a standard property. Let's
say we want to set the SKU of a product:

.. code-block:: php

    $object->SKU = 62784;

If you assign an array, all the values will be set for that metadata.
This is how, for example, you set tags.

Metadata cannot be persisted to the database until the entity has been
saved, but for convenience, ElggEntity can cache it internally and save
it when saving the entity.

Loading an object
-----------------

By GUID
~~~~~~~

.. code-block:: php

    $entity = get_entity($guid);
    if (!$entity) {
        // The entity does not exist or you're not allowed to access it.
    }

But what if you don't know the GUID? There are several options.

By user, subtype or site
~~~~~~~~~~~~~~~~~~~~~~~~

If you know the user ID you want to get objects for, or the subtype, you have several options.
The easiest is probably to call the procedural function ``elgg_get_entities``:

.. code-block:: php

    $entities = elgg_get_entities(array(
        'type' => $entity_type,
        'subtype' => $subtype,
        'owner_guid' => $owner_guid,
    ));

This will return an array of ``ElggEntity`` objects that you can iterate
through. ``elgg_get_entities`` paginates by default, with a limit of 10;
and offset 0.

You can leave out ``owner_guid`` to get all objects and leave out subtype
or type to get objects of all types/subtypes.

If you already have an ``ElggUser`` – e.g. ``elgg_get_logged_in_user_entity``,
which always has the current user's object when you're logged in – you can
simply use:

.. code-block:: php

    $objects = $user->getObjects($subtype, $limit, $offset)

But what about getting objects with a particular piece of metadata?

By properties
~~~~~~~~~~~~~

You can fetch entities by their properties using ``elgg_get_entities``. Using specific parameters passed to ``$options``
array, you can retrieve entities by their attributes, metadata, annotations, private settings and relationships.


Displaying entities
-------------------

In order for entities to be displayed in listing functions you need
to provide a view for the entity in the views system.

To display an entity, create a view EntityType/subtype where EntityType
is one of the following:

object: for entities derived from ElggObject
user: for entities derived from ElggUser
site: for entities derived from ElggSite
group: for entities derived from ElggGroup

A default view for all entities has already been created, this is called
EntityType/default.

.. _guides/database#entity-icons:

Entity Icons
~~~~~~~~~~~~

Entity icons can be saved from uploaded files, existing local files, or existing ElggFile 
objects. These methods save the `master` size of the icon defined in the system. The other defined sizes will be generated when requested.

.. code-block:: php

	$object = new ElggObject();
	$object->title = 'Example entity';
	$object->description = 'An example object with an icon.';
	
	// from an uploaded file
	$object->saveIconFromUploadedFile('file_upload_input');

	// from a local file
	$object->saveIconFromLocalFile('/var/data/generic_icon.png');

	// from a saved ElggFile object
	$file = get_entity(123);
	if ($file instanceof ElggFile) {
		$object->saveIconFromElggFile($file);
	}
	
	$object->save();

The following sizes exist by default: 
 * ``master`` - 10240px at longer edge (not upscaled)
 * ``large`` - 200px at longer edge (not upscaled)
 * ``medium`` - 100px square
 * ``small`` - 40px square
 * ``tiny`` - 25px square
 * ``topbar`` - 16px square

Use ``elgg_get_icon_sizes()`` to get all possible icon sizes for a specific entity type and subtype.
The function triggers the ``entity:icon:sizes`` :ref:`hook <guides/hooks-list#other>`.

To check if an icon is set, use ``$object->hasIcon($size)``.

You can retrieve the URL of the generated icon with ``ElggEntity::getIconURL($params)`` method.
This method accepts a ``$params`` argument as an array that specifies the size, type, and provide 
additional context for the hook to determine the icon to serve. 
The method triggers the ``entity:icon:url`` :ref:`hook <guides/hooks-list#other>`.

Use ``elgg_view_entity_icon($entity, $size, $vars)`` to render an icon. This will scan the following
locations for a view and include the first match to .

#. views/$viewtype/icon/$type/$subtype.php
#. views/$viewtype/icon/$type/default.php
#. views/$viewtype/icon/default.php

Where

$viewtype
	Type of view, e.g. ``'default'`` or ``'json'``.
$type
	Type of entity, e.g. ``'group'`` or ``'user'``.
$subtype
	Entity subtype, e.g. ``'blog'`` or ``'page'``.

You do not have to return a fallback icon from the hook handler. If no uploaded icon is found,
the view system will scan the views (in this specific order):

#. views/$viewtype/$icon_type/$entity_type/$entity_subtype.svg
#. views/$viewtype/$icon_type/$entity_type/$entity_subtype/$size.gif
#. views/$viewtype/$icon_type/$entity_type/$entity_subtype/$size.png
#. views/$viewtype/$icon_type/$entity_type/$entity_subtype/$size.jpg

Where

$viewtype
	Type of view, e.g. ``'default'`` or ``'json'``.
$icon_type
	Icon type, e.g. ``'icon'`` or ``'cover_image'``.
$entity_type
	Type of entity, e.g. ``'group'`` or ``'user'``.
$entity_subtype
	Entity subtype, e.g. ``'blog'`` or ``'page'`` (or ``'default'`` if entity has not subtype).
$size
    Icon size (note that we do not use the size with svg icons)

Icon methods support passing an icon type if an entity has more than one icon. For example, a user
might have an avatar and a cover photo icon. You would pass ``'cover_photo'`` as the icon type:

.. code-block:: php

	$object->saveIconFromUploadedFile('uploaded_photo', 'cover_photo');

	$object->getIconUrl([
		'size' => 'medium',
		'type' => 'cover_photo'
	]);


.. note::
	
	Custom icon types (e.g. cover photos) only have a preset for `master` size, to add custom sizes
	use ``entity:<icon_type>:url`` :ref:`hook <guides/hooks-list#other>` to configure them.

By default icons will be stored in ``/icons/<icon_type>/<size>.jpg`` relative to entity's directory on filestore.
To provide an alternative location, use the ``entity:<icon_type>:file`` :ref:`hook <guides/hooks-list#other>`.

Adding, reading and deleting annotations
----------------------------------------

Annotations could be used, for example, to track ratings. To annotate an
entity you can use the object's ``annotate()`` method. For example, to
give a blog post a rating of 5, you could use:

.. code-block:: php

    $blog_post->annotate('rating', 5);

.. _view: Views

To retrieve the ratings on the blog post, use
``$blogpost->getAnnotations('rating')`` and if you want to delete an
annotation, you can operate on the ``ElggAnnotation`` class, eg
``$annotation->delete()``.

Retrieving a single annotation can be done with ``get_annotation()`` if
you have the annotation's ID. If you delete an ElggEntity of any kind,
all its metadata, annotations, and relationships will be automatically
deleted as well.

Extending ElggEntity
--------------------

If you derive from one of the Elgg core classes, you'll need to tell
Elgg how to properly instantiate the new type of object so that
get\_entity() et al. will return the appropriate PHP class. For example,
if I customize ElggGroup in a class called "Committee", I need to make
Elgg aware of the new mapping. Following is an example class extension:

.. code-block:: php

    // Class source
    class Committee extends ElggGroup {

        protected function initializeAttributes() {
            parent::initializeAttributes();
            $this->attributes['subtype'] = 'committee';
        }

        // more customizations here
    }

In your plugins ``elgg-plugin.php`` file add the ``entities`` section.

.. code-block:: php

    <?php // mod/example/elgg-plugin.php
    return [
        // entities registration
        'entities' => [
			[
				'type' => 'group',
				'subtype' => 'committee',
				'class' => 'Committee',
				'searchable' => true, 
			],
		],
    ];
    
The entities will be registered upon activation of the plugin.
    
Now if you invoke ``get_entity()`` with the GUID of a committee object,
you'll get back an object of type Committee.

Advanced features
-----------------

Entity URLs
~~~~~~~~~~~

Entity urls are provided by the ``getURL()`` interface and provide the
Elgg framework with a common way of directing users to the appropriate
display handler for any given object.

For example, a profile page in the case of users.

The url is set using the ``elgg\_register\_entity\_url\_handler()``
function. The function you register must return the appropriate url for
the given type - this itself can be an address set up by a page handler.

.. _getURL(): http://reference.elgg.org/classElggEntity.html#778536251179055d877d3ddb15deeffd
.. _elgg\_register\_entity\_url\_handler(): http://reference.elgg.org/entities_8php.html#f28d3b403f90c91a715b81334eb59893

The default handler is to use the default export interface.

Entity loading performance
~~~~~~~~~~~~~~~~~~~~~~~~~~

``elgg_get_entities`` has a couple options that can sometimes be useful to improve performance.

- **preload_owners**: If the entities fetched will be displayed in a list with the owner information, you can set this option to ``true`` to efficiently load the owner users of the fetched entities.
- **preload_containers**: If the entities fetched will be displayed in a list using info from their containers, you can set this option to ``true`` to efficiently load them.
- **distinct**: When Elgg fetches entities using an SQL query, Elgg must be sure that each entity row appears only once in the result set. By default it includes a ``DISTINCT`` modifier on the GUID column to enforce this, but some queries naturally return unique entities. Setting the ``distinct`` option to false will remove this modifier, and rely on the query to enforce its own uniqueness.

The internals of Elgg entity queries is a complex subject and it's recommended to seek help on the Elgg Community site before using the ``distinct`` option.

Custom database functionality
=============================

It is strongly recommended to use entities wherever possible. However, Elgg
supports custom SQL queries using the database API.

Example: Run SQL script on plugin activation
--------------------------------------------

This example shows how you can populate your database on plugin activation.

my_plugin/activate.php:

.. code-block:: php

    if (!elgg_get_plugin_setting('database_version', 'my_plugin') {
        run_sql_script(__DIR__ . '/sql/activate.sql');
        elgg_set_plugin_setting('database_version', 1, 'my_plugin');
    }


my_plugin/sql/activate.sql:

.. code-block:: sql

    -- Create some table
    CREATE TABLE prefix_custom_table(
        id INTEGER AUTO_INCREMENT,
        name VARCHAR(32),
        description VARCHAR(32),
        PRIMARY KEY (id)
    );

    -- Insert initial values for table
    INSERT INTO prefix_custom_table (name, description)
    VALUES ('Peter', 'Some guy'), ('Lisa', 'Some girl');

Note that Elgg execute statements through PHPs built-in functions and have
limited support for comments. I.e. only single line comments are supported
and must be prefixed by "-- " or "# ". A comment must start at the very beginning
of a line.

Systemlog
=========

.. note::

   This section need some attention and will contain outdated information

The default Elgg system log is a simple way of recording what happens within an Elgg system. It's viewable and searchable directly from the administration panel.

System log storage
------------------

A system log row is stored whenever an event concerning an object whose class implements the :doc:`/design/loggable` interface is triggered. ``ElggEntity`` and ``ElggExtender`` implement :doc:`/design/loggable`, so a system log row is created whenever an event is performed on all objects, users, groups, sites, metadata and annotations.

Common events include:

- create
- update
- delete
- login

Creating your own system log
----------------------------

There are some reasons why you might want to create your own system log. For example, you might need to store a full copy of entities when they are updated or deleted, for auditing purposes. You might also need to notify an administrator when certain types of events occur.

To do this, you can create a function that listens to all events for all types of object:

.. code-block:: php

   register_elgg_event_handler('all','all','your_function_name');

Your function can then be defined as:

.. code-block:: php

   function your_function_name($object, $event) {
      if ($object instanceof Loggable) {
         ...
      }
   }

You can then use the extra methods defined by :doc:`/design/loggable` to extract the information you need.
