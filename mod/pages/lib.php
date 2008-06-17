<?php
/**
 * Custom Pages plugin main file
 * $id$
 *
 * @copyright Copyright (c) 2007 Pro Soft Resources Inc. http://www.prosoftpeople.com
 * @author Rolando Espinoza La Fuente <rho@prosoftpeople.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @todo documentation
 */

function pages_init() {
    global $CFG;

    require_once($CFG->dirroot . 'mod/pages/lib/pages.config.php');

    pages_dbsetup();
}

function pages_pagesetup() {
    // backward compatibilty
    global $CFG, $PAGE;

    // menu keyword
    $CFG->templates->variables_substitute['pagesmenu'][] = 'pages_tplkw_menu';
    $CFG->templates->variables_substitute['page'][] = 'pages_tplkw_page';
    $CFG->templates->variables_substitute['sysadminemail'][] = 'pages_tplkw_sysadminemail';

    if (defined('context') && context == 'pages' || defined('pages_external')) {
        if (pages_enabled() && permissions_check('pages::edit', page_owner())) {
            $page_name = optional_param('page');
            $do_action = optional_param('do');

            if ($do_action != 'edit') {
                // new page link
                pages_submenu_add('pages:edit', __gettext('New page'), pages_url('New_page', 'pages::edit', page_owner()), 0);
                // edit this page link
                pages_submenu_add('pages:edit', __gettext('Edit this page'), pages_url($page_name, 'pages::edit', page_owner()), 1);
            }
        }
    }

    if (!PAGES_DISABLE_USERS && isloggedin()) {
        pages_menu_add('pages', __gettext('Your Content'), get_url($_SESSION['userid'], 'pages::'));
    }

    if (pages_enabled()) {
        // not show main site pages on sidebar
        sidebar_add(25, 'pages_sidebar', null, true, __gettext('Your Content'));
    }
}

function pages_php_allowed() {
    if (page_owner() == -1
        || PAGES_ALLOW_PHP_USER 
        || (PAGES_ALLOW_PHP_ADMIN && user_flag_get('admin', page_owner())))
    {
        return true;
    } else {
        return false;
    }
}

function pages_permissions_check($type, $ident) {
    $result = false;

    if (isadmin()) {
        return true;
    }

    switch ($type) {
        case 'pages::edit':
            if (pages_enabled() && $ident > 0) {
                $result = run('permissions:check', 'profile');
            } else {
                $result = false;
            }
            break;
        case 'pages::access':
            $access = get_field('pages', 'access', 'ident', $ident);
            $result = run('users:access_level_check', $access);
            break;
    }

    return $result;
}

function pages_dbsetup() {
    global $CFG, $METATABLES;

    if (!in_array($CFG->prefix . 'pages', $METATABLES)) {
        $dbscript = $CFG->dirroot . 'mod/pages/db/' . $CFG->dbtype . '.sql';

        if (is_readable($dbscript)) {
            modify_database($dbscript);

            // pages functions
            require_once(dirname(__FILE__) . '/lib/pages.inc.php');

            // insert first page
            $page = new StdClass;
            $page->name = __gettext('About');
            $page->title = __gettext('About') . " {{sitename}}";
            $page->content = @file_get_contents(dirname(__FILE__).'/legacy/content_about.html');

            $page = pages_create_page($page);

            if ($page) {
                set_config('pages_default', $page->ident);

                $_page = new StdClass;
                $_page->uri = 'privacy.php'; //backward compatibility
                $_page->name = 'privacy.php'; //backward compatibility
                $_page->title = __gettext('Privacy Policy');
                $_page->content = @file_get_contents(dirname(__FILE__).'/legacy/content_privacy.html');
                $_page->parent = $page->ident;
                $_page = pages_create_page($_page);

                $_page = new StdClass;
                $_page->uri = 'terms.php'; //backward compatibility
                $_page->name = 'terms.php'; //backward compatibility
                $_page->title = __gettext('Terms and Conditions');
                $_page->content = @file_get_contents(dirname(__FILE__).'/legacy/content_terms.html');
                $_page->parent = $page->ident;
                $_page = pages_create_page($_page);
            }

            $page = new StdClass;
            $page->name = __gettext('FAQ');
            $page->title = __gettext('Frequently Asked Questions');
            $page->content = @file_get_contents(dirname(__FILE__).'/legacy/content_faq.html');

            $page = pages_create_page($page);

            //reload system
            header_redirect($CFG->wwwroot);

        } else {
            // not supported!
            error('Error: your database (' . $CFG->dbtype . ') is not supported by elgg pages plugin.');
        }

        print_continue('');
        exit();
    }
}

