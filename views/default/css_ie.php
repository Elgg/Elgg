/**
 * elgg_layout css for Internet Explorer > ie6
 * @uses $vars['wwwroot'] The site URL
*/
* {zoom: 1;} /* trigger hasLayout in IE */

/* main nav drop-down */
#elgg_header {z-index:1;}
.navigation li a:hover ul {display:block; position:absolute; top:21px; left:0;}
.navigation li a:hover ul li a {display:block;}
.navigation li.navigation_more ul li a {width:150px;background-color: #dedede;}


#elgg_topbar_contents a.privatemessages_new span { 
	display:block;
	padding:1px;
	position:relative;
	text-align:center;
	float:left;
	top:auto;
	right:auto; 
}
#elgg_topbar_contents a.privatemessages_new  {
	padding:0 0 0 20px;
}
