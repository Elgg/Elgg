<?php
/**
 * Elgg administrator plugin
 *
 * @copyright Copyright (c) 2007 Pro Soft Resources Inc. http://www.prosoftpeople.com
 * @author Rolando Espinoza La Fuente <rho@prosoftpeople.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */


require_once(dirname(__FILE__).'/lib/engine.inc.php');

function elggadmin_init() {
 
    if (context() == 'elggadmin') {
        require_once(dirname(__FILE__).'/lib/elggadmin.inc.php'); 

        elggadmin_add_rule('mod/elggadmin/?$', 'elggadmin_page');
        elggadmin_add_rule('mod/elggadmin/(theme|frontpage|logs)$', 'elggadmin_page');
        elggadmin_add_rule('mod/elggadmin/index.php/?(theme|frontpage|logs)?$', 'elggadmin_page');

        elggadmin_add_function('elggadmin_page_before', 'elggadmin_actions');
        // this should go somewhere else 
        elggadmin_add_rule('.*', 'elggadmin_404', 1000);
    }
  
}

function elggadmin_pagesetup() {
    // first login?
    global $CFG;
    if (user_flag_get('admin', $_SESSION['userid']) &&
        !isset($CFG->elggadmin_installed)) {
        $CFG->elggadmin_installed = true;
        set_config('elggadmin_installed', true);
        header_redirect(get_url(null, 'elggadmin::config'), __gettext('Welcome to the Elgg configuration manager!'));
    }

    if (context() == 'admin') {
        if (!plugin_is_loaded('pages')) {
            elgg_messages_add(__gettext('Error: <code>elggadmin</code> plugin needs <code>pages</code> plugin to run'));
        } else {
            pages_submenu_add('elggadmin', __gettext('Site administration'), get_url(null, 'elggadmin::'), 10);
        }

    } elseif (context() == 'elggadmin') {

        if (!plugin_is_loaded('pages')) {
            elgg_messages_add(__gettext('Error: <code>elggadmin</code> plugin needs <code>pages</code> plugin to run'));
            header_redirect(get_url(null, 'admin::'));
        }

        // submenu options
        pages_submenu_add('elggadmin', __gettext('Configuration manager'), get_url(null, 'elggadmin::'));
        pages_submenu_add('elggadmin:theme', __gettext('Default theme editor'), get_url(null, 'elggadmin::theme'));
        pages_submenu_add('elggadmin:frontpage', __gettext('Frontpage template editor'), get_url(null, 'elggadmin::frontpage'));
        pages_submenu_add('elggadmin:logs', __gettext('Error log'), get_url(null, 'elggadmin::logs'));

        sidebar_add(50, 'sidebar-'.elggadmin_currentpage(), elggadmin_sidebar());
        // clear sidebar
        $clear_sidebar[] = 'sidebar-profile';
        $clear_sidebar[] = 'sidebar-' . elggadmin_currentpage();
        sidebar_remove($clear_sidebar, true);

        if (elggadmin_is_404()) {
            header('HTTP/1.0 404 Not Found');
        }
    }
}

function elggadmin_url($object_id, $object_type) {
    global $CFG;

    $url = null;

    switch ($object_type) {
        case 'elggadmin::':
        case 'elggadmin::config':
            $url = $CFG->wwwroot . 'mod/elggadmin/index.php';
            break;
        case 'elggadmin::theme':
            $url = get_url(null, 'elggadmin::') . '/theme';
            break;
        case 'elggadmin::frontpage':
            $url = get_url(null, 'elggadmin::') . '/frontpage';
            break;
        case 'elggadmin::logs':
            $url = get_url(null, 'elggadmin::') . '/logs';
            break;

    }

    return $url;
}

?>
