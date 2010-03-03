<?php

	/**
	 * Elgg hoverover extender for messages
	 * 
	 * @package ElggMessages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 
	 //need to be logged in to send a message
	 if (isloggedin()) {

?>

	<p class="user_menu_messages">
		<a href="<?php echo $vars['url']; ?>mod/messages/send.php?send_to=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("messages:sendmessage"); ?></a>	
	</p>
	
<?php

	}

?>