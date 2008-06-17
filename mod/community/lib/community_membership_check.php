<?php


/*
 * community_members_data.php
 *
 * Created on May 4, 2007
 *
 * @author Diego Andr�s Ram�rez Arag�n <diego@somosmas.org>
 * @copyright Corporaci�n Somos m�s - 2007
 */
global $CFG;

if (isset ($parameter)) {
  $page_owner= $parameter[0];
  $community_owner = $parameter[1];

  $result= count_records_sql('SELECT COUNT(u.ident) FROM '.$CFG->prefix.'friends f
                              LEFT JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                              WHERE f.owner = ? AND f.friend = ? 
                              AND u.user_type =?' ,array($page_owner,$community_owner,'community') 
                              );
  $run_result = $result;
}
?>
