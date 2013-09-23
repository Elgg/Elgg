Database
########

Entities
========

Creating an object
------------------

To create an object in your code, you need to instantiate an
``ElggObject``. Setting data is simply a matter of adding instance
variables or properties. The built-in properties are:

-  **``guid``** The entity's GUID; set automatically
-  **``owner_guid``** The owning user's GUID
-  **``site_guid``** The owning site's GUID. This is set automatically
   when an instance of ``ElggObject`` gets created)
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

.. code:: php

    $object = new ElggObject();
    $object->subtype = "forum";
    $object->access_id = 2;
    $object->save();
    
``access_id`` is another important property. If you don't set this, your
object will be private, and only the creator user will be able to see
it. Elgg defines constants for the special values of ``access_id``:

-  **ACCESS_PRIVATE** Only the owner can see it
-  **ACCESS_FRIENDS** Only the owner and his/her friends can see it
-  **ACCESS_LOGGED_IN** Any logged in user can see it
-  **ACCESS_PUBLIC** Even visitors not logged in can see it

Saving the object will automatically populate the ``$object->guid``
property if successful. If you change any more base properties, you can
call ``$object->save()`` again, and it will update the database for you.

You can set metadata on an object just like a standard property. Let's
say we want to set the SKU of a product:

.. code:: php

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

.. code:: php

    $entity = get_entity($guid);
    if (!$entity) {
        // The entity does not exist or you're not allowed to access it.
    }

But what if you don't know the GUID? There are several options.

By user, subtype or site
~~~~~~~~~~~~~~~~~~~~~~~~

If you know the user ID you want to get objects for, or the subtype, or
the site, you have several options. The easiest is probably to call the
procedural function ``elgg_get_entities``:

.. code:: php

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

.. code:: php

    $objects = $user->getObjects($subtype, $limit, $offset)

But what about getting objects with a particular piece of metadata?

By metadata
~~~~~~~~~~~

The function ``elgg_get_entities_from_metadata`` allows fetching entities
with metadata in a variety of ways.

Displaying entities
-------------------

In order for entities to be displayed in `listing functions`_ you need
to provide a view for the entity in the views system.

To display an entity, create a view EntityType/subtype where EntityType
is one of the following:

object: for entities derived from ElggObject
user: for entities derived from ElggUser
site: for entities derived from ElggSite
group: for entities derived from ElggGroup

.. _listing functions: Views#Listing_entities

A default view for all entities has already been created, this is called
EntityType/default.

Entity Icons
~~~~~~~~~~~~

Entities all have a method called ->getIcon($size).

This method accepts a $size variable, which can be either 'large',
'medium', 'small' or 'tiny'.

The method triggers a `plugin hook`_ - 'entity:icon:url'. This is passed
the following parameters:

'entity' : The entity in question
'viewtype' : The type of `view`_ e.g. 'default' or 'mobile'.
'size' : The size.

The hook should return a url.

Hooks have already been defined, and will look in the following places
for default values (in this order):

.. _plugin hook: PluginHooks
.. _view: Views

#. views/$viewtype/graphics/icons/$type/$subtype/$size.png
#. views/$viewtype/graphics/icons/$type/default/$size.png
#. views/$viewtype/graphics/icons/default/$size.png

Where

$viewtype : The type of `view`_ e.g. 'default' or 'mobile'.
$type : The type of entity - group, site, user, object.
$subtype : Subtype of $type, e.g. blog, page.
$size : Size - 'large', 'medium', 'small' or 'tiny'

Adding, reading and deleting annotations
----------------------------------------

Annotations could be used, for example, to track ratings. To annotate an
entity you can use the object's ``annotate()`` method. For example, to
give a blog post a rating of 5, you could use:

.. code:: php

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

.. code:: php

    // Class source
    class Committee extends ElggGroup {

        protected function initializeAttributes() {
            parent::initializeAttributes();
            $this->attributes['subtype'] = 'committee';
        }

        // more customizations here
    }

    function committee_init() {
        
        register_entity_type('group', 'committee');
        
        // Tell Elgg that group subtype "committee" should be loaded using the Committee class
        // If you ever change the name of the class, use update_subtype() to change it
        add_subtype('group', 'committee', 'Committee');
    }

    register_elgg_event_handler('init', 'system', 'committee_init');
    
Now if you invoke ``get_entity()`` with the GUID of a committee object,
you'll get back an object of type Committee.

This template was extracted from the definition of ElggFile.

Advanced features
-----------------

Entity Icons
~~~~~~~~~~~~

A url for an icon representing a given entity can be retrieved by the
``getIcon()`` method.

This is handy as it provides a generic interface which allows the Elgg
framework to draw an icon for your data - it also allows you to override
icons for existing data types - for example providing `Gravatar support
for user icons`_.

.. _getIcon(): http://reference.elgg.org/classElggEntity.html#fe2a187620e99603bd08cf4ee4238a70
.. _Gravatar support for user icons: http://www.marcus-povey.co.uk/2008/10/20/overriding-icons/

If no icon can be provided for the data type a default one is used,
defined either by your current theme or the Elgg default.

Overriding the url for a specific instance
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

To override the icon of a specific instance of an entity in a
non-permanent and one off way, you can use the entity's ``setIcon()``
method.

Replacing icons via the views interface
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you want to provide an icon for a new data type, or override an
existing one you can do this simply through the views interface.

Views are in the format:

``icon/``\ **``[TYPE]``**\ ``/``\ **``[SUBTYPE]``**\ ``/``\ **``[SIZE]``**

.. _setIcon(): http://reference.elgg.org/classElggEntity.html#28b9d72a1641fdf4b65130b818f4f35f

Where:

[TYPE]: is the elgg type of the object - "user", "group", "object" or
"site".
[SUBTYPE]: is the specific subtype of the object, or "default" for the
default icon for the given type.
[SIZE]: the size, one of the following "master", "large", "medium",
"small", "topbar" or "tiny".

This view should contain the URL to the image only.

Overriding icons via a handler
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The final way to replace icons is via a handler to a plugin hook.

This method lets you perform some additional logic in order to decide
better which url to return.

The hook triggered is:

| ``trigger_plugin_hook('entity:icon:url', $entity->getType(), array('entity' => $entity, 'viewtype' => $viewtype, 'size' => $size));``
| ``       ``

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

Pre-1.8 Notes
-------------

update\_subtype(): This function is new in 1.8. In prior versions, you
would need to edit the database by hand if you updated the class name
associated with a given subtype.

elgg\_register\_entity\_url\_handler(): This function is new in 1.8. It
deprecates register\_entity\_url\_handler(), which you should use if
developing for a pre-1.8 version of Elgg.

elgg\_get\_entities\_from\_metadata(): This function is new in 1.8. It
deprecates get\_entities\_from\_metadata(), which you should use if
developing for a pre-1.8 version of Elgg.
