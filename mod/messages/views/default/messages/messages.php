<?php
/**
 * Elgg messages individual view
 *
 * @package ElggMessages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 *
 *
 * @uses $vars['entity'] Optionally, the message to view
 * @uses get_input('type') If the user accesses the message from their sentbox, this variable is passed
 * and used to make sure the correct icon and name is displayed
 */
// set some variables to use below
if(get_input("type") == "sent"){
	// send back to the users sentbox
	$url = $vars['url'] . "mod/messages/sent.php";
	// set up breadcrumbs context
	elgg_push_breadcrumb(elgg_echo('messages:sent'), $url);
	//this is used on the delete link so we know which type of message it is
	$type = "sent";
} else {
	//send back to the users inbox
	$url = $vars['url'] . "pg/messages/" . $vars['user']->username;
	// set up breadcrumbs context
	elgg_push_breadcrumb(elgg_echo('messages:inbox'), $url);
	//this is used on the delete link so we know which type of message it is
	$type = "inbox";
}

// fix for RE: RE: RE: that builds on replies
$reply_title = $vars['entity']->title;
if (strncmp($reply_title, "RE:", 3) != 0) {
	$reply_title = "RE: " . $reply_title;
}

if (isloggedin())
	if (isset($vars['entity'])) {
		if ($vars['entity']->toId == $vars['user']->guid
			|| $vars['entity']->owner_guid == $vars['user']->guid) {
			// display breadcrumbs
			elgg_push_breadcrumb($vars['entity']->title);
			echo elgg_view('navigation/breadcrumbs');
?>
<!-- display the content header block -->
			<div id="content_header" class="clearfloat">
				<div class="content_header_title"><h2><?php echo $vars['entity']->title; ?></h2></div>
				<div class="content_header_options">
					<a class="action_button message_reply" onclick="elgg_slide_toggle(this,'#elgg_page_contents','#message_reply_form');"><?php echo elgg_echo('messages:answer'); ?></a>
					<?php echo elgg_view("output/confirmlink", array(
						'href' => $vars['url'] . "action/messages/delete?message_id=" . $vars['entity']->getGUID() . "&type={$type}&submit=" . elgg_echo('delete'),
						'text' => elgg_echo('delete'),
						'confirm' => elgg_echo('deleteconfirm'),
						'class' => "action_button disabled"
						));
				?>
				</div>
			</div>

				<div class="entity_listing messages clearfloat">
					<?php
						// we need a different user icon and name depending on whether the user is reading the message
						// from their inbox or sentbox. If it is the inbox, then the icon and name will be the person who sent
						// the message. If it is the sentbox, the icon and name will be the user the message was sent to
						if($type == "sent"){
							//get an instance of the user who the message has been sent to so we can access the name and icon
							$user_object = get_entity($vars['entity']->toId);
							$message_icon = elgg_view("profile/icon",array('entity' => $user_object, 'size' => 'tiny'));
							$message_owner = elgg_echo('messages:to').": <a href='{$vars['url']}pg/profile/".$user_object->username."'>".$user_object->name."</a>";
						}else{
							$user_object = get_entity($vars['entity']->fromId);
							$message_icon = elgg_view("profile/icon",array('entity' => $user_object, 'size' => 'tiny'));
							$message_owner = elgg_echo('messages:from').": <a href='{$vars['url']}pg/profile/".$user_object->username."'>".get_entity($vars['entity']->fromId)->name."</a>";
						}
					?>
					<div class="entity_listing_icon"><?php echo $message_icon ?></div>
					<div class="entity_listing_info"><p><?php echo $message_owner ?></p>
						<p class="entity_subtext"><?php echo friendly_time($vars['entity']->time_created); ?></p>
					</div>
				</div>

				<div class="messagebody margin_top clearfloat">
					<?php
						// if the message is a reply, display the message the reply was for
						// @todo I need to figure out how to get the description out using -> (anyone?)
						if($main_message = $vars['entity']->getEntitiesFromRelationship("reply")){
							echo $main_message[0][description];
						}
					?>
					<!-- display the message -->
					<?php echo elgg_view('output/longtext',array('value' => $vars['entity']->description)); ?>
				</div>

				<!-- reply form -->
				<div id="message_reply_form" class="hidden margin_top">
					<h2><?php echo elgg_echo('messages:answer'); ?></h2>
					<form action="<?php echo $vars['url']; ?>action/messages/send" method="post" name="messageForm" class="margin_top" id="messages_send_form">
						<?php echo elgg_view('input/securitytoken'); ?>
						<p><label><?php echo elgg_echo("messages:title"); ?>: <br /><input type='text' name='title' class="input_text" value='<?php echo $reply_title; ?>' /></label></p>
						<p class="longtext_editarea"><label><?php echo elgg_echo("messages:message"); ?>:</label></p>
						<div id="message_reply_editor">
						<?php echo elgg_view("input/longtext", array(
											"internalname" => "message",
											"value" => '',
															));
						?></div>

					<?php
						//pass across the guid of the message being replied to
						echo "<input type='hidden' name='reply' value='" . $vars['entity']->getGUID() . "' />";
						//pass along the owner of the message being replied to
						echo "<input type='hidden' name='send_to' value='" . $vars['entity']->fromId . "' />";
					?>
					<input type="submit" class="submit_button" value="<?php echo elgg_echo("messages:fly"); ?>" />
					</form>
				</div>

<?php
	}
}