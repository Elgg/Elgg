<?php
    global $CFG;
    global $template;
    global $template_definition;
    global $messages;

    $sitename = $CFG->sitename;
    
    $template_definition[] = array(
                                    'id' => 'css',
                                    'name' => __gettext("Stylesheet"),
                                    'description' => __gettext("The Cascading Style Sheet for the template."),
                                    'glossary' => array(),
                                    'display'  => 1,
                                    );

    templates_add_context('css', $CFG->templatesroot . $CFG->default_template . '/css');

    $template_definition[] = array(
                                    'id' => 'pageshell',
                                    'name' => __gettext("Page Shell"),
                                    'description' => __gettext("The main page shell, including headers and footers."),
                                    'display' => 1,
                                    'glossary' => array(
                                                            '{{metatags}}' => __gettext("Page metatags (mandatory) - must be in the 'head' portion of the page"),
                                                            '{{title}}' => __gettext("Page title"),
                                                            '{{menu}}' => __gettext("Menu"),
                                                            '{{topmenu}}' => __gettext("Status menu"),
                                                            '{{mainbody}}' => __gettext("Main body"),
                                                            '{{sidebar}}' => __gettext("Sidebar")
                                                        )
                                    );
    
    templates_add_context('pageshell', $CFG->templatesroot . $CFG->default_template . '/pageshell');
    
    if (file_exists($CFG->templatesroot . $CFG->default_template . '/frontpage_loggedout')) {
        templates_add_context('frontpage_loggedout', $CFG->templatesroot . $CFG->default_template . '/frontpage_loggedout');
    } else {
        templates_add_context('frontpage_loggedout', $CFG->templatesroot . 'Default_Template/frontpage_loggedout');
        if (isadmin() && defined('context') && context == 'admin') {
            $messages[] = sprintf(__gettext('The default theme selected (%s) does not have <code>frontpage_loggedout</code> template. Using default template.'), $CFG->default_template);
        }
    } 

    if (file_exists($CFG->templatesroot . $CFG->default_template . '/frontpage_loggedin')) {
        templates_add_context('frontpage_loggedin', $CFG->templatesroot . $CFG->default_template . '/frontpage_loggedin');
    } else {
        templates_add_context('frontpage_loggedin', $CFG->templatesroot . 'Default_Template/frontpage_loggedin');
        if (isadmin() && defined('context') && context == 'admin') {
            $messages[] = sprintf(__gettext('The default theme selected (%s) does not have <code>frontpage_loggedin</code> template. Using default template.'), $CFG->default_template);
        }

    }

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
    <h1>{{title}}</h1>
	{{submenu}}
    {{body}}
    
END;

$template_definition[] = array(
                                    'id' => 'sidebarholder',
                                    'name' => __gettext("Sidebar section holder"),
                                    'description' => __gettext("Contains the sidebar section titles"),
                                    'glossary' => array(
                                                            '{{title}}' => __gettext("The header"),
                                                                                                       '{{body}}' => __gettext("The body of the page")
                                                            
                                                        )
                                    );

    $template['sidebarholder'] = <<< END
    <h2>{{title}}</h2>
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
    
     <div id="me">
        <div id="icon"><a href="{{profileurl}}">{{usericon}}</a></div>
        <div id="contents" >
          <p>
            <span class="userdetails">{{name}}<br /><a href="{{profileurl}}rss/"><img src="{{url}}mod/template/icons/rss.png" alt="RSS" border="0" /></a> | <a href="{{profileurl}}tags/">$tags</a> | <a href="{{profileurl}}newsclient/">$resources</a></span></p>
            <p>{{tagline}}</p>
            <p>{{lmshosts}}</p>
            <p class="usermenu">{{usermenu}}</p>
        </div>
       </div>

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
    
    <div id="system-message">{{messages}}</div><br />
    
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
    
        <div id="sub-menu">
        <p>
            {{submenuitems}}
        </p>
        </div>
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
    
        <ul>
            {{topmenuitems}}
        </ul>

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
    
    <li><a href="{{location}}">[{{name}}]</a></li>
    
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

<div class="infoholder">
        <div class="fieldname">
            <h3>{{name}}</h3>
        </div>
        <p>{{column1}}</p>
        <p>{{column2}}</p>
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

<div class="infoholder">
        <div class="fieldname">
            <h3>{{name}}</h3>
        </div>
        <p>{{column1}}</p>
    </div>
        
END;

$template_definition[] = array(
                                    'id' => 'adminTable',
                                    'name' => __gettext("adminTable"),
                                    'description' => __gettext("This table is used to house stats and administration details until a good CSS solution can be applied."),
                                    'glossary' => array(
                                                            '{{name}}' => __gettext("Column One"),
                                                            '{{column1}}' => __gettext("Column Two"),
                                                            '{{column2}}' => __gettext("Column Three")
                                                        )
                                    );

    $template['adminTable'] = <<< END

<div>
    <table width="100%">
    <tr>
        <td width="25%" valign="top">
            {{name}}
        </td>
        <td width="45%" valign="top">
            {{column1}}
        </td>
        <td width="30%" valign="top">
            {{column2}}
        </td>
    </tr>
    </table>
</div>

END;

$template_definition[] = array(
                                    'id' => 'flagContent',
                                    'name' => __gettext("flagContent"),
                                    'description' => __gettext("This holds the flag content function throughout Elgg"),
                                    'glossary' => array(
                                                            '{{name}}' => __gettext("Column One"),
                                                            '{{column1}}' => __gettext("Column Two"),
                                                            '{{column2}}' => __gettext("Column Three")
                                                        )
                                    );

    $template['flagContent'] = <<< END

<div class="flagcontent">
    {{name}}
    {{column1}}
    {{column2}}
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
<div class="infoholder">
        <div class="fieldname">
            <h3>{{name}}</h3>
        </div>
        <p>{{contents}}</p>
    </div>
        
END;

?>