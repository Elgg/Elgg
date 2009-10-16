<?php
/**
 * Elgg list errors
 * Lists error messages
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['object'] An array of error messages
 */

if (!empty($vars['object']) && is_array($vars['object'])) {

?>
<style type="text/css">
.messages_error {
	border:1px solid #D3322A;
	background:#F7DAD8;
	color:#000000;
	padding:3px 10px 3px 10px;
	margin:20px 0px 0px 0px;
	z-index: 9999;
	position:relative;
	width:95%;
}
</style>
	<div class="database_settings">
		<div class="messages_errors">

<?php
			foreach($vars['object'] as $error) {
				echo elgg_view('messages/errors/error',array('object' => $error));
				//echo "<hr />";
			}
?>
		</div>
	</div>
<?php
}