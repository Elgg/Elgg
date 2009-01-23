<?php

	/**
	 * Elgg generic comment
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 */


	$vars['entity'] = get_entity($vars['annotation']->entity_guid);
	$title = substr($vars['annotation']->value,0,32);
		if (strlen($vars['annotation']->value) > 32)
			$title .= " ...";
	
?>

	<item>
	  <guid isPermaLink='true'><?php echo $vars['entity']->getURL(); ?>#<?php echo $vars['annotation']->id; ?></guid>
	  <pubDate><?php echo date("r",$vars['entity']->time_created) ?></pubDate>
	  <link><?php echo $vars['entity']->getURL(); ?>#<?php echo $vars['annotation']->id; ?></link>
	  <title><![CDATA[<?php echo $title; ?>]]></title>
	  <description><![CDATA[<?php echo (autop($vars['annotation']->value)); ?>]]></description>
	</item>
