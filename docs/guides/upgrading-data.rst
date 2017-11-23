Writing a plugin upgrade
########################

Every now and then there comes a time when a plugin needs to change the contents
or the structure of the data it has stored either in the database or the dataroot.

The motivation for this may be that the data structure needs to be converted
to more efficient or flexible structure. Or perhaps due to a bug the data items have
been saved in an invalid way, and they needs to be converted to the correct format.

Migrations and convertions like this may take a long time if there is a lot of
data to be processed. This is why Elgg provides the ``Elgg\Upgrade\Batch`` interface
that can be used for implementing long-running upgrades.

Declaring a plugin upgrade
--------------------------

Plugin can communicate the need for an upgrade under the ``upgrades`` key in
``elgg-plugin.php`` file. Each value of the array must be the fully qualified
name of an upgrade class that implements the ``Elgg\Upgrade\Batch`` interface.

Example from ``mod/blog/elgg-plugin.php`` file:

.. code-block:: php

	return [
		'upgrades' => [
			Blog\Upgrades\AccessLevelFix::class,
			Blog\Upgrades\DraftStatusUpgrade::class,
		]
	];

The class names in the example refer to the classes:
 - ``mod/blog/classes/Blog/Upgrades/AccessLevelFix``
 - ``mod/blog/classes/Blog/Upgrades/DraftStatusUpgrade``

.. note:: Elgg core upgrade classes can be declared in ``engine/lib/upgrades/async-upgrades.php``.

The upgrade class
-----------------

A class implementing the ``Elgg\Upgrade\Batch`` interface has a lot of freedom
on how it wants to handle the actual processing of the data. It must however
declare some constant variables and also take care of marking whether each
processed item was upgraded successfully or not.

The basic structure of the class is the following:

.. code-block:: php

	<?php
	
	namespace Blog\Upgrades;

	use Elgg\Upgrade\Batch;
	use Elgg\Upgrade\Result;
	
	/**
	 * Fixes invalid blog access values
	 */
	class AccessLevelFix implements Batch {

		/**
		 * Version of the upgrade
		 *
		 * @return int
		 */
		public function getVersion() {
			return 2016120300;
		}

		/**
		 * Should the run() method receive an offset representing all processed items?
		 *
		 * @return bool
		 */
		public function needsIncrementOffset() {
			return true;
		}
		
		/**
		 * Should this upgrade be skipped?
		 *
		 * @return bool
		 */
		public function shouldBeSkipped() {
			return false;
		}
		
		/**
		 * The total number of items to process in the upgrade
		 *
		 * @return int
		 */
		public function countItems() {
			// return count of all blogs
		}
		
		/**
		 * Runs upgrade on a single batch of items
		 *
		 * @param Result $result Result of the batch (this must be returned)
		 * @param int    $offset Number to skip when processing
		 *
		 * @return Result Instance of \Elgg\Upgrade\Result
		 */
		public function run(Result $result, $offset) {
			// fix 50 blogs skipping the first $offset
		}
	}

.. warning:: Do not assume when your class will be instantiated or when/how often its public methods will be called.

Class methods
~~~~~~~~~~~~~

getVersion()
^^^^^^^^^^^^

This must return an integer representing the date the upgrade was added. It consists
of eight digits and is in format ``yyyymmddnn`` where:

   - ``yyyy`` is the year
   - ``mm`` is the month (with leading zero)
   - ``dd`` is the day (with leading zero)
   - ``nn`` is an incrementing number (starting from ``00``) that is used in case
     two separate upgrades have been added during the same day

shouldBeSkipped()
^^^^^^^^^^^^^^^^^

This should return ``false`` unless the upgrade won't be needed.

.. warning:: If ``true`` is returned the upgrade cannot be run later.

needsIncrementOffset()
^^^^^^^^^^^^^^^^^^^^^^

If ``true``, your ``run()`` method will receive as ``$offset`` the number of items
aready processed. This is useful if you are only modifying data, and need to use the
``$offset`` in a function like ``elgg_get_entities()`` to know how many you've already
handled.

If ``false``, your ``run()`` method will receive as ``$offset`` the total number of
failures. ``false`` should be used if your process deletes or moves data out of the
way of the process. E.g. if you delete 50 objects on each ``run()``, you don't really
need the ``$offset``.

countItems()
^^^^^^^^^^^^

Get the total number of items to process during the upgrade. If unknown, ``Batch::UNKNOWN_COUNT``
can be returned, but ``run()`` must manually mark the upgrade complete.

run()
^^^^^

This must perform a portion of the actual upgrade. And depending on how long it takes, it may be
called multiple times during a single request.

It receives two arguments:

  - ``$result``: An instance of ``Elgg\Upgrade\Result`` object
  - ``$offset``: The offset where the next upgrade portion should start (or total number of failures)
 
For each item the method processes, it must call either:
 
  - ``$result->addSuccesses()``: If the item was upgraded successfully
  - ``$result->addFailures()``: If it failed to upgrade the item

Both methods default to one item, but you can optionally pass in the number of items.
  
Additionally it can set as many error messages as it sees necessary in case something goes wrong:

 - ``$result->addError("Error message goes here")``

If ``countItems()`` returned ``Batch::UNKNOWN_COUNT``, then at some point ``run()`` must call
``$result->markComplete()`` to finish the upgrade.

In most cases your ``run()`` method will want to pass the ``$offset`` parameter to one of the
``elgg_get_entities()`` functions:

.. code-block:: php

	/**
	 * Process blog posts
	 *
	 * @param Result $result The batch result (will be modified and returned)
	 * @param int    $offset Starting point of the batch
	 * @return Result Instance of \Elgg\Upgrade\Result;
	 */
	public function run(Result $result, $offset) {
		$blogs = elgg_get_entitites([
			'type' => 'object'
			'subtype' => 'blog'
			'offset' => $offset,
		]);
		
		foreach ($blogs as $blog) {
			if ($this->fixBlogPost($blog)) {
				$result->addSuccesses();
			} else {
				$result->addFailures();
				$result->addError("Failed to fix the blog {$blog->guid}.");
			}
		}
		
		return $result;
	}


Administration interface
------------------------

Each upgrade implementing the ``Elgg\Upgrade\Batch`` interface gets
listed in the admin panel after triggering the site upgrade from the
Administration dashboard.

While running the upgrades Elgg provides:

 - Estimated duration of the upgrade
 - Count of processed items
 - Number of errors
 - Possible error messages
