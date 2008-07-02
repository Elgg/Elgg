<?php

?>

/* ***************************************
	AVATAR CONTEXTUAL MENU
*************************************** */	
#profile_icon_wrapper {
	float:left;
}
	
.usericon {
	position:relative;
}

.avatar_menu_button {
	width:15px;
	height:15px;
	position:absolute;
	cursor:pointer;
	display:none;
	right:0;
	bottom:0;
}

.usericon div.sub_menu { 
	z-index:9999; 
	display:none; 
	position:absolute; 
	padding:2px; 
	margin:0; 
	border-top:solid 1px #E5E5E5; 
	border-left:solid 1px #E5E5E5; 
	border-right:solid 1px #999999; 
	border-bottom:solid 1px #999999;  
	width:160px; 
	background:#FFFFFF; 
	text-align:left;
}

* html .usericon div.sub_menu {  } /* IE6 */
*+html .usericon div.sub_menu {  } /* IE7 */

.usericon div.sub_menu a {margin:0;padding:2px;}
.usericon div.sub_menu a:link, 
.usericon div.sub_menu a:visited, 
.usericon div.sub_menu a:hover{ display:block;}	
.usericon div.sub_menu a:hover{ background:#cccccc; text-decoration:none;}
.usericon .item_line { border-top:solid 1px #dddddd;}

.usericon div.sub_menu h3 {font-size:1.2em;}