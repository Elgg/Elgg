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
  $page_owner= $parameter[0];
  $community_owner = $parameter[1];

  $result= get_record_sql('SELECT count(u.ident) as member FROM '.$CFG->prefix.'friends f
                                                 JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                                                 WHERE u.ident = ? AND f.owner = ? AND u.user_type = ?',
                                      array($page_owner,$community_owner,'community')
                              );
  $run_result = $result->member;
}
?>
