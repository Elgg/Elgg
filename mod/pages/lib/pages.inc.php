<?php
/**
 * Custom Pages plugin functions
 * $id$
 *
 * @copyright Copyright (c) 2007 Pro Soft Resources Inc. http://www.prosoftpeople.com
 * @author Rolando Espinoza La Fuente <rho@prosoftpeople.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @todo documentation
 */

function pages_default($owner=-1) {
    if ($owner > 0) {
        $page = user_flag_get('pages_default', $owner);
        if (empty($pagename)) $page = __gettext('Main');
    } else {
        $obj = get_config('pages_default');
        $page = intval($obj->value);
    }

    return $page;
}

function pages_actions() {
    global $CFG, $messages;

    $action = optional_param('action');
    $do_action = optional_param('do');
    $owner = (int)page_owner();
    $owner = ($owner > 0) ? $owner : -1;

    if ((defined('context') && context == 'pages' || defined('pages_external')) && $action == 'pages:edit' && $do_action == 'edit' && permissions_check('pages::edit', $owner)) {
        $submit = optional_param('submit');
        // get page id
        $page_id = optional_param('page');

        if ($submit == __gettext('Save page')) {
            // default values
            $page = pages_input_override(null, $owner);
            // set owner

            // if noerrors
            if (empty($messages)) {
                if ($page->name == 'New page' || $page->title == 'New page') {
                    $messages[] = __gettext('You cannot use "New page" as page title or menu title.');
                } 
                
                // name record exists?
                if ($test = get_record_sql("SELECT ident FROM {$CFG->prefix}pages WHERE name = ? AND owner=?", array($page->name, $owner))) {
                    if (empty($page->ident) || $test->ident != $page->ident) {
                        $messages[] = __gettext('Menu title not available, please choose another.');
                    } else {
                        // update current record uri
                        if (empty($page->uri)) {
                            $page->uri = pages_build_uri($page->name);
                        }

                        if (empty($page->uri)) {
                            $messages[] = __gettext('Menu title not available, please choose another.');
                        } elseif ($test = get_record_sql("SELECT ident FROM {$CFG->prefix}pages WHERE uri = ? AND owner=?", array($page->uri, $owner))) {
                            if (empty($page->ident) || $test->ident != $page->ident) {
                                $messages[] = __gettext('Menu title not available, please choose another.');
                            }
                        }
                    }
                } else {
                    // new uri
                    if (empty($page->uri)) {
                        $page->uri = pages_build_uri($page->name);
                    }

                    if (empty($page->uri)) {
                        $messages[] = __gettext('Menu title not available, please choose another.');
                    } elseif ($test = get_record_sql("SELECT ident FROM {$CFG->prefix}pages WHERE uri = ? AND owner=?", array($page->uri, $owner))) {
                        if (empty($page->ident) || $test->ident != $page->ident) {
                            $messages[] = __gettext('Menu title not available, please choose another.');
                        }
                    }
                }

                if ($page->parent > 0 && !pages_exists((int)$page->parent, $owner)) {
                    $messages[] = __gettext('Invalid parent menu.');
                }
            }

            if (empty($messages)) {
                // set page owner if empty
                if (empty($page->owner)) {
                    $page->owner = $owner;
                }

                // let's insert the data
                if (empty($page->ident)) {
                    $rs = insert_record('pages', $page);
                    if ($rs) {
                        $messages[] = __gettext('Page created successful');
                        $page->ident = intval($rs);
                    } else {
                        $messages[] = __gettext('Error creating new page. Please try again.');
                        $page->ident = null;
                    }
                } else {
                    // last chance to verify integrity
                    if (!pages_exists((int)$page->ident, $owner)) {
                        $messages[] = __gettext('Error on update. That page does not exist.');
                        $rs = true;
                    } else {
                        $oldparent = get_field('pages', 'parent', 'ident', $page->ident);
                        $rs = update_record('pages', $page);
                        if ($rs) {
                            $messages[] = __gettext('Page updated successful');

                            // check parent menu changes
                            // If it's top menu, update childs 
                            if ($page->parent != $oldparent && $oldparent == 0) {
                                // set childs' parent to 0 (top menu)
                                execute_sql("UPDATE {$CFG->prefix}pages SET parent=0 WHERE parent={$page->ident} AND owner={$owner}", false);
                            }
                        } else {
                            $messages[] = __gettext('Error updating the page. Please try again.');
                        }
                    }
                }

                // data inserted/updated?
                if ($rs) {
                    // make default?
                    $default = optional_param('page-default');
                    if ($default) {
                        user_flag_set('pages_default', $page->ident, $owner);
                    }

                    pages_header_redirect(pages_url(intval($page->ident), 'pages::page', $owner));
                }
            }
        }

        if ($submit == __gettext('Delete')) {
            // page exists?
            if ($_page = get_record('pages', 'uri', $page_id, 'owner', $owner)) {
                // delete
                delete_records('pages', 'ident', $_page->ident);

                // update childs
                execute_sql("UPDATE {$CFG->prefix}pages SET parent=0 WHERE parent={$_page->ident} AND owner={$owner}", false);

                $messages[] = sprintf(__gettext('Page %s deleted successful.'), $page_id);
                // redirect
                pages_header_redirect(pages_url($owner, 'pages::'));
            } else {
                trigger_error(__FUNCTION__.": Trying to delete an non-existent page (page id: $page_id, owner: $owner)", E_USER_NOTICE);
            }
        }
    }
}

