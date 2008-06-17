<?php
/**
 * Elgg administrator plugin
 *
 * @copyright Copyright (c) 2007 Pro Soft Resources Inc. http://www.prosoftpeople.com
 * @author Rolando Espinoza La Fuente <rho@prosoftpeople.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

function elggadmin_actions() {
    // do actions
    $page = elggadmin_currentpage();

    if (pages_is_submitted()) {
        elggadmin_save($page);
    }
}

function elggadmin_page($page_name=null) {
    // this must go somewhere else!
    global $metatags;
    $metatags .= "\n<style type=\"text/css\">\n";
    $metatags .= file_get_contents(dirname(__FILE__).'/../elggadmin.css');
    $metatags .= "</style>";

    if ($page_name == 'theme') {
        $page = elggadmin_page_theme();
        elggadmin_currentpage('theme');
    } elseif ($page_name == 'frontpage') {
        $page = elggadmin_page_frontpage();
        elggadmin_currentpage('frontpage');
    } elseif ($page_name == 'logs') {
        $page = elggadmin_page_logs();
        elggadmin_currentpage('logs');
    } else {
        $page = elggadmin_page_config();
        elggadmin_currentpage('config');
    }

    // add global title
    $page->title = __gettext('Administration') . ' :: ' . $page->title;

    return $page;

}

function elggadmin_page_before($c=null, $args=null) {
    require_login('admin');
    if (!defined('context')) { context('elggadmin'); }
    $page = (isset($args[1])) ? $args[1] : 'config';
    elggadmin_currentpage($page);
}

function elggadmin_sidebar() {
    $output = '';
    if (elggadmin_currentpage() == 'frontpage' || elggadmin_currentpage() == 'theme') {
        $output .= "<h2>" . __gettext("Special keywords") . "</h2>";
        $output .= "<p>" . __gettext("You can insert these into your pageshell for special functionality:") . "</p>";
        $output .= "<p><b>{{url}}</b> " . __gettext("The address of your site.") . "</p>";
        $output .= "<p><b>{{sitename}}</b> " . __gettext("The name of your site.") . "</p>";
        $output .= "<p><b>{{tagline}}</b> " . __gettext("Your site's tagline.") . "</p>";
        $output .= "<p><b>{{username}}</b> " . __gettext("The current user's username.") . "</p>";
        $output .= "<p><b>{{userfullname}}</b> " . __gettext("The current user's full name.") . "</p>";
        $output .= "<p><b>{{populartags}}</b> " . __gettext("A list of the most popular tags.") . "</p>";
        $output .= "<p><b>{{randomusers}}</b> " . __gettext("A list of random users who have filled in their profiles, if some exist.") . "</p>";
        $output .= "<p><b>{{people:interests:foo:5}}</b> " . __gettext("Lists five people interested in 'foo' in a horizontal table.") . "</p>";
        $output .= "<p><b>{{toptags:town}}</b> " . __gettext("Lists the top tags of type 'town' (or select weblog, file or the profile field of your choice).") . "</p>";
    }

    return $output;
}

function elggadmin_save($page) {
    global $CFG;

    $sucess = false;
    $action = optional_param('action');

    switch ($page) {
        case 'config':
            switch ($action) {

            case 'elggadmin:config:restore':
                break;
            case 'elggadmin:config':
                $defcfg = elggadmin_get_defconfig();
                $allowed = array();
                foreach ($defcfg as $name => $obj) {
                    if (!isset($obj->noteditable)) {
                        $allowed[$name] = $obj;
                    }
                }

                $config = optional_param('config');
                if (!empty($config)) {
                    foreach ($config as $name => $value) {
                        $value = trim($value);
                        if (array_key_exists($name, $allowed) && (!isset($CFG->$name) || $value != $CFG->$name)) {
                            if (isset($allowed[$name]->important) && $value == '') {
                                elgg_messages_add(sprintf(__gettext('%s could not be empty'), $allowed[$name]->name));
                            } else {
                                if (!isset($allowed[$name]->type)) {
                                    $allowed[$name]->type = null;
                                }
                                $allowempty = false;

                                switch ($allowed[$name]->type) {
                                    case 'bool':
                                    case 'boolean':
                                        $value = (bool)$value;
                                        if (!$value) {
                                            $value = '';
                                        }
                                        $allowempty = true;
                                        break;
                                    case 'int':
                                    case 'integer':
                                    case 'debug':
                                        if (empty($value)) {
                                            $value = 0;
                                        } else {
                                            $value = (int)$value;
                                        }
                                        $value .= '';
                                        $allowempty = true;
                                        break;
                                    case 'access':
                                        $accessvals = array(
                                            'PUBLIC',
                                            'LOGGED_IN',
                                            'PRIVATE',
                                            );
                                        if (!in_array($value, $accessvals)) {
                                            $value = $CFG->default_value;
                                        }
                                        break;
                                    case 'language':
                                        if (!preg_match('!^[a-z]{2,3}(_[A-Z]{2,3})?$!', $value)) {
                                            $value = $CFG->defaultlocale;
                                        }
                                        break;
                                }

                                if (empty($value) && !$allowempty) {
                                    unset_config($name);
                                } else {
                                    set_config($name, $value);
                                }
                                elgg_messages_add(sprintf(__gettext('%s option updated'), $allowed[$name]->name));
                                $sucess = true;
                            }
                        }
                    }

                }
                break;
            default:
                break;
            }
            break;
        case 'theme':
        case 'frontpage':
            if ($page == 'theme') {
                $templates = array('css', 'pageshell');
            } else {
                $templates = array('frontpage_loggedin', 'frontpage_loggedout');
            }
            foreach ($templates as $tpl) {
                $content = optional_param($tpl, null, null);
                if (empty($content)) {
                    elgg_messages_add(__gettext('Your input could not be empty'));
                } else {
                    if (elggadmin_savetpl($tpl, $content)) {
                        elgg_messages_add(sprintf(__gettext('%s updated'), $tpl));
                        $success = true;
                    } else {
                        elgg_messages_add(sprintf(__gettext('%s could not be updated'), $tpl));
                    }
                }
            }
            break;
        default:
            break;
    }

    if ($sucess) {
        header_redirect(get_url(null, 'elggadmin::'.$page));
    }
}

function elggadmin_currentpage($page_name=null) {
    static $name;

    if (!isset($name) && !empty($page_name)) {
        $name = $page_name;
    } else {
        if (!isset($name)) {
            $name = null;
        }
    }

    return $name;
}

function elggadmin_page_frontpage() {
    
    $page = new StdClass;
    $page->title = __gettext('Front page');
    $page->body = elggadmin_tpltextarea('frontpage_loggedout', __gettext('Front page (when logged out)'));
    $page->body .= elggadmin_tpltextarea('frontpage_loggedin', __gettext('Front page (when logged in)'));
    $page->body .= pages_html_input('hidden', array('name'=>'action','value'=>'elggadmin:frontpage'));
    $page->body = pages_html_form('elggfrontpage', $page->body);

    return $page;
}

function elggadmin_page_logs() {
    global $CFG;

    $action = optional_param('action');
    if ($action == 'elggadmin:logs:clear') {
        elggadmin_writefile($CFG->dataroot.'errors.log',"");
        header_redirect(get_url(null,'elggadmin::logs'), __gettext('Error log cleared'));
    }

    $logs = elggadmin_tailfile($CFG->dataroot.'errors.log', 50);

    $clear = '&raquo; ' . pages_html_a(get_url_query(1, 'elggadmin::logs', 'action=elggadmin:logs:clear'), __gettext('Clear error log'));

    $page = new StdClass;
    $page->title = __gettext('Error log');
    $page->body = pages_html_wrap('p', $clear);
    $page->body .= pages_html_wrap('textarea', $logs, array('wrap'=>'off', 'readonly'=>'readonly'));

    return $page;
}

function elggadmin_page_theme() {
    $page = new StdClass;
    $page->title = __gettext('Site theme');
    $page->body .= elggadmin_tpltextarea('pageshell', __gettext('Main pageshell'));
    $page->body .= elggadmin_tpltextarea('css', __gettext('CSS styles'));
    $page->body .= pages_html_input('hidden', array('name'=>'action','value'=>'elggadmin:theme'));
    // wrap into form
    $page->body = pages_html_form('elggtheme', $page->body);

    return $page;
}

function elggadmin_config_restore() {
    $rs = null;

    $config = elggadmin_get_defconfig();
    foreach ($config as $name => $obj) {
        $rs = delete_records('datalists', 'name', $name);
    }

    return $rs;
}

function elggadmin_page_config() {

    // restore!
    if (optional_param('action') == 'elggadmin:config:restore') {
        if (require_confirm(__gettext(__gettext('Are you sure to restore default configuration?')))) {
            if (elggadmin_config_restore()) {
                elgg_messages_add(__gettext('Your configuration has been restored to default values'));
            }
            header_redirect(get_url(null, 'elggadmin::config'));
        }
    }

    $show_all = optional_param('view');

    $_config = elggadmin_get_defconfig();
    $page = new StdClass;
    $page->title = __gettext('Configuration manager');
    $page->body = null;

    if (empty($show_all)) {
        $view_all = '&raquo; ' . pages_html_a(get_url_query(1, 'elggadmin::', 'view=all'), __gettext('View all options'));
        $page->body .= pages_html_wrap('div', pages_html_wrap('label', $view_all), array('class'=>''));
    } else {
        $restore = '&raquo; ' . pages_html_a(get_url_query(1, 'elggadmin::', 'action=elggadmin:config:restore'), __gettext('Restore default values'));
        $page->body .= pages_html_wrap('div', pages_html_wrap('label', $restore), array('class'=>''));
    }

    $note = __gettext('Note: some fields are disabled because the value is forced by your <code>config.php</code>.');
    $note .= __gettext('To change you must hand edit your <code>config.php</code>.');
    $page->body .= pages_html_wrap('p', $note);

    foreach ($_config as $c => $obj) {
        if ((isset($obj->noteditable) || isset($obj->hidden)) && !$show_all) {
            continue;
        }

        $name = htmlspecialchars($obj->name, ENT_COMPAT, 'utf-8');
        if (isset($obj->important)) {
            $name .= ': *';
        } else {
            $name .= ': &nbsp;';
        }

        $class = 'form-item ' . (isset($obj->important) ? ' important' : '');
        $desc = (isset($obj->description)) ? $obj->description : '&nbsp;';

        $input = pages_html_wrap('label', $name, array('class'=>'input-label'));
        $input .= elggadmin_config_input($c, $obj);
        $input .= pages_html_wrap('span', $desc);

        $page->body .= pages_html_wrap('div', $input, array('class'=>$class));
    }

    $page->body .= pages_html_input('hidden', array('name'=>'action', 'value'=>'elggadmin:config'));
    $page->body = pages_html_form('elggconfig', $page->body);

    return $page;
}

function elggadmin_config_input($c, $obj) {
    global $CFG;

    // override with current values
    $value = (isset($CFG->$c)) ? $CFG->$c : null;

    $input_name = "config[$c]";

    $attrs =  array();
    $attrs['name'] = $input_name;
    $attrs['value'] = $value;
    $attrs['class'] = 'input';

    if (isset($obj->noteditable)) {
        $attrs['disabled'] = 'disabled';
        $attrs['class'] .= ' input-disabled';
    }

    if (!isset($obj->type)) {
        $obj->type = null;
    }
    switch ($obj->type) {
        case 'bool':
        case 'boolean':
            $yes = __gettext('Yes');
            $no = __gettext('No');
            $yesattrs = unserialize(serialize($attrs));
            $noattrs = unserialize(serialize($attrs));
            $yesattrs['value'] = 1;
            $noattrs['value'] = 0;

            if ((bool)$value) {
                $yesattrs['checked'] = 'checked';
            } else {
                $noattrs['checked'] = 'checked';
            }

            $result = pages_html_wrap('label', pages_html_input('radio', $yesattrs) . ' ' . $yes);
            $result .= pages_html_wrap('label', pages_html_input('radio', $noattrs) . ' ' . $no);
            $result = pages_html_wrap('div', $result, array('class'=>'input-text'));
            break;                
        case 'int':
        case 'integer':
            $attrs['class'] = ' input-numeric';
            $result = pages_html_input('text', $attrs);
            break;
        case 'access':
            unset($attrs['name']);
            unset($attrs['value']);
            $options = array();
            $_opts = array(
                'Private' => 'PRIVATE',
                'Logged in' => 'LOGGED_IN',
                'Public' => 'PUBLIC',
                );
            foreach ($_opts as $label=>$access) {
                $obj = new StdClass;
                $obj->label = $label;
                $obj->value = $access;
                if ($value == $access) {
                    $obj->selected = true;
                }
                $options[] = $obj;
            }
            $result = pages_html_select($input_name, $options, $attrs);
            break;
        case 'debug':
            unset($attrs['name']);
            unset($attrs['value']);
            $_opts = array(
                'Hide all errors' => '0',
                'Only warnings' => '7',
                'Show all errors' => '2047',
                );

            $options = array();
            foreach ($_opts as $label=>$access) {
                $obj = new StdClass;
                $obj->label = $label;
                $obj->value = $access;
                if ($value == $access) {
                    $obj->selected = true;
                }
                $options[] = $obj;
            }
            $result = pages_html_select($input_name, $options, $attrs);
            break;
        case 'language':
            if (empty($CFG->languages_installed)) {
                $result = __gettext('No languages installed');
            } else {
                ksort($CFG->languages_installed);
                $options = array();
                foreach ($CFG->languages_installed as $code => $lang) {
                    $obj = new StdClass;
                    $obj->label = $lang;
                    $obj->value = $code;
                    if ($code == $value) {
                        $obj->selected = true;
                    }
                    $options[] = $obj;
                }
            }
            $result = pages_html_select($input_name, $options, $attrs);
            break;
        case 'template':
            global $CFG;
            // get all list of templates/themes
            $themes = get_list_of_plugins($CFG->templatesroot);
            // at least should exists Default_Template
            $options = array();
            foreach ($themes as $theme) {
                $obj = new StdClass;
                $obj->label = templates_file_to_shortname($theme);
                $obj->value = $theme;
                if ($theme == $value) {
                    $obj->selected = true;
                }
                $options[] = $obj;
            }
            $result = pages_html_select($input_name, $options, $attrs);
            break;
        default:
            $attrs['class'] .= ' input-text';
            $result = pages_html_input('text', $attrs);
            break;
    }

    return $result;
}

function elggadmin_get_defconfig() {
    global $CFG;

    if (!is_readable($CFG->dirroot . 'mod/elggadmin/lib/configdef.php')) {
        trigger_error(__FUNCTION__.': can not locate <code>configdef.php</code> file, perhaps your installation is corrupted.', E_USER_ERROR);
    }

    $DEFCFG = new StdClass;
    require($CFG->dirroot . 'mod/elggadmin/lib/configdef.php');

    $current = elggadmin_get_currentconfig($CFG->dirroot);
    $defined = get_object_vars($current);
    foreach ($defined as $name => $value) {
        if (isset($DEFCFG->config[$name])) {
            $DEFCFG->config[$name]->noteditable = true;
        }
    }

    return $DEFCFG->config;
}

function elggadmin_get_currentconfig($dirroot) {
    $CFG = new StdClass;
    @include($dirroot.'config.php');

    return $CFG;
}

function elggadmin_tpltextarea($tplname, $title=null, $attrs=null) {
    $output = '';
    $_attrs = array('name' => $tplname, 'style' => 'width:95%;height:300px;margin:0px 10px 20px 10px;');

    if (is_string($title)) {
        $output .= pages_html_wrap('h2', $title);
    }

    if (!$tpl = elggadmin_loadtpl($tplname)) {
        elgg_messages_add(__gettext("Can't load <code>$tplname</code> file."));
    }

    if (is_array($attrs)) {
        $_attrs = array_merge($attrs, $_attrs);
    }

    $output .= pages_html_wrap('textarea', empty($tpl) ? ' ' : $tpl, $_attrs);

    return $output;
}

function elggadmin_loadtpl($tplname) {
    global $CFG;
    $tpl = $CFG->templatesroot . $CFG->default_template . '/' . $tplname;
    if (!is_writable($tpl)) {
        elgg_messages_add(__gettext("Please specify that <code>$tpl</code> is world-writable in order to use this administration panel."));
    }
    return elggadmin_loadfile($tpl);
}

function elggadmin_savetpl($tplname, $content) {
    global $CFG;
    $tpl = $CFG->templatesroot . $CFG->default_template . '/' . $tplname;
    if (elggadmin_writefile($tpl, $content)) {
        return true;
    } else {
        return false;
    }
}

function elggadmin_loadfile($filepath) {
    $filepath = realpath($filepath);
    // security check
    //if (strpos($filepath, $CFG->dirroot) === 0) {
        if (is_readable($filepath)) {
            return file_get_contents($filepath);
        }
    //}
    
    return false;
}

function elggadmin_writefile($filepath, $content) {
    $filepath = realpath($filepath);
    // security check
    //if (strpos($filepath, $CFG->dirroot) === 0) {
        if (is_writable($filepath)) {
            $f = @fopen($filepath, 'w');
            if ($f && @fwrite($f, $content)) {
                @fclose($f);
                return true;
            }
        }
    //}
    
    return false;
}

function elggadmin_tailfile($file, $lines=20) {
    if ($fp = @fopen($file, 'r')) {
        $pos = -1;
        $t = ' ';
        $content = '';

        if ($lines < 0) {
            $lines = 20;
        }

        while ($lines > 0) {
            if (!fseek($fp, $pos, SEEK_END)) {
                $t = fgetc($fp);
                $pos--;
                if ($t == "\n") {
                    $lines--;
                }
                $content = $t . $content;
            } else {
                rewind($fp);
                $lines--;
            }
        }

        @fclose($fp);
        return $content;
    } else {
        return null;
    }
}

?>
