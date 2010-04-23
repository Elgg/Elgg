<?php

     /**
	 * Elgg login form for walled garden
	 */
	 
	global $CONFIG;

	//login form
	$form_body = "<h2 class='master'>" . elgg_echo('login') . "</h2>";
	$form_body .= "<label>".elgg_echo('username')."</label>".elgg_view('input/text', array('internalname' => 'username', 'class' => 'login-textarea username master'));
	$form_body .= "<br /><label>".elgg_echo('password')."</label>".elgg_view('input/password', array('internalname' => 'password', 'class' => 'login-textarea password master' ));	

	
	$form_body .= "<br />" . elgg_view('input/submit', array('value' => elgg_echo('login'))) . " <div id=\"persistent_login\"><label><input type=\"checkbox\" name=\"persistent\" value=\"true\" />".elgg_echo('user:persistent')."</label></div>";
	$form_body .= "<p class=\"loginbox\">";
	$form_body .= (!isset($CONFIG->disable_registration) || !($CONFIG->disable_registration)) ? "<a href=\"{$vars['url']}account/register.php\">" . elgg_echo('register') . "</a> | " : "";
	$form_body .= "<a href='#forgotten_password' class='forgotten_password_link'>" . elgg_echo('user:password:lost') . "</a></p>";  


		
	$login_url = $vars['url'];
	if ((isset($CONFIG->https_login)) && ($CONFIG->https_login))
		$login_url = str_replace("http", "https", $vars['url']);
?>
	
	<div id="login-box">
		<?php 
			echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$login_url}action/login"));
		?>			
		<div class="clearfloat"></div>
	</div>

	
<script type="text/javascript"> 
	$(document).ready(function() { 
	
		$('.login-textarea.username.master').focus(); // only put cursor in textfirld if master login
	
		$('.login-textarea.name').focus(function() {
			if (this.value=='<?php echo elgg_echo('username'); ?>') {
				this.value='';
			}
		});
		$('.login-textarea.name').blur(function() {
			if (this.value=='') {
				this.value='<?php echo elgg_echo('username'); ?>';
			}
		});
		$('.login-textarea.password').focus(function() {
			if (this.value=='<?php echo elgg_echo('password'); ?>') {
				this.value='';
			}
		});
		
			
	//select all the a tag with name equal to modal
	$('a.forgotten_password_link').click(function(e) {
		//Cancel the link behavior
		e.preventDefault();
		
		//Get the A tag
		var id = $(this).attr('href');
	
		//Get the screen height and width
		//var maskHeight = $(document).height();
		//var maskWidth = $(window).width();
	
		//Set height and width to mask to fill up the whole screen
		//$('#mask').css({'width':maskWidth,'height':maskHeight});
		
		//transition effect		
		$('#mask').fadeIn(500);	
		$('#mask').fadeTo("slow",0.8);	
	
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();
              
		//Set the popup window to center
		$(id).css('top',  winH/4-$(id).height()/2);
		$(id).css('left', (winW-20)/2-$(id).width()/2);
	
		//transition effect
		$(id).fadeIn(1000); 
	
	});
	
	//if close button is clicked
	$('.window .close').click(function (e) {
		//Cancel the link behaviour
		e.preventDefault();
		
		$('#mask').hide();
		$('.window').hide();
	});		
	
	//if mask is clicked
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});	
			
});			
</script>
	
	
	
	
	