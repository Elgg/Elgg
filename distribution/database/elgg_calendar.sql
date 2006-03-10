# SQL script for Elgg calendar feature
#
# This will be merged into the main elgg.sql script shortly but for now run this script AFTER the elgg.sql script is run


CREATE TABLE `calendar`(
	`ident` int(10) unsigned NOT NULL auto_increment,
	`owner` int(10) unsigned NOT NULL default 0,
	
	PRIMARY KEY(`ident`)
);

CREATE TABLE `event`(
	`ident` int(10) unsigned NOT NULL auto_increment,
	`owner` int(10) unsigned NOT NULL default 0,
	`title` varchar(255) default '',
	`description` text NOT NULL,
	`access` varchar(255) default '',
	`location` varchar(50) default '',
	`date_start` int(11) NOT NULL default 0,
	`date_end` int(11) NOT NULL default 0,
	
	PRIMARY KEY(`ident`)
);

INSERT INTO calendar(`owner`) SELECT ident FROM users WHERE user_type='person';

-- Template elements for calendar feature

INSERT INTO `template_elements` 
VALUES (40000, 'dateboxvertical', 

'<div class=\\"profiletable\\">\r\n	
	<div span=\\"fieldname\\">\r\n                  
		<h3>{{name1}}</h3>\r\n	
	</div>\r\n
	<div class=\\"col1\\">\r\n
      		{{contents1}}\r\n
	</div>\r\n
	<br/>
	<div span=\\"fieldname\\">\r\n                  
		<h3>{{name2}}</h3>\r\n	
	</div>\r\n
	<div class=\\"col1\\">\r\n
      		{{contents2}}\r\n
	</div>\r\n
</div><br />', 4);


INSERT INTO `template_elements`
VALUES (40001, 'dateboxvertical', 
'<div class=\\"infobox\\">\r\n
	<table width=\\"95%\\" class=\\"fileTable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\">\r\n
		<tr>\r\n
			<td class=\\"fieldname\\">\r\n
				<p><b>{{name1}}</b></p>\r\n
			</td>\r\n
			<td class=\\"fieldname\\">\r\n
				<p><b>{{name2}}</b></p>\r\n
			</td>\r\n
		</tr>\r\n
		<tr>\r\n
			<td>\r\n
				<p>{{contents1}}</p>\r\n
			</td>\r\n
			<td>\r\n
				<p>{{contents2}}</p>\r\n
			</td>\r\n
		</tr>\r\n	
	</table>\r\n
</div>\r\n', 3);

INSERT INTO `template_elements` 
VALUES (40002, 'dateboxvertical', 
'<div class=\\"infobox\\">\r\n
	<table width=\\"95%\\" class=\\"fileTable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\">\r\n
		<tr>\r\n
			<td class=\\"fieldname\\">\r\n
				<p><b>{{name1}}</b></p>\r\n
			</td>\r\n
			<td class=\\"fieldname\\">\r\n
				<p><b>{{name2}}</b></p>\r\n
			</td>\r\n
		</tr>\r\n
		<tr>\r\n
			<td>\r\n
				<p>{{contents1}}</p>\r\n
			</td>\r\n
			<td>\r\n
				<p>{{contents2}}</p>\r\n
			</td>\r\n
		</tr>\r\n	
	</table>\r\n
</div>\r\n', 2);

INSERT INTO `template_elements` 
VALUES (40003, 'dateboxvertical', 
'<div class=\\"profiletable\\">\r\n
	<div span=\\"fieldname\\">\r\n
                  <h3>{{name1}}</h3>\r\n
	</div>\r\n
	<div class=\\"col1\\">\r\n
          	 	{{contents1}}\r\n
	</div>\r\n
	<br/>
	<div span=\\"fieldname\\">\r\n
                  <h3>{{name2}}</h3>\r\n
	</div>\r\n
	<div class=\\"col1\\">\r\n
          	 	{{contents2}}\r\n
	</div>\r\n
</div>', 1);

INSERT INTO `template_elements`
VALUES (40004, 'dayofweekbox',
'<div class="dayofweekbox">
	<b>{{contents}}</b>
</div>', 1);

INSERT INTO `template_elements`
VALUES (40005, 'dayofweekbox',
'<div class="dayofweekbox">
	<b>{{contents}}</b>
</div>', 2);

INSERT INTO `template_elements`
VALUES (40006, 'dayofweekbox',
'<div class="dayofweekbox">
	<b>{{contents}}</b>
</div>', 3);

INSERT INTO `template_elements`
VALUES (40007, 'dayofweekbox',
'<div class="dayofweekbox">
	<b>{{contents}}</b>
</div>', 4);

INSERT INTO `template_elements`
VALUES (40008, 'activemonthbox',
'<div class="activemonthbox">
		<b>{{contents}}</b>
</div>', 1);

INSERT INTO `template_elements`
VALUES (40009, 'activemonthbox',
'<div class="activemonthbox">
		<b>{{contents}}</b>
</div>', 2);

INSERT INTO `template_elements`
VALUES (40010, 'activemonthbox',
'<div class="activemonthbox">
		<b>{{contents}}</b>
</div>', 3);

INSERT INTO `template_elements`
VALUES (40011, 'activemonthbox',
'<div class="activemonthbox">
		<b>{{contents}}</b>
</div>', 4);

INSERT INTO `template_elements`
VALUES (40012, 'monthlynavigationbox',
'<div class="monthlynavigationbox">
	<table width="40%">
		<tr>
			<td width="50%">
				{{monthbefore}}
			</td>
			<td/>
			<td width="50%">
				{{monthafter}}
			</td>
		</tr>
	</table>
</div>', 1);

INSERT INTO `template_elements`
VALUES (40013, 'monthlynavigationbox',
'<div class="monthlynavigationbox">
	<table width="40%">
		<tr>
			<td width="50%">
				{{monthbefore}}
			</td>
			<td/>
			<td width="50%">
				{{monthafter}}
			</td>
		</tr>
	</table>
</div>', 2);

INSERT INTO `template_elements`
VALUES (40014, 'monthlynavigationbox',
'<div class="monthlynavigationbox">
	<table width="40%">
		<tr>
			<td width="50%">
				{{monthbefore}}
			</td>
			<td/>
			<td width="50%">
				{{monthafter}}
			</td>
		</tr>
	</table>
</div>', 3);

INSERT INTO `template_elements`
VALUES (40015, 'monthlynavigationbox',
'<div class="monthlynavigationbox">
	<table width="40%">
		<tr>
			<td width="50%">
				{{monthbefore}}
			</td>
			<td/>
			<td width="50%">
				{{monthafter}}
			</td>
		</tr>
	</table>
</div>', 4);

INSERT INTO `template_elements`
VALUES (40016, 'datelink',
'<div class="datelink">
	<a href="{{url}}">{{date}}</a>
</div>', 1);

INSERT INTO `template_elements`
VALUES (40017, 'datelink',
'<div class="datelink">
	<a href="{{url}}">{{date}}</a>
</div>', 2);

INSERT INTO `template_elements`
VALUES (40018, 'datelink',
'<div class="datelink">
	<a href="{{url}}">{{date}}</a>
</div>', 3);

INSERT INTO `template_elements`
VALUES (40019, 'datelink',
'<div class="datelink">
	<a href="{{url}}">{{date}}</a>
</div>', 4);

INSERT INTO `template_elements`
VALUES (40020, 'datelink',
'<div class="datelink">
	<a href="{{url}}">{{date}}</a>
</div>', 1);

INSERT INTO `template_elements`
VALUES (40021, 'datelink',
'<div class="datelink">
	<a href="{{url}}">{{date}}</a>
</div>', 2);

INSERT INTO `template_elements`
VALUES (40022, 'datelink',
'<div class="datelink">
	<a href="{{url}}">{{date}}</a>
</div>', 3);

INSERT INTO `template_elements`
VALUES (40023, 'datelink',
'<div class="datelink">
	<a href="{{url}}">{{date}}</a>
</div>', 4);

INSERT INTO `template_elements`
VALUES (40024, 'publicevent',
'<div class="publicevent">
	<a href="{{url}}">{{title}}</a>
</div>', 1);

INSERT INTO `template_elements`
VALUES (40025, 'publicevent',
'<div class="publicevent">
	<a href="{{url}}">{{title}}</a>
</div>', 2);

INSERT INTO `template_elements`
VALUES (40026, 'publicevent',
'<div class="publicevent">
	<a href="{{url}}">{{title}}</a>
</div>', 3);

INSERT INTO `template_elements`
VALUES (40027, 'publicevent',
'<div class="publicevent">
	<a href="{{url}}">{{title}}</a>
</div>', 4);

INSERT INTO `template_elements`
VALUES (40028, 'privateevent',
'<div class="privateevent">
	<a href="{{url}}">{{title}}</a>
</div>', 1);

INSERT INTO `template_elements`
VALUES (40029, 'privateevent',
'<div class="privateevent">
	<a href="{{url}}">{{title}}</a>
</div>', 2);

INSERT INTO `template_elements`
VALUES (40030, 'privateevent',
'<div class="privateevent">
	<a href="{{url}}">{{title}}</a>
</div>', 3);

INSERT INTO `template_elements`
VALUES (40031, 'privateevent',
'<div class="privateevent">
	<a href="{{url}}">{{title}}</a>
</div>', 4);

INSERT INTO `template_elements`
VALUES (40032, 'loggedinevent',
'<div class="loggedinevent">
	<a href="{{url}}">{{title}}</a>
</div>', 1);

INSERT INTO `template_elements`
VALUES (40033, 'loggedinevent',
'<div class="loggedinevent">
	<a href="{{url}}">{{title}}</a>
</div>', 2);

INSERT INTO `template_elements`
VALUES (40034, 'loggedinevent',
'<div class="loggedinevent">
	<a href="{{url}}">{{title}}</a>
</div>', 3);

INSERT INTO `template_elements`
VALUES (40035, 'loggedinevent',
'<div class="loggedinevent">
	<a href="{{url}}">{{title}}</a>
</div>', 4);

INSERT INTO `template_elements`
VALUES (40036, 'publiceventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="publiceventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 1);

INSERT INTO `template_elements`
VALUES (40037, 'publiceventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="publiceventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 2);

INSERT INTO `template_elements`
VALUES (40038, 'publiceventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="publiceventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 3);

INSERT INTO `template_elements`
VALUES (40039, 'publiceventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="publiceventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 4);

INSERT INTO `template_elements`
VALUES (40040, 'privateeventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="privateeventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 1);

INSERT INTO `template_elements`
VALUES (40041, 'privateeventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="privateeventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 2);

INSERT INTO `template_elements`
VALUES (40042, 'privateeventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="privateeventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 3);

INSERT INTO `template_elements`
VALUES (40043, 'privateeventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="privateeventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 4);

INSERT INTO `template_elements`
VALUES (40044, 'loggedineventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="loggedineventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 1);

INSERT INTO `template_elements`
VALUES (40045, 'loggedineventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="loggedineventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 2);

INSERT INTO `template_elements`
VALUES (40046, 'loggedineventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="loggedineventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 3);

INSERT INTO `template_elements`
VALUES (40047, 'loggedineventlegend',
'<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="loggedineventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>', 4);

REPLACE INTO `template_elements` VALUES (39366, 'css', '/* Wordpress theme adapted for Elgg */\r\n/*\r\nTheme Name: Northern-Web-Coders\r\nTheme URI: http://www.northern-web-coders.de/\r\nDescription: Northern-Web-Coders Theme\r\nVersion: 1.0\r\n\r\nAuthor: Kai Ackermann\r\n*/\r\n\r\nbody\r\n{\r\nbackground: #eee; /*#FEF4E2;*/\r\nfont-family: Lucida Grande, Verdana, sans-serif;\r\nmargin: 0;\r\npadding: 0;\r\ntext-align: center;\r\n}\r\n\r\na\r\n{\r\nfont-size: 12px;\r\ncolor: #495865;\r\ntext-decoration:none;\r\n}\r\n\r\na:hover\r\n{\r\ncolor: #6F6F6F;\r\n\r\n}\r\n\r\n#rap\r\n{\r\nbackground: #FFFFFF;\r\nmargin: 0 auto 0 auto;\r\nwidth: 769px;\r\ntext-align: left;\r\nborder: 3px solid #5F707A;\r\n}\r\n\r\nh1#header\r\n{\r\nbackground: url(/_templates/northern/himmel.jpg);\r\nwidth: 769px;\r\nheight: 205px;\r\nmargin: 0;\r\npadding: 0;\r\ntext-align: left;\r\n}\r\n\r\nh1#header a\r\n{\r\nposition: relative;\r\ntop: 170px;\r\nleft: 10px;\r\nfont-size: 24px;\r\nbackground: transparent;\r\npadding: 5px;\r\ncolor: #FEF4E2;\r\ntext-decoration: none;\r\n}\r\n\r\nh1#header a:hover\r\n{\r\nposition: relative;\r\n\r\ntop: 170px;\r\nleft: 10px;\r\nfont-size: 24px;\r\nbackground: transparent;\r\npadding: 5px;\r\ncolor: #5F5F5F;\r\ntext-decoration: none;\r\n}\r\n\r\n/*-------------------------------------------------\r\nSTATUS BAR\r\n-------------------------------------------------*/\r\n\r\n#Statusbar {\r\n	color: #1181AA;\r\n	padding: 3px 10px 2px 0;\r\n	margin: 0px;\r\n	text-align: bottom;\r\n	font-size: 9px;\r\n    height:15px;\r\n    background:#eee;\r\n}\r\n\r\n\r\n#Statusbar a {\r\n	font-size: 11px;\r\n       color: #666;\r\n}\r\n\r\n#StatusRight {\r\n	text-align: right;\r\n	padding:0px;\r\n	padding-top:0px;\r\n	padding-bottom:0px;\r\n}\r\n\r\n#StatusRight a:hover{\r\n    text-decoration:underline;\r\n}\r\n\r\n#StatusLeft {\r\n	float: left;\r\n	color: #333;\r\n}\r\n\r\n\r\n#StatusLeft p{\r\n	font-weight: normal;\r\n	font-size:12px;\r\n	font-weight:bold;\r\n	padding:0px;\r\n	padding-left:3px;\r\n	margin:0px;\r\n	color:#ggg;\r\n}\r\n\r\n\r\n#content\r\n{\r\nposition: relative;\r\nleft: 10px;\r\nfloat: left;\r\npadding: 0;\r\npadding-top:6px;\r\nwidth: 490px;\r\ncolor: #495865;\r\nfont-size: 11px;\r\n}\r\n\r\n#content a {\r\n    text-decoration:underline;\r\n}\r\n\r\n#content h2\r\n{\r\nborder-bottom: 1px solid #6F6F6F;\r\ncolor: #5F707A;\r\nfont-size: 12px;\r\nmargin: 20px 0 5px 0;\r\npadding: 0 0 3px 0;\r\ntext-align: right;\r\nwidth: 100%;\r\n}\r\n\r\n#content h2#comments a\r\n{\r\ncolor: #5F707A;\r\nfont-size: 14px;\r\n}\r\n\r\n#content h3\r\n{\r\nmargin: 0 0 5px 0;\r\npadding: 0;\r\n}\r\n\r\n#content h3 a\r\n{\r\ncolor: #990000;\r\ntext-decoration: none;\r\n}\r\n\r\n#content h3 a:hover\r\n{\r\ncolor: #990000;\r\ntext-decoration: underline;\r\n}\r\n\r\n#content ul\r\n{\r\ndisplay: inline;\r\nmargin: 0;\r\npadding: 0;\r\nlist-style-type: circle;\r\n}\r\n\r\n#navcontainer ul\r\n{\r\npadding: 0;\r\nmargin: 0;\r\nbackground: #5F707A;\r\nborder-top: 1px solid #DFDFDF;\r\nborder-bottom: 0px solid #DFDFDF;\r\nfloat: left;\r\nwidth: 769px;\r\nfont: 15px arial, helvetica, sans-serif;\r\n}\r\n\r\n\r\n\r\n#navcontainer ul li { display: inline; }\r\n\r\n#navcontainer ul li.page_item a\r\n{\r\npadding: 10px 14px 11px 14px;\r\nbackground: #9C9D95;\r\ncolor: #ffffff;\r\ntext-decoration: none;\r\nfont-weight: bold;\r\nfloat: left;\r\nborder-right: 1px solid #FFFFFF;\r\n}\r\n\r\n#navcontainer ul li.page_item a:hover\r\n{\r\ncolor: #990000;\r\nbackground: #C9C0B0;\r\n}\r\n\r\n#navcontainer ul li.current_page_item a\r\n{\r\npadding: 10px 14px 11px 14px;\r\nbackground: #C9C0B0;\r\ncolor: #990000;\r\ntext-decoration: none;\r\nfont-weight: bold;\r\nfloat: left;\r\nborder-right: 1px solid #DFDFDF;\r\n}\r\n\r\n#navcontainer ul li.current_page_item a:hover\r\n{\r\nbackground: #6F6F6F;\r\n}\r\n\r\n#menu  {\r\nclear: right;\r\nfloat: left;\r\nposition: relative;\r\ntop: 10px;\r\nleft: 50px;\r\nmargin: 0 0 10px 0;\r\nwidth: 220px;\r\n}\r\n\r\n#menu p\r\n{\r\nfont-size: 12px;\r\n}\r\n\r\np.credit\r\n{\r\ncolor: #FFFFFF;\r\nbackground: #5F707A;\r\nborder

\r\n-top: 1px solid #DFDFDF;\r\nclear: both;\r\nfont-size: 12px;\r\nmargin: 0 auto 0 auto;\r\npadding: 16px 0 17px 0;\r\ntext-align: center;\r\nwidth: 769px;\r\n}\r\n\r\np.credit a\r\n{\r\ncolor: #ffffff\r\n}\r\n\r\n/*-------------------------------------------------\r\nSELECTED HEADER\r\n-------------------------------------------------*/\r\n\r\n.SectionContent {\r\n	margin: 0 0 20px 0;\r\n}\r\n\r\n.SectionContent h1 {\r\n	padding-bottom: 2px;\r\n	border-bottom: 1px solid #666;\r\n	margin: 0;\r\n	font-size: 14px;\r\n	color: #666;\r\n}\r\n\r\n.SectionContent h3 {\r\n	font-family: verdana;\r\n	padding: 2px 0 0 0;\r\n	margin: 0;\r\n	font-size: 10px;\r\n	font-weight: normal;\r\n	color: #990000;\r\n	}\r\n\r\n.SectionContent h3 a {\r\n	font-weight:bold;\r\n        font-color:#990000;\r\n}\r\n\r\n.SectionContent h3 a:hover {\r\n	text-decoration: underline;\r\n}\r\n\r\n#menu .SectionContent {\r\n	\r\n}\r\n\r\n#menu h1 {\r\n	padding-bottom: 2px;\r\n        border:0px;\r\n	border-bottom: 1px solid #666;\r\n	margin: 0;\r\n	font-size: 11px;\r\n	color: #666;\r\n}\r\n\r\n#menu .box_user .SectionContent h1 {\r\n	padding-bottom: 2px;\r\n	border-bottom: 1px solid #666;\r\n}\r\n\r\n/* .box_user {\r\n  /*background-color:#FAC83D;*/ /*#FAC83D;*/\r\n  margin:0px;\r\n  margin-bottom:5px;\r\n} */\r\n\r\n/* .box_user .me {\r\n		padding: 3px;\r\n		/*background-color:#eee;*/\r\n		padding-top: 3px;\r\n		min-height: 71px;\r\n	} */\r\n\r\n/* .box_user p {\r\n   color:#000; /*#1181AA;*/\r\n   padding: 0px;\r\n   margin-top: 0px;\r\n   background-color:#eee;\r\n  } */\r\n\r\n/* .box_user .usermenu a {\r\n   color:#000; /*#1181AA;*/\r\n   padding: 0px;\r\n   margin-top: 0px;\r\n   font-size: 75%;\r\n   text-decoration: none;\r\n  } */\r\n\r\n\r\n/*-------------------------------------------------\r\nProfile table\r\n-------------------------------------------------*/\r\n\r\n.profiletable 	{\r\n					border: 1px;\r\n					border-style: solid;\r\n					border-color: #ddd;\r\n					text-align:top;\r\n			  	}\r\n\r\n.profiletable 	p{\r\n       padding:0px;\r\n	   margin:0px;\r\n	  }\r\n				\r\n.profiletable img {\r\n	border: 0;\r\n}\r\n\r\n.profiletable .fieldname	{\r\n					background-color: #F9F9F9;\r\n					border-right: 2px solid #eaeac7; /*#8B8C8C;*/\r\n					color: #1181AA;\r\n\r\n					padding-left: 10px;\r\n					text-align:bottom;\r\n				}\r\n\r\n.profiletable .fieldname p {\r\n      color: #666;\r\n}\r\n\r\n.fileTable {\r\n   background-color: #F9F9F9;\r\n   border:1px;\r\n   border-style:solid;\r\n   border-color:#DDD;\r\n }\r\n\r\n .fileTable p{\r\n   padding:0px;\r\n   margin:0px;\r\n   color:#1181AA;\r\n }\r\n\r\n.profile_item {\r\n    border:1px;\r\n	border-style:none;\r\n	border-color:#ebebeb;\r\n	background-color:#ebebeb;\r\n	margin:0px;\r\n	margin-bottom:3px;\r\n}\r\n\r\n.profile_item2 {\r\n    border:1px;\r\n	border-style:none;\r\n	border-color:#fff;\r\n	background-color:#fff;\r\n	margin:0px;\r\n	margin-bottom:3px;\r\n}\r\n\r\n#js{ \r\n	border:1px solid #D3322A;\r\n	background:#F7DAD8;\r\n	padding:3px 50px;\r\n	margin:0;	\r\n}\r\n\r\n#js p{\r\n   padding:0px;\r\n   margin:2px;\r\n }\r\n\r\n/* -------------  h5 -------------*/\r\n\r\n.h5 {\r\n	font-size:1.2em;\r\n}\r\n\r\n/*-------------- extra blog classes -----*/\r\n.user {\r\n	float: left;\r\n	margin: 0px;\r\n	/* padding: 0.3em 2em 2em 0; */\r\n	width: 105px;\r\n	text-align: left;\r\n}\r\n\r\n.weblog_post .post_content {\r\n	padding-left: 2em;\r\n        border:0px;\r\n        border-bottom:0px;\r\n        border-style:dashed;\r\n        border-color:#5F707A;\r\n}\r\n\r\n.clearing{clear:both;}\r\n	\r\n		.weblogdateheader		{\r\n			\r\n										font-size: 0.6ems;\r\n                        width:100%;\r\n									}\r\n.weblogdateheader h2 {\r\n    width:100%\r\n} .dayofweekbox {
	font-size: 9px;
	font-color: #FFFFFF;
	margin-left: 2px;
	margin-right: 2px;
}

.activemonthbox {
	font-size: 15px;
	font-color: #FFFFFF;
	margin-right: 5px;
}

.monthlynavigationbox{
	font-family: verdana;
	padding: 2px 0 0 0;
	margin: 0;
	font-size: 10px;
	font-weight: normal;
	color: #000;
}

.monthlynavigationbox a {
	font-weight:bold;
}

.monthlynavigationbox a:hover {
	text-decoration: underline;
}

.datelink a {
	font-weight:bold;
}

.datelink a:hover {
	text-decoration: underline;
}

.publicevent a {
	color: #66CC33;
}

.publicevent a:hover {
	text-decoration: underline;
}

.privateevent a {
	color: #CC3300;
}

.privateevent a:hover {
	text-decoration: underline;
}

.loggedinevent a {
	color: #3366FF;
}

.loggedinevent a:hover {
	text-decoration: underline;
}

.publiceventlegend {
	background-color: #66CC33;
	width: 25px;	
}

.privateeventlegend {
	background-color: #CC3300;
	width: 25px;	
}

.loggedineventlegend {
	background-color: #3366FF;
	width: 25px;	
}
}', 2);

REPLACE INTO `template_elements` VALUES (37870, 'css', '/* \r\nTheme Name: FastTrack\r\n\r\nTheme URI: http://wpthemes.info\r\nVersion: 1.0\r\nDescription: A Theme from wpthemes.Info\r\nAuthor: Sadish Balasubramanian\r\nAuthor URI: http://www.simpleinside.com\r\n\r\nHeader Image by tkekkonen\r\nhttp://sxc.hu/browse.phtml?f=view&id=101419\r\n\r\nIcons from http://www.stylegala.com/features/bulletmadness/\r\n*/\r\nbody {\r\n	margin:0;\r\n	padding:0;\r\n	font-family: Georgia, Times, Times New Roman, sans-serif;\r\n	font-size: 0.9em;\r\n	text-align:center;\r\n	color:#29303B;\r\n	line-height:1.3em;\r\n	background: #886;\r\n}\r\na {\r\n	color: #8a3207;	\r\n	text-decoration:none;\r\n}\r\na:visited {\r\n	color: #8a3207;\r\n}\r\na:hover {\r\n	color: #753206;\r\n	text-decoration:underline;\r\n}\r\ninput, textarea \r\n{\r\n	border:#8a3207 1px solid;\r\n}\r\n#rap \r\n\r\n{\r\n	background:#fff url(\\''/_templates/fasttrack/contentbg.jpg\\'') center;\r\n	width:700px;\r\n	margin:0 auto;\r\n	padding:8px;\r\n	text-align:left;\r\n	font-family: Trebuchet MS, Georgia, Arial, serif;\r\n	font-size: 0.9em;\r\n}\r\n#header {\r\n	background: url(\\''/_templates/fasttrack/greentopa.jpg\\'') no-repeat bottom; 	\r\n 	height: 175px;\r\n	margin: 0 auto;\r\n	width:700px;\r\n	padding:0;\r\n}\r\n\r\n#content {\r\n	width:460px;\r\n	float:left;\r\n	padding:5px 0 5px 5px;\r\n	margin:5px 0 0 5px;\r\n	overflow:hidden;\r\n}\r\n\r\n#sidebar {\r\n	width:180px;\r\n	float:right;\r\n	padding:10px 8px;\r\n	margin:0;\r\n	font-size:1em;\r\n} \r\na img {\r\n	border: none;\r\n}\r\nacronym, abbr {\r\n	border-bottom: 1px dotted #0c6bf0;\r\n}\r\nacronym, abbr, span.caps {\r\n	cursor: help;\r\n	letter-spacing: .07em;\r\n}\r\ncode {\r\n	font-size: 1em;\r\n	font-style: italic;\r\n}\r\nblockquote{\r\n	background: #EEE url(/_templates/fasttrack/blockquote.png) no-repeat bottom left;\r\n	/*border: 1px solid #E0E0E0;*/\r\n	padding: 10px 10px 30px 10px;\r\n	margin: 1em 1em 1em 3em;\r\n	width:250px;\r\n}\r\n\r\ncite {\r\n	font-size: 0.9em;\r\n	font-style: normal;\r\n}\r\nh3 {\r\n	margin: 0;\r\n	padding: 0;\r\n	font-size:1.3em;\r\n}\r\np {\r\n	margin: 0 0 1em;\r\n	padding: 0;\r\n	line-height: 1.5em;\r\n}\r\nh1, h2, h3, h4, h5, h6 {\r\n	font-family: Georgia, \\"Lucida Sans Unicode\\", lucida, Verdana, sans-serif;\r\n	font-weight: normal;\r\n	letter-spacing: 1px;\r\n}\r\n\r\nh6 {\r\n  font-size:11px;\r\n}\r\n#header h1 \r\n{\r\n	margin: 0;\r\n	font-size: 1.6em;\r\n	color: #0f0f0f;\r\n	padding:130px 0 0 30px;\r\n\r\n	text-align:left;\r\n}\r\n.description \r\n{\r\n	margin:0;\r\n	padding:10px 100px 0 10px;\r\n	font-size:1.1em;\r\n	color:#777;	\r\n	display:none;\r\n}\r\n#sidebar h2 {\r\n	margin: 0;\r\n	padding:0 5px;\r\n	font-size: 0.8em;\r\n	color: #333;\r\n	text-transform:uppercase;	\r\n	border-bottom:#ccc 1px solid;	\r\n}\r\nh4 {\r\n	margin-top: 0;\r\n	margin-bottom: 0;\r\n	font-size: 1.1em;\r\n	color: #999;\r\n}\r\n#sidebar ul {\r\n	list-style-type: none;\r\n	padding: 0 0 1em 5px;\r\n	margin: 0;\r\n	font-size: 0.9em;	\r\n}\r\n#sidebar ul li {\r\n	margin: 0.5em 0 0 0;\r\n\r\n	padding: 0;	\r\n}\r\n#sidebar li a:link, #sidebar li a:visited {\r\n	color: #8a3207;	\r\n	text-decoration: none;\r\n	border:none;\r\n        list-style-type: none;\r\n}\r\n#sidebar li a:hover {\r\n	color: #753206;\r\n	text-decoration:underline;\r\n	border:none;\r\n}\r\n#header a:link, #header a:visited {\r\n	color: #333;\r\n	text-decoration: none;\r\n	border-bottom: none;\r\n}\r\n#header a:hover, #header a:active {\r\n	color: #996;\r\n	text-decoration: none;\r\n	border-bottom: none;\r\n}\r\n#content ul {\r\n	margin-left: 0;\r\n	padding-left: 15px;\r\n\r\n	list-style-type: none;\r\n}\r\n#content ul li {\r\n	background: url(\\''img/bullet.png\\'') no-repeat 0 7px;\r\n	padding-left: 1.5em;\r\n}\r\n.post-footer, .copyright {\r\n	margin-bottom: 3em;\r\n	font-size: 0.9em;\r\n	color: #666;\r\n}\r\n.post-content {\r\n	padding: 1em 0 0;\r\n}\r\n.post-title {\r\n	margin: 0 0 0.2em;	\r\n	text-align: right;\r\n	padding: 0.5em 1em 0 0;\r\n	color: #999;\r\n	border-bottom:#ccc 1px solid;\r\n	font-family: \\"Lucida Grande\\", \\

\r\n"Lucida Sans Unicode\\", lucida, Verdana, sans-serif;\r\nfont-size: 0.8em;\r\n}\r\n.post-title em {\r\n	text-decoration: none;\r\n	float: left;\r\n	font-style: normal;\r\n	padding:0;	\r\n}\r\n.post-info \r\n{\r\n	margin:0;\r\n	padding:0;\r\n	font-size:1.1em;\r\n	font-family:Georgia, Arial, Verdana, Serif;\r\n}\r\n#content h2, #content h3 {\r\n	color: #666;\r\n	font-family:Georgia, Arial, Serif;\r\n	font-size:1.1em;\r\n\r\n	margin:0;\r\n}\r\n.post-info a {\r\n	text-decoration: none;\r\n	color: #8a3207;\r\n	border: none;\r\n}\r\nhr {\r\n	display: none;\r\n}\r\n#footer {\r\n	margin:0 auto;\r\n	padding: 7px 0;\r\n	border-top: 1px solid #996;\r\n	clear: both;\r\n	font-size: 0.8em;\r\n	color: #999;\r\n	text-align:center;width:690px;\r\n}\r\n#footer a {\r\nborder:none;\r\ncolor:#7A7636;\r\n}\r\n#commentlist {\r\n	font-size:1em;\r\n	font-weight:bold;\r\n	color: #ccc;\r\n}\r\n#commentlist li {\r\n	color: #666;\r\n	font-weight: normal;\r\n	font-size:0.85em;\r\n}\r\n#commentlist cite {\r\n	font-size: 0.8em;\r\n	color: #808080;\r\n	margin: 0 0 1em;\r\n	padding: 0 0 0.5em;\r\n}\r\ncite a {\r\n	border-bottom: 1px dotted #DC9204;\r\n	text-decoration: none;\r\n}\r\ncite a:visited, a:hover {\r\n	border-bottom: none;\r\n}\r\n#commentform #author, #commentform #email, #commentform #url, #commentform textarea {\r\n	background: #fafafa;\r\n	border: 1px solid #9ac2a7;\r\n	padding: 0.2em;\r\n}\r\n#commentform textarea {\r\n	width: 80%;\r\n}\r\n#commentform p {\r\n	margin: 0 0 1em;\r\n}\r\n#commentlist li ul {\r\n	border-left: 1px solid #ddd;\r\n	font-size: 110%;\r\n	list-style-type: none;\r\n}\r\n#comments,#respond {\r\n	text-transform: uppercase;\r\n	margin: 3em 0 1em 0;\r\n	color: #AA7D39;\r\n	font: 0.9em verdana, helvetica, sans-serif;\r\n}\r\n#topnav \r\n{\r\n	list-style:none;\r\n	font-size:0.9em;\r\n	margin:0 auto;	\r\n	padding:2px 0 2px 5px;\r\n	text-align:right;	\r\n	text-transform:lowercase;	\r\n}\r\n#topnav li \r\n{\r\n	list-style:none;\r\n	display:inline;\r\n	padding:0 1em;\r\n	margin:0;\r\n}\r\n\r\n#topnav li a:link, #topnav li a:visited, #topnav li a:hover, #topnav li a:active \r\n{\r\n	text-decoration:none;	\r\n	color:#666;\r\n}\r\n#topnav li a:hover\r\n{\r\n	border-bottom:#7A7636 3px solid;\r\n	color:#7A7636;	\r\n}\r\n#navHome \r\n{\r\n	padding-left:15px;\r\n	background:url(\\''/_templates/fasttrack/home.png\\'') no-repeat left center;\r\n}\r\n#navAbout \r\n{\r\n	padding-left:15px;\r\n	background:url(\\''/_templates/fasttrack/about.png\\'') no-repeat left center;\r\n}\r\n#navArchives \r\n{\r\n	padding-left:15px;\r\n	background:url(\\''/_templates/fasttrack/archives.png\\'') no-repeat left center;\r\n}\r\n#navLinks \r\n{\r\n	padding-left:15px;\r\n	background:url(\\''/_templates/fasttrack/links.png\\'') no-repeat left center;\r\n}\r\n#navContact \r\n{\r\n	padding-left:15px;\r\n	background:url(\\''/_templates/fasttrack/contact.png\\'') no-repeat left center;\r\n}\r\n#home #navHome, #about #navAbout, #links #navLinks, #contact #navContact, #archives #navArchives\r\n{\r\n	border-bottom:#7A7636 3px solid;\r\n	color:#7A7636;	\r\n}\r\n\r\n/* additional */\r\n\r\n\r\n.user {\r\n	float: left;\r\n	padding: 0.3em 2em 2em 0;\r\n}\r\n\r\n.posted {\r\n	display: block;\r\n	border-top: 1px solid #E2E2E2;\r\n	color: #666;\r\n	padding-top: 2px;\r\n}\r\n\r\n.weblogdateheader		{\r\n			\r\n										font-size: 0.6ems;\r\n										text-align: right;\r\n										border-right: 0.5em solid #886;\r\n										border-bottom: 1px solid #886;\r\n										color: #5C7B8E;\r\n										padding-right: 0.5em;\r\n\r\n									}\r\n\r\n.blogtitle {\r\n   padding:5px;\r\n}\r\n\r\n.userlist p {\r\n    font-size:12px;\r\n}\r\n\r\n\r\n#sidebar .infobox h2 {\r\n      margin-bottom:6px;\r\n}\r\n\r\n#js p{\r\n  color:#990000;\r\n  font-size:14px;\r\n} .dayofweekbox {
	font-size: 9px;
	font-color: #FFFFFF;
	margin-left: 2px;
	margin-right: 2px;
}

.activemonthbox {
	font-size: 15px;
	font-color: #FFFFFF;
	margin-right: 5px;
}

.monthlynavigationbox{
	font-family: verdana;
	padding: 2px 0 0 0;
	margin: 0;
	font-size: 10px;
	font-weight: normal;
	color: #000;
}

.monthlynavigationbox a {
	font-weight:bold;
}

.monthlynavigationbox a:hover {
	text-decoration: underline;
}

.datelink a {
	font-weight:bold;
}

.datelink a:hover {
	text-decoration: underline;
}

.publicevent a {
	color: #66CC33;
}

.publicevent a:hover {
	text-decoration: underline;
}

.privateevent a {
	color: #CC3300;
}

.privateevent a:hover {
	text-decoration: underline;
}

.loggedinevent a {
	color: #3366FF;
}

.loggedinevent a:hover {
	text-decoration: underline;
}

.publiceventlegend {
	background-color: #66CC33;
	width: 25px;	
}

.privateeventlegend {
	background-color: #CC3300;
	width: 25px;	
}

.loggedineventlegend {
	background-color: #3366FF;
	width: 25px;	
}	
}', 4);


REPLACE INTO `template_elements` VALUES (36550, 'css', '/*\r\nTheme Name: Northern-Web-Coders\r\nTheme URI: http://www.northern-web-coders.de/\r\nDescription: Northern-Web-Coders Theme\r\nVersion: 1.0\r\n\r\nAuthor: Kai Ackermann\r\n*/\r\n\r\nbody\r\n{\r\nbackground: #ebebeb;\r\nfont-family: Lucida Grande, Verdana, sans-serif;\r\nmargin: 0;\r\npadding: 0;\r\n\r\ntext-align: center;\r\n\r\n}\r\n\r\na\r\n{\r\nfont-size: 12px;\r\ncolor: #495865;\r\ntext-decoration:none;\r\n}\r\n\r\na:hover\r\n{\r\n\r\ncolor: #6F6F6F;\r\n}\r\n\r\n#rap\r\n{\r\nbackground: #FFFFFF;\r\nmargin: 0 auto 0 auto;\r\nwidth: 769px;\r\ntext-align: left;\r\nborder: 3px solid #5F707A;\r\n}\r\n\r\nh1#header\r\n{\r\nbackground:#5F707A;\r\nwidth: 769px;\r\nheight: 50px;\r\nmargin: 0;\r\npadding: 0;\r\ntext-align: left;\r\nfont-size: 18px;\r\ncolor: #FEF4E2;\r\n}\r\n\r\nh1#header a\r\n{\r\nposition: relative;\r\ntop: 20px;\r\nleft: 10px;\r\nfont-size: 20px;\r\nbackground: transparent;\r\npadding: 5px;\r\ncolor: #FEF4E2;\r\ntext-decoration: none;\r\n}\r\n\r\nh1#header a:hover\r\n{\r\nposition: relative;\r\n\r\ntop: 170px;\r\nleft: 10px;\r\nfont-size: 24px;\r\nbackground: transparent;\r\npadding: 5px;\r\ncolor: #5F5F5F;\r\ntext-decoration: none;\r\n}\r\n\r\n/*-------------------------------------------------\r\nSTATUS BAR\r\n-------------------------------------------------*/\r\n\r\n#Statusbar {\r\n	color: #1181AA;\r\n	padding: 3px 10px 2px 0;\r\n	margin: 0px;\r\n	text-align: bottom;\r\n	font-size: 9px;\r\n    height:15px;\r\n    background:#eee;\r\n}\r\n\r\n\r\n#Statusbar a {\r\n	font-size: 11px;\r\n       color: #666;\r\n}\r\n\r\n#StatusRight {\r\n	text-align: right;\r\n	padding:0px;\r\n	padding-top:0px;\r\n	padding-bottom:0px;\r\n}\r\n\r\n#StatusRight a:hover{\r\n    text-decoration:underline;\r\n}\r\n\r\n#StatusLeft {\r\n	float: left;\r\n	color: #333;\r\n}\r\n\r\n\r\n#StatusLeft p{\r\n	font-weight: normal;\r\n	font-size:12px;\r\n	font-weight:bold;\r\n	padding:0px;\r\n	padding-left:3px;\r\n	margin:0px;\r\n	color:#ggg;\r\n}\r\n\r\n\r\n#content\r\n{\r\nposition: relative;\r\nleft: 10px;\r\nfloat: left;\r\npadding: 0;\r\npadding-top:6px;\r\nwidth: 490px;\r\ncolor: #495865;\r\nfont-size: 11px;\r\n}\r\n\r\n#content a {\r\n    text-decoration:underline;\r\n}\r\n\r\n#content h2\r\n{\r\nborder-bottom: 1px solid #6F6F6F;\r\ncolor: #5F707A;\r\nfont-size: 12px;\r\nmargin: 20px 0 5px 0;\r\npadding: 0 0 3px 0;\r\ntext-align: right;\r\nwidth: 100%;\r\n}\r\n\r\n#content h2#comments a\r\n{\r\n\r\ncolor: #5F707A;\r\nfont-size: 14px;\r\n}\r\n\r\n#content h3\r\n{\r\nmargin: 0 0 5px 0;\r\npadding: 0;\r\n}\r\n\r\n#content h3 a\r\n{\r\ncolor: #990000;\r\ntext-decoration: none;\r\n}\r\n\r\n#content h3 a:hover\r\n{\r\ncolor: #990000;\r\ntext-decoration: underline;\r\n}\r\n\r\n#content h5 {\r\n    font-size:12px;\r\n     font-family:Arial;\r\n}\r\n\r\n#content ul\r\n{\r\ndisplay: inline;\r\nmargin: 0;\r\npadding: 0;\r\nlist-style-type: circle;\r\n}\r\n\r\n#navcontainer ul\r\n{\r\npadding: 0;\r\n\r\nmargin: 0;\r\nbackground: #5F707A;\r\nborder-top: 1px solid #DFDFDF;\r\nborder-bottom: 0px solid #DFDFDF;\r\nfloat: left;\r\nwidth: 769px;\r\nfont: 15px arial, helvetica, sans-serif;\r\n}\r\n\r\n\r\n\r\n#navcontainer ul li { display: inline; }\r\n\r\n#navcontainer ul li.page_item a\r\n{\r\npadding: 10px 14px 11px 14px;\r\nbackground: #9C9D95;\r\ncolor: #ffffff;\r\ntext-decoration: none;\r\nfont-weight: bold;\r\nfloat: left;\r\nborder-right: 1px solid #FFFFFF;\r\n}\r\n\r\n\r\n#navcontainer ul li.page_item a:hover\r\n{\r\ncolor: #990000;\r\nbackground: #C9C0B0;\r\n}\r\n\r\n#navcontainer ul li.current_page_item a\r\n{\r\npadding: 10px 14px 11px 14px;\r\nbackground: #C9C0B0;\r\ncolor: #990000;\r\ntext-decoration: none;\r\nfont-weight: bold;\r\nfloat: left;\r\nborder-right: 1px solid #DFDFDF;\r\n}\r\n\r\n\r\n#navcontainer ul li.current_page_item a:hover\r\n{\r\nbackground: #6F6F6F;\r\n}\r\n\r\n#menu  {\r\nclear: right;\r\nfloat: left;\r\nposition: relative;\r\ntop: 0px;\r\nleft: 59px;\r\nmargin: 0px;\r\nwidth: 220px;\r\nbackground:#ebebeb;\r\n}\r\n\r\n#menu p\r\n{\r\nfont-size: 12px;\r\n}\r\n

\r\n\r\np.credit\r\n{\r\ncolor: #FFFFFF;\r\nbackground: #5F707A;\r\nborder-top: 1px solid #DFDFDF;\r\nclear: both;\r\nfont-size: 12px;\r\nmargin: 0 auto 0 auto;\r\npadding: 16px 0 17px 0;\r\ntext-align: center;\r\nwidth: 769px;\r\n}\r\n\r\np.credit a\r\n{\r\ncolor: #ffffff\r\n}\r\n\r\n/*-------------------------------------------------\r\nSELECTED HEADER\r\n-------------------------------------------------*/\r\n\r\n.SectionContent {\r\n	margin: 0 0 20px 0;\r\n}\r\n\r\n.SectionContent h1 {\r\n	padding-bottom: 2px;\r\n	border-bottom: 1px solid #666;\r\n	margin: 0;\r\n	font-size: 13px;\r\n        font-family: Arial;\r\n	color: #666;\r\n}\r\n\r\n.SectionContent h3 {\r\n	font-family: verdana;\r\n	padding: 2px 0 0 0;\r\n	margin: 0;\r\n	font-size: 10px;\r\n	font-weight: normal;\r\n	color: #990000;\r\n	}\r\n\r\n.SectionContent h3 a {\r\n	font-weight:bold;\r\n        font-color:#990000;\r\n}\r\n\r\n.SectionContent h3 a:hover {\r\n	text-decoration: underline;\r\n}\r\n\r\n#menu .SectionContent {\r\n    background-color:#5F707A;\r\n\r\n}\r\n\r\n#menu .box_files .SectionContent {\r\n    background-color:#5F707A;\r\n}\r\n      \r\n#menu h1{\r\n	margin: 0 0 10px 0;\r\n        padding: 2px;\r\n        padding-left:4px;\r\n        border:0px;\r\n	border-bottom: 1px solid #666;\r\n	margin: 0;\r\n        background-color:#5F707A;\r\n	font-size: 12px;\r\n	color: #fff;\r\n        font-family: Arial;\r\n}\r\n\r\n#menu .box_user {\r\n  /*background-color:#FAC83D;*/ /*#FAC83D;*/\r\n  margin:0px;\r\n  margin-bottom:5px;\r\n}\r\n\r\n#menu .box_user .SectionContent {\r\n        background:#5F707A;\r\n}\r\n\r\n\r\n.box_friend .SectionContent {\r\n        background:#5F707A;\r\n}\r\n\r\n#menu .box_user .me {\r\n		padding: 3px;\r\n		padding-top: 3px;\r\n		min-height: 71px;\r\n	}\r\n\r\n#menu .box_user p {\r\n   color:#000; /*#1181AA;*/\r\n   padding: 0px;\r\n   margin-top: 0px;\r\n  }\r\n\r\n#menu .box_user .usermenu a {\r\n   color:#000; /*#1181AA;*/\r\n   padding: 0px;\r\n   margin-top: 0px;\r\n   font-size: 75%;\r\n   text-decoration: none;\r\n  }\r\n\r\n\r\n/*-------------------------------------------------\r\nProfile table\r\n-------------------------------------------------*/\r\n\r\n.profiletable 	{\r\n					border: 1px;\r\n					border-style: solid;\r\n					border-color: #ddd;\r\n					text-align:top;\r\n			  	}\r\n\r\n.profiletable 	p{\r\n       padding:0px;\r\n	   margin:0px;\r\n	  }\r\n				\r\n.profiletable img {\r\n	border: 0;\r\n}\r\n\r\n.profiletable .fieldname	{\r\n					background-color: #F9F9F9;\r\n					border-right: 2px solid #eaeac7; /*#8B8C8C;*/\r\n					color: #1181AA;\r\n\r\n					padding-left: 10px;\r\n					text-align:bottom;\r\n				}\r\n\r\n.profiletable .fieldname p {\r\n      color: #666;\r\n}\r\n\r\n.fileTable {\r\n   background-color: #F9F9F9;\r\n   border:1px;\r\n   border-style:solid;\r\n   border-color:#DDD;\r\n\r\n }\r\n\r\n\r\n .fileTable p{\r\n   padding:0px;\r\n   margin:0px;\r\n   color:#1181AA;\r\n }\r\n\r\n.profile_item {\r\n    border:1px;\r\n	border-style:none;\r\n	border-color:#ebebeb;\r\n	background-color:#ebebeb;\r\n	margin:0px;\r\n	margin-bottom:3px;\r\n}\r\n\r\n.profile_item2 {\r\n    border:1px;\r\n	border-style:none;\r\n	border-color:#fff;\r\n	background-color:#fff;\r\n	margin:0px;\r\n	margin-bottom:3px;\r\n}\r\n\r\n#js{ \r\n	border:1px solid #D3322A;\r\n	background:#F7DAD8;\r\n	padding:3px 50px;\r\n	margin:0;	\r\n}\r\n\r\n#js p{\r\n   padding:0px;\r\n   margin:2px;\r\n }\r\n\r\n/* -------------  h5 -------------*/\r\n\r\n.h5 {\r\n	font-size:1.2em;\r\n}\r\n\r\n/*-------------- extra blog classes -----*/\r\n.user {\r\n	float: left;\r\n	margin: 0px;\r\n	/* padding: 0.3em 2em 2em 0; */\r\n	width: 105px;\r\n	text-align: left;\r\n}\r\n\r\n.weblog_post .post_content {\r\n	padding-left: 2em;\r\n        border:0px;\r\n        /*border-bottom:1px;\r\n        border-style:dashed;\r\n        border-color:#5F707A;*/\r\n}\r\n\r\n.sub {\r\n  text-align:right;\r\n  font-size:10px;\r\n}\r\n\r\n.sub a {\r\n   font-size:10px;\r\n}\r\n\r\n.clearing{clear:both;}\r\n	\r\n		.weblogdateheader		{\r\n			\r\n										font-size: 0.6em

s;\r\r\n\n									}\r\n\r\n.weblog_post h4 {\r\n     font-size:12px;\r\n}\r\n	.dayofweekbox {
	font-size: 11px;
	font-color: #FFFFFF;
	margin-left: 2px;
	margin-right: 2px;
}

.activemonthbox {
	font-size: 15px;
	font-color: #FFFFFF;
	margin-right: 5px;
}

.monthlynavigationbox{
	font-family: verdana;
	padding: 2px 0 0 0;
	margin: 0;
	font-size: 10px;
	font-weight: normal;
	color: #000;
}

.monthlynavigationbox a {
	font-weight:bold;
}

.monthlynavigationbox a:hover {
	text-decoration: underline;
}

.datelink a {
	font-weight:bold;
}

.datelink a:hover {
	text-decoration: underline;
}

.publicevent a {
	color: #66CC33;
}

.publicevent a:hover {
	text-decoration: underline;
}

.privateevent a {
	color: #CC3300;
}

.privateevent a:hover {
	text-decoration: underline;
}

.loggedinevent a {
	color: #3366FF;
}

.loggedinevent a:hover {
	text-decoration: underline;
}

.publiceventlegend {
	background-color: #66CC33;
	width: 25px;	
}

.privateeventlegend {
	background-color: #CC3300;
	width: 25px;	
}

.loggedineventlegend {
	background-color: #3366FF;
	width: 25px;	
}	
}', 3);

REPLACE INTO `template_elements` VALUES (37848, 'css', '	/* Wordpress theme adapted for Elgg */\r\n	/*\r\n	Theme Name: Gentle Calm\r\n	Theme URI: http://ifelse.co.uk/gentlecalm\r\n	Description: Liquid Serenity\r\n	Version: 1.0\r\n	Author: Phu Ly\r\n	Author URI: http://ifelse.co.uk/\r\n*/\r\n\r\n/************************************************\r\n *	Main structure															*\r\n ************************************************/\r\nbody {\r\n  margin:0px;\r\n  padding:0px;\r\n  text-align:center;\r\n  font:11px \\"Lucida Grande\\", \\"Lucida Sans Unicode\\", Verdana, Helvetica, Arial, sans-serif;\r\n	color: #474E44;\r\n	background:#F3F4EC;\r\n}\r\n\r\n#maincol {\r\n  width:72%;\r\n  float:left;\r\n}\r\n#maincol .col {\r\n	padding-bottom: 0.5em;\r\n	padding-left:2.5em;\r\n	padding-right:8.5em;\r\n	line-height:1.6em;\r\n}\r\n#navcol {\r\n	padding:0.5em;\r\n	padding-top: 2em;\r\n	clear:right;\r\n\r\n	width:25%;\r\n  right:0px;\r\n	float:right;\r\n  font-size:1em;\r\ntext-align:left;\r\n}\r\n#container{	\r\n width:80%;\r\n text-align:left;\r\n margin-left: auto;\r\n display:block;\r\n margin-right: auto;\r\n padding-bottom:0;\r\n}\r\n#container:after {\r\n    content: \\".\\"; \r\n    display: block; \r\n    height: 0; \r\n    clear: both; \r\n    visibility: hidden;\r\n}\r\n\r\n\r\n\r\n#footer{\r\n	border-top:1px solid #324031;\r\n	border-bottom:1px solid #324031;\r\n	color: #eee;\r\n	background: #87a284;\r\n	clear:both;\r\n	padding: 0.3em;\r\n	text-align:center;\r\n	margin-left: auto;\r\n	display:block;\r\n  margin-right: auto;\r\n  font-size: 0.9em;\r\n  margin-top:5em;\r\n}\r\n#footer a{\r\n	color: #fff;\r\n	font-weight:bold;\r\n}\r\na{\r\n	color: #3C657B;\r\n	text-decoration: none;\r\n}\r\na:hover {\r\n	text-decoration:underline;\r\n}\r\n\r\n#menu_container {\r\n}\r\n\r\n#menuNavShell{\r\n	/*float:left;*/\r\n	margin: 10px 10px 0 0;\r\n	/*border-bottom:2px solid #eee;\r\n	background:#FFF;*/\r\n}\r\nul#menuNav, ul.tools {\r\n	margin:0 0 3px 0;\r\n	padding: 0;\r\n	white-space: nowrap;\r\n}\r\n#menuNav li, .tools li {\r\n	display: inline;\r\n	list-style-type: none;\r\n	padding:0 6px;\r\n	/*border-left:1px solid #333;*/\r\n	margin:0;\r\n        font-size:1.4em;\r\n         color:#CCCFBC;\r\n}\r\n\r\n.current {\r\n   border:0px;\r\n    border-bottom:2px;\r\n    border-color:#CCCFBC;\r\n    border-style:solid;\r\n}\r\n\r\n/*-------------------------------------------------\r\nSTATUS BAR\r\n-------------------------------------------------*/\r\n\r\n#Statusbar {\r\n	color: #1181AA;\r\n	padding: 3px 10px 2px 0;\r\n	margin: 0px;\r\n	text-align: bottom;\r\n	font-size: 9px;\r\n    height:19px;\r\n    background:#eee;\r\n}\r\n\r\n\r\n#Statusbar a {\r\n	font-size: 11px;\r\n       color: #666;\r\n}\r\n\r\n#StatusRight {\r\n	text-align: right;\r\n	padding:0px;\r\n	padding-top:0px;\r\n	padding-bottom:0px;\r\n}\r\n\r\n#StatusRight a:hover{\r\n    text-decoration:underline;\r\n}\r\n\r\n#StatusLeft {\r\n	float: left;\r\n	color: #333;\r\n}\r\n\r\n\r\n#StatusLeft p{\r\n	font-weight: normal;\r\n	font-size:12px;\r\n	font-weight:bold;\r\n	padding:0px;\r\n	padding-left:3px;\r\n	margin:0px;\r\n	color:#ggg;\r\n}\r\n\r\n/************************************************\r\n *	Header																			*\r\n ************************************************/\r\n#header {\r\n	padding: 0px;	\r\n	margin-top: 0px;\r\n	padding-top:1em;	\r\n	padding-bottom:1em;\r\n	margin-bottom:0px;\r\n	border-bottom: 1px solid #bab1b1;\r\n	background: #CCCFBC;\r\n	text-align:right;\r\n	padding-right:2em;\r\n	padding-left:-.5em;\r\n}\r\n#headbar{\r\n	background:#f3f1f1;\r\n	height:0.3em;\r\n	margin-bottom:2em;\r\n}\r\n#header h1{\r\n	padding:0px;\r\n	margin: 0px;\r\n	margin-bottom:0.3em;\r\n	font-size: 1.2em;\r\n	letter-spacing:0.2em;\r\n}\r\n#header h1 a {\r\n	color:#5B7B57;\r\n}\r\n#header h1 a:hover {\r\n	text-decoration:none;\r\n	color: #bb4444;\r\n	\r\n}\r\n#header img {\r\n	border:none;\r\n}\r\n#subtitle {\r\n	margin-bottom:0.3em;\r\n	font-size: 0.8em;\r\n	text-transform:uppercase;\r\n	color:#A37B45;\r\n}\r\n/************************************************\r\n *	C

\r\nontent																			*\r\n ************************************************/\r\nh1, h2, h3, h4, h5, h6 {\r\n	font-family:\\"Century Gothic\\", \\"Lucida Grande\\", \\"Lucida Sans Unicode\\", Verdana, Helvetica, Arial, sans-serif;\r\n}\r\nh2 {\r\n	font-size: 1.2em;\r\n	margin-bottom:0.5em;\r\n}\r\n\r\nh5 {\r\n	font-size: 1.2em;\r\n	margin-bottom:0.5em;\r\n}\r\nh6 {\r\n	font-size: 1.1em;\r\n	margin-bottom:0.5em;\r\n}\r\n\r\nh2.entrydate{\r\n	margin-bottom:0.3em;\r\n	font-size: 1.8em;\r\n	font-weight:normal;\r\n	color:#86942A;\r\n	text-transform:uppercase;\r\n}\r\n.entrymeta{\r\n	font-weight:bold;\r\n	color:#99A879;\r\n}\r\nh3.entrytitle a{\r\n	color: #507642;\r\n}\r\nh3.entrytitle{\r\n	margin-top:0px;\r\n	margin-bottom:0.1em;\r\n	font-size: 1.8em;\r\n}\r\n.entrybody p {\r\n	margin-top:0.8em;\r\n	margin-bottom:1.6em;\r\n}\r\n.entry{\r\n	padding-bottom: 2em;\r\n	font-family:\\"Trebuchet MS\\",\\"Lucida Grande\\", \\"Lucida Sans Unicode\\", Verdana, Helvetica, Arial, sans-serif;\r\n}\r\n/************************************************\r\n\r\n *	Navigation Sidebar													*\r\n ************************************************/\r\nul {\r\n margin:0 0 1em 0;\r\n padding-left:0px;\r\n list-style-type:none;\r\n}\r\n\r\n.box_user .me {\r\n		padding: 3px;\r\n		background-color:#FAC83D;\r\n		padding-top: 3px;\r\n		min-height: 71px;\r\n	}\r\n\r\n.box_user p {\r\n   color:#000; /*#1181AA;*/\r\n   padding: 0px;\r\n   margin-top: 0px;\r\n  }\r\n\r\n.box_user .usermenu a {\r\n   color:#000; /*#1181AA;*/\r\n   padding: 0px;\r\n   margin-top: 0px;\r\n   font-size: 75%;\r\n   text-decoration: underline;\r\n  }\r\n\r\n/************************************************\r\n *	Extra																				*\r\n ************************************************/\r\ncode{\r\n	font-family: \\''lucida console\\'', \\''Courier New\\'', monospace;\r\n	font-size: 0.8em;\r\n	display:block;\r\n	padding:0.5em;\r\n	background-color: #E5EaE4;\r\n	border: 1px solid #d2d8d1;\r\n}\r\ninput[type=\\"text\\"], textarea {\r\n	padding:0.3em;\r\n	border: 1px solid #CCCFBC;\r\n	color: #656F5C;\r\n	-moz-border-radius: 0.5em;\r\n}\r\ninput[type=\\"submit\\"]{\r\n	padding:0.2em;\r\n	font-size: 1.25em;\r\n	border: 1px solid #CCCFBC;\r\n	color: #353F2f;\r\n	background: #fefff8;\r\n	-moz-border-radius: 0.5em;\r\n}\r\nblockquote {\r\n	border-left: 3px solid #686868;\r\n	color: #888;\r\n	padding-left: 0.8em;\r\n	margin-left: 2.5em;\r\n}\r\na img {\r\n	border:none;\r\n}\r\n.imgborder img{\r\n	border: 1px solid #87a284;\r\n	background:#CCCFBC;\r\n	padding:0.3em;\r\n}\r\n.imgborder{\r\n	text-align: center;\r\n}\r\n\r\n.user {\r\n	float: left;\r\n	padding: 0.3em 2em 2em 0;\r\n\r\n}\r\n\r\n.weblogdateheader		{\r\n			\r\n										font-size: 0.6ems;\r\n										text-align: right;\r\n\r\n										border-right: 0.5em solid #5C7B8E;\r\n										border-bottom: 1px solid #5C7B8E;\r\n										color: #5C7B8E;\r\n										padding-right: 0.5em;\r\n									}\r\n\r\n/*---- so the default search box does not appear --*/\r\n\r\n.box_search {\r\n   display:none;\r\n}\r\n\r\n.box_friends {\r\n   text-align:left;\r\n}\r\n\r\n.user_icon {\r\n	width: 120px;\r\n	float: right;\r\n	text-align: center;\r\n}\r\n\r\n.user_icon img {\r\n	margin: 0 0 10px 10px;\r\n}\r\n\r\n\r\n\r\n.contentholder    {\r\n					background-color: #eeeeee;\r\n					border: 1px;\r\n					border-color: #000000;\r\n					border-style: solid;\r\n				}\r\n\r\n.me {\r\n  /*background-color:#FAC83D;*/ /*#FAC83D;*/\r\n  margin:0px;\r\n  margin-bottom:0px;\r\n  background-color:#CCCFBC;\r\n}\r\n\r\n.me p {\r\n   color:#000; /*#1181AA;*/\r\n   padding:3px;\r\n/*background-color:#CCCFBC;*/\r\n  }\r\n\r\n/*-------------------------------------------------\r\nSELECTED HEADER\r\n-------------------------------------------------*/\r\n\r\n.SectionContent {\r\n	margin: 0 0 20px 0;\r\n}\r\n\r\n.SectionContent h1 {\r\n	padding-bottom: 2px;\r\n	border-bottom: 1px solid #666;\r\n	margin: 0;\r\n	font-size: 16px;\r\n	color: #666;\r\n}\r\n\r\n.SectionContent h3 {\r\n	font-family: verdana;\r\n	padding: 2px 0 0 0;\r\n	margin: 0;\r\n	font-size

: 10\r\npx;\r\n	font-weight: normal;\r\n	color: #000;\r\n	}\r\n\r\n.SectionContent h3 a {\r\n	font-weight:bold;\r\n        text-decoration:underline;\r\n}\r\n\r\n.SectionContent h3 a:hover {\r\n	text-decoration: underline;\r\n}\r\n\r\n/*-------------------------------------------------\r\nProfile table\r\n-------------------------------------------------*/\r\n\r\n.profiletableBorder 	{\r\n					border: 1px;\r\n					border-style: solid;\r\n					border-color: #ddd;\r\n					text-align:top;\r\n      margin:0px;\r\n      margin-bottom:5px;\r\n      padding:3px;\r\n			  	}\r\n\r\n.profiletableBorder 	p{\r\n       padding:0px;\r\n	   margin:0px;\r\n	  }\r\n				\r\n.profiletableBorder img {\r\n	border: 0;\r\n}\r\n\r\n.profiletableBorder .fieldname	{\r\n				\r\n					border-right: 2px solid #eaeac7; /*#8B8C8C;*/\r\n					color: #1181AA;\r\n\r\n					padding-left: 10px;\r\n					text-align:left;\r\n				}\r\n\r\n.profiletableBorder .fieldname p {\r\n      color: #666;\r\n}\r\n\r\n.fileTable {\r\n   background-color: #F9F9F9;\r\n   border:1px;\r\n   border-style:solid;\r\n   border-color:#DDD;\r\n }\r\n\r\n .fileTable p{\r\n   padding:0px;\r\n   margin:0px;\r\n   color:#1181AA;\r\n }\r\n\r\n .dayofweekbox {
	font-size: 11px;
	font-color: #FFFFFF;
	margin-left: 2px;
	margin-right: 2px;
}

.activemonthbox {
	font-size: 15px;
	font-color: #FFFFFF;
	margin-right: 5px;
}

.monthlynavigationbox{
	font-family: verdana;
	padding: 2px 0 0 0;
	margin: 0;
	font-size: 10px;
	font-weight: normal;
	color: #000;
}

.monthlynavigationbox a {
	font-weight:bold;
}

.monthlynavigationbox a:hover {
	text-decoration: underline;
}

.datelink a {
	font-weight:bold;
}

.datelink a:hover {
	text-decoration: underline;
}

.publicevent a {
	color: #66CC33;
}

.publicevent a:hover {
	text-decoration: underline;
}

.privateevent a {
	color: #CC3300;
}

.privateevent a:hover {
	text-decoration: underline;
}

.loggedinevent a {
	color: #3366FF;
}

.loggedinevent a:hover {
	text-decoration: underline;
}

.publiceventlegend {
	background-color: #66CC33;
	width: 25px;	
}

.privateeventlegend {
	background-color: #CC3300;
	width: 25px;	
}

.loggedineventlegend {
	background-color: #3366FF;
	width: 25px;	
}	
}', 1);


