File System
###########

.. contents:: Contents
   :local:
   :depth: 1

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

The default behaviour of ``use_cookie`` can be controlled on the admin security settings page.

For entities that are under Elgg's access control, you may want to use cookies to ensure
that access settings are respected and users do not share download URLs with somebody else.

You can also invalidated all previously generated URLs by updating file's modified time, e.g.
by using ``touch()``.


Embedding Files
---------------

Please note that due to their nature inline and download URLs are not suitable for embedding.
Embed URLs must be permanent, whereas inline and download URLs are volatile (bound to user session
and file modification time).

To embed an entity icon, use ``elgg_get_embed_url()``.


Handling File Uploads
---------------------

In order to implement an action that saves a single file uploaded by a user, you can use the following approach:

.. code-block:: php

	// in your form
	echo elgg_view('input/file', [
		'name' => 'upload',
		'label' => 'Select an image to upload',
		'help' => 'Only jpeg, gif and png images are supported',
	]);


.. code-block:: php

	// in your action
	$uploaded_file = elgg_get_uploaded_file('upload');
	if (!$uploaded_file) {
		return elgg_error_response("No file was uploaded");
	}

	$supported_mimes = [
		'image/jpeg',
		'image/png',
		'image/gif',
	];

	$mime_type = elgg()->mimetype->getMimeType($uploaded_file->getPathname());
	if (!in_array($mime_type, $supported_mimes)) {
		return elgg_error_response("{$mime_type} is not supported");
	}

	$file = new ElggFile();
	$file->owner_guid = elgg_get_logged_in_user_guid();
	if ($file->acceptUploadedFile($uploaded_file)) {
		$file->save();
	}


If your file input supports multiple files, you can iterate through them in your action:

.. code-block:: php

	// in your form
	echo elgg_view('input/file', [
		'name' => 'upload[]',
		'multiple' => true,
		'label' => 'Select images to upload',
	]);


.. code-block:: php

	// in your action
	foreach (elgg_get_uploaded_files('upload') as $upload) {
		$file = new ElggFile();
		$file->owner_guid = elgg_get_logged_in_user_guid();
		if ($file->acceptUploadedFile($upload)) {
			$file->save();
		}
	}

.. note::

   If images are uploaded their is an automatic attempt to fix the orientation of the image.

Temporary files
===============

If you ever need a temporary file you can use ``elgg_get_temp_file()``. You'll get an instance of an ``ElggTempFile`` which has all the 
file functions of an ``ElggFile``, but writes it's data to the systems temp folder.

.. warning::
	
	It's not possible to save the ``ElggTempFile`` to the database. You'll get an ``Elgg\Exceptions\Filesystem\IOException`` if you try.
