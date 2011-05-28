<?php

	/**
	 * Elgg messages individual view
	 *
	 * @package ElggMessages
	 *
	 *
	 * @uses $vars['entity'] Optionally, the message to view
	 * @uses get_input('type') If the user accesses the message from their sentbox, this variable is passed
	 * and used to make sure the correct icon and name is displayed
	 */
	// set some variables to use below
	if(get_input("type") == "sent"){

		// send back to the users sentbox
		$url = $vars['url'] . "pg/messages/sent/";

		//this is used on the delete link so we know which type of message it is
		$type = "sent";

	} else {

		//send back to the users inbox
		$url = $vars['url'] . "pg/messages/inbox/" . $vars['user']->username;

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

?>
	<!-- get the correct return url -->
	<div id="messages_return"><!-- start of messages_return div -->
		<p><a href="<?php echo $url; ?>">&laquo; <?php echo elgg_echo('messages:back'); ?></a></p>
	</div><!-- end of messages_return div -->

	<div class="messages_single"><!-- start of the message div -->

		<div class="messages_single_icon"><!-- start of the message_user_icon div -->
			<!-- get the user icon, name and date -->
			<?php
				// we need a different user icon and name depending on whether the user is reading the message
				// from their inbox or sentbox. If it is the inbox, then the icon and name will be the person who sent
				// the message. If it is the sentbox, the icon and name will be the user the message was sent to
				if ($type == "sent") {

					//get an instance of the user who the message has been sent to so we can access the name and icon
					$user_object = get_entity($vars['entity']->toId);
					echo " " . elgg_view("profile/icon",array('entity' => $user_object, 'size' => 'tiny'));
					echo "<br class=\"clearfloat\" /><p>".elgg_echo('messages:to').": <b>" . $user_object->name . "</b><br />";
				} else {
					$entity = get_entity($vars['entity']->fromId);
					if ($entity) {
						echo " " . elgg_view("profile/icon", array('entity' => $entity, 'size' => 'tiny'));
						echo "<br class=\"clearfloat\" /><p>".elgg_echo('messages:from').": <b>" . $entity->name . "</b><br />";
					} else {
						echo "<br class=\"clearfloat\" /><p>".elgg_echo('messages:from').": <b>" . elgg_echo('messages:deleted_sender') . "</b><br />";
					}
				}
			?>
			<!-- get the time the message was sent -->
			<small><?php echo elgg_view_friendly_time($vars['entity']->time_created); ?></small>
			</p>
		</div><!-- end of the message_user_icon div -->

		<div class="message_body"><!-- start of div message_body -->

		<?php
			//if the message is a reply, display the message the reply was for
			//I need to figure out how to get the description out using -> (anyone?)
			if($main_message = $vars['entity']->getEntitiesFromRelationship("reply")){

				if($type == "sent"){
					echo "<div class='previous_message'><h3>".elgg_echo('messages:original').":</h3><p>";
				}else{
					echo "<div class='previous_message'><h3>".elgg_echo('messages:yours').":</h3><p>";
				}

				echo $main_message[0][description] . "</p></div>";

			}
		?>

		<!-- display the title -->
		<div class="actiontitle">
		<h3><?php echo $vars['entity']->title; ?></h3>
		</div>

		<!-- display the message -->
		<div class="messagebody">
		<p><?php echo elgg_view('output/longtext',array('value' => $vars['entity']->description)); ?></p>
		</div>

		<!-- display the edit options, reply and delete -->
		<div class="message_options"><!-- start of the message_options div -->

		<script type="text/javascript">
		$(document).ready(function () {
			// click function to toggle reply panel
			$('a.message_reply').click(function () {
				$('div#message_reply_form').slideToggle("medium");
				return false;
			});
		});
		</script>


			<p>
			<?php
			if($type != "sent") {
				if ($entity) {
					echo "<a href=\"javascript:void(0);\" class='message_reply'>"
						. elgg_echo('messages:answer') . "</a> &nbsp; ";
				}

				echo elgg_view("output/confirmlink", array(
					'href' => $vars['url'] . "action/messages/delete?message_id=" . $vars['entity']->getGUID() . "&type={$type}&submit=" . urlencode(elgg_echo('delete')),
					'text' => elgg_echo('delete'),
					'confirm' => elgg_echo('deleteconfirm'),
				));
			}
				?>
			</p>
		</div><!-- end of the message_options div -->

		</div><!-- end of div message_body -->
<?php
	// only show reply form if the sender hasn't been deleted.
	if ($entity) {
?>
		<!-- display the reply form -->
		<div id="message_reply_form">
			<form action="<?php echo $vars['url']; ?>action/messages/send" method="post" name="messageForm">
				<!-- populate the title space with the orginal message title, inserting re: before it -->
				<p><label><?php echo elgg_echo("messages:title"); ?>: <br /><input type='text' name='title' class="input-text" value="<?php echo htmlentities($reply_title, ENT_QUOTES, 'UTF-8'); ?>" /></label></p>
				<p class="longtext_editarea"><label><?php echo elgg_echo("messages:message"); ?>:</label></p>
				<div id="message_reply_editor">
				<?php

					echo elgg_view("input/longtext", array(
									"internalname" => "message",
									"value" => '',
													));

				?></div>

				<p>
						<?php
						// security tokens required.
						echo elgg_view('input/securitytoken');

								//pass across the guid of the message being replied to
								echo "<input type='hidden' name='reply' value='" . $vars['entity']->getGUID() . "' />";
								//pass along the owner of the message being replied to
								echo "<input type='hidden' name='send_to' value='" . $vars['entity']->fromId . "' />";

						?>
						<input type="submit" class="submit_button" value="<?php echo elgg_echo("messages:fly"); ?>" />
				</p>
			</form>
		</div><!-- end of div reply_form -->
<?php
	// end if for if user was deleted
	}
?>
	</div><!-- end of the message div -->

<?php
			}
	}
?>
