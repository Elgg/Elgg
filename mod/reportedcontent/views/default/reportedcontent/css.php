<?php

	/**
	 * Elgg reported content CSS
	 * 
	 * @package reportedcontent
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

?>
/* ***************************************
PAGE-OWNER BLOCK
*************************************** */
#owner_block_report_this {
	padding:5px 0 0 0;
}
#owner_block_report_this a {
	font-size: 90%;
	color:#999999;
	padding:0 0 4px 20px;
	background: url(<?php echo $vars['url']; ?>_graphics/icon_reportthis.gif) no-repeat left top;
}
#owner_block_report_this a:hover {
	color: #0054a7;
}

/* ***************************************
	ADMIN AREA - REPORTED CONTENT
*************************************** */
.reportedcontent_content {
	margin:0 0 5px 0;
	padding:0 7px 4px 10px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.reportedcontent_content p.reportedcontent_detail,
.reportedcontent_content p {
	margin:0;
}
.active_report {
	border:1px solid #D3322A;
	background:#F7DAD8;
}
.archived_report {
	border:1px solid #666666;
	background:#dedede;
}
a.archive_report_button {
	float:right;
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #ffffff;
	background:#4690d6;
	border: 1px solid #4690d6;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	width: auto;
	padding: 4px;
	margin:15px 0 0 20px;
	cursor: pointer;
}
a.archive_report_button:hover {
	background: #0054a7;
	border: 1px solid #0054a7;
	text-decoration: none;
}
a.delete_report_button {
	float:right;
	font: 12px/100% Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #ffffff;
	background:#999999;
	border: 1px solid #999999;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	width: auto;
	padding: 4px;
	margin:15px 0 0 20px;
	cursor: pointer;
}
a.delete_report_button:hover {
	background: #333333;
	border: 1px solid #333333;
	text-decoration:none;
}
.reportedcontent_content .collapsible_box {
	background: white;
}
