/**
 * elgg_layout css for Internet Explorer > ie6
 * @uses $vars['wwwroot'] The site URL
*/
* {zoom: 1;} /* trigger hasLayout in IE */

/* tools drop-down menu */
#elgg-header {z-index:1;}
.navigation li a:hover ul {display:block; position:absolute; top:21px; left:0;}
.navigation li a:hover ul li a {display:block;}
.navigation li.navigation-more ul li a {width:150px;background-color: #dedede;}

.clearfix { display: block; }
.hidden.clearfix { display: none; }
#elgg-page-contents {overflow: hidden;} /* remove horizontal scroll on riverdash */
#breadcrumbs {top:-2px; margin-bottom: 5px;}

/* entity list views */
.entity-metadata {max-width: 300px;}
.entity-edit {float:right;}
.access_level {float:left;}
.elgg-image-block .entity-metadata {
	min-width:400px;
	text-align: right;
}

/* profile */
.elgg-tabs.profile .profile_name {margin-left: -260px;}
#profile_content .river_comment_form.hidden .input-text { width:510px; }

/* notifications */
.friends-picker-navigation {margin:0;padding:0;}
.friends-picker-container h3 {margin:0;padding:0;line-height: 1em;}

/* private messages */
#elgg-topbar-contents a.privatemessages.new span { 
	display:block;
	padding:1px;
	position:relative;
	text-align:center;
	float:left;
	top:-1px;
	right:auto;
}
#elgg-topbar-contents a.privatemessages.new  {padding:0 0 0 20px;}
#elgg-topbar-contents a.privatemessages:hover {background-position:left 2px;}
#elgg-topbar-contents a.privatemessages.new:hover {background-position: left 2px;}

/* riverdashboard mod rules */
#riverdashboard_updates {clear:both;}
#riverdashboard_updates a.update_link {margin:0 0 9px 0;}
.riverdashboard_filtermenu {margin:10px 0 0 0;}
.river_comment_form.hidden .input-text {
	width:530px;
	float:left;
}
.river_link_divider {
	width:10px;
	text-align: center;
}

/* shared access */
.shared_access_collection h2.shared_access_name {margin-top:-15px;}

/* dropdown login */
*:first-child+html #login-dropdown #signin-button {
	line-height:10px;
}
*:first-child+html #login-dropdown #signin-button a.signin span {
	background-position:-150px -54px;
}
*:first-child+html #login-dropdown #signin-button a.signin.menu-open span {
	background-position:-150px -74px;
}