<?php
/**
 * Elgg Admin Area Canvas
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
?>
<style type="text/css">
/**
 * ELGG DEFAULT ADMIN AREA CSS
   @todo - move into separate css file
*/
/* temporary force-hide / override some base elements  */
body { background-color: #444444; }
#elgg_header { display:none; }
#elgg_footer { display:none; }

#admin_header {
	background-color:#333333;
	border-bottom:1px solid #555555;
}
#admin_footer {
	background:#333333;
	border-top:1px solid #222222;
	clear:both;
	height:30px;
	width:100%;
}
#admin_header .network_title h2 {
	height:45px;
	line-height:45px;
	margin:0;
	padding:0 0 0 20px;
	border:0;
}
#admin_header .network_title h2 a {
	color:white;
}
#admin_header .network_title h2 a:hover {
	color:white;
	text-decoration: underline;
}
#admin_header .network_title h2 a.return_to_network {
	font-size:12px;
	font-weight: normal;
	color:#666666;
	float:right;
	margin-right:40px;
}
#elgg_content.admin_area {
	margin:20px;
	min-height:400px;
	position:relative;
	width:auto;
	background-image: none;
	background-color: transparent;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}
#elgg_content.admin_area #elgg_page_contents  {
	float:left;
	margin:0;
	padding:14px;
	width:75%;
	background-color: white;
	-webkit-border-radius: 6px; 
	-moz-border-radius: 6px;
}
#elgg_content.admin_area #elgg_sidebar  {
	float:left;
	margin:0;
	min-height:400px;
	padding:0 0 0 3%;
	position:relative;
	width:17%;
}

.admin_area h1, 
.admin_area h2,
.admin_area h3,
.admin_area h4,
.admin_area h5,
.admin_area h6 { 
	color:#666666;
}
.admin_area #elgg_sidebar .submenu {
	margin:0;
	padding:0;
	list-style: none;
}
.admin_area .submenu li.selected a,
.admin_area .submenu li.selected li.selected a,
.admin_area .submenu li.selected li.selected li.selected a {
	background-color: black;
	color:white; 
}
.admin_area .submenu li a {
	display:block;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background-color:white;
	margin:0 0 3px 0;
	padding:2px 4px 2px 8px;
	color:#333333;
}
.admin_area .submenu li a:hover {
	background-color:black;
	color:white;
	text-decoration:none;
}
.admin_area #elgg_sidebar .submenu ul.child {
	margin-bottom:10px;
}
.admin_area .submenu .child li a {
	margin-left:15px;
	background-color:#dedede;
	color:#333333;
}
.admin_area .submenu .child li a:hover {
	background-color:black;
	color:white;
}

.admin_settings h3 {
	background:#999999;
	color:white;
	padding:5px;
	margin-top:10px;
	margin-bottom:10px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
}
.admin_settings label {
	color:#333333;
	font-size:100%;
	font-weight:normal;
}
.admin_area .input_textarea {
	width:98%;
}
.admin_area form#plugin_settings {
	margin-top: 10px;
}
.admin_area form#plugin_settings .action_button.disabled {
	margin-top:10px;
	float:right;
}
.admin_settings {
	margin-bottom:20px;
}
.admin_settings table.styled {
	width:100%;
}
.admin_settings table.styled {
	border-top:1px solid #cccccc;
}
.admin_settings table.styled td {
	padding:2px 4px 2px 4px;
	border-bottom:1px solid #cccccc;
}
.admin_settings table.styled td.column_one {
	width:200px;
}
.admin_settings table.styled tr:hover {
	background: #E4E4E4;
}
.admin_settings.users_online .profile_status {
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	line-height:1.2em;
}
.admin_settings.users_online .profile_status span {
	font-size:90%;
	color:#666666;
}
.admin_settings.users_online  p.owner_timestamp {
	padding-left:3px;
}
.admin_plugin_reorder {
	float:right;
	width:200px;
	text-align: right;
}
.admin_plugin_reorder a {
	padding-left:10px;
	font-size:80%;
	color:#999999;
}
.manifest_file {
	background-color:#eeeeee;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	padding:5px 10px 5px 10px;
	margin:4px 0 4px 0;
}
.admin_plugin_enable_disable {
	width:150px;
	margin:10px 0 0 0;
	float:right;
	text-align: right;
}
.admin_plugin_enable_disable a {
	margin:0;
}
.pluginsettings {
	margin:15px 0 5px 0;
	background-color:#eeeeee;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	padding:10px;
}
.pluginsettings h3 {
	padding:0 0 5px 0;
	margin:0 0 5px 0;
	border-bottom:1px solid #999999;
}
#updateclient_settings h3 {
	padding:0;
	margin:0;
	border:none;
}
.plugin_controls {
	padding: 3px 3px 3px 0;
	font-weight: bold;
	float: left;
	width: 150px;
}
form.admin_plugins_simpleview .submit_button {
	margin-right:20px;
}
.plugin_info {
	margin: 3px;
	padding-left: 150px;
	display: block;
}
.plugin_metadata {
	display:block;
	color:#999999;
}
.plugin_name input[type="checkbox"] {
	margin-right: 10px;
}
.plugin_details {
	margin:0 0 5px 0;
	padding:0 7px 4px 10px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.plugin_details p {
	margin:0;
	padding:0;
}
.plugin_settings {
	font-weight: normal;
}
.active {
	border:1px solid #999999;
	background:white;
}
.not_active {
	border:1px solid #999999;
	background:#dedede;
}
.configure_menuitems {
	margin-bottom:30px;
}
.admin_settings.menuitems .input_pulldown {
	margin-right:15px;
	margin-bottom:10px;
}
.admin_settings.menuitems li.custom_menuitem {
	margin-bottom:20px;
}
.admin_notices {
	padding-bottom: 15px;
}
.admin_notices p {
	background-color:#BDE5F8;
	color: black;
	border: 1px solid blue;
	font-weight: bold;
	padding:3px 10px;
	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}
</style>

<div id="admin_header">
	<span class="network_title"><h2>
	<a href="<?php echo $vars['url']; ?>">
	<?php echo $vars['config']->sitename; echo " ".elgg_echo('admin'); ?></a>
	<a class="return_to_network" href="<?php echo $vars['url']; ?>">&lt;&lt; Return to network</a>
	</h2></span>
</div>

<div id="elgg_content" class="clearfloat admin_area">
	
	<div id="elgg_page_contents" class="clearfloat">
		<?php 
			if (isset($vars['area1'])) echo $vars['area1'];
		?>
	</div>
	<div id="elgg_sidebar" class="clearfloat">
		<?php 
			echo elgg_view('page_elements/owner_block'); 
			if (isset($vars['area2'])) echo $vars['area2']; 
			if (isset($vars['area3'])) echo $vars['area3'];	
		?>
	</div>
</div>
<div id="admin_footer"></div>