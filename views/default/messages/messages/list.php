<?php
/**
 * Elgg list system messages
 * Lists system messages
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] An array of system messages
 */

if (!empty($vars['object']) && is_array($vars['object'])) {

?>
<div id="elgg_system_message" class="hidden radius8">
<script type="text/javascript">$(document).ready(function(){ elgg_system_message() });</script>
<?php
	foreach($vars['object'] as $message) {
		echo elgg_view('messages/messages/message',array('object' => $message));
	}
?>
</div>
<?php

}
