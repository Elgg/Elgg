<?php
/**
* Elgg WalledGarden CSS
*/
?>
#walledgarden_container {
	margin:100px auto 0 auto;
	position:relative;
	padding:0;
	width:563px;
	background: url(<?php echo $vars['url']; ?>mod/walledgarden/graphics/background_extend.gif) repeat-y left top;
}
#walledgarden {
	position: relative;
	padding:0;
	width:563px;
	min-height:230px;
	background: url(<?php echo $vars['url']; ?>mod/walledgarden/graphics/background_top.gif) no-repeat left top;
}
#walledgarden_bottom {
	margin:0 auto;
	background: url(<?php echo $vars['url']; ?>mod/walledgarden/graphics/background_bottom.gif) no-repeat left bottom;
	width:563px;
	height:54px;
	position: relative;
}
.walledgardenintro {
	float:left;
	min-height:200px;
	width:223px;
	padding:15px;
	margin:19px 0 0 23px;
}
.walledgardenlogin {
	float:left;
	min-height:200px;
	width:223px;
	padding:15px 15px 0 15px;
	margin:19px 0 0 11px;
}
.walledgardenintro h1 {
	color:#666666;
	margin-top:80px;
	line-height: 1.1em;
}
.walledgardenlogin h2 {
	color:#666666;
	border-bottom:1px solid #CCCCCC;
	margin-bottom:5px;
	padding-bottom:5px;
}
.walledgardenlogin form input.login_textarea {
	margin:0 0 10px 0;
	width:210px;
}
.walledgardenlogin form label {
	color:#666666;
}
.walledgardenlogin .remember_me label {
	font-size:1em;
	font-weight:normal;
}
.walledgardenlogin .remember_me {
	display:block;
	float:right;
	margin-left:0;
	margin-top:-34px;
	text-align:right;
	width:100%;
}
.walledgardenlogin .lost_password {
	margin-bottom: 10px;
}
.walledgardenlogin a.forgotten_password_link {
	color:#999999;
}
#walledgarden_sysmessages #elgg_system_message {
	width: 523px;
	right:0;
	margin:10px auto 0 auto;
	position: relative;
}