Cron
====

If you setup cron correctly as described in :doc:`/admin/cron` 
special hooks will be triggered so you can register for these hooks from your own code.

The example below registers a function for the daily cron.

.. code-block:: php
	
	function my_plugin_init() {
	    elgg_register_plugin_hook_handler('cron', 'daily', 'my_plugin_cron_handler');
	}

If timing is important in your cron hook be advised that the functions
are executed in order of registration. This could mean that your function may
start (a lot) later then you may have expected. However the parameters provided 
in the hook contain the original starting time of the cron, so you can always use that
information.

.. code-block:: php
	
	function my_plugin_cron_handler(\Elgg\Hook $hook) {
	    $start_time = $hook->getParam('time');	
	}

Custom intervals
----------------

Plugin developers can configure there own custom intervals.

.. warning::

	It's **NOT** recommended to do this, as the users of your plugin may also need to configure your custom interval.
	Try to work with the default intervals. If you only need to do a certain task at for example 16:30 you can use the ``halfhour`` 
	interval and check that ``date('G', $start_time) == 16`` and ``date('i', $start_time) == 30`` 

.. code-block:: php

	elgg_register_plugin_hook_hander('cron:intervals', 'system', 'my_custom_cron_interval');

	function my_custom_cron_interval(\Elgg\Hook $hook) {
		$cron_intervals = $hook->getValue();
		
		// add custom interval
		$cron_intervals['my_custom_interval'] = '30 16 * * *'; // every day at 16:30 hours
		
		return $cron_intervals;
	}

.. seealso::

   - :doc:`/design/events` has more information about hooks
   - For more information about the supported cron interval definition see `the PHP Scheduler documentation`_

.. _the PHP Scheduler documentation: https://github.com/peppeocchi/php-cron-scheduler#schedules-execution-time
