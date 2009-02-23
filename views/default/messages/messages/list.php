<?php

	/**
	 * Elgg list system messages
	 * Lists system messages
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['object'] An array of system messages
	 */

	if (!empty($vars['object']) && is_array($vars['object'])) {

?>
<!-- used to fade out the system messages after 3 seconds -->
<script>
$(document).ready(function () {
	$('.messages').animate({opacity: 1.0}, 1000); 
	$('.messages').animate({opacity: 1.0}, 5000);
	$('.messages').fadeOut('slow');
      
	$('span.closeMessages a').click(function () {
		$(".messages").stop();
		$('.messages').fadeOut('slow');
	return false;
    });
    
	$('div.messages').click(function () {
		$(".messages").stop();
		$('.messages').fadeOut('slow');
	return false;
    });
});  
</script>

	<div class="messages">
	<span class="closeMessages"><a href="#"><?php echo elgg_echo('systemmessages:dismiss'); ?></a></span>
<?php

		
			foreach($vars['object'] as $message) {
				echo elgg_view('messages/messages/message',array('object' => $message));
			}

?>

	</div>
	
<?php

	}

?>