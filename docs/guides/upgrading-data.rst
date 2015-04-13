Upgrading plugin data
#####################

Sometimes you are forced to change the way how your plugin saves its data.
Maybe you need to start storing some value as an ``ElggEntity`` instead of
``ElggMetadata``. Or maybe you need to change the way how your plugin saves
its icons or other files in the dataroot. Or perhaps you simply need to
fix a typo in the name of a plugin setting.

In situation like this you need to add a feature that upgrades the old
data to the new structure. This makes sure that sites do not lose any of the
old data when they start using the newer version of your plugin.

Elgg core provides two interfaces for writing such upgrades:

``\Elgg\Upgrades\Upgrade`` should be used for quick and simple upgrades like:
 - Adding a new database column
 - Saving a default value for a setting introduced in the new version

``\Elgg\Upgrades\BatchUpgrade`` should be used for long upgrades that process multiple items:
 - Modifying a property in many different entities
 - Migrating datatype to another
 - Reorganizing structure of the datadir

Creating a new upgrade
======================

You can generate a new upgrade file by running the file ``.scripts/create_update.php``
in your plugin directory. Give the name of the upgrade as a paramerter. The name
should be in CamelCase and it will be used also as the name of the upgrade class.

.. code::sh

	php ../../.scripts/create_upgrade.php FixUserLocationData

The script will create the file
``mod/your_plugin/classes/Elgg/Upgrades/FixUserLocationData1234567890.php``
where the last part is timestamp of the file creation time.

.. note::

	Run the script without parameters to see an example of its usage.

Now open the file and implement at least the following methods:
 - ``getTitle()``
   - This should return a title for the upgrade
 - ``getDescription()``
   - This should return a description for the upgrade
 - ``isRequired()``
   - This method should check the database or the datadir and verify whether
     there is something that needs to be upgraded. The return value will
    be ``true`` or ``false`` depending on the result.
 - ``run()``
   - This method takes care of doing to actual upgrade

After implementing these methods you can go to ``http://www.your_site.com/upgrade.php``
and it should list the upgrades that you have added.

If you need to upgrade multiple items, you should change the class to
implement the ``BatchUpgrade`` instead. Make the following change to the file:

.. code::php

	class FixUserLocationData implements Upgrade {
	
to:

.. code::php

	class FixUserLocationData implements BatchUpgrade {

After this you will also need to implement the following methods:
 - ``setOffset()``
  - Sets offset for the next upgrade batch
 - ``getTotal()``
  - Return the total amount of items that need to be upgraded
 - ``getSuccessCount()``
  - Return the amount of items upgraded successfully during the current upgrade batch 
 - ``getErrorCount()``
  - Return amount of items that failed to get upgraded during the current batch
 - ``getNextOffset()``
  - If the upgrade doesn't delete the processed items, the return value should
    be the total amount successfully processed items.
  - If the upgrade does delete the succesfully processed items, the return
    value should be the total amount of errors that have happened during the
    upgrade. This allows those items to be skipped in the next batch.

.. code::php

	public function run() {
		$users = elgg_get_entities(array(
			'type' => 'user',
			'offset' => $this->offset,
		));
		
		foreach ($users as $user) {
			if (do_something($user)) {
				$this->success_count++;
			} else {
				$this->error_count++;
			}
			
			$this->offset++;
		}
	}

.. note::

	There are also other methods that get added to the class. They are used
	internally by the upgrading system, and you shouldn't modify their return
	value manually.
