<?php
/**
 * Elgg captcha plugin captcha hook view override.
 *
 * @package ElggCaptcha
 */

// Generate a token which is then passed into the captcha algorithm for verification
$token = captcha_generate_token();
?>
<div class="captcha">
	<input type="hidden" name="captcha_token" value="<?php echo $token; ?>" />
	<label>
		<?php echo elgg_echo('captcha:entercaptcha'); ?>
		<div class="captcha_image">
			<img class="captcha-input-image" alt="captcha" src="<?php echo $vars['url'] . "pg/captcha/$token"; ?>" />
		</div>
		<div class="captcha_input">
			<?php echo elgg_view('input/text', array('internalname' => 'captcha_input')); ?>
		</div>
	</label>
</div>