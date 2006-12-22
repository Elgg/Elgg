<?php

/*** NZVLE TODO
 *** 
 *** + Break the html/css bits of the default template into separate files
 ***   they mess up the code layout and indentation, and generally don't 
 ***   belong here.
 *** + Clean up and document the calling conventions -- get rid of $parameter
 *** + the callbacks to template_variables_substitute are *evil* -- rework
 ***
 ***/

function default_template () {

    global $CFG;
    global $template;
    global $template_definition;
    $sitename = $CFG->sitename;

    $run_result = '';

    $template_definition[] = array(
                                    'id' => 'css',
                                    'name' => __gettext("Stylesheet"),
                                    'description' => __gettext("The Cascading Style Sheet for the template."),
                                    'glossary' => array(),
                                    'display'  => 1,
                                    );

    $template['css'] = file_get_contents($CFG->templatesroot . "Default_Template/css");
    
    $template_definition[] = array(
                                   'id' => 'pageshell',
                                   'name' => __gettext("Page Shell"),
                                   'description' => __gettext("The main page shell, including headers and footers."),
                                   'glossary' => array(
                                                       '{{metatags}}' => __gettext("Page metatags (mandatory) - must be in the 'head' portion of the page"),
                                                       '{{title}}' => __gettext("Page title"),
                                                       '{{menu}}' => __gettext("Menu"),
                                                       '{{topmenu}}' => __gettext("Status menu"),
                                                       '{{mainbody}}' => __gettext("Main body"),
                                                       '{{sidebar}}' => __gettext("Sidebar")
                                                       )
                                   );
    
    $welcome = __gettext("Welcome"); // gettext variable
       
    $template['pageshell'] = file_get_contents($CFG->templatesroot . "Default_Template/pageshell");

    $template['frontpage_loggedout'] = file_get_contents($CFG->templatesroot . "Default_Template/frontpage_loggedout");
    $template['frontpage_loggedin'] = file_get_contents($CFG->templatesroot . "Default_Template/frontpage_loggedin");
    
    // REMOVED stylesheet (was old version and should not have been here)
    // TODO: extract all default template stuff from lib/templates.php

$template_definition[] = array(
                                    'id' => 'contentholder',
                                    'name' => __gettext("Content holder"),
                                    'description' => __gettext("Contains the main content for a page (as opposed to the sidebar or the title)."),
                                    'glossary' => array(
                                                            '{{title}}' => __gettext("The title"),
                                                            '{{submenu}}' => __gettext("The page submenu"),
                                                            '{{body}}' => __gettext("The body of the page")
                                                        )
                                    );    

    $template['contentholder'] = <<< END
    
    <div class="SectionContent">
    
    <h1>{{title}}</h1>
    {{submenu}}
    </div>
    {{body}}
    
END;

    $template_definition[] = array(
                                    'id' => 'ownerbox',
                                    'name' => __gettext("Owner box"),
                                    'description' => __gettext("A box containing a description of the owner of the current profile."),
                                    'glossary' => array(
                                                            '{{name}}' => __gettext("The user's name"),
                                                            '{{profileurl}}' => __gettext("The URL of the user's profile page, including terminating slash"),
                                                            '{{usericon}}' => __gettext("The user's icon, if it exists"),
                                                            '{{tagline}}' => __gettext("A short blurb about the user"),
                                                            '{{usermenu}}' => __gettext("Links to friend / unfriend a user"),
                                                            '{{lmshosts}}' => __gettext("Links to any lms hosts the user is attached to"),
                                                        )
                                    );

    $tags = __gettext("Tags");
    $resources = __gettext("Resources");
    $template['ownerbox'] = <<< END
    
    <div class="me">
        <div style="float: left; width: 70px"><a href="{{profileurl}}">{{usericon}}</a></div>
        <div style="margin-left: 75px; margin-top: 0px; padding-top: 0px; text-align: left" ><p>
            <span class="userdetails">{{name}}<br /><a href="{{profileurl}}rss/">RSS</a> | <a href="{{profileurl}}tags/">$tags</a> | <a href="{{profileurl}}newsclient/">$resources</a></span></p>
            <p>{{tagline}}</p>
            <p>{{lmshosts}}</p>
            <p style="margin-bottom: 3px" class="usermenu">{{usermenu}}</p>
        </div>
    </div>

END;
                                    
    $template_definition[] = array(
                                    'id' => 'infobox',
                                    'name' => __gettext("Information Box"),
                                    'description' => __gettext("A box containing a caption and some text, used extensively throughout the site. For example, the 'friends' box and most page bodies are info boxes. Of course, you can alter this template however you wish - it doesn't need to be an actual box."),
                                    'glossary' => array(
                                                            '{{name}}' => __gettext("The title"),
                                                            '{{contents}}' => __gettext("The contents of the box")
                                                        )
                                    );
    $template['infobox'] = <<< END

<table class="infobox" width="100%">
    <caption align="top">
        {{name}}
    </caption>
    <tr>
        <td>
{{contents}}
        </td>
    </tr>
</table><br />
END;

    $template_definition[] = array(
                                    'id' => 'messageshell',
                                    'name' => __gettext("System message shell"),
                                    'description' => __gettext("A list of system messages will be placed within the message shell."),
                                    'glossary' => array(
                                                            '{{messages}}' => __gettext("The messages")
                                                        )
                                    );

    $template['messageshell'] = <<< END
    
    <div id="js">{{messages}}</div><br />
    
END;

    $template_definition[] = array(
                                    'id' => 'messages',
                                    'name' => __gettext("Individual system messages"),
                                    'description' => __gettext("Each individual system message."),
                                    'glossary' => array(
                                                            '{{message}}' => __gettext("The system message")
                                                        )
                                    );

    $template['messages'] = <<< END

    <p>
        {{message}}
    </p>
    
END;
    

    $template_definition[] = array(
                                    'id' => 'menu',
                                    'name' => __gettext("Main menu shell"),
                                    'description' => __gettext("A list of main menu items will be placed within the menubar shell."),
                                    'glossary' => array(
                                                            '{{menuitems}}' => __gettext("The menu items")
                                                        )
                                    );

    $template['menu'] = <<< END
    
      {{menuitems}}
END;

    $template_definition[] = array(
                                    'id' => 'menuitem',
                                    'name' => __gettext("Individual main menu item"),
                                    'description' => __gettext("This is the template for each individual main menu item. A series of these is placed within the menubar shell template."),
                                    'glossary' => array(
                                                            '{{location}}' => __gettext("The URL of the menu item"),
                                                            '{{name}}' => __gettext("The menu item's name")
                                                        )
                                    );

    $template['menuitem'] = <<< END
    
    <li><a href="{{location}}">{{name}}</a></li>
    
END;

$template_definition[] = array(
                                    'id' => 'selectedmenuitem',
                                    'name' => __gettext("Selected individual main menu item"),
                                    'description' => __gettext("This is the template for an individual main menu item if it is selected."),
                                    'glossary' => array(
                                                            '{{location}}' => __gettext("The URL of the menu item"),
                                                            '{{name}}' => __gettext("The menu item's name")
                                                        )
                                    );

    $template['selectedmenuitem'] = <<< END
    
    <li><a class="current" href="{{location}}">{{name}}</a></li>
    
END;

    $template_definition[] = array(
                                    'id' => 'submenu',
                                    'name' => __gettext("Sub-menubar shell"),
                                    'description' => __gettext("A list of sub-menu items will be placed within the menubar shell."),
                                    'glossary' => array(
                                                            '{{submenuitems}}' => __gettext("The menu items")
                                                        )
                                    );

    $template['submenu'] = <<< END
    
        <h3>
            {{submenuitems}}
        </h3>
END;

    $template_definition[] = array(
                                    'id' => 'submenuitem',
                                    'name' => __gettext("Individual sub-menu item"),
                                    'description' => __gettext("This is the template for each individual sub-menu item. A series of these is placed within the sub-menubar shell template."),
                                    'glossary' => array(
                                                            '{{location}}' => __gettext("The URL of the menu item"),
                                                            '{{menu}}' => __gettext("The menu item's name")
                                                        )
                                    );

    $template['submenuitem'] = <<< END
    
    <a href="{{location}}">{{name}}</a>&nbsp;|
    
END;

    $template_definition[] = array(
                                    'id' => 'topmenu',
                                    'name' => __gettext("Status menubar shell"),
                                    'description' => __gettext("A list of statusbar menu items will be placed within the status menubar shell."),
                                    'glossary' => array(
                                                            '{{topmenuitems}}' => __gettext("The menu items")
                                                        )
                                    );

    $template['topmenu'] = <<< END
    
        <div id="StatusRight">
            {{topmenuitems}}
        </div>

END;

$template_definition[] = array(
                                    'id' => 'topmenuitem',
                                    'name' => __gettext("Individual statusbar menu item"),
                                    'description' => __gettext("This is the template for each individual statusbar menu item. A series of these is placed within the status menubar shell template."),
                                    'glossary' => array(
                                                            '{{location}}' => __gettext("The URL of the menu item"),
                                                            '{{menu}}' => __gettext("The menu item's name")
                                                        )
                                    );

    $template['topmenuitem'] = <<< END
    
    [<a href="{{location}}">{{name}}</a>]&nbsp;
    
END;

    $template_definition[] = array(
                                    'id' => 'databox',
                                    'name' => __gettext("Data input box (two columns)"),
                                    'description' => __gettext("This is mostly used whenever some input is taken from the user. For example, each of the fields in the profile edit screen is a data input box."),
                                    'glossary' => array(
                                                            '{{name}}' => __gettext("The name for the data we're inputting"),
                                                            '{{column1}}' => __gettext("The first item of data"),
                                                            '{{column2}}' => __gettext("The second item of data")
                                                        )
                                    );

    $template['databox'] = <<< END

<div class="infobox">
    <table width="95%" class="profiletable" align="center" style="margin-bottom: 3px">
    <tr>

        <td width="20%" class="fieldname" valign="top">
            <p><b>{{name}}</b></p>
        </td>
        <td width="50%" valign="top">
            <p>{{column1}}</p>
        </td>
        <td width="30%" valign="top">
            <p>{{column2}}</p>
        </td>
    </tr>
    </table>
</div>
        
END;

    $template_definition[] = array(
                                    'id' => 'databox1',
                                    'name' => __gettext("Data input box (one column)"),
                                    'description' => __gettext("A single-column version of the data box."),
                                    'glossary' => array(
                                                            '{{name}}' => __gettext("The name of the data we're inputting"),
                                                            '{{column1}}' => __gettext("The data itself")
                                                        )
                                    );

    $template['databox1'] = <<< END

<div class="infobox">
    <table width="95%" class="profiletable" align="center" style="margin-bottom: 3px">
    <tr>

        <td width="20%" class="fieldname" valign="top">
            <p><b>{{name}}</b></p>
        </td>
        <td width="80%" valign="top">
            <p>{{column1}}</p>
        </td>
    </tr>
    </table>
</div>
        
END;

    $template_definition[] = array(
                                    'id' => 'databoxvertical',
                                    'name' => __gettext("Data input box (vertical)"),
                                    'description' => __gettext("A slightly different version of the data box, used on this edit page amongst other places."),
                                    'glossary' => array(
                                                            '{{name}}' => __gettext("Name of the data we\'re inputting"),
                                                            '{{contents}}' => __gettext("The data itself")
                                                        )
                                    );

    $template['databoxvertical'] = <<< END
<div class="infobox">
    <table width="95%" class="fileTable" align="center" style="margin-bottom: 3px">
        <tr>
            <td class="fieldname">
                <p><b>{{name}}</b></p>
            </td>
        </tr>
        <tr>
            <td>
                <p>{{contents}}</p>
            </td>
        </tr>
    </table>
</div>
        
END;
return $run_result;
}

