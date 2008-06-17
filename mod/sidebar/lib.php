<?php
/**
 * Sidebar plugin
 * $id$
 *
 * @copyright Copyright (c) 2007 Pro Soft Resources Inc. http://www.prosoftpeople.com
 * @author Rolando Espinoza La fuente <rho@prosoftpeople.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * Plugin initialization
 */
function sidebar_init() {
    global $CFG;
    global $function;
    global $PAGE;

    if (!isset($PAGE->sidebar)) {
        $PAGE->sidebar = array();
    }

    // base path
    $base_path = $CFG->dirroot . 'mod/sidebar/';

    //templates
    $function['init'][] = $base_path . 'default_templates.php';
    // use details, enable/disable sidebar blocks
    $function['userdetails:edit:details'][] = $base_path . 'lib/userdetails_edit.php';

    // legacy sidebar elements
    $PAGE->sidebar_legacy = array();

    $PAGE->sidebar_legacy['sidebar-profile'] = array(10, true);
    $function['sidebar-profile'][] = $CFG->dirroot . 'mod/display/lib/function_log_on_pane.php';

    $PAGE->sidebar_legacy['sidebar-stats'] = 50;
    $function['sidebar-stats'][] = $CFG->dirroot . 'mod/users/lib/function_number_of_users.php';

    $PAGE->sidebar_legacy['sidebar-blog'] = array(30, true);
    $function['sidebar-blog'][] = $CFG->dirroot . 'mod/blog/lib/weblogs_user_info_menu.php';

    $PAGE->sidebar_legacy['sidebar-friends'] = array(40, true);
    $function['sidebar-friends'][] = $CFG->dirroot . 'mod/friend/lib/profile_friends.php';

    $PAGE->sidebar_legacy['sidebar-communities'] = array(50, true);
    $function['sidebar-communities'][] = $CFG->dirroot . 'mod/community/lib/community_memberships.php';

    $PAGE->sidebar_legacy['sidebar-owned-communities'] = array(60, true);
    $function['sidebar-owned-communities'][] = $CFG->dirroot . 'mod/community/lib/communities_owned.php';

    $PAGE->sidebar_legacy['sidebar-files'] = array(60, true);
    $function['sidebar-files'][] = $CFG->dirroot . 'mod/file/lib/files_user_info_menu.php';

    // others plugins
    if (is_readable($CFG->dirroot . 'mod/category/category_sidebar.php')) {
        $PAGE->sidebar_legacy['sidebar-blogcats'] = array(30, true);
        $function['sidebar-blogcats'][] = $CFG->dirroot . 'mod/category/category_sidebar.php';
    }

    if (is_readable($CFG->dirroot . 'mod/vanillaforum/lib/user_info_menu.php')) {
        $PAGE->sidebar_legacy['sidebar-vanilla'] = 25;
        $function['sidebar-vanilla'][] = $CFG->dirroot . 'mod/vanillaforum/lib/user_info_menu.php';
    }

}

/**
 * Plugin page setup
 */
function sidebar_pagesetup() {
    global $function;
    global $PAGE;

    $runned = array();
    // legacy sidebar blocks
    if (is_array($PAGE->sidebar_legacy)) {
        foreach ($PAGE->sidebar_legacy as $key => $block) {
            if (is_array($block)) {
                $weight = $block[0];
                $userdetails = $block[1];
            } else {
                $weight = $block;
                $userdetails = false;
            }
            $label = str_replace('sidebar', '', str_replace('-', ' ', $key));
            sidebar_add($weight, $key, sidebar_legacy_wrap(run($key)), true, $label);
            $runned[] = $function[$key];
            //print_object($key);
        }
    }

    // filter legacy sidebar code already included
    if (isset($function['display:sidebar'])) {
        foreach ($function['display:sidebar'] as $n => $script) {
            if (!in_arrayr($script, $runned)) {
                // temp function()
                $function["sidebar:$n"][] = $script;
                // include as generic block
                sidebar_add(99, "sidebar-$n", sidebar_legacy_wrap(run("sidebar:$n")));
                // unset
                unset($function["sidebar:$n"]);
            }
        }
        //print_object($runned);
    }

    // replace old code
    $function['display:sidebar'] = array(dirname(__FILE__) . '/lib/sidebar_display.php');
}

/**
 * Adds new block to sidebar stack
 *
 * @param integer $weigth the weight/position of the block
 * @param string $id unique block identificator
 * @param bool $userdetails allow users to show/hide (true or false)
 * @param string $label label to show on account settings
 * @param string $class optional css class
 */
