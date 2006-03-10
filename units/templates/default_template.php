<?php

	global $template;
	global $template_definition;
	$sitename = sitename;
	$url = url;

	$template_definition[] = array(
									'id' => 'css',
									'name' => gettext("Stylesheet"),
									'description' => gettext("The Cascading Style Sheet for the template."),
									'glossary' => array(),
									'display' => 1
									);

	$template['css'] = <<< END
/*
	CSS for Elgg default
*/

body{
	padding: 0;
	font-family: arial, verdana, helvetica, sans-serif;
	color: #333;
	background: #eee;
	width:97%;
	margin:auto;
	font-size:80%;
	}

a {
		text-decoration: none;
		color: #7289AF;
		background: #fff;
		font-family:verdana, arial, helvetica, sans-serif;
		font-size:100%;

	}

p {
	font-size: 100%;
}

h1 {
	margin:0px 0px 15px 0px;
	padding:0px;
	font-size:120%;
	font-weight:900;
}


h2 {
	margin:0px 0px 5px 0px;
	padding:0px;
	font-size:100%
}


h3 {
	margin:0px 0px 5px 0px;
	padding:0px;
	font-size:100%
}

h4 {
	margin:0px 0px 5px 0px;
	padding:0px;
	font-size:100%
}

h5 {
	margin:0px 0px 5px 0px;
	padding:0px;
	color:#1181AA;
	background:#fff;
	font-size:100%
}

blockquote {
	padding: 0 1pc 1pc 1pc;
	border: 1px solid #ddd;
	background-color: #F0F0F0;
	color:#000;
	background-image: url("/_templates/double-quotes.png");
	background-repeat: no-repeat;
	background-position: -10px -7px;
}

/*---------------------------------------
Wraps the entire page 
-----------------------------------------*/

#container {
	margin: 0 auto;
	text-align: center;
	width: 100%;
	min-width: 750px;
	}


/*-----------------------------------------
TOP STATUS BAR 
-------------------------------------------*/

#statusbar {
	padding: 3px 0px 2px 0;
	margin: 0px;
	height:19px;
	background:#eee;
	color: #333;
	font-size:85%;
}

#statusbar a {
	color: #666;
	background:#eee;
}

#welcome {
	float: left;
}

#welcome p{
	font-weight:bold;
	font-size:110%;
	padding:0 0 0 4px;
	margin:0px;
}

#global_menuoptions {
	text-align: right;
	padding:0px;
	margin:0px;
	float:right;
}

#global_menuoptions ul {
	margin: 0; 
	padding: 0;
}

#global_menuoptions li {
	margin: 0; 
	padding: 0;
	display: inline;
	list-style-type: none;
	border: none;
}

#global_menuoptions a {
	text-decoration: none;
}

#global_menuoptions a:hover{
	text-decoration:underline;
}


/*---------------------------------------------
HEADER 
------------------------------------------------*/

#header {
	width: 100%;
	background: #1181AA;
	color:#fff;
	border: 1px solid #ccc;
	border-bottom: none;
	padding: 0px;
	margin: 0px;
	text-align: left;
	}

#header h1 {
	padding: 0 0 4px 0;
	margin: 7px 0 0 20px;
	color: #FAC83D;
	background: #1181AA;
	text-align: left;
	font-size:140%;
	font-weight:normal;
	}

#header h2 {
	padding: 0 0 7px 0;
	margin: 0 0 0 20px;
	font-weight: normal;
	color: #fff;
	background: #1181AA;
	border: none;
	font-family: "Lucida Grande", arial, sans-serif;
	font-size:120%;
	}

/*--------------------------------------------
NAVIGATION 
----------------------------------------------*/

#navigation {
	height: 19px;
	margin: 0;
	padding-left: 20px;
	text-align:left;
}

#navigation li {
	margin: 0; 
	padding: 0;
	display: inline;
	list-style-type: none;
	border: none;
}
	