function pages_input_override($page=null, $owner=-1, $verbose=true) {
    global $CFG, $messages;

    $do_action = optional_param('do');

    if ($do_action == 'edit') {
        $submit = optional_param('submit');

        $title = trim(optional_param('page-title'));
        if (pages_php_allowed()) {
            $content = trim(optional_param('page-content', null, null));
        } else {
            $content = trim(optional_param('page-content'));
        }
        $name = trim(optional_param('page-name'));
        $parent = optional_param('menu-parent', 0, PARAM_INT);
        $weight = optional_param('menu-weight', 0, PARAM_INT);
        $uri = str_replace(' ', '', trim(optional_param('page-uri')));
        $access = optional_param('page-access');

        // try to get from uri query
        if (empty($page)) {
            $page_name = optional_param('page');
            if (!empty($page_name)) {
                //$page = get_record_sql("SELECT * FROM {$CFG->prefix}pages WHERE uri=? ", array($page_name));
                $page = get_record('pages', 'uri', $page_name, 'owner', $owner);
                if (empty($page)) {
                    $new_page_title = str_replace('_', ' ', $page_name);
                    // new page
                    $page = new StdClass;
                    $page->title = $new_page_title;
                    $page->content = pages_html_wrap('p', $new_page_title . __gettext(' Content'));
                    $page->name = $new_page_title;
                }
            } else {
                //  new page?
                //  always there should be page param
                $messages[] = __gettext('Page name empty.');
            }
        }

        // not null
        if (!empty($page)) {
            if (!empty($title)) {
                $page->title = htmlspecialchars_decode($title, ENT_QUOTES);
            } elseif ($verbose) {
                $messages[] = __gettext('Your title was empty.');
            }

            if (!empty($content)) {
                //$page->content = htmlspecialchars_decode($content, ENT_COMPAT);
                $page->content = $content;
            } elseif ($verbose) {
                $messages[] = __gettext('Your body was empty.');
            }

            if (!empty($name)) {
                $textlib = textlib_get_instance();
                $page->name = htmlspecialchars_decode($textlib->substr($name, 0, 127), ENT_QUOTES);
            } elseif ($verbose) {
                $messages[] = __gettext('Your menu title was empty.');
            }

            if (in_array($submit, array(__gettext('Save page'), __gettext('Preview')))) {
                $page->parent = $parent;
                $page->weight = $weight;
                $page->access = $access;
            }

            $page->parent = !isset($page->parent) ? $parent : $page->parent;
            $page->weight = !isset($page->weight) ? $weight : $page->weight;
            $page->access = !isset($page->access) ? $CFG->default_access : $page->access;

            if (!empty($uri)) {
                $page->uri = $uri;
            }

            //workaround
            if ($owner == -1 && pages_is_frontpage($page->name)) {
                $page->parent = -1;
                $page->access = 'PUBLIC';
                $page->owner = -1;
            }
        }
    }

    return $page;
}

function pages_create_page($page) {
    global $messages;
    if (!is_object($page)) {
        trigger_error(__FUNCTION__.": invalid argument (page: is not an object)", E_USER_ERROR);
    }

    if (empty($page->title) || empty($page->content)) {
        trigger_error(__FUNCTION__.": invalid argument (page title or content empty)", E_USER_ERROR);
    }

    if (empty($page->name)) {
        $page->name = $page->title;
    }

    if (empty($page->uri)) {
        $page->uri = pages_build_uri($page->name);
    }

    $rs = insert_record('pages', $page);
    if ($rs) {
        $page->ident = $rs;
        return $page;
    } else {
        return false; 
    }
}

