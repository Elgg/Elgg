<?php

    // Draw a page element using a specified template (or, if the template is -1, the default)
    // $parameter['template'] = the template ID, $parameter['element'] = the template element,
    // all other $parameter[n] = template elements

    // Initialise global template variable, which contains the default template
        global $template;
        
    // Initialise global template ID variable, which contains the template ID we're using
        global $template_id;
        global $page_owner;
        
        global $page_template_cache;
        
        if (!isset($page_template_cache)) {
            $page_template_cache = array();
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
        if (isset($_REQUEST['template_preview'])) {
            $template_id = (int) $_REQUEST['template_preview'];
        }
        
    // Grab the template content
        if ($template_id == -1) {
            $template_element = $template[$parameter['context']];
        } else {
            $template_context = $parameter['context'];
                if (!isset($page_template_cache[$template_context])) {
                    $page_template_cache[$template_context] = get_record('template_elements','template_id',$template_id,'name',$template_context);
                }
                
                $result = $page_template_cache[$template_context];
                echo "<pre>" . var_export($page_template_cache,true) . "</pre>";

                if (!empty($result)) {
                    $template_element = $result->content;
                } else {
                    $template_element = $template[$parameter['context']];
                }
        }

    // Substitute elements

        $functionbody = "
            \$passed = array(".var_export($parameter,true).",\$matches[1], " . $template_id . ");
            return run('templates:variables:substitute', \$passed);
        ";
        
        // $template_element = run("templates:variables:substitute",array($parameter,$template_element));
        $body = preg_replace_callback("/\{\{([A-Za-z_0-9]*)\}\}/i",create_function('$matches',$functionbody),$template_element);
        
        $run_result = $body;
        
?>