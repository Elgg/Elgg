<?php
global $CFG;
global $USER;
// Display icons and allow user to edit their names or delete some
    
global $page_owner;
$page_username = user_info('username', $page_owner);

// Get all icons associated with a user
$icons = get_records('icons','owner',$page_owner);

if ($page_owner != $USER->ident) {
    $currenticon = get_field_sql('SELECT i.ident 
                                  FROM '.$CFG->prefix.'users u 
                                  JOIN '.$CFG->prefix.'icons i ON i.ident = u.icon
                                  WHERE u.ident = ?',array($page_owner));
} else {
    $currenticon = $USER->icon;
}

$header = __gettext("Site pictures"); // gettext variable
$body = <<< END
        <h2>
            $header
        </h2>
END;
        
// If we have some icons, display them; otherwise explain that there isn't anything to edit
if (!empty($icons)) {
    
    $desc = __gettext("Site pictures are small pictures that act as a representative icon throughout the system."); // gettext variable
    $body .= <<< END
        <form action="" method="post">        
            <p>
                $desc
            </p>
END;
    foreach($icons as $icon) {
        
        $delete = __gettext("Delete");
        $name = <<< END
                        <label for="icons_deletecheckbox{$icon->ident}">$delete:
                            <input type="checkbox" id="icons_deletecheckbox{$icon->ident}" name="icons_delete[]" value="{$icon->ident}" />
                        </label>
END;
        $defaulticon = htmlspecialchars(stripslashes($icon->description), ENT_COMPAT, 'utf-8');
        $column1 = <<< END
                        <img alt="{$defaulticon}" src="{$CFG->wwwroot}_icon/user/{$icon->ident}" />
END;
        if ($icon->ident == $currenticon) {
            $checked = 'checked="checked"';
        } else {
            $checked = "";
        }
        $nameLabel = __gettext("Name:");//gettext variable
        $default = __gettext("Default:");//gettext variable
        $column2 = <<< END
                        <label>$nameLabel
                            <input type="text" name="description[{$icon->ident}]" value="{$defaulticon}" />
                        </label><br />
                        <label>$default <input type="radio" name="defaulticon" value="{$icon->ident}" {$checked} /></label>
END;

        $body .= templates_draw(array(
                                      'context' => 'databox',
                                      'name' => $column1,
                                      'column1' => $column2,
                                      'column2' => $name
                                      )
                                );
        
    }
    
    if ($_SESSION['icon'] == "default.png") {
        $checked = 'checked="checked"';
    } else {
        $checked = "";
    }
    $noDefault = __gettext("No default:");
    $column1 = <<< END
                        <label>$noDefault
                        <input type="radio" name="defaulticon" value="-1" {$checked} /></label>
END;
    $body .= templates_draw(array(
                                  'context' => 'databox',
                                  'column1' => $column1
                                  )
                            );
    $save = __gettext("Save"); // gettext variable
    $body .= <<< END
                <p align="center">
                    <input type="hidden" name="action" value="icons:edit" />
                    <input type="submit" value="$save" />        
                </p>
            </form>
END;
    
} else {
    
    $noneLoaded = __gettext("You don't have any site pictures loaded yet."); // gettext variable
    $body .= <<< END
        <p>
            $noneLoaded
        </p>
END;
            
}

$run_result .= $body;
?>