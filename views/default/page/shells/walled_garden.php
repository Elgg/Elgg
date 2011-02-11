<?php
/**
 * 
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

// Set title
$site_title = elgg_get_config('sitename');
if (empty($vars['title'])) {
	$title = $site_title;
} else if (empty($site_title)) {
	$title = $vars['title'];
} else {
	$title = $site_title . ": " . $vars['title'];
}

// @todo - move the css below into it's own style-sheet 
// that is called when running as a private network
?>
<html>
<?php echo elgg_view('page/elements/head', $vars); ?>
<body>
	<style type="text/css">
	body {background: white;}
	/* ***************************************
		WalledGarden
	*************************************** */
	#walledgarden_container {
		margin:100px auto 0 auto;
		position:relative;
		padding:0;
		width:563px;
		background: url(<?php echo elgg_get_site_url(); ?>_graphics/walled_garden_background_extend.gif) repeat-y left top;
		text-align: left;
		word-wrap:break-word;
	}
	#walledgarden {
		position: relative;
		padding:0;
		width:563px;
		min-height:230px;
		background: url(<?php echo elgg_get_site_url(); ?>_graphics/walled_garden_background_top.gif) no-repeat left top;
	}
	#walledgarden_bottom {
		margin:0 auto;
		background: url(<?php echo elgg_get_site_url(); ?>_graphics/walled_garden_background_bottom.gif) no-repeat left bottom;
		width:563px;
		height:54px;
		/* position: relative; */
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
	.walledgardenlogin form input.login-textarea {
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
		color:#999999;
	}
	.walledgardenlogin a.forgotten_password_link,
	.walledgardenlogin a.registration_link {
		color:#999999;
	}
	
	/* override default form styles (for when a theme is running) */
	.walledgardenlogin input {
		font: 120% Arial, Helvetica, sans-serif;
		padding: 5px;
		border: 1px solid #cccccc;
		color:#666666;
		background-color: white;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
	}
	.walledgardenlogin textarea {
		font: 120% Arial, Helvetica, sans-serif;
		border: solid 1px #cccccc;
		padding: 5px;
		color:#666666;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
	}
	.walledgardenlogin textarea:focus,
	.walledgardenlogin input[type="text"]:focus {
		border: solid 1px #4690d6;
		background: #e4ecf5;
		color:#333333;
		-webkit-box-shadow: none;
		-moz-box-shadow: none;
		box-shadow: none;	
	}
	.walledgardenlogin .elgg-input-password {
		width:200px;
	}
	.walledgardenlogin input.elgg-input-password:focus {
		border: solid 1px #4690d6;
		background-color: #e4ecf5;
		color:#333333;
		-webkit-box-shadow: none;
		-moz-box-shadow: none;
		box-shadow: none;
	}
	.walledgardenlogin input[type="password"]:focus {
		border: solid 1px #4690d6;
		background-color: #e4ecf5;
		color:#333333;
		-webkit-box-shadow: none;
		-moz-box-shadow: none;
		box-shadow: none;
	}
	.walledgardenlogin .submit_button {
		font-size: 14px;
		font-weight: bold;
		color: white;
		text-shadow:1px 1px 0px black;
		text-decoration:none;
		border: 1px solid #4690d6;
		background-color:#4690d6;
		background-image: url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
		background-repeat: repeat-x;
		background-position: left 10px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		width: auto;
		padding: 2px 4px;
		margin:0 10px 10px 0;
		cursor: pointer;
		-webkit-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
		-moz-box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.40);
	}
	.walledgardenlogin .submit_button:hover {
		color: white;
		border-color: #0054a7;
		text-decoration:none;
		background-color:#0054a7;
		background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png);
		background-repeat:  repeat-x;
		background-position:  left 10px;
	}
	.walledgardenlogin input.action_button {
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		background-color:#cccccc;
		background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
		background-repeat:  repeat-x;
		background-position: 0 0;
		border:1px solid #999999;
		color:#333333;
		padding:2px 15px 2px 15px;
		text-align:center;
		font-weight:bold;
		text-decoration:none;
		text-shadow:0 1px 0 white;
		cursor:pointer;
		-webkit-box-shadow: none;
		-moz-box-shadow: none;
	}
	.walledgardenlogin input.action_button:hover,
	.walledgardenlogin input.action_button:focus {
		background-position:0 -15px;
		background-image:  url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif);
		background-repeat:  repeat-x;
		color:#111111;
		text-decoration: none;
		background-color:#cccccc;
		border:1px solid #999999;
	}
	.walledgardenlogin .action_button.elgg-state-disabled  {
		color:#999999;
		padding:2px 7px;
	}
	
	/* override some elgg system message styles */
	#walledgarden_sysmessages {
		position: absolute;
		width:100%;
		text-align: center;
		margin:0 auto;
		top:0;
		z-index:9600;
	}
	#walledgarden_sysmessages #elgg-system-message {
		width: 515px;
		max-width: 515px;
		right:auto;
		margin:30px auto 0 auto;
		position: relative;
	}
	
	
	#lostpassword_form,
	#registration_form {
		right:0;
		position:absolute;
		top:0;
		width:260px;
		background-color: white;
		padding:0;
		background: url(<?php echo elgg_get_site_url(); ?>_graphics/walled_garden_backgroundfull_top.gif) no-repeat left top;
		height:auto;
	}
	#hiddenform_body {
		padding:30px 40px 0 40px;
		height:auto;
	}
	#hiddenform_bottom {
		margin:0 auto;
		background: url(<?php echo elgg_get_site_url(); ?>_graphics/walled_garden_backgroundfull_bottom.gif) no-repeat left bottom;
		width:563px;
		height:54px;
		position: relative;
	}
	
	#hiddenform_body .cancel_request {
		margin-left:15px;
	}
	
	/* override some visual_captcha styles */
	.walledgardenlogin .visual_captcha_choices  {
		margin:10px 0 0 0;
		padding:0;
		height:60px;
	}
	.walledgardenlogin ul.visual_captcha_choices li img {
		width:50px;
		height:auto;
	}
		
	</style>
	<div id="walledgarden_sysmessages" class="clearfix">
		<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
	</div>
	<div id="walledgarden_container">
		<div id="walledgarden" class="clearfix">
			<div class="walledgardenintro clearfix">
				<h1>Welcome to:<br /><?php echo $title; ?></h1>
			</div>
			<div class="walledgardenlogin clearfix">
				<?php echo $vars['body']; ?>
			</div>
		</div>
		<div id="walledgarden_bottom"></div>
	</div>
</body>
</html>