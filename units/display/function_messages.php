<?php

	global $messages;
	if (sizeof($messages) > 0) {

?>

		<table width="100%" id="messages">
			<tr>
				<td>
					<ul>
<?php
		foreach($messages as $message) {
?>
						<li><?php echo $message; ?></li>
<?php
		}
?>
					</ul>
				</td>
			</tr>
		</table>

<?php

	}

?>