<?php
/**
 * Elgg Members search
 * 
 * @package Members
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */
?>
<div class="SidebarBox">

<h3><?php echo elgg_echo('members:searchtag'); ?></h3>
<form id="memberssearchform" action="<?php echo $vars['url']; ?>mod/members/index.php?" method="get">
	<input type="text" name="tag" value="Member tags" onclick="if (this.value=='Member tags') { this.value='' }" class="search_input" />
	<input type="hidden" name="subtype" value="" />
	<input type="hidden" name="object" value="user" />
	<input type="hidden" name="filter" value="search_tags" />	
	<input type="submit" value="<?php echo elgg_echo('go'); ?>" />
</form>

<h3><?php echo elgg_echo('members:searchname'); ?></h3>
<form id="memberssearchform" action="<?php echo $vars['url']; ?>mod/members/index.php?" method="get">
	<input type="text" name="tag" value="Members name" onclick="if (this.value=='Members name') { this.value='' }" class="search_input" />
	<input type="hidden" name="subtype" value="" />
	<input type="hidden" name="object" value="user" />
	<input type="hidden" name="filter" value="search" />	
	<input type="submit" value="<?php echo elgg_echo('go'); ?>" />
</form>

</div>