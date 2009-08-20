<?php
	/**
	 * Elgg htmLawed tag filtering.
	 * 
	 * @package ElgghtmLawed
	 * @author Curverider Ltd
	 * @author Brett Profitt
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise plugin
	 *
	 */
	function htmlawed_init()
	{
		/** For now declare allowed tags and protocols here, TODO: Make this configurable */
		global $CONFIG;
		$CONFIG->htmlawed_config = array(
			// seems to handle about everything we need.
			'safe' => true,
			'deny_attribute' => 'class',
			
			'schemes' => '*: http,https,ftp,news,mailto,rtsp,teamspeak,gopher,mms,callto;'
				. 'style: color,cursor,text-align,font-size,font-weight,font-style,border,margin,padding,float'
		);
		
		register_plugin_hook('validate', 'input', 'htmlawed_filter_tags', 1);
	}
	
	/**
	 * htmLawed filtering of tags, called on a plugin hook
	 *
	 * @param mixed $var Variable to filter
	 * @return mixed
	 */
	function htmlawed_filter_tags($hook, $entity_type, $returnvalue, $params)
	{
		$return = $returnvalue;
		$var = $returnvalue;
		
		if (@include_once(dirname(__FILE__) . "/vendors/htmLawed/htmLawed.php")) {
			
			global $CONFIG;
			
			$htmlawed_config = $CONFIG->htmlawed_config;
			
			if (!is_array($var)) {
				$return = "";
				$return = htmLawed($var, $htmlawed_config);
			} else {
				$return = array();
				
				foreach($var as $key => $el) {
					$return[$key] = htmLawed($el, $htmlawed_config);
				}
			}
		}
	
		return $return;
	}
	
	
	register_elgg_event_handler('init','system','htmlawed_init');
        
?>
