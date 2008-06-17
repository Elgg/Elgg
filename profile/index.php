<?php

//    ELGG profile view page

// Run includes
require_once(dirname(dirname(__FILE__)) . '/includes.php');
require_once($CFG->dirroot . 'profile/profile.class.php');

// define what profile to show
$profile_name = optional_param('profile_name', '', PARAM_ALPHANUM);
if (!empty($profile_name)) {
    $profile_id = user_info_username('ident', $profile_name);
}
if (empty($profile_id)) {
    $profile_id = optional_param('profile_id', -1, PARAM_INT);
}
// and the page_owner naturally
$page_owner = $profile_id;

define("context", "profile");
templates_page_setup();

// two column version

class ElggProfile2 extends ElggProfile {
    
    function view () {

        global $data;
        global $page_owner;
        global $CFG;
        
        $run_result = '';
        $usertype = user_type($page_owner);
        
        $icon = user_info('icon',$page_owner);
        $username = user_info('username',$page_owner);
        $icon_url = $CFG->wwwroot.'_icon/user/'.$icon.'/w/240';
        
        // $first_column_fields = array('biography','likes','dislikes');
        // $id_block_fields = array('gender','town','country','birth_date');

        
        // Cycle through all defined profile detail fields and display them

        $allvalues = get_records('profile_data','owner',$this->id);
        $first_column_fields = array();
        $second_column_fields = array();
        $firstcol = "";
        $secondcol = "";
        foreach($data['profile:details'] as $field) {
            if (is_array($field)) {
                $flabel = !empty($field[0]) ? $field[0] : '';
                $fname  = !empty($field[1]) ? $field[1] : '';
                $ftype  = !empty($field[2]) ? $field[2] : '';
                $fblurb = !empty($field[3]) ? $field[3] : '';
                $fusertype = !empty($field[4]) ? $field[4] : '';
                $finvisible = false;
                $frequired = false;
                $fcat = __gettext("Main");
            // Otherwise map things the new way!
            } else {
                $flabel = $field->name;
                $fname = $field->internal_name;
                $ftype = $field->field_type;
                $fblurb = $field->description;
                $fusertype = $field->user_type;
                $finvisible = $field->invisible;
                $frequired = $field->required;
                if (!isset($field->col1)) {
                    $col1 = false;
                } else {
                    $col1 = $field->col1;
                    $first_column_fields[] = $fname;
                }
                if (!isset($field->col2)) {
                    $col2 = false;
                } else {
                    $col2 = $field->col2;
                    $second_column_fields[] = $fname;
                }
                if (!empty($field->category)) {
                    $fcat = $field->category;
                } else {
                    $fcat = __gettext("Main");
                }
            }
            if (empty($fusertype) || $usertype == $fusertype) {
            // $field is an array, with the name
            // of the field in $field[0]
                if (in_array($fname,$first_column_fields)) {
                    $firstcol .= $this->field_display($field,$allvalues);
                } else if (in_array($fname,$second_column_fields)) {
                    $secondcol .= $this->field_display($field,$allvalues);
                }
            }
        }
        // $other_fields = array_merge($first_column_fields,$second_column_fields);
        $run_result .= '<div class="profile_main">'."\n";
        $run_result .= '<div class="profile_primary">'."\n";
        // $run_result .= '<div class="profile_icon"><img src="'.$icon_url.'"></div>'."\n";
        $run_result .= $firstcol;
        $run_result .= templates_draw(array(
                                                           'context' => 'databox1',
                                                           'name' => __gettext("Extended profile"),
                                                           'column1' => "<a href=\"{$CFG->wwwroot}profile/extended.php?profile_name={$username}\">" . __gettext("Click here to view extended profile") . "</a>"
                                                           )
                                   );
        $run_result .= '</div>'."\n";        
        $run_result .= '<div class="profile_secondary">'."\n";
        
        $run_result .= $secondcol;
        $run_result .= "</div>\n";
        $run_result .= '<div class="profile_main_bottom"></div>'."</div>\n";

                
        // Draw the user's comment wall
		if (function_exists("commentwall_displayonprofile")) {
			
			$offset = optional_param('offset', 0);
			$limit = optional_param('limit', 3);
			$run_result .= commentwall_displayonprofile($page_owner, $limit, $offset); 
		}  
        
        $view = array();
        $view['body'] = $run_result;
        
        $run_result = '';
        
        $username = user_info('username',$this->id);
        $run_result .= '<div id="profile_widgets">'."\n"; 
        $run_result .= widget_page_display($page_owner,'profile',0,2);     

        $run_result .= "</div>\n";
        
        $view['body'] .= $run_result;
        
        return $view;
    }
    
