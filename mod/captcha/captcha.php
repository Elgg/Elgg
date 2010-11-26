<?php
	/**
	 * Elgg captcha plugin graphics file generator
	 * 
	 * @package ElggCaptcha
	 */

	global $CONFIG;
	$token = get_input('captcha_token');

	// 	Output captcha
	if ($token)
	{ 
		// Set correct header
		header("Content-type: image/jpeg");
	
		// Generate captcha
		$captcha = captcha_generate_captcha($token);
		
		// Pick a random background image
		$n = rand(1, $CONFIG->captcha_num_bg);
		$image = imagecreatefromjpeg($CONFIG->pluginspath . "captcha/backgrounds/bg$n.jpg");	
		
		// Create a colour (black so its not a simple matter of masking out one colour and ocring the rest)
		$colour = imagecolorallocate($image, 0,0,0);
		
		// Write captcha to image
		//imagestring($image, 5, 30, 4, $captcha, $black);
		imagettftext($image, 30, 0, 10, 30, $colour, $CONFIG->pluginspath . "captcha/fonts/1.ttf", $captcha);
		
		// Output image
		imagejpeg($image);
		
		// Free memory
		imagedestroy($image);
	}
?>