#navigation a:link, #navigation a:visited {

	background: #eaeac7;
	font-weight: normal;
	padding: 4px 6px;
	margin: 0 2px 0 0;
	border: 0px solid #036;
	text-decoration: none;
	color: #333;
	font-size:85%;
}

#navigation a:link.current, #navigation a:visited.current {
	border-bottom: 1px solid #fff;
	background: #fff;
	color: #393;
	font-weight: bold;
}

#navigation a:hover {
	color: #000;
	background: #ffc;
}

#navigation li a:hover{
	background:#FCD63F;
	color: #000;
	}


/*-----------------------------------------------
SITE CONTENT WRAPPER 
-------------------------------------------------*/

#content_holder {
	margin: 0;
	padding: 20px 0;
	width: 100%;
	text-align: left;
	float: left;
	border: 1px solid #ccc;
	border-top: none;
	background-color: #fff;
	color:#000;
}

/*-------------------------------------------------
HOLDS THE MAIN CONTENT E.G. BLOG, PROFILE ETC 
----------------------------------------------------*/

#maincontent_container {
	margin: 0;
	padding: 5px;
	text-align: left;
	width: 65%;
	float: left;
	}

#maincontent_container h2 {
	padding-bottom: 5px;
	padding-top: 5px;
	margin: 0;
	/*color: #666;
	background-color:#fff;*/
}

#maincontent_container h1 {
	padding-bottom: 5px;
	padding-top: 5px;
	margin: 0;
	color: #666;
	background-color:#fff;
}

#maincontent_container h3 {
	padding-bottom: 5px;
	padding-top: 5px;
	margin: 0;
	/*color: #666;
	background-color:#fff;*/
}


/*-------------------------------------------------------------
THIS DISPLAYS THE ACTUAL CONTENT WITHIN maincontent_container
--------------------------------------------------------------*/

#maincontent_display {
	margin: 0;
	padding: 0 0 20px 20px;
	width: 100%;
	text-align: left;
	float: left;
	background-color: #fff;
	color:#000;
}

#maincontent_display h1 {
	padding-bottom: 2px;
	border-bottom: 1px solid #666;
	margin: 0;
	font-size:130%;
	color: #666;
	background-color: #fff;
}

/*---- Sub Menu attributes ----*/

#maincontent_display #sub_menu {
	font-family: verdana;
	padding: 0px;
	margin: 5px 0 20px 0;
	color: #000;
	background-color:#fff;
}

#maincontent_display #sub_menu a {
	font-weight:bold;
	margin:0px;
	padding:0px;
}

#maincontent_display #sub_menu a:hover {
	text-decoration: underline;
}

#maincontent_display #sub_menu p {
	margin:0px;
	padding:0px;
}

/*-----------------------------------------------------------------------
DIV's to help control look and feel - infoholder holds all the profile data
and is always located in within 'maincontentdisplay'

-------------------------------------------------------------------------*/

/*------ holds profile data -------*/
.infoholder {
	border:1px;
	border-color:#eee;
	border-style:solid;
	margin:0 0 5px 0;
}

.infoholder p {
	padding:0 0 0 5px;
}

.infoholder .fieldname h2 {
	border:0;
	border-bottom:1px;
	border-color:#eee;
	border-style:solid;
	padding:5px;
	color:#666;
	background:#fff;
}

.infoholder_twocolumn {
	padding:4px;
	border:1px;
	border-color:#eee;
	border-style:solid;
	margin:0 0 10px 0;
}

.infoholder_twocolumn .fieldname h3{
	color:#666;
	background:#fff;
	border:0px;
	border-bottom:1px;
	border-color:#eee;
	border-style:solid;
}

/*----------- holds administration data---------*/

.admin_datatable {
	border:1px;
	border-color:#eee;
	border-style:solid;
	margin:0 0 5px 0;
}

.admin_datatable p {
	padding:0px;
	margin:0px;
}

.admin_datatable a {
	
}


.admin_datatable td {
	text-align:left;
}

