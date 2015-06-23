Icons
#####

It is possible to easily save an icon for any entity by linking to
``https://www.yousite.com/image/edit/<guid>`` where ``<guid>`` is
the GUID of the entity. This page will allow you to both upload
and crop the image.

The image will be automatically saved into multiple versions of
different sizes. The versions are defined by the site-wide
``icons_sizes`` setting.

Location of the files
=====================

Images saved using the feature will be saved in the Elgg dataroot
under the entity's own directory. If Elgg's dataroot is located
in ``/var/data/`` the icons created for example for to the entity
whose GUID is 185 would be saved like this:

.. code-block::

    data/
      1/
        185/
          icon/
            large.jpg
            master.jpg
            medium.jpg
            small.jpg
            tiny.jpg
            topbar.jpg

Image information in the database
=================================

When the images get saved, the following information gets attached
to the entity as metadata:

- A Unix timestamp called ``icontime`` that tells when the image was
  uploaded or modified. The metadata gets removed along with the image
  so it can be used to verify whether an entity has an image or not.
  The timestamp can also be used for caching.

- Image cropping coordinates:

  - ``x1``
  - ``x2``
  - ``y1``
  - ``y2``

Accessing the images
====================

It is possible to easily print an entity icon using the function
``elgg_view_entity_icon()``:

.. code-block:: php

	echo elgg_view_entity_icon($entity, 'medium');

If you need more control, you can access a single file using the
``ElggFile`` class:

.. code-block:: php

	$file = new ElggFile();
	$file->owner_guid = $guid;
	$file->setFilename("icon/{$size}.jpg");

	// Get location on disk
	$file->getFilenameOnFilestore();

	// Get size of the file in bytes
	$file->getSize();
