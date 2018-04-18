<?php
	$user = new ElggUser();
	$user->username = 'theme_sandbox';

	$group = new ElggGroup();
	
	$sizes = ['large', 'medium', 'small', 'tiny'];
?>
<table class="elgg-table">
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
			echo elgg_view_entity_icon($user, $size, ['use_hover' => false, 'use_link' => false]);
			echo '</td>';
		}
		?>
	</tr>
	<tr>
		<th>Group</th>
		<?php
		foreach ($sizes as $size) {
			echo '<td>';
			echo elgg_view_entity_icon($group, $size, ['use_hover' => false, 'use_link' => false]);
			echo '</td>';
		}
		?>
	</tr>
</table>
