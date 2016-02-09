File System
###########

Filestore
=========

Location
--------

Elgg's filestore is located in the site's ``dataroot`` that is configured during
installation, and can be modified via site settings in Admin interface.


Directory Structure
-------------------

The structure of the filestore is tied to file ownership by Elgg entities. Whenever
the first file owned by an entity is written to the filestore, a directory corresponding
to the entity GUID will be created within a parent bucket directory (buckets are bound to 5000 guids).
E.g. files owned by user with guid 7777 will be located in ``5000/7777/``.

When files are created, filenames can contain subdirectory names (often referred to as
`$prefix` throughout the code). For instance, avatars of the above user, can be found
under ``5000/7777/profile/``.


File Objects
============

Writing Files
-------------

To write a file to the filestore, you would use an instance of ``ElggFile``. Even though
``ElggFile`` extends `ElggObject` and can be stored as an actual Elgg entity, that is not
always necessary (e.g. when writing thumbs of an image).

.. code-block:: php

	$file = new ElggFile();
	$file->owner_guid = 7777;
	$file->setFilename('portfolio/files/sample.txt');
	$file->open('write');
	$file->write('Contents of the file');
	$file->close();

	// to uprade this file to an entity
	$file->subtype = 'file';
	$file->save();


Reading Files
-------------

You can read file contents using instanceof of ``ElggFile``.

.. code-block:: php

	// from an Elgg entity
	$file = get_entity($file_guid);
	readfile($file->getFilenameOnFilestore());


.. code-block:: php

	// arbitrary file on the filestore
	$file = new ElggFile();
	$file->owner_guid = 7777;
	$file->setFilename('portfolio/files/sample.txt');

	// option 1
	$file->open('read');
	$contents = $file->grabFile();
	$file->close();

	// option 2
	$contents = file_get_contents($file->getFilenameOnFilestore());



Serving Files
-------------

You can serve files from filestore using ``elgg_get_inline_url()`` and ``elgg_get_download_url()``.
Both functions accept 3 arguments:

-  **``file``** An instance of ``ElggFile`` to be served
-  **``use_cookie``** If set to true, validity of the URL will be limited to current session
-  **``expires``** Expiration time of the URL

You can use ``use_cookie`` and ``expires`` arguments as means of access control. For example,
users avatars in most cases have a long expiration time and do not need to be restricted by
current session - this will allows browsers to cache the images and file service will
send appropriate ``Not Modified`` headers on consecutive requests.

For entities that are under Elgg's access control, you may want to use cookies to ensure
that access settings are respected and users do not share download URLs with somebody else.

You can also invalidated all previously generated URLs by updating file's modified time, e.g.
by using ``touch()``.
