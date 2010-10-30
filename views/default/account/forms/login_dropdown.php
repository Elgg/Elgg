<?php
/**
* Elgg drop-down login form
*
*/

if (!isloggedin()) {
	$form_body  = "<p class='loginbox'><label>" . elgg_echo('loginusername') . "</label>" . elgg_view('input/text', array('internalname' => 'username', 'class' => 'login_textarea name'));
	$form_body .= "<label>" . elgg_echo('password') . "</label>" . elgg_view('input/password', array('internalname' => 'password', 'class' => 'login_textarea'));
	$form_body .= elgg_view('input/submit', array('value' => elgg_echo('login'))) . " <span class='remember'><label><input type=\"checkbox\" name=\"persistent\" value=\"true\" />".elgg_echo('user:persistent')."</label></span></p>";

	$form_body .= elgg_view('login/extend');

	$form_body .= "<p class='loginbox'>";
	$form_body .= $CONFIG->allow_registration ? "<a href=\"".elgg_get_site_url()."pg/register/\">" . elgg_echo('register') . '</a> | ' : '';
	$form_body .= "<a href=\"".elgg_get_site_url()."pages/account/forgotten_password.php\">" . elgg_echo('user:password:lost') . "</a></p>";
	$form_body .= "<input type='hidden' name='returntoreferer' value='true' />";

	$login_url = elgg_get_site_url();
	if ((isset($CONFIG->https_login)) && ($CONFIG->https_login)) {
		$login_url = str_replace("http", "https", elgg_get_site_url());
	}
?>

	<div id="login_dropdown">
		<div id="signin_button" class="signin_button">
			<a href="<?php echo $CONFIG->url; ?>pg/login" class="signin"><span><?php echo elgg_echo('login') ?></span></a>
		</div>
		<fieldset id="signin_menu">
<?php
			echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$login_url}action/login"));
?>
		</fieldset>
	</div>

<?php
}
?>

<script type="text/javascript">
$(document).ready(function() {

	$(".signin").click(function(e) {
		e.preventDefault();
		$("fieldset#signin_menu").toggle();
		$(".signin").toggleClass("menu_open");
		$('.login_textarea.name').focus();
	});

	$("fieldset#signin_menu").mouseup(function() {
		return false
	});

	$(document).mouseup(function(e) {
		if($(e.target).parent("a.signin").length==0) {
			$(".signin").removeClass("menu_open");
			$("fieldset#signin_menu").hide();
		}
	});

});
</script>

<style type="text/css">
/* DROPDOWN LOGIN BOX */
#login_dropdown {
	float:right;
	position: absolute;
	top:10px;
	right:0;
	z-index: 9599;
}
#login_dropdown #signin_button {
	padding:10px 0px 12px;
	line-height:23px;
	text-align:right;
}
#login_dropdown #signin_button a.signin {
	padding:2px 6px 3px 6px;
	text-decoration:none;
	font-weight:bold;
	position:relative;
	margin-left:0;
	color:white;
	border:1px solid #71B9F7;
	-webkit-border-radius:4px;
	-moz-border-radius:4px;
	border-radius:4px;
}
#login_dropdown #signin_button a.signin span {
	padding:4px 0 6px 12px;
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-position:-150px -51px;
	background-repeat:no-repeat;
}
#login_dropdown #signin_button a.signin:hover {
	background-color:#71B9F7;
	/* color:black; */
}
#login_dropdown #signin_button a.signin:hover span {
	/* background-position:-150px -71px; */
}
#login_dropdown #signin_button a.signin.menu_open {
	background:#cccccc !important;
	color:#666666 !important;
	border:1px solid #cccccc;
	outline:none;
}
#login_dropdown #signin_button a.signin.menu_open span {
	background-position:-150px -71px;
	color:#333333;
}
#login_dropdown #signin_menu {
	-moz-border-radius-topleft:5px;
	-moz-border-radius-bottomleft:5px;
	-moz-border-radius-bottomright:5px;
	-webkit-border-top-left-radius:5px;
	-webkit-border-bottom-left-radius:5px;
	-webkit-border-bottom-right-radius:5px;
	display:none;
	background-color:white;
	position:absolute;
	width:210px;
	z-index:100;
	border:5px solid #CCCCCC;
	text-align:left;
	padding:12px;
	top: 26px;
	right: 0px;
	margin-top:5px;
	margin-right: 0px;
	color:#333333;
	-webkit-box-shadow: 0 3px 3px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 3px 3px rgba(0, 0, 0, 0.45);
}
#login_dropdown #signin_menu input[type=text],
#login_dropdown #signin_menu input[type=password] {
	width:203px;
	margin:0 0 5px;
}
#login_dropdown #signin_menu p {
	margin:0;
}
#login_dropdown #signin_menu label {
	font-weight:normal;
	font-size: 100%;
}
#login_dropdown #signin_menu .submit_button {
	margin-right:15px;
}

/* ie7 fixes */
*:first-child+html #login_dropdown #signin_button {
	line-height:10px;
}
*:first-child+html #login_dropdown #signin_button a.signin span {
	background-position:-150px -54px;
}
*:first-child+html #login_dropdown #signin_button a.signin.menu_open span {
	background-position:-150px -74px;
}
</style>