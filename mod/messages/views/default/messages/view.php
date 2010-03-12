<?php
/**
 * Elgg messages view page
 *
 * @package ElggMessages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 *
 * @uses $vars['entity'] An array of messages to view
 * @uses $vars['page_view'] This is the page the messages are being accessed from; inbox or sentbox
 *
 */

$limit = $vars['limit']; if (empty($limit)) $limit = 10;
$offset = $vars['offset']; if (!isset($offset)) $offset = 0;

// If there are any messages to view, view them
if (isloggedin())
if (is_array($vars['entity']) && sizeof($vars['entity']) > 0) {

	// get the correct display for the inbox view
	if($vars['page_view'] == "inbox") {

		$counter = 0;

		foreach($vars['entity'] as $message) {
			if ($message->owner_guid == $vars['user']->guid || $message->toId == $vars['user']->guid) {
				
				//make sure to only display the messages that have not been 'deleted' (1 = deleted)
				if($message->hiddenFrom != 1){
					// check to see if the message has been read, if so, set the correct container class
					if($message->readYet == 1){
		                echo "<div class='message read clearfloat'>";
		            }else{
		                echo "<div class='message notread clearfloat'>";
		            }
				    // get the icon of the user who owns the message
				    $from = get_entity($message->fromId);
					echo "<div class='entity_listing_icon'>".elgg_view("profile/icon",array('entity' => $from, 'size' => 'tiny'))."</div>";
					// message block (message sender, message subject, delete checkbox)
					echo "<div class='entity_listing_info'><div class='message_sender'>".$from->name."<p class='entity_subtext'>".friendly_time($message->time_created)."</p></div>";
					// display message subject
					echo "<div class='message_subject'>";
					// display delete button
					echo "<span class='delete_button'>" . elgg_view("output/confirmlink", array(
						'href' => $vars['url'] . "action/messages/delete?message_id=" . $message->getGUID() . "&type=inbox&submit=" . urlencode(elgg_echo('delete')),
						'text' => elgg_echo('delete'),
						'confirm' => elgg_echo('deleteconfirm'),
					)) . "</span>";
					echo "<p class='entity_title'><input type='checkbox' name=\"message_id[]\" value=\"{$message->guid}\" />";
					echo "<a href=\"{$message->getURL()}\">" . $message->title . "</a></p>";								
				    echo "</div></div></div>"; // close the message container
				}//end of hiddenFrom if statement
				} // end of user check 
				$counter++;
				if ($counter == $limit) break;

			}//end of for each loop
		}//end of inbox if statement

		// get the correct display for the sentbox view
		if($vars['page_view'] == "sent") {

			$counter = 0;

			foreach($vars['entity'] as $message) {

				//make sure to only display the messages that have not been 'deleted' (1 = deleted)
				if($message->hiddenTo != 1){

					//get the correct user entity
					$user = get_entity($message->toId);
					echo "<div class='message sent clearfloat'>";				
					//get the icon for the user the message was sent to
					echo "<div class='entity_listing_icon'>".elgg_view("profile/icon",array('entity' => $user, 'size' => 'tiny'))."</div>";
					echo "<div class='entity_listing_info'><div class='message_sender'>".$_SESSION['user']->name."<p class='entity_subtext'>".friendly_time($message->time_created)."</p></div>";
					// display message subject
					echo "<div class='message_subject'>";
					//display the link to 'delete'
					echo "<div class='delete_button'>" . elgg_view("output/confirmlink", array(
						'href' => $vars['url'] . "action/messages/delete?message_id=" . $message->getGUID() . "&type=sent&submit=" . urlencode(elgg_echo('delete')),
						'text' => elgg_echo('delete'),
						'confirm' => elgg_echo('deleteconfirm'),
					)) . "</div>";
					echo "<p class='entity_title'><input type='checkbox' name=\"message_id[]\" value=\"{$message->guid}\" /> ";
					echo "<a href=\"{$message->getURL()}?type=sent\">" . $message->title . "</a></p>";
					echo "</div></div></div>"; // close the message container
				}//close hiddeTo if statement

				$counter++;
				if ($counter == $limit) break;

			}//close foreach

		}//close page_view sent if statement

		$baseurl = $_SERVER['REQUEST_URI'];
		$nav = '';

		if (sizeof($vars['entity']) > $limit) {
			$newoffset = $offset + $limit;
			$nexturl = elgg_http_add_url_query_elements($baseurl, array('offset' => $newoffset));

			$nav .= '<a class="pagination_previous" href="'.$nexturl.'">&laquo; ' . elgg_echo('previous') . '</a> ';
		}

		if ($offset > 0) {
			$newoffset = $offset - $limit;
			if ($newoffset < 0) $newoffset = 0;

			$prevurl = elgg_http_add_url_query_elements($baseurl, array('offset' => $newoffset));

			$nav .= '<a class="pagination_next" href="'.$prevurl.'">' . elgg_echo('next') . ' &raquo;</a> ';
		}


		if (!empty($nav)) {
			echo '<div class="pagination"><p>'.$nav.'</p></div>';
		}

} else {
	echo "<p>".elgg_echo("messages:nomessages")."</p>";
}
