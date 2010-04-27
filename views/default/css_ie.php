/**
 * elgg_layout css for Internet Explorer > ie6
 * @uses $vars['wwwroot'] The site URL
*/
* {zoom: 1;} /* trigger hasLayout in IE */

/* tools drop-down menu */
#elgg_header {z-index:1;}
.navigation li a:hover ul {display:block; position:absolute; top:21px; left:0;}
.navigation li a:hover ul li a {display:block;}
.navigation li.navigation_more ul li a {width:150px;background-color: #dedede;}

.clearfloat { display: block; }
#elgg_page_contents {overflow:hidden;}
#breadcrumbs {top:-2px; margin-bottom: 5px;}

.entity_metadata {max-width: 300px;}
.entity_edit {float:right;}
.access_level {float:left;}


/* profile */
.elgg_horizontal_tabbed_nav.profile .profile_name {
	margin-left: -260px;
}

/* private messages */
#elgg_topbar_contents a.privatemessages.new span { 
	display:block;
	padding:1px;
	position:relative;
	text-align:center;
	float:left;
	top:-1px;
	right:auto; 
}
#elgg_topbar_contents a.privatemessages.new  {
	padding:0 0 0 20px;
}
#elgg_topbar_contents a.privatemessages:hover {
	background-position:left 2px;
}
#elgg_topbar_contents a.privatemessages.new:hover {
	background-position: left 2px;
}

/* riverdashboard mod rules */
#riverdashboard_updates {
	clear:both;
}
#riverdashboard_updates a.update_link {
	margin:0 0 9px 0;
}
.riverdashboard_filtermenu {
	margin:10px 0 0 0;
}
.river_comment_form.hidden .input_text {
	width:530px;
	float:left;
}
.river_link_divider {
	width:10px;
	text-align: center;
}