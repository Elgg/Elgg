<?php
/**
 * Icons CSS
 */

$title = 'Icons';

require dirname(__FILE__) . '/head.php';

?>
<style>li {margin: 10px; float: left;} ul {background-color: #e0e0e0;}</style>
<body>
	<div class="elgg-page mal">
		<h1 class="mbl"><a href="index.php">Index</a> > <?php echo $title; ?></h1>
		<ul class="clearfix">
			<li><span class="elgg-icon elgg-icon-settings"></span>Settings</li>
			<li><span class="elgg-icon elgg-icon-friends"></span>Friends</li>
			<li><span class="elgg-icon elgg-icon-help"></span>Help</li>
			<li><span class="elgg-icon elgg-icon-delete"></span>Delete</li>
			<li><span class="elgg-icon elgg-icon-likes"></span>Likes</li>
			<li><span class="elgg-icon elgg-icon-liked"></span>Liked</li>
			<li><span class="elgg-icon elgg-icon-following"></span>Following</li>
			<li><span class="elgg-icon elgg-icon-rss"></span>RSS</li>
			<li><span class="elgg-icon elgg-icon-arrow-s"></span>Arrow S</li>
		</ul>
	</div>
</body>
</html>