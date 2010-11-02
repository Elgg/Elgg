<?php
/**
* View message
* 
* @package ElggMessages
*/

$body = elgg_view("messages/view",$vars);

$body .= '<div class="messages_buttonbank">';
$body .= '<input type="hidden" name="type" value="'.$vars['page_view'].'" />';
$body .= '<input type="hidden" name="offset" value="'.$vars['offset'].'" />';
$body .= '<input type="submit" name="submit" value="'.elgg_echo('delete').'" /> ';

if($vars['page_view'] == "inbox"){
	$body .= '<input type="submit" name="submit" value="'.elgg_echo('messages:markread').'" /> ';
}

$body .= '<input class="cancel_button" type="button" onclick="javascript:$(\'input[type=checkbox]\').click();" value="'.elgg_echo('messages:toggle').'" />';
$body .= '</div>';

echo elgg_view('input/form',array('body' => $body, 'action' => 'action/messages/delete', 'method' => 'post', 'internalid' => 'messages_list_form'));