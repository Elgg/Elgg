<?php

	global $template;
	global $template_definition;
	$sitename = sitename;
	$url = url;
	
	$template_definition[] = array(
									'id' => 'pageshell',
									'name' => gettext("Page Shell"),
									'description' => gettext("The main page shell, including headers and footers."),
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
<body bgColor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
 <!-- elgg banner and logo -->
 <div class="container">
<div id="Statusbar">
	<div id="StatusLeft">
		<p>$welcome {{userfullname}}</p>
	</div>
	<div id="StatusRight">
		{{topmenu}}
	</div>

</div>
<div id="Header">
			 <h1>$sitename</h1>
       		<h2>Learning Landscape</h2>
	    <ul id="Tabs">  
          {{menu}}
		 </ul>
</div>
<div id="ContentFrame">
<div class="Left">
  <div class="col">
  	{{messageshell}}
  	{{mainbody}}
  </div>
</div>
<div class="Right">

  <div class="col">
    <div class="Sidebar">
    	{{sidebar}}
	</div>
  </div>
</div>
</div>
<div class="ClearAll" />
 <div id="Footer">
		<a href="http://elgg.net"><img src="{$url}_templates/elgg_powered.png" alt="Powered by Elgg" title="Powered by Elgg" border="0"></a>	
 </div>
 </body>
 </html>
			
END;

	$template_definition[] = array(
									'id' => 'css',
									'name' => gettext("Stylesheet"),
									'description' => gettext("The Cascading Style Sheet for the template."),
									'glossary' => array()
									);

	$template['css'] = <<< END
/*

	CSS for Elgg default - a huge thanks to Enej for providing most of the CSS!

*/

body{
	margin: 0;
	padding: 0;
	font-family: "Lucida Grande", verdana, arial, helvetica, sans-serif;
	color: #333;
    background: #eee;
	width:97%;
	margin:auto;
	}

a {
		text-decoration: none;
		font-family: verdana, arial, helvetica;
		color: #7289AF;
		font-size:13px;
	}

p {
	font-family: arial, helvetica, Tahoma;
	color: #000000; 
	font-size: 75%;		
}


h2 {
	font-family: arial, helvetica, Tahoma;
}


h3 {
	font-family: arial, helvetica, Tahoma;
}

h4 {
	font-family: arial, helvetica, Tahoma;
}

h5 {
	color:#1181AA;
}

/*-------------------------------------------------
STATUS BAR
-------------------------------------------------*/

#Statusbar {
	color: #1181AA;
	padding: 3px 10px 2px 0;
	margin: 0px;
	text-align: bottom;
	font-size: 9px;
    height:19px;
    background:#eee;
}


#Statusbar a {
	font-size: 11px;
       color: #666;
}

#StatusRight {
	text-align: right;
	padding:0px;
	padding-top:0px;
	padding-bottom:0px;
}

#StatusRight a:hover{
    text-decoration:underline;
}

#StatusLeft {
	float: left;
	color: #333;
}

#StatusLeft p{
	font-weight: normal;
	font-size:12px;
	font-weight:bold;
	padding:0px;
	padding-left:3px;
	margin:0px;
	color:#ggg;
}

/*-------------------------------------------------
HEADER
-------------------------------------------------*/

#Header {
	width: 100%;
	background: #1181AA; 
	border: 1px solid #ccc;
	border-bottom: none;
	padding: 0px;
	margin: 0px;
	text-align: left;
	}

#Header h1 {
	padding: 0;
	padding-bottom: 4px;
	margin: 7px 0 0 20px;
	font-size: 24px;
	font-weight: normal;
	color: #FAC83D; 
	text-align: left;
	}	

#Header h2 {
	padding: 0 0 7px 0;
	margin: 0 0 0 20px;
	font-size: 16px;
	font-weight: normal;
	color: #fff;
	border: none;
	font-family: "Lucida Grande", arial, sans-serif;
	}	

#Header h3 {
	padding: 0 20px 0 0;
	margin: 7px 0px 0 0;
	width: 200px;
	text-align: right;
	float: right;
	font-size: 10px;
	font-weight: normal;
	font-family: verdana;
}

#Header h3 a {
	font-weight: bold;
	text-decoration: none;
}
/*-------------------------------------------------
contents
-------------------------------------------------*/

.Container {
	margin: 0 auto;
	text-align: center;
	width: 100%;
	min-width: 750px;
	}

.ClearAll {
	padding: 0px;
	clear: both;
	font-size: 0px;
	}

