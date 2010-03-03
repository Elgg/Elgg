<?php

	$body = elgg_view("messages/view",$vars);
	
	$body .= '<div class="messages_buttonbank">';
	$body .= '<input type="hidden" name="type" value="'.$vars['page_view'].'" />';
	$body .= '<input type="hidden" name="offset" value="'.$vars['offset'].'" />';
	$body .= '<input type="submit" name="submit" value="'.elgg_echo('delete').'" /> ';
	$body .= '<input type="submit" name="submit" value="'.elgg_echo('messages:markread').'" /> ';
	$body .= '<input type="button" onclick="javascript:$(\'input[type=checkbox]\').click();" value="'.elgg_echo('messages:toggle').'" />';
	$body .= '</div>';
	
	echo elgg_view('input/form',array('body' => $body, 'action' => $vars['url'] . 'action/messages/delete', 'method' => 'post'));

?>