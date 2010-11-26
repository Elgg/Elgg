<?php

    /**
	 * Elgg Pages welcome message
	 * 
	 * @package ElggPages
	 */
	 
	 if($vars['entity']){
    	 
    	 foreach($vars['entity'] as $welcome){
    	 
    	    echo "<div class=\"contentWrapper pageswelcome\">" . $welcome->description . "</div>";
    	    
	    }
    	 
	 } else {

?>

<div class="contentWrapper pageswelcome"><p><?php echo sprintf(elgg_echo('pages:welcomemessage'), $vars['config']->sitename); ?></p></div>
    
<?php
    }
?>