function pages_get_page($page_name, $owner=-1) {

    if (empty($page_name)) {
        if ($owner > 0) {
            $page_id = (int) user_flag_get('pages_default', $owner);
        } else {
            $obj = get_config('pages_default');
            $page_id = intval($obj->value);
        }
        //TODO: do header redirect
        if (pages_exists($page_id, $owner)) {
            $page_url = pages_url($page_id, 'pages::page', $owner);
        } else {
            $page_url = pages_url(__gettext('Main'), 'pages::edit', $owner);
        }

        pages_header_redirect($page_url, 301);
    } else {
        // remove trailing slash
        $page_name = preg_replace('|(/)$|', '', $page_name);
    }

    $page = get_record('pages', 'uri', $page_name, 'owner', $owner);

    //FIXME: auto correct parent
    if ($page->ident == $page->parent) {
        $_page = new StdClass;
        $_page->ident = $page->ident;
        $_page->parent = 0;
        update_record('pages', $_page);
        unset($_page);
    }

    if (isset($page->ident) && permissions_check('pages::access', $page->ident) || permissions_check('pages::edit', $owner)) {
        if (isset($page->content)) {
            pages_current_page($page);
        } else {
            // check for legacy content pages
            if ($owner == -1) {
                $page = pages_get_legacy($page_name);
            }

            if (empty($page)) {
                // Page not found
                $page = pages_page_not_found();
            }
        }
    } else {
        if (isset($page->ident)) { // page exists?
            $page = pages_page_denied();
        } else {
            // check for legacy content pages
            if ($owner == -1) {
                $page = pages_get_legacy($page_name);
            }

            if (empty($page)) {
                // Page not found
                $page = pages_page_not_found();
            }
        }
    }

    if (!empty($page->title)) $page->title = pages_process_content($page->title);
    if (!empty($page->content)) $page->content = pages_process_content($page->content);

    return $page;
}

function pages_get_legacy($page_name) {
    global $CFG, $PAGE;

    if (isset($PAGE->pages->old_compat[$page_name])) {
        $page = new StdClass;
        $page->title = $PAGE->pages->old_compat[$page_name]['title'];
        $page->content = run($PAGE->pages->old_compat[$page_name]['function']);
        // strip content title
        $page->content = preg_replace('|^<h1>(.+)</h1>|', '', $page->content);
        $page->name = $page->title;
    } else {
        $page = null;
    }

    return $page;
}

function pages_process_content($content) {
    // execute php code
    if (pages_php_allowed()) {
        $content = pages_eval($content);
    }

    // parse elgg template keywords
    if (PAGES_PARSE_KEYWORDS) {
        // wrap into templates_draw
        global $template;
        $tmp_name = 'pages:' . mt_rand();
        $template[$tmp_name] = $content;

        // replace all keywords
        $content = templates_draw(array('context' => $tmp_name));

        // clear template
        unset($template[$tmp_name]);
    }

    // process with blog filters
    if (PAGES_BLOG_TEXTPROC) {
        $content = run('weblogs:text:process', $content);
    }

    return $content;
}

function pages_is_frontpage($page_name) {
    if ($page_name == 'frontpage_loggedin' || $page_name == 'frontpage_loggedout') {
        return true;
    } else {
        return false;
    }
}

function pages_is_editing() {
    global $PAGE;

    return isset($PAGE->pages->editing);
}

