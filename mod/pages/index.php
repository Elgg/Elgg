<?php
/**
 * Custom Pages manager wrapper
 *
 * @copyright Copyright (c) 2007 Pro Soft Resources Inc. http://www.prosoftpeople.com
 * @author Rolando Espinoza La Fuente <rho@prosoftpeople.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

//FIXME: workaround for walledgarden
if (isset($_GET['owner']) && $_GET['owner'] == -1) {
    define('context', 'external');
    define('pages_external', true);
}

include(dirname(dirname(dirname(__FILE__))) . '/includes.php');
require(dirname(__FILE__) . '/lib/pages.inc.php');

if (page_owner() > 0) {
    define('context', 'pages');
}

// pages init
pages_actions();

templates_page_setup();

if (page_owner() < 1) {
    // remove all but pages block
    sidebar_remove(array('pages_sidebar'), true);
}

$page_id = optional_param('page');
$do_action = optional_param('do');

if (!pages_enabled()) {
    $page = new StdClass;
    $page->title = __gettext('Plugin disabled for users');
    $page->content = __gettext('This plugin is currently disabled by site administrator');
} else {
    if ($do_action == 'edit') {
        $page = pages_edit_page($page_id, page_owner());
    } else {
        $page = pages_get_page($page_id, page_owner());
    }   
}


if (!empty($page->ident)) {
    // link title
    $title = pages_html_a(get_url($page_id, 'pages::page', page_owner()), $page->title);
} else {
    $title = $page->title;
}

templates_page_output($title, $page->content);

?>
