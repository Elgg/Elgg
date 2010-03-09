<?php
/**
 * All site blog stats
 **/
 
$count_blogs = get_entities("object", "blog",0,"",10,0,true,0,null,0,0);
$count_blog_comments = count_annotations(0, "object", "blog","generic_comment");

echo "<div class='SidebarBox'>";
echo "<h3>Blog stats</h3>";
echo "<div class='ContentWrapper'>";
echo $count_blogs . " blog posts written with " . $count_blog_comments . " comments.";
echo "</div></div>";