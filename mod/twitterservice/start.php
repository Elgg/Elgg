<?php
	/**
	 * Elgg Twitter Service.
	 * This plugin provides a wrapper around David Grudl's twitter library and exposes some basic functionality.
	 * 
	 * @package ElggSMS
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	require_once($CONFIG->pluginspath . "twitterservice/vendors/twitter/twitter.class.php"); 

	function twitterservice_init()
	{
		// Listen for wire create event
		register_elgg_event_handler('create','object','twitterservice_wire_listener');
		
	}
	
	/**
	 * Post a message to a twitter feed.
	 *
	 */
	function twitterservice_send($twittername, $twitterpass, $twittermessage)
	{
		$twitter = new Twitter($twittername, $twitterpass);
		
		return $twitter->send($twittermessage);
	}
	
	/**
	 * Listen for thewire and push messages accordingly.
	 */
	function twitterservice_wire_listener($event, $object_type, $object)
	{

		if (($object) && ($object->subtype == get_subtype_id('object', 'thewire')) )
		{
			if (get_plugin_usersetting('sendtowire', $object->owner_guid, 'twitterservice')=='yes')
			{
				$twittername = get_plugin_usersetting('twittername', $object->owner_guid, 'twitterservice');
				$twitterpass = get_plugin_usersetting('twitterpass', $object->owner_guid, 'twitterservice');
			
				if (($twittername) && ($twitterpass))
					twitterservice_send($twittername, $twitterpass, $object->description);
			}
		}
	}
	
	register_elgg_event_handler('init','system','twitterservice_init');
?>