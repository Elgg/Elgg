<?php

	// Actions to perform on the groups screen
	
		if (isset($_REQUEST['action'])) {
			switch($_REQUEST['action']) {
				
				// Create a new group
				case "group:create":
										if (isset($_POST['name']) && logged_on) {
											$name = addslashes($_POST['name']);
											$ident = (int) $_SESSION['userid'];
											db_query("insert into groups set
														name = '$name',
														owner = $ident");
											unset($_SESSION['groups_cache']);
										}
										break;
				// Edit a group
				case "group:edit":
										if (
												logged_on &&
												isset($_REQUEST['groupid']) &&
												isset($_REQUEST['groupname'])
											) {
												$groupid = (int) $_REQUEST['groupid'];
												$ident = (int) $_SESSION['userid'];
												$name = addslashes($_REQUEST['groupname']);
												db_query("update groups set name = '$name'
																		where owner = $ident
																		and ident = $groupid");
												unset($_SESSION['groups_cache']);
												$messages[] = gettext("Your group was updated.");
												foreach($data['access'] as $key => $accessarray) {
													if ($accessarray[1] == "group" . $groupid) {
														$data['access'][$key] = array(stripslashes($_REQUEST['groupname']),"group" . $groupid);
													}
												}
											}
										break;
				// Delete a group
				case "group:delete":
										if (isset($_POST['groupid']) && logged_on) {
											$groupid = (int) $_POST['groupid'];
											$ident = (int) $_SESSION['userid'];
											run("groups:delete",$groupid);
											db_query("delete from groups where ident = $groupid and owner = $ident");
											if (db_affected_rows() > 0) {
												db_query("delete from group_membership where group_id = $groupid");
											}
											unset($_SESSION['groups_cache']);
										} else {
											// var_export($_POST);
										}
										break;
				// Add someone to a group
				case "group:addmember":
										if (isset($_POST['groupid']) && logged_on) {
											$groupid = (int) $_POST['groupid'];
											$ident = (int) $_SESSION['userid'];
											$exists = db_query("select * from groups where ident = $groupid and owner = $ident");
											if (sizeof($exists) > 0) {
												if (sizeof($_POST['friends']) > 0) {
													foreach($_POST['friends'] as $newmember) {
														$newmember = (int) $newmember;
														$exists = db_query("select * from group_membership
																				where user_id = $newmember
																				and group_id = $groupid");
														if (sizeof($exists) < 1) {
															db_query("insert into group_membership
																				set user_id = $newmember,
																					group_id = $groupid");
														} 
													}
												} 
											} 
											unset($_SESSION['groups_cache']);
										}
										break;
				// Remove someone from a group
				case "group:removemember":
										if (isset($_POST['groupid']) && logged_on) {
											$groupid = (int) $_POST['groupid'];
											$ident = (int) $_SESSION['userid'];
											$exists = db_query("select * from groups where ident = $groupid and owner = $ident");
											if (sizeof($exists) > 0) {
												if (sizeof($_POST['members']) > 0) {
													foreach($_POST['members'] as $newmember) {
														$newmember = (int) $newmember;
														db_query("delete from group_membership where user_id = $newmember
																								and group_id = $groupid");
													}
												}
											}
											unset($_SESSION['groups_cache']);
										}
										break;
				
			}
			
		}

?>