function pages_edit_page($page_name, $owner=-1) {
    global $CFG, $PAGE;

    $PAGE->pages->editing = true;

    if (pages_enabled() && !empty($page_name) && permissions_check('pages::edit', $owner)) {
        if ($owner == -1 && pages_is_frontpage($page_name)) {
            $is_frontpage = true;
        } else {
            $is_frontpage = false;
        }

        if (!$page = get_record('pages', 'uri', $page_name, 'owner', $owner)) {
            // try to edit legacy content
            if ($owner == -1) {
                $page = pages_get_legacy($page_name);
            }
        } else {
            pages_current_page($page);
        }

        $page = pages_input_override($page, $owner, false);

        if (isset($page->ident)) {
            $page->ident = intval($page->ident);
        }

        //if frontpage force some value
        if ($is_frontpage) {
            $page->name = $page_name;
            $page->parent = -1; //hidden
            $page->access = 'PUBLIC'; //force public
        } 

        $title_enc = htmlspecialchars($page->title, ENT_QUOTES, 'utf-8');
        //$page->content = htmlspecialchars(stripslashes($page->content), ENT_COMPAT, 'utf-8');
        $page->content = trim($page->content);
        $page->name = htmlspecialchars($page->name, ENT_QUOTES, 'utf-8');

        $input_title = pages_html_wrap('label', __gettext('Title:'), array('for' => 'page-title'));
        $input_title .= pages_html_input(
                        'text',
                        array(
                            'id' => 'page-title',
                            'name' => 'page-title',
                            'value' => $title_enc,
                            'maxlength' => 127,
                            'style' => 'width:100%;',
                            )
                        );

        $input_content = pages_html_wrap('label', __gettext('Content:'), array('for' => 'page-content'));
        $input_content .= pages_html_wrap(
                        'textarea', htmlspecialchars($page->content, ENT_NOQUOTES, 'utf-8'), // prevent parsing html tags like <textarea>
                        array(
                            'id' => 'page-content',
                            'name' => 'page-content',
                            'rows'=>20,
                            'cols'=>'79',
                            'style' => 'width:100%;',
                        ));

        if ($is_frontpage) {
            $input_menu = pages_html_input('hidden', array('name'=>'page-name', 'value'=>stripslashes($page->name)));
            $input_default = null;
            $input_parent = pages_html_input('hidden', array('name'=>'menu-parent', 'value'=>-1));
            $input_weight = null;
            $input_access = pages_html_input('hidden', array('name'=>'page-access', 'value'=>'PUBLIC'));
        } else {
            $input_menu = pages_html_wrap('label', __gettext('Menu title:'), array('for' => 'page-name'));
            $input_menu .= pages_html_input(
                            'text',
                            array(
                                'id' => 'page-name',
                                'name' => 'page-name',
                                'value' => stripslashes($page->name),
                                'maxlength' => 127,
                                )
                            );

            if (!isset($page->ident) && $page_name == __gettext('Main')) {
                $page_default = 'checked';
            } else {
                $page_default = pages_is_default(isset($page->ident) ? $page->ident : 0, $owner) ? 'checked' : 'dummy';
            }

            if ($page_default == 'dummy') {
                $input_default = pages_html_wrap('label', __gettext('Make default:'), array('for' => 'page-default'));
                $input_default .= pages_html_input('checkbox', array('id' => 'page-default', 'name' => 'page-default', $page_default => $page_default));
            } else {
                // hidden checked input
                $input_default = pages_html_input('hidden', array('name' => 'page-default', 'value' => true));
            }

            $input_parent = pages_html_wrap('label', __gettext('Parent element:'), array('for' => 'menu-parent'));

            if (isset($page->ident)) {
                $menu_parents = get_records_select('pages', 'parent=? AND ident<>? AND owner=?', array(0, $page->ident, $owner));
            } else {
                $menu_parents = get_records_select('pages', 'parent=? AND owner=?', array(0, $owner));
            }
            $menu_parents_opts = array();
            $menu_parents_opts[] = (object) array('label' =>  __gettext('Top menu'), 'value' => 0);

            if (is_array($menu_parents)) {
                foreach ($menu_parents as $m) {
                    $opt = new StdClass;
                    $opt->value = $m->ident;
                    $opt->label = '- ' . $m->name;

                    if ($m->ident == $page->parent) {
                        $opt->selected = true;
                    }

                    $menu_parents_opts[] = $opt;
                }
            }

            $input_parent .= pages_html_select('menu-parent', $menu_parents_opts, array('id' => 'menu-parent'));

            $input_weight = pages_html_wrap('label', __gettext('Weight:'), array('for' => 'menu-weight'));

            $weights = array();
            for ($i=-10; $i<=10; $i++) {
                $w = (object) array('label'=>" $i", 'value'=>$i);
                if ($page->weight == $i) {
                    $w->selected = true;
                }

                $weights[] = $w;
            }

            $input_weight .= pages_html_select('menu-weight', $weights, array('id' => 'menu-weight'));

            // access level
            $input_access = pages_html_wrap('label', __gettext('Access:'), array('for' => 'page-access'));
            $input_access .= run('display:access_level_select', array('page-access', $page->access));
        }

        $input_uri  = pages_html_wrap('label', __gettext('URI:'), array('for' => 'page-uri'));
        $input_uri .= pages_html_input('text', array('name' => 'page-uri', 'value' => $page->uri));
        $input_uri .= pages_html_wrap('span', __gettext('Optional'));

        // help
        $form_help = __gettext('You can use {{page}} keyword to link to others pages.');
        $form_help .= '<br />&raquo; ';
        $form_help .= __gettext('<em>{{page:About_me}}</em> will link to your page with menu title "About me". e.g. {{page:Main}}');
        $form_help .= '<br />&raquo; ';
        $form_help .= __gettext('<em>{{page:user:Title}}</em> will link to user\'s page "Title". e.g. {{page:news:Main}}');
        $form_help .= '<br />&raquo; ';
        $form_help .= __gettext('<em>{{page:content:Title}}</em> will link to site main pages "Title". e.g. {{page:content:About}}');
        $form_help  = pages_html_wrap('p', $form_help);

        $input_buttons = pages_html_input('submit', array(
                                            'id' => 'page-save',
                                            'name' => 'submit',
                                            'value' => __gettext('Save page'),
                                            )
                                        );

        $input_buttons .= pages_html_input('submit', array(
                                            'id' => 'page-preview',
                                            'name' => 'submit',
                                            'value' => __gettext('Preview'),
                                            )
                                        );

        if ($page->name != 'New page' && !$is_frontpage) {
            $input_buttons .= pages_html_input('submit', array(
                                                'id' => 'page-delete',
                                                'name' => 'submit',
                                                'value' => __gettext('Delete'),
                                                'onclick' => 'return confirm_delete()',
                                                )
                                            );

            $confirm_delete = __gettext('Are you sure that you want to delete this page?');
            $input_buttons .= pages_html_wrap('script', "
               <!--
                function confirm_delete() {
                    return confirm('{$confirm_delete}');
                }
                --> 
                ", array('type' => 'text/javascript'
                    )
                );
        }

        // hidden action
        $input_buttons .= pages_html_input('hidden', array('name' => 'action', 'value' => 'pages:edit'));

        $content = pages_html_wrap('div', $input_title, array('class' => 'form-item'));
        $content .= pages_html_wrap('div', $input_content, array('class' => 'form-item'));
        $content .= pages_html_wrap('div', $form_help, array('class' => 'form-item'));
        $content .= pages_html_wrap('div', $input_menu . $input_default, array('class' => 'form-item'));
        $content .= pages_html_wrap('div', $input_parent . $input_weight, array('class' => 'form-item'));
        $content .= pages_html_wrap('div', $input_access, array('class' => 'form-item'));
        $content .= pages_html_wrap('div', $input_uri, array('class' => 'form-item'));
        $content .= pages_html_wrap('div', $input_buttons, array('class' => 'form-item form-button'));

        $content = pages_html_wrap('form', $content, array(
                                            'id' => 'page-form',
                                            'method' => 'post',
                                            'action' => $_SERVER['REQUEST_URI'],
                                            )
                                        );

        // check for preview action 
        if (optional_param('submit') == __gettext('Preview')) {
            $content = pages_preview_page($page) . $content;
        }

        // override content 
        $page->content = $content;
    } else {
        $page = pages_page_denied();
    }
    
    if (empty($page)) {
        $page = pages_page_not_found();
    }

    return $page;
}

