<?php
/**
 * Icons CSS
 */

$title = 'Icons';

require dirname(__FILE__) . '/head.php';

$url = current_page_url();

?>
<style>li {margin: 10px; float: left;} ul {background-color: #e0e0e0;}</style>
<body>
	<div class="elgg-page mal">
		<h1 class="mbs">
			<a href="index.php">Index</a> > <a href="<?php echo $url; ?>"><?php echo $title; ?></a>
		</h1>
		<div class="mbl">
			<a href="widgets.php">< previous</a>&nbsp;&nbsp;next >
		</div>
		<h2>Icon Sprites</h2>
		<ul class="mbl clearfix">
			<li><span class="elgg-icon elgg-icon-settings"></span>Settings</li>
			<li><span class="elgg-icon elgg-icon-friends"></span>Friends</li>
			<li><span class="elgg-icon elgg-icon-help"></span>Help</li>
			<li><span class="elgg-icon elgg-icon-delete"></span>Delete</li>
			<li><span class="elgg-icon elgg-icon-likes"></span>Likes</li>
			<li><span class="elgg-icon elgg-icon-liked"></span>Liked</li>
			<li><span class="elgg-icon elgg-icon-following"></span>Following</li>
			<li><span class="elgg-icon elgg-icon-dragger"></span>Dragger</li>
			<li><span class="elgg-icon elgg-icon-rss"></span>RSS</li>
			<li><span class="elgg-icon elgg-icon-arrow-s"></span>Arrow S</li>
			<li><span class="elgg-icon elgg-icon-hover-menu"></span>Hover Menu</li>
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
</body>
</html>