.userlist {
    clear: both;
    margin:0px;
    margin-bottom:5px;
}

#ContentFrame, .ContentFrame {
	margin: 0;
	padding: 20px 0;
	width: 100%;
	text-align: left;
	float: left;
	border: 1px solid #ccc;
	border-top: none;
	background-color: #fff;
}

.Left {
	margin: 0;
	padding: 0;
	text-align: left;
	width: 68%;
	float: left;
	}

.Left h2 {
	padding-bottom: 5px;
       padding-top: 5px;
	border: 0px;
	margin: 0;
	font-size: 14px;
	color: #666;
}

.Left h1 {
	padding-bottom: 5px;
       padding-top: 5px;
	border: 0px;
	margin: 0;
	font-size: 15px;
	color: #666;
}



.Right {
	margin: 0px;
	padding: 0;
	text-align: left;
	float: right;
	width: 30%;
      overflow: hidden;
	}

#Footer {
	font-size: 10px;
	color: #fff;
	margin: 10px 0 20px 20px;
	text-align: center;
	padding:5px;
}

#Footer a:link, #Footer a:visited {
	color: #666;
	text-align:right;
}

#Footer a:hover {
	color: #fff;
	background: #666;
}

/*-------------------------------------------------
TABS
-------------------------------------------------*/

#Tabs {
	height: 21px;
	margin: 0;
	padding-left: 20px;
	text-align:left;
}

#Tabs li {
	margin: 0; 
	padding: 0;
	display: inline;
	list-style-type: none;
	border: none;
}
	
#Tabs a:link, #Tabs a:visited {

	background: #eaeac7;
	font-size: 11px;
	font-weight: normal;
	padding: 4px 6px;
	margin: 0 2px 0 0;
	border: 0px solid #036;
	border-bottom: #eaeac7;
	text-decoration: none;
	color: #333;
}

#Tabs a:link.current, #Tabs a:visited.current {
	border-bottom: 1px solid #fff;
	background: #fff;
	color: #393;
	font-weight: bold;
}

#Tabs a:hover {
	color: #000;
	background: #ffc;
}
#Tabs li a:hover{
	background:#FCD63F;
	}

/*-------------------------------------------------
Profile table
-------------------------------------------------*/

.profiletable 	{
					border: 1px;
					border-style: solid;
					border-color: #ddd;
					text-align:top;
			  	}

.profiletable 	p{
       padding:0px;
	   margin:0px;
	  }
				
.profiletable img {
	border: 0;
}

.profiletable .fieldname	{
					background-color: #F9F9F9;
					border-right: 2px solid #eaeac7; /*#8B8C8C;*/
					color: #1181AA;
					padding-left: 10px;
					text-align:bottom;
				}

.profiletable .fieldname p {
      color: #666;
}

.fileTable {
   background-color: #F9F9F9;
   border:1px;
   border-style:solid;
   border-color:#DDD;
 }

 .fileTable p{
   padding:0px;
   margin:0px;
   color:#1181AA;
 }

/*-------------------------------------------------
SELECTED HEADER
-------------------------------------------------*/

.SectionContent {
	margin: 0 0 20px 0;
}

.SectionContent h1 {
	padding-bottom: 2px;
	border-bottom: 1px solid #666;
	margin: 0;
	font-size: 16px;
	color: #666;
}

.SectionContent h3 {
	font-family: verdana;
	padding: 2px 0 0 0;
	margin: 0;
	font-size: 10px;
	font-weight: normal;
	color: #000;
	}

.SectionContent h3 a {
	font-weight:bold;
}

.SectionContent h3 a:hover {
	text-decoration: underline;
}

/*-------------------------------------------------
INDIVIDUAL POSTS
-------------------------------------------------*/

.Post {
	margin: 0 0 10px 0;
	padding: 0 0 20px 110px;
       font-size:75%;
       font-family: arial;
}

.Post p {
	padding: 0;
	margin: 3px 0 10px 0;
	font-size: 12px;
	line-height: 16px;
}

.Post h1 {
	/* padding-top: 5px;*/
	color: #000;
}

.Post h1 a {
	color: #000;
	text-decoration: none;
}

.Post h1 a:hover {
	background: #fff;
	color: #000;
	text-decoration: underline;
}	

h2.date, h2.weblogdateheader {
	padding: 0 0 0 0;
	margin: 0 0 2px 0;
	color: #333;
	font-size: 10px;
	font-weight: normal;
	font-style: italic;
	line-height: 12px;
	border-bottom: 1px solid #ccc;
}

