<?php
?>
<div id='layout_spotlight' class="migration_banner">
<div id='wrapper_spotlight'>
<div class="collapsable_box">
<div class="collapsable_box_header">

<a href="javascript:void(0);" class="elgg-banner-close"><?php echo elgg_echo('close'); ?></a>
<div class="collapsable_box_content" <?php if ($closed) echo "style=\"display:none\"" ?>>
<div><? echo elgg_get_plugin_setting("text", "banner"); ?></div>
</div>


</div>
</div>
</div>
</div>
<div>
</div>
