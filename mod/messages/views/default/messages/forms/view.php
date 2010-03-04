<?php
/**
* View message
* 
* @package ElggMessages
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
* @author Curverider Ltd <info@elgg.com>
* @copyright Curverider Ltd 2008-2010
* @link http://elgg.com/
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

echo elgg_view('input/form',array('body' => $body, 'action' => $vars['url'] . 'action/messages/delete', 'method' => 'post'));