function pages_url($id=null, $type=null, $owner=-1) {
    global $CFG;
    if (!is_numeric($owner)) {
        trigger_error(__FUNCTION__.": invalid argument (owner: $owner)", E_USER_ERROR);
    }

    $url = '#'; //safe value

    if (empty($id)) {
        $id = pages_default($owner);
    }

    if (empty($type)) {
        $type = 'pages::page';
    }


    switch ($type) {
        case 'pages::':
            //id is user ident
            if ($id > 0) {
                $url = get_url($id, 'profile::');
                if (!empty($url)) $url .= 'content/'; // url for user's pages
            } else {
                $url = $CFG->wwwroot . 'content/'; // url for site pages
            }
            break;
        case 'pages::page':
            if (is_string($id)) {
                // get from uri
                $page_name = $id;
            } elseif (is_int($id)) {
                $page_name = get_field('pages', 'uri', 'ident', $id, 'owner', $owner);
                if (empty($page_name)) {
                    trigger_error(__FUNCTION__.": page does not exist (page id: $id, owner: $owner)", E_USER_WARNING);
                    return null;
                }
            } else {
                trigger_error(__FUNCTION__.": invalid argument id.", E_USER_ERROR);
            }

            $url = get_url($owner, 'pages::') . $page_name;
            break;
        case 'pages::edit':
            $url = pages_url($id, 'pages::page', $owner) . '?do=edit';
            break;
    }

    return $url;
}

function pages_tplkw_menu($vars) {
    if (isset($vars[1])) {
        $owner = intval($vars[1]);
    } else {
        $owner = page_owner();
    }
    // force only keyword for site pages
    $owner = -1;

    return pages_html_menu(pages_get_mainmenu($owner));
}

function pages_tplkw_page($vars) {
    global $messages;
    $output = '';

    if (!isset($vars[1])) {
        $msg = "{{page}} keyword error, must provied a page name. e.g. {{page:Main}}";
        if (page_owner() == $_SESSION['userid']) {
            // show error to page owner
            $messages[] = $msg;
        }
        trigger_error($msg, E_USER_WARNING);
    } else {
        if (isset($vars[2])) {
            $page_id = $vars[2];
            $page_name = pages_build_uri($page_id);

            $username = $vars[1];
            // main site content
            if ($username == 'content') {
                $user_id = -1;
            } else {
                $user_id = (int)user_info_username('ident', $username);
            }

            if (empty($user_id)) {
                $msg = "{{page}} keyword error, invalid username: {$username}";
                if (page_owner() == $_SESSION['userid']) {
                    // show error to page owner
                    $messages[] = $msg;
                }
                trigger_error($msg, E_USER_WARNING);
            } else {
                $page_url = pages_url($page_name, 'pages::page', $user_id);
            }
        } else {
            $page_id = $vars[1];
            $page_name = pages_build_uri($page_id);
            $page_url = pages_url($page_name, 'pages::page', page_owner());
        }

        // return html link
        if (isset($page_url)) {
            $output =  pages_html_a($page_url, $page_id);
        }
    }

    return $output;
}

function pages_tplkw_sysadminemail() {
    global $CFG;
    return $CFG->sysadminemail;
}

function pages_frontpage($logged=false) {
    require_once(dirname(__FILE__) . '/lib/pages.inc.php');
    if ($logged) {
        $context = 'frontpage_loggedin';
        
        if (isadmin()) {
            pages_submenu_add('pages:edit_in', __gettext('Edit frontpage_loggedin'), pages_url('frontpage_loggedin', 'pages::edit', -1));
            pages_submenu_add('pages:edit_out', __gettext('Edit frontpage_loggedout'), pages_url('frontpage_loggedout', 'pages::edit', -1));
        }
    } else {
        $context = 'frontpage_loggedout';
    }
    return pages_get_page($context, -1);
}

function pages_sidebar() {
    global $CFG, $page_owner;

    $owner = page_owner();

    $menu_elements = pages_get_mainmenu($owner);

    if ($owner > 0) {
        if ($_SESSION['userid'] == $owner) {
            $title = __gettext('Your Content');
        }else {
            //$title = sprintf(__gettext("%s's pages"), htmlspecialchars(user_name($owner), ENT_COMPAT, 'utf-8'));
            $title = __gettext("Content");
        }
    } else {
        $title = $CFG->sitename;
    }
    
    $body = templates_draw(array(
        'context' => 'sidebarholder',
        'title' => $title,
        'body' => pages_html_menu($menu_elements),
        ));

    return $body;
}

function pages_enabled() {
    if (page_owner() == -1 ||
       (page_owner() > 0 && !PAGES_DISABLE_USERS) ) {
        return true;
    } else {
        return false;
    }
}

