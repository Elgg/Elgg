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

?>
	<div id="messages" /><!-- start the main messages wrapper div -->

<?php

			// get the correct display for the inbox view
			if($vars['page_view'] == "inbox") {

				$counter = 0;

				foreach($vars['entity'] as $message) {
					if ($message->owner_guid == $vars['user']->guid
						|| $message->toId == $vars['user']->guid) {

					//make sure to only display the messages that have not been 'deleted' (1 = deleted)
					if($message->hiddenFrom != 1){

						// check to see if the message has been read, if so, get the correct background color
						if($message->readYet == 1){
							echo "<div class=\"message_read\" />";
						}else{
							echo "<div class=\"message_notread\" />";
						}

						//set the table
						echo "<table width=\"100%\" cellspacing='0'><tr>";
						//get the icon of the user who owns the message
						$from = get_entity($message->fromId);
						echo "<td width='200px'>" . elgg_view("profile/icon",array('entity' => $from, 'size' => 'tiny')) . "<div class='msgsender'><b>" . $from->name . "</b><br /><small>" . friendly_time($message->time_created) . "</small></div></td>";
						//display the message title
						echo "<td><div class='msgsubject'>";
						echo "<input type=\"checkbox\" name=\"message_id[]\" value=\"{$message->guid}\" /> ";
						echo "<a href=\"{$message->getURL()}\">" . $message->title . "</a></div></td>";
						//display the link to 'delete'

						echo "<td width='70px'>";
						echo "<div class='delete_msg'>" . elgg_view("output/confirmlink", array(
																'href' => $vars['url'] . "action/messages/delete?message_id=" . $message->getGUID() . "&type=inbox&submit=" . urlencode(elgg_echo('delete')),
																'text' => elgg_echo('delete'),
																'confirm' => elgg_echo('deleteconfirm'),
															)) . "</div>";

						echo "</td></tr></table>";
						echo "</div>"; // close the message background div

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
						echo "<div class=\"message_sent\" />";
						echo "<table width=\"100%\" cellspacing='0'><tr>";

						//get the icon for the user the message was sent to
						echo "<tr><td width='200px'>" . elgg_view("profile/icon",array('entity' => $user, 'size' => 'tiny')) . "<div class='msgsender'><b>" . $user->name . "</b><br /><small>" . friendly_time($message->time_created) . "</small></div></td>";
						//display the message title
						echo "<td><div class='msgsubject'>";
						echo "<input type=\"checkbox\" name=\"message_id[]\" value=\"{$message->guid}\" /> ";
						echo "<a href=\"{$message->getURL()}?type=sent\">" . $message->title . "</a></div></td>";
						//display the link to 'delete'

						echo "<td width='70px'>";
							echo "<div class='delete_msg'>" . elgg_view("output/confirmlink", array(
							'href' => $vars['url'] . "action/messages/delete?message_id=" . $message->getGUID() . "&type=sent&submit=" . urlencode(elgg_echo('delete')),
							'text' => elgg_echo('delete'),
							'confirm' => elgg_echo('deleteconfirm'),
						)) . "</div>";
						echo "</td></tr></table></div>";

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
				echo '<div class="pagination"><p>'.$nav.'</p><div class="clearfloat"></div></div>';
			}

			echo "</div>"; // close the main messages wrapper div

	} else {

		echo "<div class=\"contentWrapper\"><p class='messages_nomessage_message'>" . elgg_echo("messages:nomessages") . "</p></div>";

	}//end of the first if statement
?>
