<?php

/*
 * community_members_data.php
 *
 * Created on May 4, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2007
 */
global $CFG;

if (isset ($parameter)) {
  $community_id = $parameter;
  if (COMMUNITY_ALLOW_COMMUNITY_TYPE_MEMBERS) {
    $run_result = get_record_sql('SELECT count(*) as members FROM ' . $CFG->prefix . 'friends f
                                   JOIN ' . $CFG->prefix . 'users u ON u.ident = f.owner
                                   WHERE f.friend = ?', array ($community_id));

  } else {
    $run_result = get_record_sql('SELECT count(*) as members FROM ' . $CFG->prefix . 'friends f
                                   JOIN ' . $CFG->prefix . 'users u ON u.ident = f.owner
                                   WHERE f.friend = ? AND u.user_type = ?', array ($community_id,'person'));
  }

  if(!empty($run_result)){
    $run_result = $run_result->members;
  }else{
    $run_result = 0;
  }
}
?>