.admin_datatable h3{
	color:#666;
	background:#fff;
}

.admin_datatable h4 {
}

/*---- header plus one row of content ------*/

.databox_vertical {
	background-color: #F9F9F9;
	color:#000;
	border:1px;
	border-style:solid;
	border-color:#DDD;
	margin:0 0 5px 0;
	padding:5px;
 }

.databox_vertical p{
	padding:0px;
	margin:0px;
	color:#1181AA;
	background:#fff;
 }

.databox_vertical .fieldname h3 {
	padding:0px;
	margin:0px;
	color:#1181AA;
	background:#fff;
}

/*------- holds file content ----*/

.filetable {
	background-color: #F9F9F9;
	color:#000;
	border:1px;
	border-style:solid;
	border-color:#DDD;
	margin:0 0 5px 0;
	width:100%;
}

.filetable p{
	padding:0px;
	margin:0px;
	color:#000; /*#1181AA;*/
	background:#fff;
}

.filetable a{
	
}


.filetable table {
	text-align:left;
}

#edit_files h4 {
	
}


/*------- holds folder content ------*/

.foldertable {
	background-color: #F9F9F9;
	color:#000;
	border:1px;
	border-style:solid;
	border-color:#DDD;
	margin:0 0 5px 0;
	width:100%;
}

.foldertable a{
	
}

.foldertable p{
	padding:0px;
	margin:0px;
	color:#1181AA;
	background:#fff;
 }

.foldertable table {
	text-align:left;
}

/*------- holds network data ------*/

.networktable {
	
}


/*-------------------------------------------
SIDEBAR CONTAINER 
---------------------------------------------*/

#sidebar_container {
	margin: 0px;
	text-align: left;
	float: right;
	width: 26%;
	min-width: 100px;
	border-left: 1px dotted #dcdcdc;
	padding: 0 10px;
	/*width:220px;*/
	/*overflow: hidden;*/
	}

/*-----------------------------------------
ACTUAL SIDEBAR CONTENT
-------------------------------------------*/

#sidebar {
min-width: 100px;
	padding: 0 10px;
	}

#sidebar ul {
	margin: 0;
	padding: 0;
	list-style: none;
}

#sidebar ul li ul {
	
}

#sidebar ul li {
	margin: 10px 0;
	padding-left: 5px;
}


#sidebar h2 {
	font-family: "Lucida Grande", arial, sans-serif;
	font-weight: bold;
	color: #333;
	background:#fff;
	margin: 20px 0 3px 0;
	padding: 0;
	border: none;
}

#sidebar h2 {
	border-bottom: 1px solid #666;
}

/*-------------------------------------------
SIDEBAR DISPLAY COMPONENTS 
----------------------------------------------*/

#sidebar_user {
}

#recent_activity {
}

#community_owned {
}

#community_membership {
}

#sidebar_friends {
}

#search {
}

#me {
	padding: 0 3px 3px 3px;
	background-color:#FAC83D;
	min-height: 71px;
}

#me a {
	background-color:#FAC83D;
	color: #7289AF;
}

#me #icon {
	margin:3px 0 0 0;
	float: left; 
	width: 70px;
}

#me #contents {
	margin: 0 0 0 75px;
	text-align: left;
}


/*--- extra div's when looking at someone else's page ---*/

#sidebar_weblog {
}

#sidebar_files {
}



/*------------------------------------------
  FOOTER 
  ------------------------------------------*/

#footer {
	margin: 10px 0 20px 20px;
	text-align: center;
	padding:5px;
}

#footer a:link, #footer a:visited {
	text-align:right;
}


/*-------------------------------------------
  INDIVIDUAL BLOG POSTS 
  -------------------------------------------*/


/*------ wraps all blog components ------*/

.weblog_posts {
}

.weblog_posts .entry h3 {
	color:#1181AA;
	background:#fff;
	padding: 0 0 10px 110px;
}

