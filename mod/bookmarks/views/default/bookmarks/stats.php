<?php
/**
 * All site bookmark stats
 **/
 
$count_bookmarks = elgg_get_entities("object", "bookmarks",0,"",10,0,true,0,null,0,0);
$count_bookmark_comments = count_annotations(0, "object", "bookmarks","generic_comment");

echo "<h3>Bookmark stats</h3>";
echo "<p>".$count_bookmarks . " resources bookmarked.</p>";
