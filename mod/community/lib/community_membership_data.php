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
  $all = false;
  $page_owner= $parameter[0];
  if(count($parameter)== 2){
    $all = $parameter[1];
  }
  if(!$all){
    $run_result= get_records_sql('SELECT DISTINCT u.ident,u.username,u.name FROM ' . $CFG->prefix . 'friends f
                                         JOIN ' . $CFG->prefix . 'users u ON u.ident = f.friend
                                         WHERE f.owner = ? AND u.user_type = ? AND u.owner != ?', array ($page_owner,'community',$page_owner)
                              );
  }
  else{
    $run_result = get_records_sql('SELECT u.*, f.ident AS friendident FROM '.$CFG->prefix.'friends f
                               JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                               WHERE f.owner = ? AND u.user_type = ?', array($page_owner,'community'));
  }
}
?>
