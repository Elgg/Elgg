<?php

$run_result .= templates_add();
/*
global $USER;
global $CFG;

if (empty($CFG->disable_usertemplates)) {
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

        $panel .= templates_draw(array(
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
        
        if ($templates = get_records_select('templates',"owner = ? OR public = ?",array($USER->ident,'yes'),'public')) {
            foreach($templates as $template) {
                $column1 .= "<option value=\"".$template->ident."\">".stripslashes($template->name) . "</option>";
            }
        }
        
        $column1 .= <<< END
            </select>
END;

        
            $panel .= templates_draw(array(
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

}

$run_result .= $panel;
*/
?>