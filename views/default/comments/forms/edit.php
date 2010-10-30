<?php
/**
 * Elgg comments add form
 *
 * @package Elgg
 *
 * @uses $vars['entity']
 */
	 
	 if (isset($vars['entity']) && isloggedin()) {
    	 
		 $form_body = "<div class='comment margin_top'><p class='longtext_inputarea'><label>".elgg_echo("generic_comments:text")."</label>" . elgg_view('input/longtext',array('internalname' => 'generic_comment')) . "</p>";
		 $form_body .= elgg_view('input/hidden', array('internalname' => 'entity_guid', 'value' => $vars['entity']->getGUID()));
		 $form_body .= elgg_view('input/submit', array('value' => elgg_echo("generic_comments:post"))) . "</div>";
		 
		 echo elgg_view('input/form', array('body' => $form_body, 'action' => elgg_get_site_url()."action/comments/add"));

    }
    
?>