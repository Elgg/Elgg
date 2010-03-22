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

// login check already performed in profile/icon
?>
<li class="user_menu_profile">
	<a class="send_message" href="<?php echo $vars['url']; ?>mod/messages/send.php?send_to=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("messages:sendmessage"); ?></a>	
</li>