function templates_main () {

    global $PAGE;

    $run_result = '';

    /*
    *    Templates unit
    */

    // Load default values
        $function['init'][] = path . "units/templates/default_template.php";
        
    // Actions
        $function['templates:init'][] = path . "units/templates/template_actions.php";

    // Draw template (returns HTML as opposed to echoing it straight to the screen)
        $function['templates:draw'][] = path . "units/templates/template_draw.php";
        
    // Function to substitute variables within a template, used in templates:draw
        $function['templates:variables:substitute'][] = path . "units/templates/variables_substitute.php";

    // Function to draw the page, once supplied with a main body and title
        $function['templates:draw:page'][] = path . "units/templates/page_draw.php";
        
    // Function to display a list of templates
        $function['templates:view'][] = path . "units/templates/templates_view.php";
        $function['templates:preview'][] = path . "units/templates/templates_preview.php";
                
    // Function to display input fields for template editing
        $function['templates:edit'][] = path . "units/templates/templates_edit.php";
        
    // Function to allow the user to create a new template
        $function['templates:add'][] = path . "units/templates/templates_add.php";
        
    if ($context == "account") {
        $PAGE->menu_sub[] = array( 'name' => 'template:edit',
                                   'html' => templates_draw(array( 'context' => 'submenuitem',
                                                                   'name' => __gettext("Change theme"),
                                                                   'location' => url . '_templates/')));
    }

    return $run_result;
}