.user {
	float: left;
	margin: 0px;
	padding:0 0 5px 0;
	width: 105px;
	text-align: left;
}

.user a {
	
}

.post {
	margin: 0 0 10px 0;
	padding: 0 0 20px 110px;
	font-family: arial;
}

.post p {
	padding: 0;
	margin: 3px 0 10px 0;
	line-height: 16px;
}

.post ol, .post ul {
	margin: 3px 0 10px 0;
	padding: 0;
}

.post li {
	margin: 0 0 0 30px;
	line-height: 16px;
}

.post ul li {
	list-style-type: square;
}

.post .blog_edit_functions p {
	
}

.post .blog_edit_functions a {
	
}

.post .weblog_keywords p {
	
}

.post .weblog_keywords a {
	
}

.info p {
	padding: 0px;
	margin: 0 0 5px 0;
	color: #666;
	background:#fff;
	font-family: verdana;
	font-weight: normal;
	line-height: 14px;
	text-align: left;
}

.info p a {
	color: #666;
	background:#fff;
	text-decoration: none;
	border-bottom: 1px dotted #666;
	padding-bottom: 0;
}

#comments ol, #comments ul {
	margin: 3px 0 10px 0;
	padding: 0;
}

#comments li {
	margin: 0 0 0 30px;
	line-height: 16px;
}

#comments ul li {
	list-style-type: square;
}

#comments h4 {
	color:#1181AA;
}

.weblog_dateheader {
	padding: 0px;
	margin: 0 0 5px 0;
	color: #333;
	background:#fff;
	font-weight: normal;
	font-style: italic;
	line-height: 12px;
	border:0px;
	border-bottom: 1px solid #ccc;
}

.clearing{clear:both;}

/*---------------------------------------------
  Your Resources
-----------------------------------------------*/

.feeds {
	border-bottom: 1px dotted #aaaaaa;
	background: transparent url("/_templates/sunflower.jpg") bottom right no-repeat;
}

.feed_content a {
	color:black;
	border:0px;
	border-bottom:1px;
	border-style:dotted;
	border-color:#eee;
}

.feed_content a:hover{
	background:#fff;
	}

.feed_content img {
	border: 1px solid #666666;
	padding:5px;
}

.feed_content h3 {
	padding:0 0 4px 0;
	margin:0px;
}

.feed_content h3 a{
	color:black;
	border:0px;
	border-bottom:1px;
	border-style:dotted;
	border-color:#eee;
}

.feed_content h3 a:hover{
	background:#FCD63F;
	color:#000;
	}

.feed_date h2 {
	font-size:13px;
	line-height: 21px;
	font-weight: bold;
	padding: 5px 10px 5px 5px;
	background: #D0DEDF;
	color:#000;
	text-decoration:none;
}

.via a {
	font-size:80%;
	color:#1181AA;
	background:#fff;
	border:0px;
	border-bottom:1px;
	border-style:dashed;
	border-color:#ebebeb;
}

.via a:hover {
	background:#ffc;
	color:#1181AA;
}


/*---------------------------------------
  SYSTEM MESSAGES 
  ---------------------------------------*/

#system_message{ 
	border:1px solid #D3322A;
	background:#F7DAD8;
	color:#000;
	padding:3px 50px;
	margin:0 0 0 20px;
}

#system_message p{
	padding:0px;
	margin:2px;
}


/* -------------  help files -------------*/

.helpfiles ul {
	font-family: arial, helvetica, Tahoma;
	color: #000000;
	background:#fff;
}

.helpfiles h4 {
	
}

/*------ site news for home.php ---------*/

.sitenews {
	background:#ebebeb;
	color:#000;
}

.sitenews h2 {
	background:#1181AA;
	color:#FAC83D;
	padding:0 0 5px 0;
}

/*-------------------------------------
  Input forms
--------------------------------------*/

.textarea {
	border: 1px solid #7F9DB9;
	color:#71717B;
	width: 95%;
	height:200px;
	padding:3px;
}

