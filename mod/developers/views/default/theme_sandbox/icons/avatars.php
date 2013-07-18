<?php
	$user = new ElggUser();
	$group = new ElggGroup();
	
	$sizes = array('large', 'medium', 'small', 'tiny');
?>
<table class="theme-sandbox-table">
	<tr>
		<th></th>
		<?php
			foreach ($sizes as $size) {
				echo "<th>$size</th>";
			}
		?>
	</tr>
	<tr>
		<th>User</th>
		<?php
			foreach ($sizes as $size) {
				echo '<td>';
				echo elgg_view_entity_icon($user, $size, array('use_hover' => false));
				echo '</td>';
			}
		?>
	</tr>
	<tr>
		<th>Group</th>
		<?php
			foreach ($sizes as $size) {
				echo '<td>';
				echo elgg_view_entity_icon($group, $size, array('use_hover' => false));
				echo '</td>';
			}
		?>
	</tr>
</table>
