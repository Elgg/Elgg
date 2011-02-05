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
			<li><span class="elgg-icon elgg-icon-rss"></span>RSS</li>
			<li><span class="elgg-icon elgg-icon-arrow-s"></span>Arrow S</li>
			<li><span class="elgg-icon elgg-icon-hover-menu"></span>Hover Menu</li>
		</ul>
		<h2>Ajax loader</h2>
		<div class="mbl">
			<?php echo elgg_view('graphics/ajax_loader', array('hidden' => false)); ?>
		</div>
	</div>
</body>
</html>