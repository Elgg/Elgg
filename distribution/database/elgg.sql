--
-- ELGG database schema
--
-- This must be installed into your chosen Elgg database before you can run Elgg
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `file_folders`
-- 

CREATE TABLE `file_folders` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `parent` int(11) NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `access` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`parent`,`name`,`access`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `file_metadata`
-- 

CREATE TABLE `file_metadata` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  `file_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `name` (`name`,`file_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `files`
-- 

CREATE TABLE `files` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `folder` int(11) NOT NULL default '-1',
  `community` int(11) NOT NULL default '-1',
  `title` varchar(255) NOT NULL default '',
  `originalname` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  `access` varchar(255) NOT NULL default '',
  `size` int(11) NOT NULL default '0',
  `time_uploaded` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`folder`,`access`),
  KEY `size` (`size`),
  KEY `time_uploaded` (`time_uploaded`),
  KEY `originalname` (`originalname`),
  KEY `community` (`community`)
) TYPE=MyISAM;

-- 
-- Table structure for table `friends`
-- 

CREATE TABLE `friends` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `friend` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`friend`)
) TYPE=MyISAM;

-- 
-- Table structure for table `group_membership`
-- 

CREATE TABLE `group_membership` (
  `ident` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `user_id` (`user_id`,`group_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `groups`
-- 

CREATE TABLE `groups` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `access` varchar(255) NOT NULL default 'PUBLIC',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`name`),
  KEY `access` (`access`)
) TYPE=MyISAM;

-- 
-- Table structure for table `icons`
-- 

CREATE TABLE `icons` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `filename` varchar(128) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`filename`,`description`)
) TYPE=MyISAM;

-- 
-- Table structure for table `invitations`
-- 

CREATE TABLE `invitations` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `code` varchar(128) NOT NULL default '',
  `owner` int(11) NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `name` (`name`,`email`,`code`,`owner`,`added`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `profile_data`
-- 

CREATE TABLE `profile_data` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `owner` int(10) unsigned NOT NULL default '0',
  `access` varchar(16) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`access`,`name`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `tags`
-- 

CREATE TABLE `tags` (
  `ident` int(11) NOT NULL auto_increment,
  `tag` varchar(128) NOT NULL default '',
  `tagtype` varchar(128) NOT NULL default '',
  `ref` int(11) NOT NULL default '0',
  `access` varchar(128) NOT NULL default '',
  `owner` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `tag` (`tag`,`tagtype`,`ref`,`access`),
  KEY `owner` (`owner`),
  FULLTEXT KEY `tag_2` (`tag`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `template_elements`
-- 

CREATE TABLE `template_elements` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `content` text NOT NULL,
  `template_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `name` (`name`,`template_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `template_elements`
-- 

INSERT INTO `template_elements` VALUES (24787, 'weblogcomment', '	\r\n<li>\r\n	{{body}}\r\n	<p style=\\"border: 0px; border-bottom: 1px; border-style: solid; border-color: #000000\\">\r\n		<small>Posted by {{postedname}} on {{posted}}</small>\r\n	</p>\r\n</li>\r\n	', 1);
INSERT INTO `template_elements` VALUES (24786, 'weblogcomments', '			<hr noshade=\\"noshade\\" />\r\n			<h3>Comments</h3>\r\n			<ol>\r\n			{{comments}}\r\n			</ol>', 1);
INSERT INTO `template_elements` VALUES (24785, 'weblogpost', '<table width=\\"100%\\" style=\\"margin-bottom: 5px; background-color: #FFFFFF; border: 1px; border-color: #C5D3F6; border-style: dashed\\" >\r\n	<tr>\r\n		<td valign=\\"top\\" align=\\"left\\">		\r\n			<a href=\\"/{{username}}/weblog/\\"><img src=\\"/_icons/data/{{usericon}}\\" border=\\"0\\" align=\\"right\\" style=\\"margin-left: 10px; margin-bottom: 10px\\" /></a>\r\n			<p>\r\n				<b>{{title}}</b>\r\n			</p>\r\n			<div style=\\"margin-left: 20px\\">\r\n				{{body}}\r\n			</div>\r\n			<p>\r\n				<small>Posted by <a href=\\"/{{username}}/weblog/\\">{{fullname}}</a> at {{date}} :: {{commentslink}} {{trackbackslink}}</small>\r\n			</p>\r\n			{{comments}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n', 1);
INSERT INTO `template_elements` VALUES (24784, 'databoxvertical', '	\r\n<table width=\\"95%\\" class=\\"profiletable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\">\r\n	<tr>\r\n		<td align=\\"center\\" class=\\"fieldname\\">\r\n			{{name}}\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td >\r\n			{{contents}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n	', 1);
INSERT INTO `template_elements` VALUES (24783, 'databox1', '<table width=\\"95%\\" class=\\"profiletable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\">\r\n	<tr>\r\n		<td width=\\"20%\\" class=\\"fieldname\\">\r\n			{{name}}\r\n		</td>\r\n		<td width=\\"80%\\">\r\n			{{column1}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n	', 1);
INSERT INTO `template_elements` VALUES (24782, 'databox', '<table width=\\"95%\\" class=\\"profiletable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\">\r\n	<tr>\r\n		<td width=\\"20%\\" class=\\"fieldname\\">\r\n			{{name}}\r\n		</td>\r\n		<td width=\\"50%\\">\r\n			{{column1}}\r\n		</td>\r\n		<td width=\\"30%\\">\r\n			{{column2}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n	', 1);
INSERT INTO `template_elements` VALUES (24781, 'menuitem', '	\r\n	<a href=\\"{{location}}\\">{{name}}</a> |\r\n	', 1);
INSERT INTO `template_elements` VALUES (24780, 'menu', '	\r\n	<table width=\\"100%\\" class=\\"menubar\\">\r\n		<tr>\r\n			<td>\r\n				{{menuitems}}\r\n			</td>\r\n		</tr>\r\n	</table>\r\n	', 1);
INSERT INTO `template_elements` VALUES (24778, 'messageshell', '	\r\n	<table width=\\"100%\\" class=\\"messages\\">\r\n		<tr>\r\n			<td>\r\n				<ul>\r\n					{{messages}}\r\n				</ul>\r\n			</td>\r\n		</tr>\r\n	</table>\r\n	', 1);
INSERT INTO `template_elements` VALUES (24779, 'messages', '	<li>\r\n		{{message}}\r\n	</li>	\r\n	', 1);
INSERT INTO `template_elements` VALUES (24775, 'pageshell', '<!DOCTYPE html PUBLIC \\"-//W3C//DTD XHTML 1.0 Transitional//EN\\" \\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\\">\r\n<html xmlns=\\"http://www.w3.org/1999/xhtml\\">\r\n<head>\r\n<title>{{title}}</title>\r\n{{metatags}}\r\n<meta http-equiv=\\"Content-Type\\" content=\\"text/html; charset=iso-8859-1\\" />\r\n</head>\r\n\r\n<body>\r\n{{messageshell}}\r\n<br />\r\n<table width=\\"750\\"  border=\\"0\\" cellpadding=\\"0\\" cellspacing=\\"0\\" align=\\"center\\">\r\n  <tr>\r\n	<td width=\\"240\\" align=\\"left\\" valign=\\"top\\" id=\\"mainbody\\">{{sidebar}}</td>\r\n	<td width=\\"10\\" align=\\"right\\" valign=\\"top\\" id=\\"mainbody\\">&nbsp;</td>\r\n    <td width=\\"500\\" align=\\"right\\" valign=\\"top\\" id=\\"mainbody\\">{{mainbody}}</td>\r\n  </tr>\r\n  <tr>\r\n    <td colspan=\\"3\\">\r\n    	&nbsp;\r\n    </td>\r\n  </tr>\r\n</table>\r\n<table align=\\"center\\">\r\n  <tr>\r\n    <td colspan=\\"3\\" align=\\"center\\" valign=\\"top\\" id=\\"mainbody\\">{{menu}}</td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>	', 1);
INSERT INTO `template_elements` VALUES (24776, 'css', 'body			{\r\n					margin: 0px;\r\n					background-color: #CAAAF2;\r\n					font-family: arial, helvetica;\r\n					font-size: 10pt;\r\n					line-height: 1.2ems;\r\n					margin: 0px;\r\n					padding: 0px;\r\n				}\r\n\r\nh1				{\r\n					13pt;\r\n					margin: 0px;\r\n					padding: 0px;\r\n				}\r\nh2				{\r\n					12pt;\r\n				}\r\nh3				{\r\n					11pt;\r\n				}\r\nform			{\r\n					margin: 0px;\r\n					padding: 0px;\r\n				}\r\n				\r\n/* Page top */\r\n#pagetop		{\r\n					background-color: #5F1741;\r\n				}\r\n#pagetop ul		{\r\n					position: absolute;\r\n					right: 0;\r\n					top: 0;\r\n					list-style-type: none;\r\n					margin: 0px;\r\n					padding: 0px;\r\n				}\r\n#pagetop li		{\r\n					font-family: verdana, arial, helvetica, helv;\r\n					color:#ffffff;\r\n					margin: 0;\r\n					padding: 0;\r\n				}\r\n#pagetop li a	{\r\n					display: block;\r\n					text-decoration: none;\r\n					color: #ffa800;\r\n					text-align: center;\r\n					background-color: #444444;\r\n				}\r\n#pagetop li a:hover	{\r\n					color: #000000;\r\n					background-color: #ffa800;\r\n				}\r\n.messages		{\r\n					background-color: #000000;\r\n					color: #ffffff;\r\n					padding: 0px;\r\n					margin: 0px;\r\n				}\r\n.messages ul	{\r\n					padding: 0px;\r\n					margin: 0px;\r\n				}\r\n.menubar		{\r\n					background-color: #433663;\r\n					padding: 3px;\r\n					border: 0px;\r\n					border-bottom: 1px;\r\n					border-color: #CAAAF2;\r\n					border-style: solid;\r\n					font-size: 8pt;\r\n				}\r\n.menubar a		{\r\n					color: #ffffff;\r\n					padding: 3px;\r\n					text-decoration: none;\r\n				}\r\n.menubar a:hover	{\r\n					text-decoration: underline;\r\n					color: #FACBFC;\r\n				}\r\n.actionbox		{\r\n					background-color: #eeeeee;\r\n					border: 1px;\r\n					border-color: #000000;\r\n					border-style: solid;\r\n					padding: 7px;\r\n					\r\n				}\r\n.actionbox caption	{\r\n					background-color: #aaaaaa;\r\n					color: #ffffff;\r\n					vertical-align: middle;\r\n					text-align: center;\r\n					padding: 4px;\r\n					font-weight: bold;\r\n					border: 1px;\r\n					border-color: #000000;\r\n					border-style: solid;\r\n					border-bottom: 0px;\r\n					font-size: 1.1em;\r\n					\r\n				}\r\n.infobox		{\r\n					background-color: #eeeeee;\r\n					border: 1px;\r\n					border-color: #000000;\r\n					border-style: solid;\r\n				}\r\n.infobox td		{\r\n					padding: 7px;\r\n				}\r\n.infobox caption	{\r\n					font-family: georgie, times, palatino;\r\n					background-color: #433663;\r\n					color: #ffffff;\r\n					vertical-align: middle;\r\n					text-align: center;\r\n					padding: 4px;\r\n					font-weight: bold;\r\n					border: 1px;\r\n					border-color: #000000;\r\n					border-style: solid;\r\n					border-bottom: 0px;\r\n					font-size: 1.1em;\r\n				}\r\n.profiletable 	{\r\n					border: 1px;\r\n					border-style: dashed;\r\n					border-color: #eeeeee;\r\n					background-color: #ffffff;\r\n			  	}\r\n.profiletable .fieldname	{\r\n					background-color: #C6DEFD;\r\n				}\r\n		\r\n		.weblogdateheader		{\r\n			\r\n										font-size: 0.6ems;\r\n									}\r\n	', 1);
INSERT INTO `template_elements` VALUES (24777, 'infobox', '	\r\n<table class=\\"infobox\\" width=\\"100%\\">\r\n	<caption align=\\"top\\">\r\n		{{name}}\r\n	</caption>\r\n	<tr>\r\n		<td align=\\"left\\">\r\n{{contents}}\r\n		</td>\r\n	</tr>\r\n</table><br />\r\n	', 1);
INSERT INTO `template_elements` VALUES (25102, 'weblogcomment', '	\r\n<li>\r\n	{{body}}\r\n	<p style=\\"border: 0px; border-bottom: 1px; border-style: solid; border-color: #000000\\">\r\n		Posted by {{postedname}} on {{posted}}\r\n	</p>\r\n</li>\r\n	', 2);
INSERT INTO `template_elements` VALUES (25103, 'folder', '					<table>\r\n						<tr>\r\n							<td valign=\\"middle\\">\r\n								<a href=\\"{{url}}\\">\r\n									<img src=\\"{{icon}}\\" width=\\"93\\" height=\\"90\\" border=\\"0\\" alt=\\"\\" />\r\n								</a>\r\n							</td>\r\n							<td valign=\\"middle\\">\r\n								<a href=\\"{{url}}\\">{{name}}</a>{{menu}}<br />{{keywords}}\r\n							</td>\r\n						</tr>\r\n					</table>', 2);
INSERT INTO `template_elements` VALUES (25104, 'file', '	\r\n					<table>\r\n						<tr>\r\n							<td>\r\n								<a href=\\"{{url}}\\">\r\n									<img src=\\"{{icon}}\\" width=\\"90\\" height=\\"90\\" border=\\"0\\" alt=\\"\\" />\r\n								</a>\r\n							</td>\r\n							<td valign=\\"middle\\">\r\n								<a href=\\"{{url}}\\">\r\n									<b>{{title}}</b>\r\n								</a>{{menu}}<br />\r\n									{{description}}<br />\r\n{{keywords}}<br />\r\n									<small>{{originalname}}</small>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n	', 2);
INSERT INTO `template_elements` VALUES (25101, 'weblogcomments', '<hr noshade=\\"noshade\\" />\r\n<h3>Comments</h3>\r\n<ol>\r\n{{comments}}\r\n</ol>\r\n	', 2);
INSERT INTO `template_elements` VALUES (25096, 'menuitem', '<a href=\\"{{location}}\\">{{name}}</a> |\r\n	', 2);
INSERT INTO `template_elements` VALUES (25097, 'databox', '<table width=\\"95%\\" class=\\"profiletable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\" border=\\"0\\">\r\n	<tr>\r\n		<td width=\\"20%\\" class=\\"fieldname\\">\r\n			{{name}}\r\n		</td>\r\n		<td width=\\"50%\\">\r\n			{{column1}}\r\n		</td>\r\n		<td width=\\"30%\\">\r\n			{{column2}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n	', 2);
INSERT INTO `template_elements` VALUES (25098, 'databox1', '<table width=\\"95%\\" class=\\"profiletable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\" border=\\"0\\">\r\n	<tr>\r\n		<td width=\\"20%\\" class=\\"fieldname\\">\r\n			{{name}}\r\n		</td>\r\n		<td width=\\"80%\\">\r\n			{{column1}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n	', 2);
INSERT INTO `template_elements` VALUES (25099, 'databoxvertical', '	\r\n<table width=\\"95%\\" class=\\"profiletable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\" border=\\"0\\">\r\n	<tr>\r\n		<td align=\\"center\\" class=\\"fieldname\\">\r\n			{{name}}\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td >\r\n			{{contents}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n	', 2);
INSERT INTO `template_elements` VALUES (25100, 'weblogpost', '<table width=\\"100%\\" style=\\"border: 0px; border-bottom: 1px; border-style: solid; border-color: #000000\\">\r\n	<tr>\r\n		<td valign=\\"top\\" width=\\"100\\" align=\\"center\\">\r\n			<div style=\\"float:right\\">\r\n				<p><br />\r\n				<a href=\\"/{{username}}/weblog/\\">\r\n					<img src=\\"/_icons/data/{{usericon}}\\" border=\\"0\\" /><br />\r\n					<a href=\\"/{{username}}/weblog/\\">{{fullname}}</a></p>\r\n		</td>\r\n		<td width=\\"20\\">&nbsp;</td>\r\n		<td valign=\\"top\\">		\r\n			<p>\r\n				<br />\r\n				<b>{{title}}</b>\r\n			</p>\r\n			<div style=\\"margin-left: 20px\\">\r\n				{{body}}\r\n			</div>\r\n			<p>\r\n				Posted by {{username}} at {{date}} | {{commentslink}} {{trackbackslink}}\r\n			</p>\r\n			{{comments}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n', 2);
INSERT INTO `template_elements` VALUES (25095, 'menu', '<table class=\\"menubar\\" width=\\"100%\\" cellpadding=\\"0\\" cellspacing=\\"0\\" border=\\"0\\">\r\n		<tr><td>{{menuitems}}</td></tr>\r\n	</table>\r\n\r\n		\r\n	', 2);
INSERT INTO `template_elements` VALUES (25094, 'messages', '	<li>\r\n		{{message}}\r\n	</li>	\r\n	', 2);
INSERT INTO `template_elements` VALUES (25093, 'messageshell', '	\r\n	<table width=\\"100%\\" class=\\"messages\\">\r\n		<tr>\r\n			<td>\r\n				<ul>\r\n					{{messages}}\r\n				</ul>\r\n			</td>\r\n		</tr>\r\n	</table>\r\n	', 2);
INSERT INTO `template_elements` VALUES (25092, 'infobox', '	\r\n<table class=\\"infobox\\" width=\\"100%\\">\r\n	<caption align=\\"top\\">\r\n		{{name}}\r\n	</caption>\r\n	<tr>\r\n		<td>\r\n                 {{contents}}\r\n		</td>\r\n	</tr>\r\n</table><br />\r\n	', 2);
INSERT INTO `template_elements` VALUES (25090, 'pageshell', '<!DOCTYPE html PUBLIC \\"-//W3C//DTD XHTML 1.0 Transitional//EN\\" \\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\\">\r\n<html xmlns=\\"http://www.w3.org/1999/xhtml\\">\r\n<head>\r\n<title>{{title}}</title>\r\n{{metatags}}\r\n<meta http-equiv=\\"Content-Type\\" content=\\"text/html; charset=iso-8859-1\\" />\r\n</head>\r\n<body>\r\n<table width=\\"100%\\" cellspacing=\\"0\\" cellpadding=\\"0\\" cellpadding=\\"0\\" border=\\"0\\" class=\\"header\\">\r\n	<tr>\r\n		<td><a href=\\"{{url}}\\"><img src=\\"/_templates/cleanview/logo2.jpg\\" alt=\\"Elgg\\" border=\\"0\\" /></a></td>\r\n	</tr>\r\n</table>\r\n<table width=\\"100%\\" cellspacing=\\"0\\" cellpadding=\\"0\\" border=\\"0\\">\r\n	<tr>\r\n		<td align=\\"left\\">		\r\n			{{menu}}{{messageshell}}\r\n		</td>\r\n                <td>\r\n                </td>\r\n	</tr>\r\n</table>\r\n<table cellspacing=\\"2\\" cellpadding=\\"5\\" border=\\"0\\" width=\\"100%\\">\r\n  <tr>\r\n    <td width=\\"25%\\" valign=\\"top\\" align=\\"left\\">\r\n      <table cellspacing=\\"0\\" cellpadding=\\"0\\" border=\\"0\\" class=\\"leftPane\\" width=\\"90%\\">\r\n         <tr>\r\n            <td>\r\n              {{sidebar}}\r\n            </td>\r\n         </tr>\r\n      </table>\r\n</td>\r\n    <td align=\\"left\\" valign=\\"top\\" width=\\"75%\\">\r\n	<table width=\\"95%\\" cellpadding=\\"3\\" cellspacing=\\"0\\" border=\\"0\\" class=\\"rightPane\\">\r\n<tr>\r\n			<td>\r\n{{mainbody}}\r\n</td>\r\n  </tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n<table border=\\"0\\" cellpadding=\\"3\\" cellspacing=\\"0\\" class=\\"footer\\" width=\\"100%\\">\r\n  <tr>\r\n    <td align=\\"center\\">\r\n      <p>Copyright &copy; 2004 ELGG</p>\r\n      <a href=\\"/content/about.php\\">About ELGG</a> | <a href=\\"/content/faq.php\\">FAQ</a> | <a href=\\"/content/privacy.php\\">Privacy Policy</a> | <a href=\\"/content/run_your_own.php\\">Run your own ELGG</a></td>\r\n  </tr>\r\n</table>\r\n</body>\r\n</html>\r\n	', 2);
INSERT INTO `template_elements` VALUES (25091, 'css', 'body{\r\n	margin: 0px;\r\n	padding:0px;\r\n        font-size: 10pt;\r\n        line-height: 1.2ems;\r\n        font-family: arial, helvetica;\r\n	}\r\n\r\na {\r\n		text-decoration: none;\r\n		font-family: arial, helvetica;\r\n	}\r\n\r\np {\r\n	font-family: arial, helvetica, Tahoma, arial;\r\n	color: #000000;\r\n\r\n\r\n}\r\n\r\nh3 {\r\n	font-family: helvetica, Tahoma, arial;\r\n}\r\n\r\nh4 {\r\n	font-family: arial, helvetica, Tahoma;\r\n}\r\n\r\n.header {\r\nbackground-image:url(\\"/_templates/cleanview/banner_back.jpg\\");	\r\n	}\r\n\r\n.header img {\r\n	margin-left:10px;\r\n}\r\n\r\n/* menu items */\r\n\r\n.menubar		{\r\n					background-color: #FFFFFF; \r\n					padding: 4px;\r\n                                        margin:0px;\r\n                                        margin-bottom:2px;\r\n					border: 0px;\r\n					border-bottom: 1px;\r\n                                        border-top: 1px;\r\n					border-color: #0D1364;\r\n					border-style: solid;\r\n				}\r\n.menubar td {\r\n              border: 0px;\r\n					border-right: 0px;\r\n					border-color: #0D1364;\r\n					border-style: solid;\r\n                                        padding:0px;\r\n}\r\n.menubar a		{\r\n					color: #000000;\r\n					padding: 2px;\r\n					text-decoration: none;\r\n				}\r\n.menubar a:hover	{\r\n					text-decoration: underline;\r\n					color: #DB7816;\r\n				}\r\n\r\n\r\n/* contents table */\r\n\r\n.leftPane {\r\n	padding:0px;\r\n	padding-left:0px;\r\n        margin:0px;\r\n        margin-left:15px;\r\n        margin-top:15px;\r\n	border:0px;\r\n	border-right:0px;\r\n	border-style:solid;\r\n	border-color: #000000;\r\n        background-color:#FFFFFF;\r\n}\r\n\r\n.leftPane a{\r\n	padding:5px;\r\n	color: #DB7816;\r\n}\r\n\r\n.leftPaneheader{\r\n	padding:0px;\r\n	margin:0px;\r\n        border:0px;\r\n	border-bottom:1px;\r\n	border-style:solid;\r\n	border-color: #000000;\r\n        background-color:#6B8BB4;\r\n}\r\n\r\n/* right pane */\r\n\r\n.rightPane {\r\n	padding:0px;\r\n	padding-right:10px;\r\n	padding-left:5px;\r\n        margin:0px;\r\n        margin-left:5px;\r\n        margin-top:15px;\r\n        margin-right:15px;\r\n}\r\n\r\n/* footer */\r\n\r\n.footer {\r\n	border:0px;\r\n	margin:0px;\r\n	margin-top:5px;\r\n	text-align:center;\r\n        border:0px;\r\n	border-top:1px;\r\n	border-style:solid;\r\n	border-color: #24447A;\r\n\r\n}\r\n\r\n.footer p {\r\n	border:0px;\r\n	margin:0px;\r\n        font-size:12px;\r\n}\r\n\r\n.footer a {\r\n	border:0px;\r\n	margin:0px;\r\n	color:#000000;\r\n	text-decoration:underline;\r\n        font-size:11px;\r\n}\r\n\r\n.infobox		{\r\n					background-color: #ffffff;\r\n					border: 1px;\r\n					border-color: #000000;\r\n					border-style: solid;\r\n                                        color: #000000;\r\n                                        margin:0px;\r\n                                        padding:0px;\r\n                                        border-bottom: 1px;\r\n					border-color: #9FB688;\r\n					border-style: solid;\r\n				}\r\n.infobox td		{\r\n					padding: 2px;\r\n				}\r\n.infobox caption	{\r\n					font-family: arial, georgie, times, palatino;\r\n					background-color: #5879AB;\r\n					color: #FFFFFF;\r\n					vertical-align: bottom;\r\n					text-align: left;\r\n					padding: 2px;\r\n					font-weight: 500;\r\n                                        border:0px;\r\n                                        border-bottom: 1px;\r\n					border-color: #9FB688;\r\n					border-style: solid;\r\n					font-size: 1.1em;\r\n				}\r\n\r\n\r\n.messages		{\r\n					background-color: #000000;\r\n					color: #ffffff;\r\n					padding: 0px;\r\n					margin: 0px;\r\n				}\r\n.messages ul	{\r\n					padding: 0px;\r\n					margin: 0px;\r\n				}\r\n.actionbox		{\r\n					background-color: #eeeeee;\r\n					border: 1px;\r\n					border-color: #000000;\r\n					border-style: solid;\r\n					padding: 7px;\r\n					\r\n				}\r\n.actionbox caption	{\r\n					background-color: #aaaaaa;\r\n					color: #ffffff;\r\n					vertical-align: middle;\r\n					text-align: center


;\r\n					padding: 4px;\r\n					font-weight: bold;\r\n					border: 1px;\r\n					border-color: #000000;\r\n					border-style: solid;\r\n					border-bottom: 0px;\r\n					font-size: 1.1em;\r\n					\r\n				}\r\n.profiletable 	{\r\n					border: 1px;\r\n					border-style: solid;\r\n					border-color: #eeeeee;\r\n					background-color: #ffffff;\r\n			  	}\r\n.profiletable .fieldname	{\r\n					background-color: #C6DEFD;\r\n\r\n				}\r\n		\r\n		.weblogdateheader		{\r\n			\r\n										font-size: 0.6ems;\r\n									}', 2);
INSERT INTO `template_elements` VALUES (25179, 'file', '	\r\n					<table>\r\n						<tr>\r\n							<td>\r\n								<a href=\\"{{url}}\\">\r\n									<img src=\\"{{icon}}\\" width=\\"90\\" height=\\"90\\" border=\\"0\\" alt=\\"\\" />\r\n								</a>\r\n							</td>\r\n							<td valign=\\"middle\\">\r\n								<a href=\\"{{url}}\\"><b>{{title}}</b></a>\r\n								<small>{{menu}}</small><br />\r\n									{{description}}<br />\r\n\r\n{{keywords}}<br />\r\n								<small>{{originalname}}</small>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n	', 3);
INSERT INTO `template_elements` VALUES (25176, 'weblogcomments', '<hr noshade=\\"noshade\\" />\r\n<h3>Comments</h3>\r\n<ol>\r\n{{comments}}\r\n</ol>\r\n	', 3);
INSERT INTO `template_elements` VALUES (25177, 'weblogcomment', '	\r\n<li>\r\n	{{body}}<br /><br />\r\n	<p style=\\"border: 0px; border-bottom: 1px; border-style: solid; border-color: #000000\\">\r\n		<small>Posted by {{postedname}} on {{posted}}</small>\r\n	</p>\r\n</li><br />\r\n	', 3);
INSERT INTO `template_elements` VALUES (25178, 'folder', '					<table>\r\n						<tr>\r\n							<td valign=\\"middle\\">\r\n								<a href=\\"{{url}}\\">\r\n									<img src=\\"{{icon}}\\" width=\\"93\\" height=\\"90\\" border=\\"0\\" alt=\\"\\" />\r\n								</a>\r\n							</td>\r\n							<td valign=\\"middle\\">\r\n								<a href=\\"{{url}}\\">{{name}}</a> <small>{{menu}}</small><br />{{keywords}}\r\n							</td>\r\n						</tr>\r\n					</table>', 3);
INSERT INTO `template_elements` VALUES (25175, 'weblogpost', '<table width=\\"100%\\" style=\\"border: 0px; border-bottom: 1px; border-style: solid; border-color: #000000\\">\r\n	<tr>\r\n		<td valign=\\"top\\" width=\\"100\\" align=\\"center\\">\r\n			<div style=\\"float:right\\">\r\n				<p><br />\r\n				<a href=\\"/{{username}}/weblog/\\">\r\n					<img src=\\"/_icons/data/{{usericon}}\\" border=\\"0\\" /><br />\r\n					<a href=\\"/{{username}}/weblog/\\">{{fullname}}</a></p>\r\n		</td>\r\n		<td width=\\"20\\">&nbsp;</td>\r\n		<td valign=\\"top\\">		\r\n			<p>\r\n				<br />\r\n				<b>{{title}}</b>\r\n			</p><br />\r\n			<div style=\\"margin-left: 20px\\">\r\n				{{body}}\r\n			</div><br />\r\n			<p>\r\n				<small>Posted by {{username}} at {{date}} | {{commentslink}} {{trackbackslink}}</small>\r\n			</p>\r\n			<small>{{comments}}</small>\r\n		</td>\r\n	</tr>\r\n</table>\r\n', 3);
INSERT INTO `template_elements` VALUES (25174, 'databoxvertical', '	\r\n<table width=\\"95%\\" class=\\"profiletable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\">\r\n	<tr>\r\n		<td align=\\"center\\" class=\\"fieldname\\">\r\n			{{name}}\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td >\r\n			{{contents}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n	', 3);
INSERT INTO `template_elements` VALUES (25173, 'databox1', '<table width=\\"100%\\" class=\\"profiletable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\">\r\n	<tr>\r\n		<td width=\\"23%\\" class=\\"fieldname\\" valign=\\"top\\">\r\n			<b>{{name}} ::</b>\r\n		</td>\r\n		<td width=\\"77%\\">\r\n			{{column1}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n	', 3);
INSERT INTO `template_elements` VALUES (25172, 'databox', '<table width=\\"95%\\" class=\\"profiletable\\" align=\\"center\\" style=\\"margin-bottom: 3px\\">\r\n	<tr>\r\n		<td width=\\"20%\\" class=\\"fieldname\\">\r\n			{{name}}\r\n		</td>\r\n		<td width=\\"50%\\">\r\n			{{column1}}\r\n		</td>\r\n		<td width=\\"30%\\">\r\n			{{column2}}\r\n		</td>\r\n	</tr>\r\n</table>\r\n	', 3);
INSERT INTO `template_elements` VALUES (25171, 'menuitem', '	\r\n	<a href=\\"{{location}}\\">{{name}}</a>\r\n	', 3);
INSERT INTO `template_elements` VALUES (25170, 'menu', '<table cellspacing=\\"0\\" cellpadding=\\"0\\" border=\\"0\\">\r\n  <tr>\r\n      <td align=\\"left\\" id=\\"siteLinks\\">\r\n	{{menuitems}}\r\n      </td>\r\n   </tr>\r\n</table>\r\n			', 3);
INSERT INTO `template_elements` VALUES (25168, 'messageshell', '	\r\n	<table width=\\"100%\\" class=\\"messages\\">\r\n		<tr>\r\n			<td>\r\n				<ul>\r\n					{{messages}}\r\n				</ul>\r\n			</td>\r\n		</tr>\r\n	</table>\r\n	', 3);
INSERT INTO `template_elements` VALUES (25169, 'messages', '	<li>\r\n		{{message}}\r\n	</li>	\r\n	', 3);
INSERT INTO `template_elements` VALUES (25167, 'infobox', '	\r\n<table class=\\"infobox\\" width=\\"100%\\">\r\n	<caption align=\\"top\\">\r\n		{{name}}\r\n	</caption>\r\n	<tr>\r\n		<td>\r\n{{contents}}\r\n		</td>\r\n	</tr>\r\n</table><br />\r\n	', 3);
INSERT INTO `template_elements` VALUES (25165, 'pageshell', '	\r\n<!DOCTYPE html PUBLIC \\"-//W3C//DTD XHTML 1.0 Transitional//EN\\" \\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\\">\r\n<html xmlns=\\"http://www.w3.org/1999/xhtml\\">\r\n<head>\r\n<title>{{title}}</title>\r\n{{metatags}}\r\n<meta http-equiv=\\"Content-Type\\" content=\\"text/html; charset=iso-8859-1\\" />\r\n</head>\r\n\r\n<body>\r\n<center>\r\n<br />\r\n<table cellspacing=\\"0\\" cellpadding=\\"0\\" border=\\"0\\" summary=\\"banner\\" id=\\"bannertable\\">\r\n<tr>\r\n    <td align=\\"right\\">\r\n	   <h4>ELGG Learning Landscape</h4>\r\n    </td>\r\n</tr>\r\n<tr>\r\n	<td align=\\"right\\">\r\n		<a href=\\"{{url}}\\"><h1><img src=\\"/_templates/waterdrop/logo.jpg\\" id=\\"banner_logo\\" alt=\\"ELGG Learning Landscape\\" border=\\"0\\" /></h1></a>\r\n	</td>	\r\n</tr>\r\n</table>\r\n<table cellspacing=\\"0\\" cellpadding=\\"0\\" border=\\"0\\" class=\\"whitespacetable\\">\r\n<tr>\r\n	<td align=\\"center\\">\r\n		&nbsp;\r\n	</td>\r\n</tr>\r\n</table>\r\n<table cellspacing=\\"0\\" cellpadding=\\"0\\" border=\\"0\\" \r\nid=\\"linkstable\\"><tr><td align=\\"center\\">\r\n   {{menu}}\r\n{{messageshell}}\r\n</td></tr></table>\r\n\r\n<table cellspacing=\\"2\\" cellpadding=\\"5\\" border=\\"0\\" summary=\\"banner\\"  id=\\"maintable\\">\r\n  <tr>\r\n	<td width=\\"25%\\" valign=\\"top\\" align=\\"left\\">\r\n		<table cellspacing=\\"0\\" cellpadding=\\"2\\" border=\\"0\\" summary=\\"banner\\" width=\\"100%\\" bgcolor=\\"#FFFFFF\\" id=\\"openPage\\">\r\n			<tr>\r\n				<td>{{sidebar}}</td>\r\n			</tr>\r\n		</table>\r\n	</td>\r\n	<td>\r\n		&nbsp;\r\n	</td>\r\n	<td align=\\"left\\" valign=\\"top\\" width=\\"78%\\">\r\n		{{mainbody}}\r\n	</td>\r\n   </tr>\r\n</table>\r\n<table cellspacing=\\"0\\" cellpadding=\\"0\\" border=\\"0\\" class=\\"whitespacetable\\">\r\n<tr>\r\n	<td align=\\"center\\">\r\n		&nbsp;\r\n	</td>\r\n</tr>\r\n</table>\r\n<table cellspacing=\\"0\\" cellpadding=\\"0\\" border=\\"0\\" summary=\\"footer\\" id=\\"footer\\">\r\n<tr>\r\n	<td align=\\"center\\">\r\n		Copyright &copy; 2004 ELGG      <br />\r\n      <a href=\\"/content/about.php\\">About ELGG</a> | <a href=\\"/content/faq.php\\">FAQ</a> | <a href=\\"/content/privacy.php\\">Privacy Policy</a> | <a href=\\"/content/run_your_own.php\\">Run your own ELGG</a>\r\n	</td>\r\n</tr>\r\n</table>\r\n</center>\r\n</body>\r\n</html>', 3);
INSERT INTO `template_elements` VALUES (25166, 'css', 'body{\r\n	margin-top: 20px;\r\n	margin-right: 0px;\r\n	margin-bottom: 20px;\r\n	margin-left: 0px;\r\n        color: #627CA7;\r\n	background-image:url(\\"/_templates/waterdrop/background_lines.gif\\");\r\n	}\r\n\r\na {\r\n		text-decoration: none;\r\n		font-family: verdana, arial, helvetica;\r\n		/*color: #7289AF;*/\r\ncolor: #4F6487;\r\n	}\r\n\r\np {\r\n	font-family: verdana, arial, helvetica;\r\n	/*color: #A4A4C0;*/\r\n	color: #627CA7;\r\n	font-size: 13px;		\r\n}\r\n\r\nh1 {\r\n	padding:0px;\r\n	margin:0px;\r\n        font-size:90%;\r\n        font-family: arial, helvetica;\r\n}\r\n\r\nh2 {\r\n	padding:0px;\r\n	margin:0px;\r\n        font-size:90%;\r\n        font-family: arial, helvetica;\r\n}\r\n\r\nh3 {\r\n	padding:0px;\r\n	margin:0px;\r\n}\r\n\r\nh4 {\r\n	font-family: verdana, arial, helvetica;\r\n	color: #7289AF;\r\n	margin:0px;\r\n	margin-top:5px;\r\n	margin-bottom:0px;\r\n	font-size: 16px;		\r\n}\r\n\r\nh5 {\r\n	font-family: verdana, arial, helvetica;\r\n	color: #7289AF;\r\n	margin:0px;\r\n	margin-top:5px;\r\n	margin-bottom:5px;\r\n	font-size: 15px;		\r\n}\r\n\r\n.borderBottom {\r\n    border: 0px;\r\n	border-bottom: 1px;\r\n	border-style:dashed;\r\n	color: #24447A;\r\n	margin-bottom:10px;\r\n}\r\n\r\nul		{\r\n			margin: 13px;\r\n			margin-bottom: 15px;\r\n			font-family: verdana, arial, helvetica;\r\n			color: #627CA7;\r\n			/*color: #A4A4C0;*/\r\n			font-size: 13px;		\r\n	}\r\n\r\n#maintable	{\r\n			width: 80%;\r\n			background-color: #FFFFFF;\r\n		}\r\n\r\n#maintable a {\r\n	font-size: 13px;\r\n}\r\n\r\n#maintable p {\r\n	margin: 0px;\r\n	margin-bottom:5px;\r\n}\r\n\r\n#bannertable	{\r\n			width: 80%;\r\n			background-color: #FFFFFF;\r\n		}\r\n.whitespacetable	{\r\n			width: 80%;\r\n			background-color: #FFFFFF;\r\n		}\r\n#linkstable        {\r\n                        background-color: #FFFFFF;\r\n                    border-top : 1px solid #DDDDDD;\r\n                        width: 80%;\r\n                }\r\n\r\n#siteLinks {\r\n                font-family: verdana, arial, helvetica;\r\n                font-size: 14px;\r\n                padding-top: 10px;\r\n                padding-bottom: 10px;\r\n                margin-top:5px;\r\n                margin-bottom:5px;\r\n                margin-left:5px;\r\n                border-left : 1px solid #DDDDDD;\r\n                border-bottom : 1px solid #DDDDDD;\r\n                border-right : 1px solid #DDDDDD;\r\n        }\r\n\r\n#siteLinks a,\r\n#siteLinks a:link,\r\n#siteLinks a:visited,\r\n#siteLinks a:active {\r\n                        color: #7289AF;\r\n                        margin-right: 10px;\r\n                        margin-left: 10px;\r\n                }\r\n#siteLinks a:hover        {\r\n                        border: 0px;\r\n                        border-bottom: 2px solid #7289AF;\r\n                }\r\n\r\n.activeLink {\r\n	border: 0px;\r\n	border-bottom: 2px;\r\n	border-style:solid;\r\n	color: #24447A;\r\n}\r\n\r\n#cvMenu {\r\n	border: 0px;\r\n	border-right: 1px;\r\n	border-top: 1px;\r\n	border-color:#C8C8CE;\r\n	border-style:solid;\r\n	color:#91919E;\r\n	font-size: 13px;\r\n}\r\n\r\n#cvMenu a,\r\n#cvMenu a:link,\r\n#cvMenu a:visited,\r\n#cvMenu a:active {\r\n			color: #7289AF;\r\n		}\r\n#cvMenu a:hover	{\r\n			background-color: #C4D8BD;\r\n			display:block;\r\n		}\r\n\r\n.activeCVMenuLink {\r\n	 background-color: #C4D8BD;\r\n	display:block;\r\n	color: #24447A;\r\n}\r\n\r\n#footer {\r\n	font-family: verdana, arial, helvetica;\r\n	color: #FFFFFF;\r\n	background-color: #BCBCC4;\r\n	width: 80%;\r\n}\r\n\r\n#footer p {\r\n	color: #FFFFFF;\r\n	font-size: 12px;		\r\n}\r\n\r\n#footer a {\r\n	color: #FFFFFF;\r\n	font-size: 12px;		\r\n}\r\n\r\n#openPage {\r\n	border: 0px;\r\n	border-right: 1px;\r\n	border-top: 1px;\r\n	border-color:#C8C8CE;\r\n	border-style:solid;\r\n	color:#91919E;\r\n	font-size: 12px;		\r\n}\r\n\r\n#openPageLinks {\r\n	border: 0px;\r\n	border-left: 1px;\r\n	border-top: 1px;\r\n	border-bottom: 1px;\r\n	border-color:#C8C8CE;\r\n	border-style:solid;\r\n	color:#91919E;\r\n	font-s


ize: 12px;		\r\n}\r\n\r\n.infoBottom {\r\n    border: 0px;\r\n	border-bottom: 1px;\r\n	border-style:dashed;\r\n	color: #91919E;\r\n	margin-bottom:10px;\r\n}	\r\n\r\n\r\n.infobox		{\r\n					background-color: #eeeeee;\r\n					border: 1px;\r\n					border-color: #DDDDDD;\r\n					border-style: solid;\r\n					padding: 7px;\r\n				}\r\n.infobox caption	{\r\n					background-color: #C4D8BD;\r\n					color: #ffffff;\r\n					vertical-align: middle;\r\n					text-align: left;\r\n					padding: 4px;\r\n					font-weight: bold;\r\n					border: 1px;\r\n					border-color: #DDDDDD;\r\n					border-style: solid;\r\n					border-bottom: 0px;\r\n					font-size: 1.2em;\r\n				}\r\n\r\n.weblogdateheader {\r\n             font-size:14px;\r\n}\r\n\r\n.profiletable td {\r\n           background-color:#ffffff;\r\n}\r\n\r\n.profiletable fieldname p{\r\n               font-weight:600;\r\n               color:#4F6487;\r\n}\r\n\r\n.messages		{\r\n					color: #990000;\r\n					padding: 0px;\r\n					margin: 0px;\r\n				}\r\n\r\n.messages li {\r\n    color: #990000;\r\n}', 3);
INSERT INTO `template_elements` VALUES (24788, 'folder', '					<table>\r\n						<tr>\r\n							<td valign=\\"middle\\">\r\n								<a href=\\"{{url}}\\">\r\n									<img src=\\"{{icon}}\\" width=\\"93\\" height=\\"90\\" border=\\"0\\" alt=\\"\\" />\r\n								</a>\r\n							</td>\r\n							<td valign=\\"middle\\">\r\n								<a href=\\"{{url}}\\">{{name}}</a>{{menu}}<br />{{keywords}}\r\n							</td>\r\n						</tr>\r\n					</table>', 1);
INSERT INTO `template_elements` VALUES (24789, 'file', '	\r\n					<table>\r\n						<tr>\r\n							<td>\r\n								<a href=\\"{{url}}\\">\r\n									<img src=\\"{{icon}}\\" width=\\"90\\" height=\\"90\\" border=\\"0\\" alt=\\"\\" />\r\n								</a>\r\n							</td>\r\n							<td valign=\\"middle\\">\r\n								<a href=\\"{{url}}\\">\r\n									<b>{{title}}</b>\r\n								</a>{{menu}}<br />\r\n									{{description}}<br />\r\n									<small>{{originalname}}</small><br /><small>{{keywords}}</small>\r\n							</td>\r\n						</tr>\r\n					</table>\r\n	', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `templates`
-- 

CREATE TABLE `templates` (
  `ident` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `owner` int(11) NOT NULL default '0',
  `public` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (`ident`),
  KEY `name` (`name`,`owner`,`public`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `templates`
-- 

INSERT INTO `templates` VALUES (1, 'Simple Elegant', 1, 'yes');
INSERT INTO `templates` VALUES (2, 'The crags', 1, 'yes');
INSERT INTO `templates` VALUES (3, 'Water drops on wood', 1, 'yes');

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `ident` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(128) NOT NULL default '',
  `name` varchar(128) NOT NULL default '',
  `icon` int(11) NOT NULL default '-1',
  `active` enum('yes','no') NOT NULL default 'yes',
  `alias` varchar(128) NOT NULL default '',
  `code` varchar(32) NOT NULL default '',
  `icon_quota` int(11) NOT NULL default '10',
  `file_quota` int(11) NOT NULL default '10000000',
  `template_id` int(11) NOT NULL default '-1',
  `community` enum('yes','no') NOT NULL default 'no',
  `community_owner` int(11) NOT NULL default '-1',
  PRIMARY KEY  (`ident`),
  KEY `username` (`username`,`password`,`name`,`active`),
  KEY `code` (`code`),
  KEY `icon` (`icon`),
  KEY `icon_quota` (`icon_quota`),
  KEY `file_quota` (`file_quota`),
  KEY `email` (`email`),
  KEY `template_id` (`template_id`),
  KEY `community` (`community`,`community_owner`),
  FULLTEXT KEY `name` (`name`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (1, 'news', '5f4dcc3b5aa765d61d8327deb882cf99', '', 'News', -1, 'yes', '', '', 10, 10000000, -1, 'no', -1);

-- --------------------------------------------------------

-- 
-- Table structure for table `weblog_comments`
-- 

CREATE TABLE `weblog_comments` (
  `ident` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL default '0',
  `owner` int(11) NOT NULL default '0',
  `postedname` varchar(128) NOT NULL default '',
  `body` text NOT NULL,
  `posted` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`),
  KEY `posted` (`posted`),
  KEY `post_id` (`post_id`),
  KEY `postedname` (`postedname`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `weblog_posts`
-- 

CREATE TABLE `weblog_posts` (
  `ident` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `community` int(11) NOT NULL default '-1',
  `access` varchar(255) NOT NULL default '',
  `posted` int(11) NOT NULL default '0',
  `title` text NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`ident`),
  KEY `owner` (`owner`,`access`,`posted`),
  KEY `community` (`community`)
) TYPE=MyISAM;