function templates_page_setup (){

    global $PAGE;
    global $CFG;

    if (!empty($PAGE->setupdone)) {
        return false; // don't run twice
    }

    $PAGE->setupdone = true; // leave your mark

    //
    // Populate $PAGE with links for non-module core code
    //

    if (isadmin()) {
    $PAGE->menu_top [] = array( 'name' => 'admin',
                                //'html' => a_href("{$CFG->wwwroot}_admin/",
                                //                "Administration"));
                                'html' => "<li><a href=\"" . $CFG->wwwroot . "_admin/\">" . __gettext("Administration") . "</a></li>");
    }
    
    $PAGE->menu_top[] = array(
                              'name' => 'userdetails',
                              //'html' => a_href("{$CFG->wwwroot}_userdetails/",
                              //                  "Account settings"));
                              'html' => "<li><a href=\"" . $CFG->wwwroot . "_userdetails/\">" . __gettext("Account settings") . "</a></li>");

    $PAGE->menu_top[] = array(
                              'name' => 'logoff',
                              //'html' => a_href("{$CFG->wwwroot}login/logout.php",
                              //                 "Log off"));
                              'html' => "<li><a href=\"" . $CFG->wwwroot . "login/logout.php\">" . __gettext("Log off") . "</a></li>");

    if (defined("context") && context == "account") {
        $PAGE->menu_sub[] = array(
                                  'name' => 'user:edit',
                                  'html' => a_href("{$CFG->wwwroot}_userdetails/",
                                                   __gettext("Edit user details")));
        $PAGE->menu_sub[] = array(
                                  'name' => 'user:icon',
                                  'html' => a_href("{$CFG->wwwroot}_icons/",
                                                   __gettext("Your site picture")));
    }

    if (defined("context") && context == "admin" && logged_on && user_flag_get("admin", $_SESSION['userid'])) {
        $PAGE->menu_sub[] = array(
                                  'name' => 'admin',
                                  'html' => a_href("{$CFG->wwwroot}_admin/",
                                                   __gettext("Main")));
   
        $PAGE->menu_sub[] = array(
                                  'name' => 'admin:useradd',
                                  'html' => a_href("{$CFG->wwwroot}_admin/users_add.php",
                                                   __gettext("Add users")));

        $PAGE->menu_sub[] = array(
                                  'name' => 'admin:users',
                                  'html' => a_href("{$CFG->wwwroot}_admin/users.php",
                                                   __gettext("Manage users")));

        $PAGE->menu_sub[] = array(
                                  'name' => 'admin:flaggedcontent',
                                  'html' => a_href("{$CFG->wwwroot}_admin/flags.php",
                                                   __gettext("Manage flagged content")));
   
        $PAGE->menu_sub[] = array(
                                  'name' => 'admin:spam',
                                  'html' => a_href("{$CFG->wwwroot}_admin/antispam.php",
                                                   __gettext("Spam control")));
            
    }


    //
    // Give a chance to all registered modules
    //
    if ($allmods = get_list_of_plugins('mod') ) {
        foreach ($allmods as $mod) {
            $mod_pagesetup = $mod . '_pagesetup';
            if (function_exists($mod_pagesetup)) {
                $mod_pagesetup();
            } else {
                notify("Function $mod_pagesetup doesn't exist!");
            }
        }
    }
}

