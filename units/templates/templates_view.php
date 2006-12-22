<?php
global $USER;
        $user_template = user_info('template_id', $USER->ident);
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
            $column1 = "<h4>" . $template['name'] . "</h4>";
            $column2 = "<a href=\"".url."_templates/preview.php?template_preview=".$template['id']."\" target=\"preview\">" . __gettext("preview") . "</a>";
            $panel .= templates_draw(array(
                                              'context' => 'adminTable',
                                              'name' => $name,
                                              'column1' => $column1,
                                              'column2' => $column2
                                          )
                                    );
        }
        $templates = get_records('templates','owner',$USER->ident);
        $header2 = __gettext("Personal themes"); // gettext variable
        $desc2 = __gettext("These are themes that you have created. You can edit and delete these. These theme(s) only control actual look and feel - you cannot change any content here. To change any of your content you need to use the other menu options such as: edit profile, update weblog etc."); // gettext variable

        if (!empty($templates)) {
            $panel .= <<< END
    <br />
    <h2>
        $header2
    </h2>
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
                    $column1 = "<h4>" . stripslashes($template->name) . "</h4>";
                    $column2 = "<a href=\"".url."_templates/preview.php?template_preview=".$template->ident."\" target=\"preview\">" . __gettext("preview") . "</a>";

                    $column2 .= " | <a href=\"".url."_templates/edit.php?id=".$template->ident."\" >". __gettext("Edit") ."</a>";
                    $column2 .= " | <a href=\"".url."_templates/?action=deletetemplate&amp;delete_template_id=".$template->ident."\"  onclick=\"return confirm('" . __gettext("Are you sure you want to permanently remove this template?") . "')\">" . __gettext("Delete") . "</a>";
                    $panel .= templates_draw(array(
                                                        'context' => 'adminTable',
                                                        'name' => $name,
                                                        'column1' => $column1,
                                                        'column2' => $column2
                                                    )
                                                    );
            }
            
        }
        
        $ownerCommunities = get_records('users','owner',$USER->ident);
        $header3 = __gettext("Change templates");
        $decs3 = __gettext("The selected changes will affect:");
        
        $panel .= <<< END
<br />
    <h2>
        $header3
    </h2>
    <p>
        $decs3
    </p>
END;
    
        $name = "<input type='checkbox' name='affected_areas[]' value='".$USER->ident."' checked=\"checked\" />";
        $column1 = "<h4>User page</h4>";
        $column2 = "<h4>". __gettext("Your personal space") ."</h4>";
        $panel .= templates_draw(array(
                            'context' => 'adminTable',
                        'name' => $name,
                        'column1' => $column1,
                        'column2' => $column2
                        )
                        );
    
        if(!empty($ownerCommunities)) {
            foreach($ownerCommunities as $ownerCommunity) {
                $name = "<input type='checkbox' name='affected_areas[]' value='".$ownerCommunity->ident."' />";
                $column1 = "<h4>".stripslashes($ownerCommunity->name)."</h4>";
                $column2 = "<h4>".__gettext("Community: ") . stripslashes($ownerCommunity->name)."</h4>";
                $panel .= templates_draw(array(
                                    'context' => 'adminTable',
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
            
?>