function pages_get_mainmenu($owner=-1) {
    global $CFG;
    if (!is_numeric($owner)) {
        trigger_error(__FUNCTION__.": invalid argument (owner: $owner)");
    }

    $elements = get_records_sql("SELECT ident,parent,name,uri,weight,owner,access FROM {$CFG->prefix}pages WHERE owner=? AND parent>=0 ORDER BY parent,weight,name", array($owner));

    $menu = array();
    if (is_array($elements)) {
        foreach ($elements as $e) {

            // check access
            if (!pages_permissions_check('pages::access', $e->ident)) {
               continue;
            }

            //FIXME: fix parent
            if ($e->ident == $e->parent) {
                $e->parent = 0;
            }

            if ($e->parent == 0) {
                if (!isset($menu[$e->weight])) {
                    $menu[$e->ident] = array();
                }
                $menu[$e->ident][0] = $e;
            } else {
                if (!isset($menu[$e->parent][1])) {
                    $menu[$e->parent][1] = array();
                } 
                $menu[$e->parent][1][] = $e;
            }
        }
    }

    return $menu;
}

function pages_html_menu($elements) {
    $menu = '';
    global $page_owner;

    if (is_array($elements)) {
        $current_page = pages_current_page();
        $current_uri = !empty($current_page->uri) ? $current_page->uri : '';

        foreach ($elements as $p => $li) {
            if (is_array($li)) {
                $parent = $li[0];
                $childs = isset($li[1]) ? $li[1] : null;

                // workaround
                if (!$parent) {
                    // this never should happen
                    trigger_error(__FUNCTION__.": invalid menu element", E_USER_NOTICE);
                    continue;
                }

                // set owner
                if (!isset($owner)) $owner = $parent->owner;

                if ($parent->uri == $current_uri) {
                    $parent_link = pages_html_a(pages_url($parent->uri, 'pages::page', $parent->owner), $parent->name, array('class' => 'selected'));
                } else {
                    $parent_link = pages_html_a(pages_url($parent->uri, 'pages::page', $parent->owner), $parent->name);
                }

                if (is_array($childs)) {
                    $submenu = '';
                    foreach ($childs as $child) {
                        if ($child->uri == $current_uri) {
                            $_li = pages_html_a(pages_url($child->uri, 'pages::page', $child->owner), $child->name, array('class' => 'selected'));
                        } else {
                            $_li = pages_html_a(pages_url($child->uri, 'pages::page', $child->owner), $child->name);
                        }
                        $submenu .= pages_html_wrap('li', $_li, array('class' => 'menu-item'));
                    }

                    $menu .= pages_html_wrap('li', $parent_link . pages_html_wrap('ul', $submenu), array('class' => 'menu-parent'));
                } else {
                    $menu .= pages_html_wrap('li', $parent_link, array('class' => 'menu-item'));
                }
            } else {
            }
        }
    }

    // workaround
    if (!isset($owner)) $owner = empty($page_owner) ? page_owner() : $page_owner;

    if (empty($menu)) {
        $main_link = pages_html_a(pages_url(__gettext('Main'), 'pages::page', $owner), __gettext('Main'));
        $menu .= pages_html_wrap('li', $main_link, array('class' => 'menu-item'));
    }

    if (!empty($menu)) {
        $menu = pages_html_wrap('ul', $menu);
    }

    return $menu;
}

function pages_current_page($page=null) {
    global $PAGE;

    if (!is_null($page)) {
        $PAGE->pages->current = $page;
    }

    if (!isset($PAGE->pages->current)) {
        $PAGE->pages->current = null;
    }

    return $PAGE->pages->current;
}

function pages_html_wrap($tag, $content=null, $attrs=null) {
    if (is_array($attrs)) {
        $extra = pages_html_array2attrs($attrs);
    } else {
        $extra = '';
    }

    if (isset($content)) {
        $result = "<{$tag}{$extra}>{$content}</{$tag}>\n";
    } else {
        $result = "\n<{$tag} {$extra} />\n";
    }

    return $result;
}

function pages_html_a($url, $title, $attrs=null) {
    // Default attrs
    $extra = array(
        'title' => $title,
        'href' => $url,
        );

    if (is_array($attrs)) {
        $extra = array_merge($extra, $attrs);
    }

    return pages_html_wrap('a', htmlspecialchars($title, ENT_QUOTES, 'utf-8'), $extra);
}

function pages_html_input($type, $attrs=null) {
    if (!isset($attrs)) $attrs = array();

    $attrs = array_merge(array('type' => $type), $attrs);

    return pages_html_wrap('input', null, $attrs);
}

