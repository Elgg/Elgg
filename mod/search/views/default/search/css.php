<?php
/**
 * Elgg Search css
 * 
 * @package search
 */
?>

.elgg-page-header .elgg-search {
	bottom: 5px;
	height: 23px;
	position: absolute;
	right: 0;
}
.elgg-page-header .elgg-search input[type=text] {
	width: 198px;
}
.elgg-search input[type=text] {
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border: 1px solid #71b9f7;
	color: white;
	font-size: 12px;
	font-weight: bold;
	padding: 2px 4px 2px 26px;
	background-color: transparent;
	background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-position: 2px -220px;
	background-repeat: no-repeat;
}
.elgg-search input[type=text]:focus, .elgg-search input[type=text]:active {
	background-color: white;
	color: #0054A7;
	border: 1px solid white;
	background-position: 2px -257px;
}
.elgg-page-header .elgg-search input[type=submit] {
	display: none;
}

.search_listing  {
	background:none;
	border-bottom:1px dotted #CCCCCC;
	clear:both;
	display:block;
	margin:0;
	padding:5px 0 7px;
	position:relative;
}
.search_listing_icon {
	float:left;
	margin-left:3px;
	margin-top:3px;
}
.search_listing_icon .avatar_menu_button img {
	width: 15px;
	margin:0;
}
.search_listing_info {
	float:left;
	margin-left:7px;
	min-height:28px;
	width:693px;
}
.search_listing_info p {
	margin:0;
}
.search_listing_category_title {
	margin-top:20px;
}
.search_listing_category_title h2 {
	color:#666666;
}
.search_listing.more {
	display: block;
}


/* search matches */
.searchtype {
	background: #FFFACD;
	color: black;
}
.searchtypes {
	border: 1px #EEEEEE solid;
	padding: 4px;
	margin: 6px;
}
.searchMatch {
	background-color: #bbdaf7;
}
.searchMatchColor1 {
	background-color: #bbdaf7;
}
.searchMatchColor2 {
	background-color: #A0FFFF;
}
.searchMatchColor3 {
	background-color: #FDFFC3;
}
.searchMatchColor4 {
	background-color: #cccccc;
}
.searchMatchColor5 {
	background-color: #4690d6;
}

/* formatting for the search results */

.search_listing .item_timestamp {
	font-style: italic;
}