function pages_preview_page($page) {
    $title = pages_html_wrap('h1', __gettext('Preview: ') . $page->title);
    $title = pages_process_content($page->title);
    $content = pages_process_content($page->content);

    $preview_content = pages_html_wrap('div', $title . $content, array('id' => 'page-preview'));

    return $preview_content;
}

function pages_is_default($page_id, $owner=-1) {
    if ($owner > 0) {
        $default = user_flag_get('pages_default', $owner);
    } else {
        $obj = get_config('pages_default');
        $default = isset($obj->value) ? $obj->value : ('Main');
    }
    return $page_id == $default;
}

function pages_page_not_found() {
    pages_header_status(404);

    $page = new StdClass;
    $page->title = __gettext('Page Not Found');
    $page->content = pages_html_wrap('p', __gettext('The page that you requested does not exist.'));

    return $page;
}

function pages_page_denied() {
    pages_header_status(503);

    $page = new StdClass;
    $page->title = __gettext('Access denied');
    $page->content = pages_html_wrap('p', __gettext('You do not have access to this page'));

    return $page;
}

function pages_exists($pid, $owner) {
    if (is_int($pid)) {
        return record_exists('pages', 'ident', $pid, 'owner', $owner);
    }else {
        return record_exists('pages', 'uri', $pid, 'owner', $owner);
    }
}

function pages_header_redirect($url, $status=null) {
    global $messages;
    // save messages into session
    $_SESSION['messages'] = $messages;

    if (!empty($status)) {
        pages_header_status($status);
    }

    header('Location: ' . $url);
    exit();
}

function pages_header_status($status) {
    // TODO: add more status
    switch ($status) {
        case '301':
            $http_status = 'HTTP/1.1 301 Moved Permanently';
            break;
        case '404':
            $http_status = 'HTTP/1.0 404 Not Found';
            break;
        case '403':
            $http_status = 'HTTP/1.1 403 Access Denied';
            break;
        default:
            $http_status = 'HTTP/1.1 ' . $status;
            break;
    }

    // silence warnings
    @header($http_status);
}

function pages_eval($code) {
    ob_start();
    echo eval('?>' . $code);
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

?>