.Post h2.subhead {
	padding: 0;
	margin: 15px 0 0 0;
	color: #000;
	font-size: 12px;
	font-weight: bold;
	font-style: normal;
	line-height: 12px;
}

.Post h5 {
		margin-top: 0px;
		padding-top: 0px;
             font-size:100%
	}

.Post h3 {
	padding: 0 0 0 0;
	margin: 0 0 5px 0;
	color: #666; 
	font-family: verdana;
	font-size: 10px;
	font-weight: normal;
	line-height: 14px;
	text-align: left;
}

.Post h3 a {
	color: #666;
	text-decoration: none;
	border-bottom: 1px dotted #666;
	padding-bottom: 0;
}

.Post h3 a:hover {
	color: #fff;
	background-color: #666;
}

.Post h3 a.commentlink:link {
	font-weight: bold;
	color: #000;
}

.Post h3 a.commentlink:hover {
	color: #fff;
	background: #333;
}

.Post ol, .Post ul {
	margin: 3px 0 10px 0;
	padding: 0;
}

.Post li {
	margin-left: 30px;
	font-size: 12px;
	line-height: 16px;
}

.Post ul li {
	list-style-type: square;
}

.Post table {
	background: #dcdcdc;
}

.Post td {
	background: #fff;
	padding: 5px;
	font-size: 12px;
}


/*-------------------------------------------------
SIDEBAR
-------------------------------------------------*/

.Sidebar h1 {
	padding: 4px 0 2px 0;
	border-bottom: 1px solid #666;
	margin: 0 0 5px 0;
	font-size: 12px;
	color: #000;
}

.Sidebar p.incategory {
	margin-top: 0;
	background: #ececec;
	padding: 4px;
}

.Sidebar h2, .Sidebar h2.border {
	font-family: "Lucida Grande", arial, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #333;
	margin: 20px 0 3px 0;
	padding: 0;
	border: none;
}

.Sidebar h2.border {
	border-bottom: 1px solid #d5d5d5;
}


.box_user {
  /*background-color:#FAC83D;*/ /*#FAC83D;*/
  margin:0px;
  margin-bottom:5px;
}

.box_user .me {
		padding: 3px;
		background-color:#FAC83D;
		padding-top: 3px;
		min-height: 71px;
	}

.box_user p {
   color:#000; /*#1181AA;*/
   padding: 0px;
   margin-top: 0px;
  }

.box_user .usermenu a {
   color:#000; /*#1181AA;*/
   padding: 0px;
   margin-top: 0px;
   font-size: 75%;
   text-decoration: underline;
  }
  
.profile_item {
    border:1px;
	border-style:none;
	border-color:#ebebeb;
	background-color:#ebebeb;
	margin:0px;
	margin-bottom:3px;
}

.profile_item2 {
    border:1px;
	border-style:none;
	border-color:#fff;
	background-color:#fff;
	margin:0px;
	margin-bottom:3px;
}

#js{ 
	border:1px solid #D3322A;
	background:#F7DAD8;
	padding:3px 50px;
	margin:0;	
}

#js p{
   padding:0px;
   margin:2px;
 }


/*-------------------------------------------------
COMMENTS
-------------------------------------------------*/

.Comments h4 {
	margin: 0 0 10px 0;
	font-size: 10px;
	color: #666;
	padding: 3px 0;
	font-weight: normal;
	border-bottom: 1px solid #d5d5d5;
}

.Comments h2 {
	padding: 2px 4px;
	color: #fff;
	background: #666;
	font-size: 11px;
	border-bottom: 1px solid #333;
	margin: 0;

}

.Comments div.Post {
	background: #f9f9f9;
	border-bottom: 1px solid #efefef;
	margin: 0 0 5px 0;
	clear: left;
	color: #333;
	padding: 5px;
}

.Comments div.Post:after {
  content: ".";
  display: block;
  height: 0;
  clear: both;
  visibility: hidden;
}

/* Hides form IE-mac \*/
* html .Comments div.Post {height: 1%;}
/* End hide from IE-mac */

.Comments #OriginalPost {
	padding: 0 0 30px 0;
	background: #fff;
	border-bottom: none;
}

.Post#Preview {
	border: 2px solid #ef9c00;
	padding: 0;
}

.Post#Preview h2 {
	margin: 0;
	padding: 5px 10px;
	background: #ff6;
	border: none;
	color: #000;
}

