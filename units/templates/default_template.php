<?php

	global $template;
	global $template_definition;
	
	$template_definition[] = array(
									'id' => 'pageshell',
									'name' => "Page Shell",
									'description' => "The main page shell, including headers and footers.",
									'glossary' => array(
															'{{metatags}}' => 'Page metatags (mandatory) - must be in the "head" portion of the page',
															'{{title}}' => 'Page title',
															'{{menu}}' => 'Menu',
															'{{mainbody}}' => 'Main body',
															'{{sidebar}}' => 'Sidebar'
														)
									);
	
	$template['pageshell'] = <<< END
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{title}}</title>
{{metatags}}
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="header">
  <tr>
	<td width="4%"><img src="/_templates/default/graphics/leaf.jpg" border="0"></td><td><a href="{{url}}" >{{title}}</a></td><td width="4%">&nbsp;</td>
  </tr>
</table>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="menubar">
  <tr>
    <td width="4%">&nbsp;</td><td  align="left" valign="top">{{messageshell}}</td><td width="4%">&nbsp;</td>
  </tr>
</table>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="5">
    	&nbsp;
    </td>
  </tr>
  <tr>
    <td width="4%" align="left" valign="top" >&nbsp;</td>
    <td align="70%" align="right" valign="top" class="mainbody">{{mainbody}}</td>
<td width="2%" align="right" valign="top" >&nbsp;</td>
<td width="20%" align="center" valign="top" class="sidebar">{{sidebar}}<br />{{menu}}</td>
    <td width="4%" align="right" valign="top" >&nbsp;</td>
  </tr>
</table><br />
<table width="100%" border="0" cellpadding="4" cellspacing="0" class="footer">
  <tr>
    <td colspan="5" align="center" valign="middle">
      Copyright &copy; 2004&nbsp;&nbsp;
      <a href="/content/faq.php">FAQ</a>&nbsp;&nbsp;<a href="/content/privacy.php">Privacy Policy</a>&nbsp;&nbsp;<p>Powered by <a href="http://apcala.com">Apcala</a></p>
</td>
  </tr>
</table>
</body>
</html>
	
END;

	$template_definition[] = array(
									'id' => 'css',
									'name' => "Stylesheet",
									'description' => "The Cascading Style Sheet for the template.",
									'glossary' => array()
									);

	$template['css'] = <<< END
	
/*

	Swish template CSS

*/

body			{
					margin: 0px;
					background-color: #ffffff;
					font-family: arial, helvetica;
					font-size: 9pt;
					line-height: 1.2ems;
					margin: 0px;
					padding: 0px;
				}

h1				{
					13pt;
				}
h2				{
					12pt;
				}
h3				{
					11pt;
				}
form			{
					margin: 0px;
					padding: 0px;
				}

/* Page top */

.header {
	font-size:16px;
	padding:8px;
	background-color: #ffffff;
        
}

.header a {
	text-decoration:none;
	color:#7E7D7D;
	font-family: arial, georgia, "times new roman", palatino;
	font-size: 26pt;
	text-transform: lowercase;
}

.messages		{
					background-color: #000000;
					color: #ffffff;
					padding: 0px;
					margin: 0px;
				}
.messages ul	{
					padding: 0px;
					margin: 0px;
					list-style-type: none;
				}
.menubar		{
					background-color: #ebebeb;
					text-align: justify;
				}
.menubar a		{
					padding: 3px;
					text-decoration: none;
					margin: 1px;
					background-color: #F3C886;
					color: #ffffff;
					display: block;
				}
.menubar a:hover	{
					background-color: #C95922;
				}
.actionbox		{
					background-color: #FFFFFF;
					border: 1px;
					border-color: #000000;
					border-style: solid;
					padding: 7px;

				}
.actionbox caption	{
					background-color: #ebebeb;
					color: #000000;
					vertical-align: middle;
					text-align: center;
					padding: 4px;
					font-weight: bold;
					border: 1px;
					border-color: #000000;
					border-style: solid;
					border-bottom: 0px;
					font-size: 1.1em;

				}
.infobox		{
                                        border:1px;
                                         border-color:#5F5E5E;
                                        border-style:solid;
				}

.infobox caption
				{
					font-weight: bold;
					padding: 10px;
                                        color:#000000;
				}
.mainbody .infobox	{
					background-color: #ffffff;
					padding: 12px;
				}
.mainbody .infobox caption	{
				
					/*background-color: #ebebeb;
					color: #000000;*/
                                        display: none;
					
				}				
.sidebar .infobox	{
					background-color: #FFFFFF;
					padding: 7px;
                                        border:1px;
                                         border-color:#5F5E5E;
                                        border-style:solid;
				}
.sidebar .infobox caption	{
					background-color: #ebebeb;
					color: #000000;
                                        border:0px;
                                        border-top:1px;
                                        border-right:1px;
                                        border-left:1px;
                                         border-color:#5F5E5E;
                                        border-style:solid;
                                        
				}
.profiletable 	{
					padding: 5px;
			  	}
.profiletable .fieldname	{
					background-color: #ebebeb;
					color: #000000;
					padding: 5px;
				}
