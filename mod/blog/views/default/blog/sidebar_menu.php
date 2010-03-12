<?php
/**
 * Blog sidebar menu.
 *
 * @package Blog
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

// a few cases to consider:
// 1. looking at all posts
// 2. looking at a user's post
// 3. looking at your posts

/*
Logged in or not doesn't matter unless you're looking at your blog.
	Does it matter then on the side bar?

All blogs:
	Archives

Owned blogs;
	Archives



*/

$loggedin_user = get_loggedin_user();
$page_owner = page_owner_entity();

if ($loggedin_user) {

}

?>

<ul class="submenu">
	<li><a href="">Drafts</a></li>
</ul>