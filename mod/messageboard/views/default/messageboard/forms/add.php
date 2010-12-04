<?php

/**
 * Elgg Message board add form
 *
 * @package ElggMessageBoard
 */

?>
<div id="mb_input_wrapper">
	<form action="<?php echo elgg_get_site_url(); ?>action/messageboard/add" method="post" name="messageboardForm">

		<!-- textarea for the contents -->
		<textarea name="message_content" value="" class="input-textarea"></textarea><br />

		<!-- the page owner, this will be the profile owner -->
		<input type="hidden" name="pageOwner" value="<?php echo elgg_get_page_owner_guid(); ?>"  />

		<?php echo elgg_view('input/securitytoken'); ?>

		<!-- submit messages input -->
		<input type="submit" id="postit" value="<?php echo elgg_echo('messageboard:postit'); ?>">

	</form>
</div>
