<?php
/**
 * Elgg Members search
 * 
 * @package Members
 */
?>
<div class="sidebar_container">

<h3><?php echo elgg_echo('members:searchtag'); ?></h3>
<form id="memberssearchform" class="member_tags" action="<?php echo elgg_get_site_url(); ?>mod/members/index.php?" method="get">
	<input type="text" name="tag" value="Member tags" onclick="if (this.value=='Member tags') { this.value='' }" class="search-input" />
	<input type="hidden" name="filter" value="search_tags" />	
	<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" />
</form>

<h3><?php echo elgg_echo('members:searchname'); ?></h3>
<form id="memberssearchform" class="member_name" action="<?php echo elgg_get_site_url(); ?>mod/members/index.php?" method="get">
	<input type="text" name="tag" value="Members name" onclick="if (this.value=='Members name') { this.value='' }" class="search-input" />
	<input type="hidden" name="subtype" value="" />
	<input type="hidden" name="object" value="user" />
	<input type="hidden" name="filter" value="search" />	
	<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" />
</form>

</div>