function templates_page_draw ($param) {
    // Draws the page, given a title and a main body (parameters[0] and [1]).
    $title = $param[0];
    $mainbody = $param[1];

    $run_result = '';

    global $messages;

    ////
    //// Prepare things for the module run
    //// populating $PAGE as required
    ////
    if (empty($PAGE->setupdone)) {
        templates_page_setup();
    }

    $messageshell = "";
    if (isset($messages) && sizeof($messages) > 0) {
        foreach($messages as $message) {
            $messageshell .=templates_draw(array(
                                                 'context' => 'messages',
                                                 'message' => $message
                                                 )
                                           );
        }
        $messageshell =templates_draw(array(
                                            'context' => 'messageshell',
                                            'messages' => $messageshell
                                            )
                                      );
    }

    // If $parameter[2] is set, we'll substitute it for the
    // sidebar
    if (isset($param[2])) {
        $sidebarhtml = $param[2];
    } else {
        $sidebarhtml = run("display:sidebar");
    }    
    
    $run_result .=  templates_draw(array(
                            'context'      => 'pageshell',
                            'title'        => htmlspecialchars($title, ENT_COMPAT, 'utf-8'),
                            'menu'         => displaymenu(),
                            'submenu'      => displaymenu_sub(),
                            'top'          => displaymenu_top(),
                            'sidebar'      => $sidebarhtml,
                            'mainbody'     => $mainbody,
                            'messageshell' => $messageshell
                            ));
            
    return $run_result;
}

function templates_actions () {

    global $CFG,$USER,$db;

    // Actions

    global $template;
    global $messages;
    
    $action = optional_param('action');
    if (!logged_on) {
        return false;
    }

    $run_result = '';
    
    switch ($action) {
        case "templates:select":
            $id = optional_param('selected_template',0,PARAM_INT);
            if ($id == -1) {
                $exists = 1;
            } else {
                $exists = record_exists_sql('SELECT ident FROM '.$CFG->prefix.'templates WHERE ident = '.$id.' AND (owner = '.$USER->ident ." OR public='yes')");
            }
            if ($exists) {
                $t = new StdClass;
                $t->template_id = $id;
                $t->ident = $USER->ident;
                update_record('users',$t);
                
                $messages[] = __gettext("Your current template has been changed.");
            }
            break;
            
        case "templates:save":
            $template = optional_param('template');
            $id = optional_param('save_template_id',0,PARAM_INT);
            $templatetitle = optional_param('templatetitle');
            if (!empty($template) && !empty($id) && !empty($templatetitle)) {
                unset($_SESSION['template_element_cache'][$id]);
                $exists = record_exists('templates','ident',$id,'owner',$USER->ident);
                if ($exists) {
                    $t = new StdClass;
                    $t->name = $templatetitle;
                    $t->ident = $id;
                    update_record('templates',$t);
                    delete_records('template_elements','template_id',$id);
                    $contents = optional_param('template');
                    foreach ($contents as $name => $content) {
                        //TODO Fix this with PARAM_CLEANHTML or similar
                        $slashname = addslashes($name);
                        $slashcontent = addslashes($content);
                        if ($content != "" && $content != $template[$name]) {
                            $te = new StdClass;
                            $te->name = $slashname;
                            $te->content = $slashcontent;
                            $te->template_id = $id;
                            insert_record('template_elements',$te);
                        }
                    }
                    $messages[] = __gettext("Your template has been updated.");
                }
            }
            break;
        case "deletetemplate":
            $id = optional_param('delete_template_id',0,PARAM_INT);
            unset($_SESSION['template_element_cache'][$id]);
            $exists = record_exists('templates','ident',$id,'owner',$USER->ident);
            if ($exists) {
                //$db->execute('UPDATE '.$CFG->prefix.'users SET template_id = -1 WHERE template_id = '.$id);
                set_field('users', 'template_id', -1, 'template_id', $id);
                delete_records('template_elements','template_id',$id);
                delete_records('template','ident',$id);
                $messages[] = __gettext("Your template was deleted.");
            }
            break;
        case "templates:create":
            $based_on = optional_param('template_based_on',0,PARAM_INT);
            $name = optional_param('new_template_name');
            if (!empty($name)) { // $based_on can be empty, surely?  -Penny
                $t = new StdClass;
                $t->name = $name;
                $t->public = 'no';
                $t->owner = $USER->ident;
                $new_template_id = insert_record('templates',$t);
                if ($based_on != -1) {
                    $exists = record_exists_sql('SELECT ident FROM '.$CFG->prefix.'templates WHERE ident = ? AND (owner = ? OR public = ?)',array($based_on,$USER->ident,'yes'));
                    if ($exists) {
                        if ($elements = get_records('template_elements','template_id',$based_on)) {
                            foreach($elements as $element) {
                                $element->template_id = $new_template_id;
                                insert_record('template_elements',$element);
                            }
                        }
                    }
                }
            }
            break;
    }
    return $run_result;
}
    
