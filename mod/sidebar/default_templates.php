<?php
/**
 * Sidebar plugin
 * $id$
 *
 * @copyright Copyright (c) 2007 Pro Soft Resources Inc. http://www.prosoftpeople.com
 * @author Rolando Espinoza La fuente <rho@prosoftpeople.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

global $template;
global $template_definition;

$template_definition[] = array(
    'id' => 'sidebar:wrap',
    'name' => __gettext('Sidebar holder wrap'),
    'description' => __gettext('Sidebar'),
    'glossary' => array(
        '{{body}}' => __gettext('Sidebar body'),
        ),
    );

$sidebar_wrap = <<< END

<div id="sidebar-holder">
    {{body}}
</div>
END;


// override legacy sidebarholder template
$template_definition[] = array(
    'id' => 'sidebarholder',
    'name' => __gettext('Sidebar boxholder'),
    'description' => __gettext('Content for each block'),
    'glossary' => array(
        '{{title}}' => __gettext('Title of block'),
        '{{body}}' => __gettext('Body of block'),
        ),
    );

$sidebarholder = <<< END
    <div class="sidebar-title">
        <h2>{{title}}</h2>
    </div>

    <div class="sidebar-body">
        {{body}}
    </div>
END;

// block wrapper
$template_definition[] = array(
    'id' => 'sidebar:block',
    'name' => __gettext('Sidebar block'),
    'description' => __gettext('Sidebar block holder'),
    'glossary' => array(
        '{{id}}' => __gettext('CSS id'),
        '{{class}}' => __gettext('CSS class'),
        '{{body}}' => __gettext('Body of block'),
        ),
    );


$sidebar_block = <<< END

<div id="{{id}}" class="sidebar-block {{class}}">
    {{body}}
</div>
END;

// add templates and override if exists
templates_add_context('sidebar:wrap', $sidebar_wrap, false, true);
templates_add_context('sidebarholder', $sidebarholder, false, true);
templates_add_context('sidebar:block', $sidebar_block, false, true);

?>