.medium_textarea {
	width:95%;
	height:100px;
}

.small_textarea {
	width:95%;
}

.keywords_textarea {
	width:95%;
	height:100px;
}


/*--------------------------------------
  MISC
--------------------------------------*/

.clearall {
	padding: 0px;
	clear: both;
	font-size: 0px;
	}

.flagcontent {
	background:#eee;
	color:#000;
	border:1px;
	border-color:#000;
	border-style:solid;
	padding:3px;
}

.flagcontent h5 {
	background:#eee;
	color:#1181AA;
}

END;

	$template_definition[] = array(
									'id' => 'pageshell',
									'name' => gettext("Page Shell"),
									'description' => gettext("The main page shell, including headers and footers."),
									'display' => 1,
									'glossary' => array(
															'{{metatags}}' => gettext("Page metatags (mandatory) - must be in the 'head' portion of the page"),
															'{{title}}' => gettext("Page title"),
															'{{menu}}' => gettext("Menu"),
															'{{topmenu}}' => gettext("Status menu"),
															'{{mainbody}}' => gettext("Main body"),
															'{{sidebar}}' => gettext("Sidebar")
														)
									);
	
	$welcome = gettext("Welcome"); // gettext variable
	
	$template['pageshell'] = <<< END
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{title}}</title>
{{metatags}}
</head>
<body>
<!-- elgg banner and logo -->
<div id="container"><!-- start container -->
	<div id="statusbar"><!-- start statusbar -->
		<div id="welcome"><!-- start welcome -->
			<p>$welcome {{userfullname}}</p>
		</div><!-- end welcome -->
		{{topmenu}}
	</div><!-- end statusbar -->
	<div id="header"><!-- start header -->
		<h1>$sitename</h1>
			<h2>Community learning space</h2>
			<ul id="navigation">
				{{menu}}
			</ul>
	</div><!-- end header -->
	<div id="content_holder"><!-- start contentholder -->
		<div id="maincontent_container"><!-- start main content -->
			{{messageshell}}
			{{mainbody}}
		</div><!-- end main content -->
		<div id="sidebar_container">
			<div id="sidebar"><!-- start sidebar -->
				<ul><!-- open sidebar lists -->
				{{sidebar}}
				</ul>
			</div><!-- end sidebar -->
		</div><!-- end sidebar_container -->
	</div><!-- end contentholder -->
	<div class="clearall" />
	<div id="footer"><!-- start footer -->
		<a href="http://elgg.net"><img src="{$url}_templates/elgg_powered.png" alt="Powered by Elgg" title="Powered by Elgg" border="0" /></a>
	</div><!-- end footer -->
</div><!-- end container -->
</body>
</html>
			
END;

	$template_definition[] = array(
									'id' => 'contentholder',
									'name' => gettext("Content holder"),
									'description' => gettext("Contains the main content for a page (as opposed to the sidebar or the title)."),
									'glossary' => array(
															'{{title}}' => gettext("The title"),
															'{{submenu}}' => gettext("The page submenu"),
															'{{body}}' => gettext("The body of the page")
														)
									);	

	$template['contentholder'] = <<< END
	
	<div id="maincontent_display">

	<h1>{{title}}</h1>
	{{submenu}}
	{{body}}
	</div>
	
END;

	$template_definition[] = array(
									'id' => 'sidebarholder',
									'name' => gettext("Sidebar section holder"),
									'description' => gettext("Contains the sidebar section titles"),
									'glossary' => array(
										'{{title}}' => gettext("The header"),
										'{{body}}' => gettext("The body of the page")
															
										)
									);

	$template['sidebarholder'] = <<< END

	<h2>{{title}}</h2>
	{{body}}

