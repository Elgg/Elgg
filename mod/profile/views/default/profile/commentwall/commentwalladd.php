<?php
/**
 * Elgg profile comment wall add
 */ 
?>
<div id="comment_wall_add">
<form action="<?php echo $vars['url']; ?>action/profile/addcomment" method="post" name="messageboardForm">
    <!-- textarea for the contents -->
    <textarea name="message_content" value="" class="commentwall"></textarea><br />
    <!-- the person posting an item on the message board -->
    <input type="hidden" name="guid" value="<?php echo $_SESSION['guid']; ?>"  />
    <!-- the page owner, this will be the profile owner -->
    <input type="hidden" name="pageOwner" value="<?php echo page_owner(); ?>"  />
    <?php echo elgg_view('input/securitytoken'); ?>
    <!-- submit messages input -->
    <input type="submit" id="postit" value="<?php echo elgg_echo('profile:commentwall:add'); ?>">
</form>
</div>