.profiletable input, .profiletable select, .profiletable textarea
				{
					font-family: verdana;
					font-size: 8pt;
					background-color: #E0E8F4;
					border: 2px;
					border-style: solid;

					border-color: #E5B54C;
				}
.profiletable input:hover, 
.profiletable select:hover, 
.profiletable textarea:hover
				{
					border-color: #000000;
				}
.footer {
     background-color:#ffffff;
	 color:#000000;
	 font-size: 12px;
}

.footer a {
	color:#000000;
	text-decoration:underline;
	font-size: 12px;
	font: Georgia, Verdana, Arial;

}

	
		.weblogdateheader		{
			
										font-size: 0.6ems;
									}
	
.weblog {
      border:1px;
      border-color:#7E7D7D;
      border-style:dashed;
}	
END;

	$template_definition[] = array(
									'id' => 'infobox',
									'name' => "Information Box",
									'description' => "A box containing a caption and some text, used extensively throughout the site. For example, the 'friends' box and most page bodies are info boxes. Of course, you can alter this template however you wish - it doesn't need to be an actual box.",
									'glossary' => array(
															'{{name}}' => 'The title',
															'{{contents}}' => 'The contents of the box'
														)
									);

	$template['infobox'] = <<< END
	
<table class="infobox" width="100%">
	<caption align="top">
		{{name}}
	</caption>
	<tr>
		<td align="left">
{{contents}}
		</td>
	</tr>
</table><br />
	
END;

	$template_definition[] = array(
									'id' => 'messageshell',
									'name' => "System message shell",
									'description' => "A list of system messages will be placed within the message shell.",
									'glossary' => array(
															'{{messages}}' => 'The messages'
														)
									);

	$template['messageshell'] = <<< END
	
	<table width="100%" class="messages">
		<tr>
			<td>
				<ul>
					{{messages}}
				</ul>
			</td>
		</tr>
	</table>
	
END;

	$template_definition[] = array(
									'id' => 'messages',
									'name' => "Individual system messages",
									'description' => "Each individual system message.",
									'glossary' => array(
															'{{message}}' => 'The system message'
														)
									);

	$template['messages'] = <<< END

	<li>
		{{message}}
	</li>	
	
END;
	

	$template_definition[] = array(
									'id' => 'menu',
									'name' => "Menubar shell",
									'description' => "A list of menu items will be placed within the menubar shell.",
									'glossary' => array(
															'{{menuitems}}' => 'The menu items'
														)
									);

	$template['menu'] = <<< END
	
    <table width="100%" class="menubar">
       <tr>
           <td>
               {{menuitems}}
           </td>
       </tr>
   </table>
	
END;

	$template_definition[] = array(
									'id' => 'menuitem',
									'name' => "Individual menu item",
									'description' => "This is the template for each individual menu item. A series of these is placed within the menubar shell template.",
									'glossary' => array(
															'{{location}}' => 'The URL of the menu item',
															'{{menu}}' => 'The menu item\'s name'
														)
									);

	$template['menuitem'] = <<< END
	
	<a href="{{location}}" class="menuitem">{{name}}</a>
	
END;

	$template_definition[] = array(
									'id' => 'databox',
									'name' => "Data input box (two columns)",
									'description' => "This is mostly used whenever some input is taken from the user. For example, each of the fields in the profile edit screen is a data input box.",
									'glossary' => array(
															'{{name}}' => 'The name for the data we\'re inputting',
															'{{column1}}' => 'The first item of data',
															'{{column2}}' => 'The second item of data'
														)
									);

	$template['databox'] = <<< END

<table width="95%" class="profiletable" align="center" style="margin-bottom: 3px">
   <tr>
       <td width="20%" class="fieldname">
           {{name}}

       </td>
       <td width="50%">
           {{column1}}
       </td>
       <td width="30%">
           {{column2}}
       </td>
   </tr>
</table>
	
END;

	$template_definition[] = array(
									'id' => 'databox1',
									'name' => "Data input box (one column)",
									'description' => "A single-column version of the data box.",
									'glossary' => array(
															'{{name}}' => 'The name of the data we\'re inputting',
															'{{column1}}' => 'The data itself'
														)
									);

	$template['databox1'] = <<< END

<table width="95%" class="profiletable" align="center" style="margin-bottom: 3px">
   <tr>
       <td width="15%" class="fieldname">
           {{name}}
       </td>
       <td width="85%" style="padding-left: 15px">
           {{column1}}
       </td>
   </tr>
</table>
	
END;

	$template_definition[] = array(
									'id' => 'databoxvertical',
									'name' => "Data input box (vertical)",
									'description' => "A slightly different version of the data box, used on this edit page amongst other places.",
									'glossary' => array(
															'{{name}}' => 'Name of the data we\'re inputting',
															'{{contents}}' => 'The data itself'
														)
									);

	$template['databoxvertical'] = <<< END
	
<table width="95%" class="profiletable" align="center" style="margin-bottom: 3px">
   <tr>
       <td align="center" class="fieldname">
           {{name}}
       </td>
   </tr>
   <tr>
       <td >
           {{contents}}
       </td>
   </tr>
</table>
	
END;

?>