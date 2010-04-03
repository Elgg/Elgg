<?php

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'thewire' => "The wire",
			'thewire:user' => "%s's wire",
			'thewire:posttitle' => "%s's notes on the wire: %s",
			'thewire:everyone' => "All wire posts",
	
			'thewire:read' => "Wire posts",
			
			'thewire:strapline' => "%s",
	
			'thewire:add' => "Post to the wire",
		    'thewire:text' => "A note on the wire",
			'thewire:reply' => "Reply",
			'thewire:via' => "via",
			'thewire:wired' => "Posted to the wire",
			'thewire:charleft' => "characters left",
			'item:object:thewire' => "Wire posts",
			'thewire:notedeleted' => "note deleted",
			'thewire:doing' => "What are you doing? Tell everyone on the wire:",
			'thewire:newpost' => 'New wire post',
			'thewire:addpost' => 'Post to the wire',
			'thewire:by' => "Wire post by %s",

	
        /**
	     * The wire river
	     **/
	        
	        //generic terms to use
	        'thewire:river:created' => "%s posted",
	        
	        //these get inserted into the river links to take the user to the entity
	        'thewire:river:create' => "on the wire.",
	        
	    /**
	     * Wire widget
	     **/
	     
	        'thewire:sitedesc' => 'This widget shows the latest site notes posted to the wire',
	        'thewire:yourdesc' => 'This widget displays your latest wire posts',
	        'thewire:friendsdesc' => 'This widget will show the latest from your friends on the wire',
	        'thewire:friends' => 'Your friends on the wire',
	        'thewire:num' => 'Number of items to display',
	        'thewire:moreposts' => 'More wire posts',
	        
	
		/**
		 * Status messages
		 */
	
			'thewire:posted' => "Your message was successfully posted to the wire.",
			'thewire:deleted' => "Your wire post was successfully deleted.",
	
		/**
		 * Error messages
		 */
	
			'thewire:blank' => "Sorry; you need to actually put something in the textbox before we can save it.",
			'thewire:notfound' => "Sorry; we could not find the specified wire post.",
			'thewire:notdeleted' => "Sorry; we could not delete this wire post.",
	
	
		/**
		 * Settings
		 */
			'thewire:smsnumber' => "Your SMS number if different from your mobile number (mobile number must be set to public for the wire to be able to use it). All phone numbers must be in international format.",
			'thewire:channelsms' => "The number to send SMS messages to is <b>%s</b>",
			
	);
					
	add_translation("en",$english);

?>