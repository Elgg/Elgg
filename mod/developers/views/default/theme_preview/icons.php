<?php
/**
 * Icons CSS
 */

?>
<style>li {margin: 10px; float: left;} ul {background-color: #e0e0e0;}</style>
<div class="elgg-page mal">
	<?php echo elgg_view('theme_preview/header', $vars); ?>
	<h2>Icon Sprites</h2>
	<ul class="mbl clearfix">
	<?php 
		$icons = array(
			'settings' => 'Settings', 
			'friends' => 'Friends', 
			'help' => 'Help', 
			'delete' => 'Delete', 
			'thumbs-up' => 'Thumbs Up',
			'thumbs-up-alt' => 'Thumbs Up Alternate',
			'following' => 'Following', 
			'dragger' => 'Dragger', 
			'rss' => 'RSS', 
			'arrow-s' => 'Arrow S', 
			'hover-menu' => 'Hover Menu',
		);
		
		foreach ($icons as $icon_id => $icon_label) {
			echo "<li>" . elgg_view_icon($icon_id) . $icon_label . "</li>";
		}
	
	?>
	</ul>
	<h2>Ajax loader</h2>
	<div class="mbl">
		<?php echo elgg_view('graphics/ajax_loader', array('hidden' => false)); ?>
	</div>
	<h2>Avatars</h2>
	<div class="mbl">
		<?php
			$user = new ElggUser();
			$sizes = array('large', 'medium', 'small', 'tiny');
			echo '<table>';
			echo '<tr>';
			foreach ($sizes as $size) {
				echo "<td class=\"center\"><h4>$size</h4></td>";
			}
			echo '</tr>';
			echo '<tr>';
			foreach ($sizes as $size) {
				echo '<td class="phs">';
				echo elgg_view_entity_icon($user, $size, array('hover' => false));
				echo '</td>';
			}
			echo '</tr>';
			echo '</table>';
		?>
	</div>
</div>
