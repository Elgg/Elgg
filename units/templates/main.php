<?php

    /*
    *    Templates unit
    */

    // Load default values
        $function['init'][] = $CFG->dirroot . "units/templates/default_template.php";
        
    // Actions
        $function['templates:init'][] = $CFG->dirroot . "units/templates/template_actions.php";

    // Draw template (returns HTML as opposed to echoing it straight to the screen)
        $function['templates:draw'][] = $CFG->dirroot . "units/templates/template_draw.php";
        
    // Function to substitute variables within a template, used in templates:draw
        $function['templates:variables:substitute'][] = $CFG->dirroot . "units/templates/variables_substitute.php";

    // Function to draw the page, once supplied with a main body and title
        $function['templates:draw:page'][] = $CFG->dirroot . "units/templates/page_draw.php";
        
    // Function to display a list of templates
        $function['templates:view'][] = $CFG->dirroot . "units/templates/templates_view.php";
        $function['templates:preview'][] = $CFG->dirroot . "units/templates/templates_preview.php";
                
    // Function to display input fields for template editing
        $function['templates:edit'][] = $CFG->dirroot . "units/templates/templates_edit.php";
        
    // Function to allow the user to create a new template
        $function['templates:add'][] = $CFG->dirroot . "units/templates/templates_add.php";
        
?>