/// NOTE: this function takes a named array as single parameter
function templates_draw ($parameter) {

    // Draw a page element using a specified template (or, if the template is -1, the default)
    // $parameter['template'] = the template ID, $parameter['element'] = the template element,
    // all other $parameter[n] = template elements

    // Initialise global template variable, which contains the default template
        global $template;
        
    // Initialise global template ID variable, which contains the template ID we're using
        global $template_id;
        global $page_owner;
        global $CFG;
        
        global $page_template_cache;
        
        $run_result = '';

        if ($parameter['context'] === 'topmenuitem') {
            // error_log("templates_draw pcontext " . print_r($parameter,1));
        }
    // Get template details
        if (!isset($template_id)) {
            if (!isset($page_owner) || $page_owner == -1) {
                $template_id = -1;
            } else {
                // if (!isset($_SESSION['template_id_cache'][$page_owner])) {
                if (!$template_id = user_info('template_id',$page_owner)) {
                    $template_id = -1;
                }
                // }
                // $template_id = $_SESSION['template_id_cache'][$page_owner];
            }
        }
        
    // Template ID override
        $t = optional_param('template_preview',0,PARAM_INT);
        if (!empty($t)) {
            $template_id = $t;
        }

    // Grab the template content
        if ($template_id == -1 || ($parameter['context'] != "css" && $parameter['context'] != "pageshell")) {
            $template_element = $template[$parameter['context']];            
        } else {
            $template_context = addslashes($parameter['context']);
                if (!isset($page_template_cache[$parameter['context']])) {
                    $result = get_template_element($template_id, $template_context);
                    $page_template_cache[$parameter['context']] = $result;
                } else {
                    $result = $page_template_cache[$parameter['context']];
                }
                if (!empty($result)) {
                    $template_element = stripslashes($result->content);
                } else {
                    $template_element = $template[$parameter['context']];
                }
        }
        
        if (!empty($CFG->templates->variables_substitute) && (is_callable($CFG->templates->variables_substitute[$parameter['context']]) || is_array($CFG->templates->variables_substitute[$parameter['context']]))) {
            if (is_array($CFG->templates->variables_substitute[$parameter['context']])) {
                foreach ($CFG->templates->variables_substitute[$parameter['context']] as $sub_function) {
                    $template_element .= $sub_function($vars);
                }
            } else {
                $template_element .= $CFG->templates->variables_substitute[$parameter['context']]($vars);
            }
        }

        if ($parameter['context'] === 'topmenuitem') {
            // error_log("templates_draw pcontext " . print_r($template_element));
        }

    // Substitute elements

        $functionbody = "
            \$passed = array(".var_export($parameter,true).",\$matches[1], " . $template_id . ");
            return templates_variables_substitute(\$passed);
        ";
        
        // $template_element = templates_variables_substitute(array($parameter,$template_element));
        $body = preg_replace_callback("/\{\{([A-Za-z_0-9: ]*)\}\}/i",create_function('$matches',$functionbody),$template_element);
        
        $run_result = $body;
        return $run_result;
}

function templates_add () {
    global $USER;

    // Create a new template
        $header = __gettext("Create theme"); // gettext variable
        $desc = __gettext("Here you can create your own themes based on one of the existing public themes. Just select which public theme you would like to alter and then create your own. You will now have edit privilages."); // gettext variable

        $panel = <<< END
        
        <h2>$header</h2>
        <p>$desc</p>
        <form action="index.php" method="post">
        
END;

        $panel .= <<< END
        
END;

        $panel .=templates_draw(array(
                                                'context' => 'databox1',
                                                'name' => __gettext("Theme name"),
                                                'column1' => display_input_field(array("new_template_name","","text"))
                                            )
                                            );
        
        $default = __gettext("Default Theme"); // gettext variable
        $column1 = <<< END
        
            <select name="template_based_on">
                <option value="-1">$default</option>
END;
        
        if ($templates = get_records_select('templates','owner = '.$USER->ident." OR public = 'yes'",'public')) {
            foreach($templates as $template) {
                $column1 .= "<option value=\"".$template->ident."\">".stripslashes($template->name) . "</option>";
            }
        }
        
        $column1 .= <<< END
            </select>
END;
                        
        $panel .=templates_draw(array(
                                                'context' => 'databox1',
                                                'name' => __gettext("Based on"),
                                                'column1' => $column1
                                            )
                                            );
            
        $buttonValue = __gettext("Create Theme"); // gettext variable
        $panel .= <<< END
            
            <p>
                <input type="hidden" name="action" value="templates:create" />
                <input type="submit" value="$buttonValue" />
            </p>
        
        </form>
        
END;

        $run_result .= $panel;
        return $run_result;
}

function templates_edit () {


    global $template;
    global $template_definition;
    global $USER;

    if (!isset($parameter)) {
    // Get template details
        if (!$template_id = user_info('template_id',$USER->ident)) {
            $template_id = -1;
        }
    } else {
        if (!is_array($parameter)) {
            $template_id = (int) $parameter;
        } else {
            $template_id = -1;
        }
    }

    // Grab title, see if we can edit the template
        $editable = 0;
        if ($template_id == -1) {
            $templatetitle = __gettext("Default Theme");
        } else {
            $templatestuff = get_record('templates','ident',$template_id);
            $templatetitle = stripslashes($templatestuff->name);
            if ($templatestuff->owner == $USER->ident) {
                $editable = 1;
            }
            if (($templatestuff->owner != $USER->ident) && ($templatestuff->public != 'yes')) {
                $template_id = -1;
            }
        }
    
    // Grab the template content
        if ($template_id == -1) {
            $current_template = $template;
        } else {
            if ($elements = get_records('template_elements','template_id',$template_id)) {
                foreach($result as $element) {
                    $current_template[stripslashes($element->name)] = stripslashes($element->content);
                }
            } else {
                $current_template = $template;
            }
        }
    
    $run_result .= <<< END
    
    <form action="" method="post">
    
END;
    
    $run_result .= templates_draw(array(
                                                'context' => 'databoxvertical',
                                                'name' => __gettext("Theme Name"),
                                                'contents' => display_input_field(array("templatetitle",$templatetitle,"text"))
                                            )
                                            );

    foreach($template_definition as $element) {
        
        $name = "<b>" . $element['name'] . "</b><br /><i>" . $element['description'] . "</i>";
        $glossary = __gettext("Glossary"); // gettext variable

        if (is_array($element['glossary']) && sizeof($element['glossary']) > 0) {
            $column1 = "<b>$glossary</b><br />";
            foreach($element['glossary'] as $gloss_id => $gloss_descr) {
                $column1 .= $gloss_id . " -- " . $gloss_descr . "<br />";
            }
        } else {
            $column1 = "";
        }
        
        if ($current_template[$element['id']] == "" || !isset($current_template[$element['id']])) {
            $current_template[$element['id']] = $template[$element['id']];
        }
        
        $column2 = display_input_field(array("template[" . $element['id'] . "]",$current_template[$element['id']],"longtext"));
/*        
        $run_result .=templates_draw(array(
                                'context' => 'databox',
                                'name' => $name,
                                'column2' => $column1,
                                'column1' => $column2
                            )
                            );
*/
        $run_result .=templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $name,
                                'contents' => $column1 . "<br />" . $column2
                            )
                            );

                                    
    }
    
    if ($editable) {
        $save = __gettext("Save"); // gettext variable
        $run_result .= <<< END
    
        <p align="center">
            <input type="hidden" name="action" value="templates:save" />
            <input type="hidden" name="save_template_id" value="$template_id" />
            <input type="submit" value="$save" />
        </p>
    
END;
    } else {
        $noEdit = __gettext("You may not edit this theme. To create a new, editable theme based on the default, go to <a href=\"index.php\">the main themes page</a>."); // gettext variable
        $run_result .= <<< END
        
        <p>
            $noEdit
        </p>
        
END;
    }
    $run_result .= <<< END
        
    </form>
    