function sidebar_add($weight, $id, $body, $userdetails=false, $label=null, $class=null) {
    global $PAGE;

    if (isset($PAGE->sidebar_locked) && !in_array($id, $PAGE->sidebar_locked)) {
        return; // no add anything 
    }

    if (!isset($PAGE->sidebar[$weight])) {
        $PAGE->sidebar[$weight] = array();
    }

    if (!isset($PAGE->sidebar_blacklist)) {
        $PAGE->sidebar_blacklist = array();
    }

    if (!in_array($id, $PAGE->sidebar_blacklist) && (!empty($body) || is_callable($id))) {
        // add sidebar block
        $PAGE->sidebar[$weight][] = array(
            'id'  => $id,
            'class' => $class,
            'body' => $body,
            'userdetails' => $userdetails,
            'label' => $label,
            );
    }
}

/**
 * Removes a block or blocks from sidebar
 *
 * @param mixed $id block identificator to remove, string or array of id's
 * @param bool $overrideall if all blocks will be removed except provided $id 
 */
function sidebar_remove($id, $overrideall=false) {
    global $PAGE;

    if (empty($id)) {
        trigger_error(__FUNCTION__.": invalid argument (id: empty)", E_USER_ERROR);
    }

    if ($overrideall) {
        // clear sidebar
        $block = array();
        if (!is_array($id)) $id = array($id);
        // exists?
        foreach ($PAGE->sidebar as $w => $v) {
            foreach ($v as $e) {
                // found
                if (in_array($e['id'], $id)) {
                    while (isset($block[$w])) {$w++;}
                    $block[$w] = $e;
                }
            }
        }
        if (!empty($block)) {
            unset($PAGE->sidebar);
            foreach ($block as $w => $b) {
                sidebar_add($w, $b['id'], $b['body'], $b['userdetails'], $b['label'], $b['class']);
            }
        }
        $PAGE->sidebar_locked = $id;
        // end
        return;
    }

    if (!isset($PAGE->sidebar_blacklist)) {
        $PAGE->sidebar_blacklist = array();
    }

    if (is_array($id)) {
        foreach ($id as $e) {
            sidebar_remove($e);
        }
    }
    // proceed if not blacklisted before
    elseif (!in_array($id, $PAGE->sidebar_blacklist)) {
        $PAGE->sidebar_blacklist[] = $id;

        // prevent further inclusions
        if (!empty($PAGE->sidebar) && is_array($PAGE->sidebar)) {

            // remove block if exists
            $sidebar = array();

            foreach ($PAGE->sidebar as $w => $blocks) {
                if (!isset($sidebar[$w])) {
                    $sidebar[$w] = array();
                } 

                foreach ($blocks as $block) {
                    if ($block['id'] == $id) {
                        continue;
                    } else {
                        $sidebar[$w][] = $block;
                    }
                }
            }

            // override sidebar
            $PAGE->sidebar = $sidebar;
            if (isset($PAGE->sidebar_locked)) {
                // unlock
                unset($PAGE->sidebar_locked);
            }
        }
    }
}

/**
 * Get all blocks registered
 *
 * @return mixed 
 */
function sidebar_get_blocks() {
    global $PAGE;

    $result = array();
    if (!empty($PAGE->sidebar) && is_array($PAGE->sidebar)) {
        foreach ($PAGE->sidebar as $w => $blocks) {
            foreach ($blocks as $b) {
                if ($b['userdetails'] === true) {
                    $result[] = $b;
                }
            }
        }
    }

    return $result;
}

/**
 * Render sidebar
 *
 * @return string
 */
function sidebar_display() {
    global $PAGE;

    $body = '';

    if (!empty($PAGE->sidebar) && is_array($PAGE->sidebar)) {
        $sidebar = $PAGE->sidebar;

        // sort if needed
        ksort($sidebar);

        foreach ($sidebar as $w => $blocks) {
            foreach ($blocks as $block) {
                if (user_flag_get('sidebar'.$block['id'], page_owner()) != 'no') {
                    // print_object($block['id']);
                    if (empty($block['body']) && is_callable($block['id'])) {
                        // call function that returns sidebar body
                        $block_body = $block['id']();
                    } else {
                        $block_body = $block['body'];
                    }

                    $body .= templates_draw(array(
                        'context' => 'sidebar:block',
                        'id' => $block['id'],
                        'class' => $block['class'],
                        'body' => $block_body, 
                        ));
                }
            }
        }
    }

    return templates_draw(array(
        'context' => 'sidebar:wrap',
        'body' => $body,
    ));
}

/**
 * Removed legacy code from old blocks format
 *
 * @param string $body legacy block
 * @return string stripped body
 */
function sidebar_legacy_wrap($body) {
    // remove <li></li>
    return preg_replace("#^\s*(<li|<li\s+[\"\'\w-_\.\s\=]*\s*)>(.*)</li>\s*$#si", "\${2}", $body);
}

// recursive array search
if (!function_exists('in_arrayr')) {
    function in_arrayr($needle, $haystack) {
        foreach ($haystack as $v) {
            if ($needle == $v) {
                return true;
            } elseif (is_array($v)) {
                if (in_arrayr($needle, $v) === true) {
                    return true;
                }
            }
        }

        return false;
    }
}

?>