function pages_html_form($name, $body, $attrs=null) {
    if (!isset($attrs)) {$attrs = array();}

    if (!isset($attrs['id'])) { $attrs['id'] = $name; }
    if (!isset($attrs['name'])) { $attrs['name'] = $name; }
    if (!isset($attrs['method'])) { $attrs['method'] = 'post'; }
    if (!isset($attrs['action'])) { $attrs['action'] = ''; }

    if (isset($attrs['buttons'])) {
        $buttons = $attrs['buttons'];
    } else {
        $buttons = pages_html_input('submit', array('value' => __gettext('Submit')));
    }

    // add form key
    $buttons .= pages_html_input('hidden', array('name'=>'form_key', 'value'=>elggform_key_get($name)));

    $body .= pages_html_wrap('div', $buttons, array('class' => 'form-buttons'));

    return pages_html_wrap('form', $body, $attrs);
}

function pages_html_select($name, $options, $attrs=null) {
    if (!isset($attrs)) $attrs = array();

    $opts = '';
    foreach ($options as $opt) {
        $opt_attr = array('value' => addslashes($opt->value));

        if (isset($opt->selected)) {
            $opt_attr['selected'] = 'selected';
        }

        $opts .= pages_html_wrap('option', htmlspecialchars($opt->label, ENT_COMPAT, 'utf-8'), $opt_attr);
    }

    $attrs = array_merge(array('name' => $name), $attrs);

    return pages_html_wrap('select', $opts, $attrs);
}

function pages_html_array2attrs($props) {
    global $db;

    $result = '';

    foreach ($props as $prop => $val) {
        $result .= " {$prop}=" . $db->qstr($val, true);
    }

    return $result;
}

function pages_is_submitted() {
    $result = false;

    if (isset($_POST['submit']) || strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        $result = true;
    }

    return $result;
}

// should this go on templates lib?
function pages_menu_add($name, $title, $url, $weight=10) {
    global $PAGE;

    $clean_name = 'menu_'.clean_filename($name);
    $link = pages_html_a($url, $title);
    
    $menu = array(
        'name' => $name,
        'html' => pages_html_wrap('li', $link, array('class'=>$clean_name)),
        );

    array_insert($PAGE->menu, $weight, array($menu));
}

function pages_submenu_add($name, $title, $url, $weight=10) {
    global $PAGE;

    $clean_name = 'submenu_'.clean_filename($name);
    $link = pages_html_a($url, $title, array('class' => $clean_name));
    
    $menu_sub  = array(
        'name' => $name,
        'html' => $link,
        );

    array_insert($PAGE->menu_sub, $weight, array($menu_sub));
}

function pages_build_uri($title) {
    // Use elgg's textlib functions
    $textlib = textlib_get_instance();

    $title = strip_tags($title);
    // Preserve escaped octets.
    //$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
    // Remove percent signs that are not part of an octet.
    //$title = str_replace('%', '', $title);
    // Restore octets.
    //$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

    //$title = $textlib->strtolower($title, 'utf-8');
    $title = pages_remove_accents($title);
    $title = preg_replace('/&.+?;/', '', $title); // kill entities
    //$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
    $title = preg_replace('/[^A-Za-z0-9,;:\]\[\(\)\. _-]/', '', $title);
    $title = preg_replace('/[\s,;:\]\[\(\)]+/', '_', $title);
    $title = preg_replace('/\.+$|^\.+/', '', $title);
    $title = preg_replace('/\.+-|-\.+/', '_', $title);
    $title = preg_replace('|-+|', '_', $title);
    $title = trim($title, '_');

    return $textlib->substr($title, 0, 127);
}

function pages_remove_accents($string) {
    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
    // Euro Sign
    chr(226).chr(130).chr(172) => 'E');
    
    $string = strtr($string, $chars);
    return $string;
}

if (!function_exists('array_insert')) {

    function array_insert(&$array, $pos, $insert_array) {
        if (!is_int($pos)) {
                $i = 0;
                foreach ($array as $k => $v) {
                    if ($k == $pos) {
                                    $pos = $i;
                                    break;
                                }
                            $i++;
                        }
            }
    
        $first_array = array_splice($array, 0, $pos);
        $array = array_merge($first_array, $insert_array, $array);
    }
}

if (!function_exists('html_entity_decode')) {
    function html_entity_decode($string) {
        // replace numeric entities
        $string = preg_replace('/&#x([0-9a-f]+);/ei', 'chr(hexdec("\\1"))', $string);
        $string = preg_replace('/&#([0-9]+);/e', 'char("\\1")', $string);
        // replace literal entities
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        return strtr($string, $trans_tbl);

    }
}
?>
