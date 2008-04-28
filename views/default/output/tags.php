<?php

	/**
	 * Elgg tags
	 * Displays a list of tags, separated by commas
	 * 
	 * Tags can be a single string (for one tag) or an array of strings
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['tags'] The tags to display
	 * 
	 */

	if (empty($vars['tags']) && !empty($vars['value']))
		$vars['tags'] = $vars['value'];
    if (!empty($vars['tags'])) {
        
        $string = "";
        if (!is_array($vars['tags']))
        	$vars['tags'] = array($vars['tags']);

        foreach($vars['tags'] as $tag) {
            if (!empty($tagstr)) {
                $tagstr .= ", ";
            }
           if (!empty($vars['type'])) {
               $type = "&type={$vars['type']}";
           } else {
               $type = "";
           }
           if (is_string($tag)) {
               $tagstr .= "<a href=\"{$vars['url']}search/?tag=".urlencode($tag) . "{$type}\">{$tag}</a>";
           }
        }
        echo $tagstr;
        
    }
?>