<?php

    /**
	 * Elgg groups plugin display topic posts
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

    //display follow up comments
    $count = $vars['entity']->countAnnotations('group_topic_post');
    $offset = (int) get_input('offset',0);
    
    foreach($vars['entity']->getAnnotations('group_topic_post', 50, $offset, "asc") as $post) {

    	$post->title = '';
    	$post->description = $post->value;
    	echo elgg_view('object/default', array('entity' => $post));
	    // echo elgg_view("forum/topicposts",array('entity' => $post));
		
	}

?>