END;
    return $run_result;
}

function templates_preview () {

    global $CFG;
    $run_result = '';

    // Preview template
    
    // Basic page elements
    
        $name = "Basic page elements";
        $heading1 = __gettext("Heading one"); // gettext variable
        $heading2 = __gettext("Heading two"); // gettext variable
        $bulletList = __gettext("A bullet list"); // gettext variable
        $heading3 = __gettext("Heading three"); // gettext variable
        $numberedList = __gettext("A numbered list"); // gettext variable
        $body = <<< END
        
    <img src="{$CFG->wwwroot}_templates/leaves.jpg" width="300" height="225" alt="A test image" align="right" />
    <h1>$heading1</h1>
    <p>Paragraph text</p>
    <h2>$heading2</h2>
    <ul>
        <li>$bulletList</li>
    </ul>
    <h3>$heading3</h3>
    <ol>
        <li>$numberedList</li>
    </ol>
        
END;

        $run_result .= templates_draw(array(
                                                    'context' => 'contentholder',
                                                    'title' => $name,
                                                    'body' => $body
                                                )
                                                );

    // Form elements
    
        $name = "Data input";

        $body =templates_draw(array(
                                                'context' => 'databox',
                                                'name' => __gettext("Some text input"),
                                                'column1' => display_input_field(array("blank","","text")),
                                                'column2' => run("display:access_level_select",array("blank","PUBLIC"))
                                            )
                                            );
        $body .=templates_draw(array(
                                                'context' => 'databox1',
                                                'name' => __gettext("Some longer text input"),
                                                'column1' => display_input_field(array("blank","","longtext"))
                                            )
                                            );
        $body .=templates_draw(array(
                                                'context' => 'databoxvertical',
                                                'name' => __gettext("Further text input"),
                                                'contents' => display_input_field(array("blank","","longtext")) . "<br />" . display_input_field(array("blank","","text")) . "<br /><input type='button' value='Button' />"
                                            )
                                            );
        
        $run_result .=templates_draw(array(
                                                        'context' => 'contentholder',
                                                        'title' => $name,
                                                        'body' => $body,
                                                        'submenu' => ''
                                                    )
                                                    );
        return $run_result;
}

function templates_view () {
    global $USER;

    $user_template = user_info('template_id',$USER->ident);
        $sitename = sitename;
        $title = __gettext("Select / Create / Edit Themes"); // gettext variable
        $header = __gettext("Public Themes"); // gettext variable
        $desc = sprintf(__gettext("The following are public themes that you can use to change the way your %s looks - these do not change the content only the appearance. Check the preview and then select the one you want. If you wish you can adapt one of these using the 'create theme' option below."), $sitename); // gettext variable
        $panel = <<< END

    <h2>$title</h2>
    <form action="" method="post">
    <h3>
        $header
    </h3>
    <p>
        $desc
    </p>
    
END;

        $template_list[] = array(
                                    'name' => __gettext("Default Theme"),
                                    'id' => -1
                                );
        if ($templates = get_records('templates','public','yes')) {
            foreach($templates as $template) {
                $template_list[] = array(
                                         'name' => stripslashes($template->name),
                                         'id' => stripslashes($template->ident)
                                         );
            }
        }
        foreach($template_list as $template) {
            $name = "<input type='radio' name='selected_template' value='".$template['id']."' ";
            if ($template['id'] == $user_template) {
                $name .= "checked=\"checked\"";
            }
            $name .=" /> ";
            $column1 = "<b>" . $template['name'] . "</b>";
            $column2 = "<a href=\"".url."_templates/preview.php?template_preview=".$template['id']."\" target=\"preview\">" . __gettext("preview") . "</a>";
            $panel .=templates_draw(array(
                                                        'context' => 'databox',
                                                        'name' => $name,
                                                        'column1' => $column1,
                                                        'column2' => $column2
                                                    )
                                                    );
        }

        $templates = get_records('templates','owner',$USER->ident);
        $header2 = __gettext("Personal themes"); // gettext variable
        $desc2 = __gettext("These are themes that you have created. You can edit and delete these. These theme(s) only control actual look and feel - you cannot change any content here. To change any of your content you need to use the other menu options such as: edit profile, update weblog etc."); // gettext variable

        if (is_array($templates) && sizeof($templates) > 0) {
            $panel .= <<< END
    <h3>
        $header2
    </h3>
    <p>
        $desc2
    </p>
        
END;

            foreach($templates as $template) {
                    $name = "<input type='radio' name='selected_template' value='".$template->ident."'";
                    if ($template->ident == $user_template) {
                        $name .= "checked=\"checked\"";
                    }
                    $name .=" /> ";
                    $column1 = "<b>" . stripslashes($template->name) . "</b>";
                    $column2 = "<a href=\"".url."_templates/preview.php?template_preview=".$template->ident."\" target=\"preview\">" . __gettext("preview") . "</a>";

                    $column2 .= " | <a href=\"".url."_templates/edit.php?id=".$template->ident."\" >". __gettext("Edit") ."</a>";
                    $column2 .= " | <a href=\"".url."_templates/?action=deletetemplate&amp;delete_template_id=".$template->ident."\"  onclick=\"return confirm('" . __gettext("Are you sure you want to permanently remove this template?") . "')\">" . __gettext("Delete") . "</a>";
                    $panel .=templates_draw(array(
                                                        'context' => 'databox',
                                                        'name' => $name,
                                                        'column1' => $column1,
                                                        'column2' => $column2
                                                    )
                                                    );
            }
        }
        
    $submitValue = __gettext("Select new theme"); // gettext variable
    $panel .= <<< END
    
        <p>
            <input type="submit" value="$submitValue" />
            <input type="hidden" name="action" value="templates:select" />
        </p>
        
    </form>
    
END;

    $run_result .= $panel;
    return $run_result;
}

