Upgrading plugin data
#####################

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

.. code:: php

	return [
		'upgrades' => [
			'Blog\Upgrades\AccessLevelFix',
			'Blog\Upgrades\DraftStatusUpgrade',
		]
	];

The class names in the example refer to the classes:
 - `mod/blog/classes/Blog/Upgrades/AccessLevelFix`
 - `mod/blog/classes/Blog/Upgrades/DraftStatusUpgrade`

The upgrade class
-----------------

A class implemening the ``Elgg\Upgrade\Batch`` interface has a lot of freedom
on how it wants to handle the actual processing of the data. It must however
declare some constant variables and also take care of marking whether each
processed item was upgraded successfully or not.

The basic structure of the class is the following:

.. code:: php

	<?php
	
	namespace Blog\Upgrades;
	
	use Elgg\Upgrade\Batch;
	use Elgg\Upgrade\Result;
	
	/**
	 * Fixes invalid blog access values
	 */
	class AccessLevelFix implements BatchUpgrade {
		const INCREMENT_OFFSET = true;
		
		const VERSION = 2016120300;
		
		/**
		 * Run the upgrade
		 *
		 * @param Result $result
		 * @param int    $offset
		 * @return Result result
		 */
		public function run(Result $result, $offset) {
		
		}
	}

Class constants
~~~~~~~~~~~~~~~

The class must declare the following constant variables:

INCREMENT_OFFSET
^^^^^^^^^^^^^^^^

This is a boolean value that tells Elgg core whether it should increment
the offset of the upgrade after each run. If the upgrade leaves the data
itself intact and simply modifies it in some way, the value should be
set to ``true``. If the upgrade either moves or completely deletes the
items within the data, the value should be ``false``.  

VERSION
^^^^^^^

The version constant tells the date when the upgrade was added. It consists
of eight digits and is in format ``yyyymmddnn`` where:

   - ``yyyy`` is the year
   - ``mm`` is the month (with leading zero)
   - ``dd`` is the day (with leading zero)
   - ``nn`` is an incrementing number (starting from ``00``) that is used in case
     two separate upgrades have been added during the same day

Class methods
~~~~~~~~~~~~~

countItems()
^^^^^^^^^^^^

Counts and returns the total amount of items that need to be processed
by the upgrade.

run()
^^^^^

Takes care of the actual upgrade. It takes two parameters:

  - ``$result``: An instance of ``Elgg\Upgrade\Result`` object
  - ``$offset``: The offset where the next upgrade batch should start 
 
 For each item the method processes, it must call either:
 
  - ``$result->addSuccesses()``: If the item was upgraded successfully
  - ``$result->addFailures()``: If it failed to upgrade the item

Both methods default to one item, but you can optionally pass in
the number of items.
  
Additionally it can set as many error messages as it sees necessary in case
something goes wrong:

 - ``$result->addError("Error message goes here")``

In most cases the ``$offset`` parameter is passed directly to one of the
``elgg_get_entities*()`` functions:

.. code:: php

	/**
	 * Process blog posts
	 *
	 * @param Result $result Object that holds results of the batch
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
			// Do something to the blog objects here
			if (do_something($blog)) {
				$result->addSuccesses()
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
