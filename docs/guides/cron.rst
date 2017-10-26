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
	
	function my_plugin_cron_handler($hook, $period, $return, $params) {
	    $start_time = elgg_extract('time', $params);	
	}

.. seealso::

   :doc:`/design/events` has more information about hooks
