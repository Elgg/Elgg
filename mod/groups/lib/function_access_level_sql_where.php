<?php

    // Returns an SQL "where" clause containing all the access codes that the user can see
    
if (isloggedin()) {
            
  $groupslist = array();
  
  if ($groups = run("groups:getmembership",array($_SESSION['userid']))) {
    foreach($groups as $group) {
      $groupslist[] = $group->ident;
    }
    if (empty($run_result)) {
                $run_result = '';
            } else {
                $run_result .= ' OR ';
            }
    
    $run_result .= " access IN ('group" . implode("', 'group", $groupslist) . "')";
  }
  
 }

?>