<?php

    /**
	 * Elgg external blog. Turns your internal blog into an external one.
	 */

    // Load Elgg engine will not include plugins
    require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
  	
    global $CONFIG;
    
    $user = get_input('user');
    $user_object = get_user_by_username($user);
    
    // Get a list of blog posts
	set_context('search');
	$area2 = "<div id=\"blogs\">" . list_user_objects($user_object->guid,'blog',10,false, false) . "<div class='clearfloat'></div></div>";
	set_context('blog');
	
	//get some user details
	$user_name = $user_object->name;
	$user_desc = $user_object->briefdescription;
	$user_location = $user_object->location;
	
	//get archive list
	if ($dates = get_entity_dates('object','blog',$user_object->guid)) {
		foreach($dates as $date) {
			$timestamplow = mktime(0,0,0,substr($date,4,2),1,substr($date,0,4));
			$timestamphigh = mktime(0,0,0,((int) substr($date,4,2)) + 1,1,substr($date,0,4));
			$link = $CONFIG->wwwroot . 'pg/blog/' . $page_owner->username . '/archive/' . $timestamplow . '/' . $timestamphigh;
			$year = substr($date,0,-2);
			$month = date('F',mktime(0, 0, 0, substr($date,4,2), 1)); //substr($date,4,2);
			$display_date = $month . " " . $year;	
			$archive_list .= "<li><a href=\"{$link}\">" . $display_date . "</a></li>";
		}								
	}
?>
<html>
<head>
<title>Brighton news blog</title>
<?php
	//require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/mod/blog/themes/ColdBlue/css/style.php");
?>
</head>
<body>
	<div id="header"><div class="inner clear">
		<h1><a href=""><?php echo $user_object->name; ?>: blog</a></h1>
		<ul id="navigation">
			<li><a href="<?php echo $CONFIG->wwwroot . 'pg/blog/' . $user .'?view=rss'; ?>">RSS Feed</a></li>
		</ul>
	</div></div>
	<div id="search"><div class="inner clear">
		<a id="rss-link" href="<?php echo $CONFIG->wwwroot . 'pg/blog/' . $user .'?view=rss'; ?>"><strong>Subscribe to the RSS Feed</strong></a>
		<a id="technorati-link" href="http://technorati.com/faves?add=""><strong>Add to your Favorites</strong></a>
	</div></div>
	<div id="wrapper" class="clear">
	<div id="content">
	<?php echo $area2; ?>
		<div class="post-navigation">
			<div class="left"></div>
			<div class="right"></div>
		</div>
	</div>
<ul id="sidebar">
<li><h2>About</h2>
	<ul>
		<li>
			<p><b><?php echo $user_name; ?></b></p>
			<p><b><?php echo $user_desc; ?></b></p>
			<p><b>Location: <?php echo $user_location; ?></b></p>
		</li>
	</ul>
</li>
<li><h2>Archives</h2>
	<ul>
	<?php 
		//archives
		echo $archive_list;
	?>
	</ul>
</li>
</ul>
</div>
<div id="footer">
	<p id="blog-name">Copyright &copy; 2009 <a href="<?php echo $user_object->getURL(); ?>"><?php echo $user_object->name; ?></a></p>
	<p id="webrevolutionary-link">
		<a href="http://webrevolutionary.com/coldblue/">ColdBlue</a> v1.0 &mdash; A theme by <a href="http://webrevolutionary.com/">WebRevolutionary</a> &amp; <a href="http://www.forwebdesigners.com/">ForWebdesigners</a>
	</p>
</div>
<!-- ColdBlue v1.0 theme designed by WebRevolutionary.com -->
</body>
</html>