END;

	$template_definition[] = array(
									'id' => 'ownerbox',
									'name' => gettext("Owner box"),
									'description' => gettext("A box containing a description of the owner of the current profile."),
									'glossary' => array(
															'{{name}}' => gettext("The user's name"),
															'{{profileurl}}' => gettext("The URL of the user's profile page, including terminating slash"),
															'{{usericon}}' => gettext("The user's icon, if it exists"),
															'{{tagline}}' => gettext("A short blurb about the user"),
															'{{usermenu}}' => gettext("Links to friend / unfriend a user")
														)
									);

	$tags = gettext("Tags");
	$resources = gettext("Resources");
	$template['ownerbox'] = <<< END
	
	<div id="me">
		<div id="icon"><a href="{{profileurl}}">{{usericon}}</a></div>
		<div id="contents" ><p>
			<span class="userdetails">{{name}}<br /><a href="{{profileurl}}rss/">RSS</a> | <a href="{{profileurl}}tags/">$tags</a> | <a href="{{profileurl}}feeds/">$resources</a></span></p>
			<p>{{tagline}}</p>
			<p class="usermenu">{{usermenu}}</p>
		</div>
	</div>

END;
	
	$template_definition[] = array(
									'id' => 'messageshell',
									'name' => gettext("System message shell"),
									'description' => gettext("A list of system messages will be placed within the message shell."),
									'glossary' => array(
															'{{messages}}' => gettext("The messages")
														)
									);

	$template['messageshell'] = <<< END
	
	<div id="system_message">{{messages}}</div><br />
	
END;

	$template_definition[] = array(
									'id' => 'messages',
									'name' => gettext("Individual system messages"),
									'description' => gettext("Each individual system message."),
									'glossary' => array(
															'{{message}}' => gettext("The system message")
														)
									);

	$template['messages'] = <<< END

	<p>
		{{message}}
	</p>	
	
END;
	

	$template_definition[] = array(
									'id' => 'menu',
									'name' => gettext("Main menu shell"),
									'description' => gettext("A list of main menu items will be placed within the menubar shell."),
									'glossary' => array(
															'{{menuitems}}' => gettext("The menu items")
														)
									);

	$template['menu'] = <<< END
	
		{{menuitems}}
END;

	$template_definition[] = array(
									'id' => 'menuitem',
									'name' => gettext("Individual main menu item"),
									'description' => gettext("This is the template for each individual main menu item. A series of these is placed within the menubar shell template."),
									'glossary' => array(
															'{{location}}' => gettext("The URL of the menu item"),
															'{{name}}' => gettext("The menu item's name")
														)
									);

	$template['menuitem'] = <<< END
	
	<li><a href="{{location}}">{{name}}</a></li>
	
END;

$template_definition[] = array(
									'id' => 'selectedmenuitem',
									'name' => gettext("Selected individual main menu item"),
									'description' => gettext("This is the template for an individual main menu item if it is selected."),
									'glossary' => array(
															'{{location}}' => gettext("The URL of the menu item"),
															'{{name}}' => gettext("The menu item's name")
														)
									);

	$template['selectedmenuitem'] = <<< END
	
	<li><a class="current" href="{{location}}">{{name}}</a></li>
	
END;

	$template_definition[] = array(
									'id' => 'submenu',
									'name' => gettext("Sub-menubar shell"),
									'description' => gettext("A list of sub-menu items will be placed within the menubar shell."),
									'glossary' => array(
															'{{submenuitems}}' => gettext("The menu items")
														)
									);

	$template['submenu'] = <<< END
	
	<div id="sub_menu">
		<p>
			{{submenuitems}}
		</p>
	</div>
END;

	$template_definition[] = array(
									'id' => 'submenuitem',
									'name' => gettext("Individual sub-menu item"),
									'description' => gettext("This is the template for each individual sub-menu item. A series of these is placed within the sub-menubar shell template."),
									'glossary' => array(
															'{{location}}' => gettext("The URL of the menu item"),
															'{{menu}}' => gettext("The menu item's name")
														)
									);

	$template['submenuitem'] = <<< END
	
	<a href="{{location}}">{{name}}</a>&nbsp;|
	
