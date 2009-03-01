<?php

    /**
	 * Elgg comments add form
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity']
	 */
	 
	 if (isset($vars['entity']) && isloggedin()) {
    	 
		 $form_body = "<div class=\"contentWrapper\"><p class='longtext_editarea'><label>".elgg_echo("generic_comments:text")."<br />" . elgg_view('input/longtext',array('internalname' => 'generic_comment')) . "</label></p>";
		 $form_body .= "<p>" . elgg_view('input/hidden', array('internalname' => 'entity_guid', 'value' => $vars['entity']->getGUID()));
		 $form_body .= elgg_view('input/submit', array('value' => elgg_echo("save"))) . "</p></div>";
		 
		 echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$vars['url']}action/comments/add"));

    }
    
?>