function templates_variables_substitute ($param) {
    
    global $CFG;

    $variables         = $param[0];
    $template_variable = $param[1];

    $run_result = '';

    // Substitute variables in templates:
    // where {{variablename}} is found in the template, this function is passed
    // "variablename" and returns the proper variable

    global $menubar;
    global $submenubar;
    global $metatags;
    global $PAGE;
    global $template_id;
    global $db;
    
    //error_log("tvs " . print_r($template_variable,1));

    $result = "";
    if (isset($variables[$template_variable])) {
        $result .= $variables[$template_variable];
    } else {
        $vars = array();
        if (substr_count($template_variable,":") > 0) {
            $vars = explode(":",$template_variable);
            $template_variable = $vars[0];
        }
        switch($template_variable) {
                
        case "username":
            if (logged_on) {
                $result =  $_SESSION['username'];
            } else {
                $result =  __gettext("Guest");
            }
            break;
        case "usericonid":
            if (logged_on) {
                $result =  user_info("icon",$_SESSION['userid']);
            } else {
                $result =  0;
            }
            break;
        case "name":
            if (logged_on) {
                $result =  htmlspecialchars($_SESSION['name'], ENT_COMPAT, 'utf-8');
            } else {
                $result =  __gettext("Guest");
            }
            break;
        case "userfullname":
            if (logged_on) {
                $result =  htmlspecialchars($_SESSION['name'], ENT_COMPAT, 'utf-8');
            } else {
                $result =  __gettext("Guest") . " [<a href=\"".url."login/index.php\">" . __gettext("Log in") . "</a>]";
            }
            break;
        case "menu":
            if (logged_on) {
                $result =  templates_draw(array(
                                            'menuitems' => menu_join('', $PAGE->menu),
                                            'context' =>   'menu'
                                            ));
            }
            break;

        case "submenu":
            $result =  templates_draw(array(
                                        'submenuitems' => menu_join('&nbsp;|&nbsp;', $PAGE->menu_sub),
                                        'context' => 'submenu'
                                        ));
            break;

        case "topmenu":
            if (logged_on) {
                $result =  templates_draw(array(
                                            'topmenuitems' => menu_join('', $PAGE->menu_top),
                                            'context' => 'topmenu'
                                            ));
            }
            break;

        case "url":
            $result =  url;
            break;
        
        case "sitename":
            $result =  $CFG->sitename;
            break;

        case "tagline":
            $result =  $CFG->tagline;
            break;

        case "metatags":
            // $run_result = "<link href=\"/".$template_variable.".css\" rel=\"stylesheet\" type=\"text/css\" />";
            $result =   "<style type=\"text/css\"><!--\n"
                . templates_draw(array(
                                       'template' => $template_id,
                                       'context' => 'css'
                                       )
                                 )
                . "// -->\n</style>\n"
                . $metatags;
            break;
            
        case 'perf':
            $perf = get_performance_info();
            if (defined('ELGG_PERFTOLOG')) {
                error_log("PERF: " . $perf['txt']);
            }
            if (defined('ELGG_PERFTOFOOT') || $CFG->debug > 7 || $CFG->perfdebug > 7) {
                $result =  $perf['html'];
            }

            break;
            
        case 'randomusers':
            $result = "";
            
            if (isset($vars[1])) {
                $vars[1] = (int) $vars[1];
            } else {
                $vars[1] = 3;
            }
            
            if ($users = get_records_sql("SELECT DISTINCT u.*,i.filename AS iconfile, ".$db->random." as rand 
                                    FROM ".$CFG->prefix."tags t JOIN ".$CFG->prefix."users u ON u.ident = t.owner
                                    LEFT JOIN ".$CFG->prefix."icons i ON i.ident = u.icon 
                                    WHERE t.tagtype IN (?,?,?) AND u.icon != ? AND t.access = ? AND u.user_type = ? 
                                    ORDER BY rand LIMIT " . $vars[1],array('biography','minibio','interests',-1,'PUBLIC','person'))) {
                $usercount = 0;
                foreach($users as $user) {
                    if ($usercount > 0) {
                        $result .= ", ";
                    } else {
                        $result .= " ";
                    }
                    $result .= "<a href=\"" . $CFG->wwwroot . $user->username . "/\">" . stripslashes($user->name) . "</a>";
                    $usercount++;
                }
            }
            
            break;
        case 'people':
            $result = "";
            if (isset($vars[1])) {
                $vars[1] = $db->qstr($vars[1]);
            } else {
                $vars[1] = "'interests'";
            }
            
            if (isset($vars[2])) {
                $vars[2] = $db->qstr($vars[2]);
            } else {
                $vars[2] = "'foo'";
            }
            
            if (isset($vars[3])) {
                $vars[3] = (int) $vars[3];
            } else {
                $vars[3] = 5;
            }

            $users = get_records_sql("SELECT users.*, icons.filename as iconfile, icons.ident as iconid FROM ".$CFG->prefix."tags LEFT JOIN ".$CFG->prefix."users ON users.ident = tags.owner left join ".$CFG->prefix."icons on icons.ident = users.icon WHERE tags.tag = ".$vars[2]." AND tags.tagtype = ".$vars[1]." AND users.icon != -1 AND tags.access = 'PUBLIC' and users.user_type = 'person' ORDER BY rand( ) LIMIT " . $vars[3]);
            if (sizeof($users) > 0 && is_array($users)) {
                
                $result .= <<< END
                    <table width="550px" border="0" cellpadding="0" cellspacing="0">
                       <tr>
END;
                
                foreach($users as $user) {

                    $user->name = stripslashes($user->name);
                    $result .= <<< END
                    
                      <td align="center">
                         <div class="image_holder">
                         <a href="{$CFG->wwwroot}{$user->username}/"><img src="{$CFG->wwwroot}_icon/user/{$user->iconid}/w/80/h/80" border="0" /></a>
                         </div>
                        <div class="userdetails">
                            <p><a href="{$CFG->wwwroot}{$user->username}/">{$user->name}</a></p>
                        </div>
END;
                }
                
                $result .= <<< END
                <tr>
        </table>
END;
            }
            
            break;
            
        case "toptags":
            if (isset($vars[1])) {
                $vars[1] = $db->qstr($vars[1]);
            } else {
                $vars[1] = "'town'";
            }
            $tags = get_records_sql("SELECT tag, count(ident) as numtags FROM `".$CFG->prefix."tags` WHERE access = 'public' and tagtype=".$vars[1]." group by tag order by numtags desc limit 20");
            $tag_count = 0;
            foreach($tags as $tag) {
                
                
                $tag->tag = stripslashes($tag->tag);
                $result .= "<a href=\"".url."tag/".urlencode(htmlspecialchars(strtolower(($tag->tag)), ENT_COMPAT, 'utf-8'))."\" title=\"".htmlspecialchars($tag->tag, ENT_COMPAT, 'utf-8')." (" .$tag->numtags. ")\">";
                $result .= $tag->tag . "</a>";
                if ($tag_count < sizeof($tags) - 1) {
                    $result .= ", ";
                }
                $tag_count++;
            }
            
            break;
        case "populartags":
            $result = "";
            $tags = get_records_sql("SELECT tag, count(ident) as numtags FROM `".$CFG->prefix."tags` WHERE access = 'public' and tag!='' group by tag having numtags > 1 order by ident desc limit 20");
            $max = 0;
            foreach($tags as $tag) {
                if ($tag->numtags > $max) {
                    $max = $tag->numtags;
                }
            }
            
            $tag_count = 0;
            foreach($tags as $tag) {
                
                if ($max > 1) {
                    $size = round((log($tag->numtags) / log($max)) * 300);
                } else {
                    $size = 100;
                }
                
                $tag->tag = stripslashes($tag->tag);
                $result .= "<a href=\"".url."tag/".urlencode(htmlspecialchars(strtolower(($tag->tag)), ENT_COMPAT, 'utf-8'))."\" style=\"font-size: $size%\" title=\"".htmlspecialchars($tag->tag, ENT_COMPAT, 'utf-8')." (" .$tag->numtags. ")\">";
                $result .= $tag->tag . "</a>";
                if ($tag_count < sizeof($tags) - 1) {
                    $result .= ", ";
                }
                $tag_count++;
            }
            
            break;
        default:
            break;
            
        }
    }
    if (!empty($CFG->templates->variables_substitute) && (is_callable($CFG->templates->variables_substitute[$template_variable]) || is_array($CFG->templates->variables_substitute[$template_variable]))) {
        if (is_array($CFG->templates->variables_substitute[$template_variable])) {
            foreach ($CFG->templates->variables_substitute[$template_variable] as $sub_function) {
                $result .= $sub_function($vars);
            }
        } else {
            $result .= $CFG->templates->variables_substitute[$template_variable]($vars);
        }
    }
    $run_result .= $result;
    return $run_result;
}

/***
 *** Fetches the template elements from disk or db. 
 ***/
function get_template_element ($template_id, $element_name) {
    global $CFG;

    if ($CFG->templatestore === 'db') {
        $result = get_record('template_elements','template_id',$template_id,'name',$element_name);
    } else {
        $template_name = get_field('templates', 'name', 'ident', $template_id);
        $template_name = strtolower(clean_param($template_name, PARAM_ALPHANUM));
        $template_file = $CFG->templatesroot . $template_name . '/' . $element_name;
        
        if (! $element_content = file_get_contents($template_file)) {
            trigger_error("Problems retrieving template element from $template_file");
        } else {
            $result = new StdClass;
            $result->content = $element_content;
            $result->ident   = $template_id;
            $result->name    = $element_name;
        }
    }
    return $result;
}

/*** menu_join()
 ***  performs a join on one of 
 ***  the $PAGE->menu* variables
 ***  returns HTML
 ***/
function menu_join ($separator, $menuarray) {
    $html = array();
    foreach ($menuarray as $entry) {
        array_push($html, $entry['html']);
    }
    return join($separator, $html);
}


?>