END;

	$template_definition[] = array(
									'id' => 'topmenu',
									'name' => gettext("Status menubar shell"),
									'description' => gettext("A list of statusbar menu items will be placed within the status menubar shell."),
									'glossary' => array(
															'{{topmenuitems}}' => gettext("The menu items")
														)
									);

	$template['topmenu'] = <<< END
	
		<ul id="global_menuoptions">
			{{topmenuitems}}
		</ul>

END;

$template_definition[] = array(
									'id' => 'topmenuitem',
									'name' => gettext("Individual statusbar menu item"),
									'description' => gettext("This is the template for each individual statusbar menu item. A series of these is placed within the status menubar shell template."),
									'glossary' => array(
															'{{location}}' => gettext("The URL of the menu item"),
															'{{menu}}' => gettext("The menu item's name")
														)
									);

	$template['topmenuitem'] = <<< END
	
	<li><a href="{{location}}">[{{name}}]</a></li>
	
END;

	$template_definition[] = array(
									'id' => 'databox',
									'name' => gettext("Data input box (two columns)"),
									'description' => gettext("This is mostly used whenever some input is taken from the user. For example, each of the fields in the profile edit screen is a data input box."),
									'glossary' => array(
															'{{name}}' => gettext("The name for the data we're inputting"),
															'{{column1}}' => gettext("The first item of data"),
															'{{column2}}' => gettext("The second item of data")
														)
									);

	$template['databox'] = <<< END

	<div class="infoholder_twocolumn">
		<div class="fieldname">
			<h3>{{name}}</h3>
		</div>
		<p>{{column1}}</p>
		<p>{{column2}}</p>
	</div>
		
END;

	$template_definition[] = array(
									'id' => 'databox1',
									'name' => gettext("Data input box (one column)"),
									'description' => gettext("A single-column version of the data box."),
									'glossary' => array(
															'{{name}}' => gettext("The name of the data we're inputting"),
															'{{column1}}' => gettext("The data itself")
														)
									);

	$template['databox1'] = <<< END

	<div class="infoholder">
		<div class="fieldname">
			<h2>{{name}}</h2>
		</div>
		<p>{{column1}}</p>
	</div>
		
END;

$template_definition[] = array(
									'id' => 'adminTable',
									'name' => gettext("adminTable"),
									'description' => gettext("This table is used to house stats and administration details until a good CSS solution can be applied."),
									'glossary' => array(
															'{{name}}' => gettext("Column One"),
															'{{column1}}' => gettext("Column Two"),
															'{{column2}}' => gettext("Column Three")
														)
									);

	$template['adminTable'] = <<< END

<div class="admin_datatable">
	<table width="80%">
	<tr>
		<td width="25%" valign="top">
			{{name}}
		</td>
		<td width="45%" valign="top">
			{{column1}}
		</td>
		<td width="30%" valign="top">
			{{column2}}
		</td>
	</tr>
	</table>
</div>

END;

$template_definition[] = array(
									'id' => 'flagContent',
									'name' => gettext("flagContent"),
									'description' => gettext("This hold the flag content function throughout Elgg"),
									'glossary' => array(
															'{{name}}' => gettext("Column One"),
															'{{column1}}' => gettext("Column Two"),
															'{{column2}}' => gettext("Column Three")
														)
									);

	$template['flagContent'] = <<< END

<div class="flagcontent">
	{{name}}
	{{column1}}
	{{column2}}
</div>

END;

	$template_definition[] = array(
									'id' => 'databoxvertical',
									'name' => gettext("Data input box (vertical)"),
									'description' => gettext("A slightly different version of the data box, used on this edit page amongst other places."),
									'glossary' => array(
															'{{name}}' => gettext("Name of the data we\'re inputting"),
															'{{contents}}' => gettext("The data itself")
														)
									);

	$template['databoxvertical'] = <<< END
	<div class="databox_vertical">
		<div class="fieldname">
			<h3>{{name}}</h3>
		</div>
		<p>{{contents}}</p>
	</div>
		
END;

?>