.Post#Preview #PreviewBody {
	padding: 10px;
}

.Post#Preview #PreviewButtons {
	text-align: left;
	background: #ffc;
	padding: 5px 10px;
}

div.Comments img.avatar {
  margin: 0 10px 10px 0;
  width: 48px;
  float: left;
  padding-left: 5px;
}

div.Comments div.Post ul,
div.Comments div.Post ol {
  clear: left;
}

div.Comments div.Post ol li,
div.Comments div.Post ul li {
}

/*-------------------------------------------------
alignment
-------------------------------------------------*/

.col {
	padding: 0 5px;
	text-align: left;
}

.Left .col {
	padding: 0 30px 0 20px;
       min-width: 100px;
}

.Right .col {
	min-width: 100px;
	border-left: 1px dotted #dcdcdc;
	padding: 0 10px;
}

.infobox {
   padding:0px;
   margin:0px;
   margin-bottom:0px;
  }

/* -------------  help files -------------*/

.helpFiles ul {
	font-family: arial, helvetica, Tahoma;
	color: #000000; 
	font-size: 75%;		
}


/*-------------- extra blog classes -----*/
.user {
	float: left;
	margin: 0px;
       padding:0px;
       padding-bottom:5px;
	/* padding: 0.3em 2em 2em 0; */
	width: 105px;
	text-align: left;
}

.weblog_post .post_content {
	padding-left: 2em;
}

.clearing{clear:both;}

/* site news for home.php */

.siteNews {
     background:#ebebeb;
}

.siteNews h2 {
     background:#1181AA;
     font-size:90%;
     color:#FAC83D;
     padding:0px;
     padding-left:5px;
}


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
	
    <div class="SectionContent">
	
	<h1>{{title}}</h1>
	{{submenu}}
	</div>
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
	
	<div class="me">
		<div style="float: left; width: 70px"><a href="{{profileurl}}">{{usericon}}</a></div>
		<div style="margin-left: 75px; margin-top: 0px; padding-top: 0px; text-align: left" ><p>
			<span class="userdetails">{{name}}<br /><a href="{{profileurl}}rss/">RSS</a> | <a href="{{profileurl}}tags/">$tags</a></p>
			<p>{{tagline}}</p>
			<p style="margin-bottom: 3px" class="usermenu">{{usermenu}}</p>
		</div>
	</div>	

END;
									
	$template_definition[] = array(
									'id' => 'infobox',
									'name' => gettext("Information Box"),
									'description' => gettext("A box containing a caption and some text, used extensively throughout the site. For example, the 'friends' box and most page bodies are info boxes. Of course, you can alter this template however you wish - it doesn't need to be an actual box."),
									'glossary' => array(
															'{{name}}' => gettext("The title"),
															'{{contents}}' => gettext("The contents of the box")
														)
									);
	$template['infobox'] = <<< END

<table class="infobox" width="100%">
	<caption align="top">
		{{name}}
	</caption>
	<tr>
		<td>
{{contents}}
		</td>
	</tr>
</table><br />	
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
	
	<div id="js">{{messages}}</div><br />
	
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
	
		<h3>
			{{submenuitems}}
		</h3>
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
	
		<div id="StatusRight">
			{{topmenuitems}}
		</div>

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
	
	[<a href="{{location}}">{{name}}</a>]&nbsp;
	
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

<div class="infobox">
	<table width="95%" class="profiletable" align="center" style="margin-bottom: 3px">
	<tr>

		<td width="20%" class="fieldname" valign="top">
			<p><b>{{name}}</b></p>
		</td>
		<td width="50%" valign="top">
			<p>{{column1}}</p>
		</td>
		<td width="30%" valign="top">
			<p>{{column2}}</p>
		</td>
	</tr>
	</table>
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

<div class="infobox">
	<table width="95%" class="profiletable" align="center" style="margin-bottom: 3px">
	<tr>

		<td width="20%" class="fieldname" valign="top">
			<p><b>{{name}}</b></p>
		</td>
		<td width="80%" valign="top">
			<p>{{column1}}</p>
		</td>
	</tr>
	</table>
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
<div class="infobox">
	<table width="95%" class="fileTable" align="center" style="margin-bottom: 3px">
		<tr>
			<td class="fieldname">
				<p><b>{{name}}</b></p>
			</td>
		</tr>
		<tr>
			<td>
				<p>{{contents}}</p>
			</td>
		</tr>
	</table>
</div>
		
END;

?>