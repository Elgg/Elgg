<?php
global $USER, $CFG;
    // Actions

        global $template;
        
        if (isset($_REQUEST['action']) && logged_on && !$CFG->disable_templatechanging) {
            
            switch($_REQUEST['action']) {
                
                case "templates:select":
                    if (isset($_REQUEST['selected_template'])) {
                        $id = (int) $_REQUEST['selected_template'];
                        if ($id == -1) {
                            $exists = 1;
                        } else {
                            $exists = record_exists_sql('SELECT ident FROM '.$CFG->prefix.'templates t
                                                         WHERE ident = '.$id.' AND (owner = '.$USER->ident." OR public = 'yes')");
                        }
                        if ($exists) {
                            if(sizeof($_REQUEST['affected_areas'])) {
                                foreach($_REQUEST['affected_areas'] as $index => $value) {
                                    //TODO - check security
                                    set_field('users','template_id',$id,'ident',$value);
                                }
                                $messages[] = __gettext("The templates have been changed according to your choices.");
                            }
                            else {
                                $messages[] = __gettext("No changes made as no area of change where selected!");
                            }                            
                        }
                    }
                    break;
                    
                    
                case "templates:save":
                    if (
                            isset($_REQUEST['template'])
                            && isset($_REQUEST['save_template_id'])
                            && isset($_REQUEST['templatetitle'])
                        ) {
                            $id = (int) $_REQUEST['save_template_id'];
                            unset($_SESSION['template_element_cache'][$id]);
                            if (record_exists('templates','ident',$id,'owner',$USER->ident)) {
                                $templatetitle = trim($_REQUEST['templatetitle']);
                                set_field('templates','name',$templatetitle,'ident',$id);
                                delete_records('template_elements','template_id',$id);
                                foreach($_REQUEST['template'] as $name => $content) {
                                    $te = new StdClass;
                                    $te->name = trim($name);
                                    $te->content = trim($content);
                                    $te->template_id = $id;
                                    $noslashname = stripslashes($te->name);
                                    $noslashcontent = stripslashes($te->content);
                                    if ($noslashcontent != "" && $noslashcontent != $template[$noslashname]) {
                                        insert_record('template_elements',$te);
                                    }
                                }
                                $messages[] = __gettext("Your template has been updated.");
                            }
                        }
                    break;
                    
                    
                case "deletetemplate":
                    if (
                            isset($_REQUEST['delete_template_id'])
                        ) {
                            $id = (int) $_REQUEST['delete_template_id'];
                            unset($_SESSION['template_element_cache'][$id]);
                            if (record_exists('templates','ident',$id,'owner',$USER->ident)) {
                                set_field('users','template_id',-1,'template_id',$id);
                                delete_records('template_elements','template_id',$id);
                                delete_records('templates','ident',$id);
                                $messages[] = __gettext("Your template was deleted.");
                            }
                        }
                    break;
                    
                    
                case "templates:create":
                    $name = optional_param('new_template_name');
                    $based_on = optional_param('template_based_on',0,PARAM_INT);
                    if (empty($CFG->disable_usertemplates) && !empty($name)) {
                            $t = new StdClass;
                            $t->name = trim($name);
                            $t->owner = $USER->ident;
                            $t->public = 'no';
                            $new_template_id = insert_record('templates',$t);
                            if (!empty($based_on) && $based_on != -1) {
                                if (record_exists_sql('SELECT ident FROM '.$CFG->prefix.'templates t 
                                                       WHERE ident = '.$based_on.' AND (owner = '.$USER->ident." OR public = 'yes')")) {
                                    if ($elements = get_records('template_elements','template_id',$based_on)) {
                                        foreach($elements as $element) {
                                            $te = new StdClass;
                                            $te->name = addslashes($element->name);
                                            $te->content = addslashes($element->content);
                                            $te->template_id = $new_template_id;
                                            insert_record('template_elements',$te);
                                        }
                                    }
                                }
                            }
                        }
                    break;
                
            }
            
        }

?>