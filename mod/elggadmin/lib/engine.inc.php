<?php
/**
 * Elgg administrator engine
 *
 * @copyright Copyright (c) 2007 Pro Soft Resources Inc. http://www.prosoftpeople.com
 * @author Rolando Espinoza La Fuente <rho@prosoftpeople.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

// ported from wp
function elggadmin_parse_request() {
    global $CFG;

    $pathinfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $pathinfo_array = explode('?', $pathinfo);
    $pathinfo = str_replace('%', '%25', $pathinfo_array[0]);

    $req_uri = elggadmin_requested_url();
    $req_uri_array = explode('?', $req_uri);
    $req_uri = $req_uri_array[0];

    $self = $_SERVER['PHP_SELF'];

    $elggpath = parse_url($CFG->wwwroot);
    if (isset($elggpath['path'])) {
        $elggpath = $elggpath['path'];
    }
    $elggpath = trim($elggpath, '/');

    $req_uri = str_replace($pathinfo, '', rawurldecode($req_uri));
    $req_uri = trim($req_uri, '/');
    $req_uri = preg_replace("!^$elggpath!", '', $req_uri);
    $req_uri = trim($req_uri, '/');

    $pathinfo = trim($pathinfo, '/');
    $pathinfo = preg_replace("!^$elggpath!", '', $pathinfo);
    $pathinfo = trim($pathinfo, '/');

    $self = trim($self, '/');
    $self = preg_replace("!^$elggpath!", '', $self);
    $self = str_replace($elggpath, '', $self);
    $self = trim($self, '/');

    if (!empty($pathinfo) && !preg_match('!^.*index\.php$!', $pathinfo)) {
        $request = $pathinfo;
    } else {
        if ($req_uri == 'index.php') {
            $req_uri = '';
        }
        $request = $req_uri;
    }

    $rules = elggadmin_get_rules();
    $request_match = $request;
    foreach ($rules as $regex => $callback) {
        if (!empty($req_uri) && (strpos($regex, $req_uri) === 0) && ($req_uri != $request)) {
            $request_match = $req_uri . '/' . $request;
        }

        if (preg_match("!^$regex!", $request_match, $matches)
            || preg_match("!^$regex!", urldecode($request_match), $matches)) {
                unset($matches[0]);
                return array($callback, $matches);
        }
    }
    // duh!
    return array('elggadmin_404', array());
}

function elggadmin_render_output($controller) {

    $before_render = $controller[0].'_before';
    run2($before_render, $controller);

    //before render
    templates_page_setup();

    //print_object($controller);
    //run func, must return page object
    $result = run2($controller[0], $controller[1]);
    //take only the output of controller
    $page = $result[$controller[0]];

    if (!isset($page->title) || !isset($page->body)) {
        trigger_error(__FUNCTION__.": returned page title or body not defined", E_USER_WARNING);
    }

    templates_page_output($page->title, $page->body);
}

// ported from mediawiki 
function elggadmin_requested_url() {
    if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // IIS
        $base = $_SERVER['HTTP_X_REWRITE_URL'];
    } elseif (isset($_SERVER['REQUEST_URI'])) {
        $base = $_SERVER['REQUEST_URI'];
    } elseif (isset($_SERVER['ORIG_PATH_INFO'])) { // php as cgi
        $base = $_SERVER['ORIG_PATH_INFO'];
        if (!empty($_SERVER['QUERY_STRING'])) {
            $base .= '?' . $_SERVER['QUERY_STRING'];
        }
    } elseif (isset($_SERVER['SCRIPT_NAME'])) {
        //IIS?
        $base = $_SERVER['SCRIPT_NAME'];
        if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') {
            $base .= '?' . $_SERVER['QUERY_STRING'];
        }
    } else {
        trigger_error(__FUNCTION__.": unknown REQUEST_URI or SCRIPT_NAME", E_USER_ERROR);
    }

    $hash = strpos($base, '#');
    if ($hash !== false) {
        $base = substr($base, 0, $hash);
    }
    if ($base{0} == '/') {
        return $base;
    } else {
        return preg_replace('!^[^:]+://[^/]+/!', '/', $base);
    }
}

function elggadmin_add_rule($regex, $callback, $priority=null, $top=false) {
    global $CFG;

    if (is_null($priority)) { $priority = 10; }

    if (empty($callback) || !is_callable($callback)) {
        trigger_error(__FUNCTION__.": invalid argument (callback: $callback)", E_USER_ERROR);
    }

    if (empty($regex) || strpos($regex, '!') !== false || $regex{0} == '^') {
        trigger_error(__FUNCTION__.": invalid argument (regex: $regex)", E_USER_ERROR);
    }

    if (!is_numeric($priority)) {
        trigger_error(__FUNCTION__.": invalid argument (priority: $priority)", E_USER_ERROR);
    }

    $regex = str_replace('{{user}}', '[A-Za-z0-9]+', $regex);

    if (!isset($CFG->rewrite_rules[$priority][$regex])) { $CFG->rewrite_rules[$priority][$regex] = array(); }

    if ($top !== true) {
        $CFG->rewrite_rules[$priority][$regex] = $callback;
    } else {
        // workaround
        $rewrite[$regex] = true;
        $CFG->rewrite_rules[$priority] = array_merge($rewrite, $CFG->rewrite_rules[$priority]);
        $CFG->rewrite_rules[$priority][$regex] = $callback;
    }
}

function elggadmin_get_rules() {
    global $CFG;

    static $rules;

    if (!isset($rules) && isset($CFG->rewrite_rules)) {
        $rules = array();
        foreach ($CFG->rewrite_rules as $pri) {
            foreach ($pri as $regex => $callback) {
                $rules[$regex] = $callback;
            }
        }
    }

    return isset($rules) ? $rules : array();
}

function elggadmin_add_function($tag, $name, $priority=10) {
    global $function_callable;

    if (!is_callable($name)) {
        trigger_error(__FUNCTION__.": invalid argument, function not callable (name :$name)");
    }

    $function_callable[$tag][$priority][] = $name;
}

function elggadmin_404() {
    $page = new StdClass;
    $page->title = __gettext('Page not found');
    $page->body = __gettext('duh!');

    return $page;
}

function elggadmin_404_before() {
    global $PAGE;
    $PAGE->is_404 = true;

    context('external');
}

function elggadmin_is_404() {
    global $PAGE;
    return isset($PAGE->is_404);
}

function run2($name, $parameters=null) {
    global $function;
    global $function_callable;

    $run_result = null;

    if (is_null($parameters)) {
        $parameters = array();
    }

    if (is_callable($name)) {
        $run_result[$name] = call_user_func_array($name, $parameters);
    }

    // recursive call
    if (isset($function_callable[$name])) {
        foreach ($function_callable[$name] as $v => $p) {
            foreach ($p as $f) {
                $run_result[$f] = run2($f, $parameters);
            }
        }
    }

    if (isset($function[$name])) {
        $run_result['legacy_run'] = run($name, $parameters);
    }

    return $run_result;
}

function context($context=null) {
    if (isset($context)) {
        if (defined('context')) {
            trigger_error(__FUNCTION__.": context already defined (context: ".context.")", E_USER_WARNING);
        } else {
            define('context', $context);
        }
    } else {
        if (defined('context')) {
            $context = context;
        }
    }

    return $context;
}

function plugin_is_loaded($plugin) {
    if (empty($plugin) || !is_string($plugin)) {
        trigger_error(__FUNCTION__.": invalid argument (plugin: $plugin)", E_USER_ERROR);
    }

    $plugins = get_list_of_plugins('mod');
    return in_array($plugin, $plugins);
}

function elgg_messages_add($message) {
    global $messages;
    if (!is_string($message)) {
        trigger_error(__FUNCTION__.": invalid argument (message: $message)", E_USER_ERROR);
    } else {
        $messages[] = $message;
    }
}

function check_walledgarden() {
    global $CFG;

    if (!empty($CFG->walledgarden) && !isloggedin() && (!context() || context() == 'external')) {
        require_login();
    }
}

function unset_config($name) {
    global $CFG;

    if (empty($name)) {
        trigger_error(__FUNCTION__.": invalid argument empty", E_USER_ERROR);
    }

    $rs = null;

    if (isset($CFG->$name)) {
        unset($CFG->$name);
        $rs = delete_records('datalists', 'name', $name);
    }

    return $rs;
}

?>
