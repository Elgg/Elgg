<?php
/**
 * Elgg messages topbar extender
 * 
 * @package ElggMessages
 */

gatekeeper();

//get unread messages
$num_messages = messages_count_unread();
if($num_messages){
	$num = $num_messages;
} else {
	$num = 0;
}

if($num == 0) {
?>
	<a href="<?php echo elgg_get_site_url(); ?>pg/messages/<?php echo get_loggedin_user()->username; ?>" class="privatemessages" >&nbsp;</a>
<?php
    }else{
?>
    <a href="<?php echo elgg_get_site_url(); ?>pg/messages/<?php echo get_loggedin_user()->username; ?>" class="privatemessages new" ><span><?php echo $num; ?></span></a>
<?php
    }