    function bare_field_display ($field, $allvalues) {

        global $data;

        $run_result = '';

        if (is_array($field)) {
            $flabel = !empty($field[0]) ? $field[0] : '';
            $fname  = !empty($field[1]) ? $field[1] : '';
            $ftype  = !empty($field[2]) ? $field[2] : '';
            $fblurb = !empty($field[3]) ? $field[3] : '';
            $fusertype = !empty($field[4]) ? $field[4] : '';
            $finvisible = false;
            $frequired = false;
            $fcat = __gettext("Main");
        // Otherwise map things the new way!
        } else {
            $flabel = $field->name;
            $fname = $field->internal_name;
            $ftype = $field->field_type;
            $fblurb = $field->description;
            $fusertype = $field->user_type;
            $finvisible = $field->invisible;
            $frequired = $field->required;
            if (!empty($field->category)) {
                $fcat = $field->category;
            } else {
                $fcat = __gettext("Main");
            }
        }
    
        // $value = get_record('profile_data','name',$field[1],'owner',$this->id);
    
        foreach($allvalues as $curvalue) {
            if ($curvalue->name == stripslashes($fname)) {
                $value = $curvalue;
                break; // found it, done!
            }
        }

        if (!isset($value)) {
            return '';
        }

        if ((($value->value != "" && $value->value != "blank")) 
            && run("users:access_level_check", $value->access)) {
            $column1 = display_output_field(array($value->value,$ftype,$fname,$flabel,$value->ident));
            $run_result .= $column1;
        }
        return $run_result;
    }

        
    function get_value ($field, $allvalues) {

        global $data;

        $run_result = '';

        if (is_array($field)) {
            $flabel = !empty($field[0]) ? $field[0] : '';
            $fname  = !empty($field[1]) ? $field[1] : '';
            $ftype  = !empty($field[2]) ? $field[2] : '';
            $fblurb = !empty($field[3]) ? $field[3] : '';
            $fusertype = !empty($field[4]) ? $field[4] : '';
            $finvisible = false;
            $frequired = false;
            $fcat = __gettext("Main");
        // Otherwise map things the new way!
        } else {
            $flabel = $field->name;
            $fname = $field->internal_name;
            $ftype = $field->field_type;
            $fblurb = $field->description;
            $fusertype = $field->user_type;
            $finvisible = $field->invisible;
            $frequired = $field->required;
            if (!empty($field->category)) {
                $fcat = $field->category;
            } else {
                $fcat = __gettext("Main");
            }
        }
    
        // $value = get_record('profile_data','name',$field[1],'owner',$this->id);
    
        foreach($allvalues as $curvalue) {
            if ($curvalue->name == stripslashes($fname)) {
                $value = $curvalue;
                break; // found it, done!
            }
        }

        if (!isset($value)) {
            return '';
        }

        if ((($value->value != "" && $value->value != "blank")) 
            && run("users:access_level_check", $value->access)) {
            return $value->value;
        }

        return '';
    }
    

    function doRelativeDate($in_seconds) {
        /**
            This function returns either a relative date or a formatted date depending
            on the difference between the current datetime and the datetime passed.
                $posted_date should be in the following format: YYYYMMDDHHMMSS
            
            Relative dates look something like this:
                3 weeks, 4 days ago
            
            The function includes 'ago' or 'on' and assumes you'll properly add a word
            like 'Posted ' before the function output.
        **/
    
        $diff = time()-$in_seconds;
        $months = floor($diff/2592000);
        $diff -= $months*2419200;
        $weeks = floor($diff/604800);
        $diff -= $weeks*604800;
        $days = floor($diff/86400);
        $diff -= $days*86400;
        $hours = floor($diff/3600);
        $diff -= $hours*3600;
        $minutes = floor($diff/60);
        $diff -= $minutes*60;
        $seconds = $diff;
        
        $relative_date = '';
    
        if ($months>0) {
            // over a month old, just show date (mm/dd/yyyy format)
            return 'on '.date('r',$in_seconds);
        } else {
            if ($weeks>0) {
                // weeks and days
                $relative_date .= ($relative_date?', ':'').$weeks.' week'.($weeks>1?'s':'');
                $relative_date .= $days>0?($relative_date?', ':'').$days.' day'.($days>1?'s':''):'';
            } elseif ($days>0) {
                // days and hours
                $relative_date .= ($relative_date?', ':'').$days.' day'.($days>1?'s':'');
                $relative_date .= $hours>0?($relative_date?', ':'').$hours.' hour'.($hours>1?'s':''):'';
            } elseif ($hours>0) {
                // hours and minutes
                $relative_date .= ($relative_date?', ':'').$hours.' hour'.($hours>1?'s':'');
                $relative_date .= $minutes>0?($relative_date?', ':'').$minutes.' minute'.($minutes>1?'s':''):'';
            } elseif ($minutes>0) {
                // minutes only
                $relative_date .= ($relative_date?', ':'').$minutes.' minute'.($minutes>1?'s':'');
            } else {
                // seconds only
                $relative_date .= ($relative_date?', ':'').$seconds.' second'.($seconds>1?'s':'');
            }
        }
        // show relative date and add proper verbiage
        return $relative_date.' ago';
    }           
}

// init library
$profile = new ElggProfile2($profile_id); 
        
$title = $profile->display_name();
// $title = 'Profile';
$view  = $profile->view();

templates_page_output($title, $view['body']);

?>
