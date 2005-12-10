<?php

	// Actions to perform
	
		if (isset($_REQUEST['action'])) {
			
			switch($_REQUEST['action']) {
				
				// Create a new weblog post
					case "weblogs:post:add":	if (
														logged_on
														&& isset($_REQUEST['new_weblog_title'])
														&& isset($_REQUEST['new_weblog_post'])
														&& isset($_REQUEST['new_weblog_access'])
														&& isset($_REQUEST['new_weblog_keywords'])
														&& run("permissions:check", "weblog")
													) {
														$title = addslashes($_REQUEST['new_weblog_title']);
														$body = addslashes($_REQUEST['new_weblog_post']);
														$access = addslashes($_REQUEST['new_weblog_access']);
														db_query("insert into weblog_posts
																	set title = '$title',
																		body = '$body',
																		access = '$access',
																		posted = ".time().",
																		weblog = $page_owner,
																		owner = ".$_SESSION['userid']);
														$insert_id = db_id();
														if ($_REQUEST['new_weblog_keywords'] != "") {
															$value = $_REQUEST['new_weblog_keywords'];
															$value = str_replace("\n","",$value);
															$value = str_replace("\r","",$value);
															$keyword_list = explode(",",$value);
															sort($keyword_list);
															if (sizeof($keyword_list) > 0) {
																foreach($keyword_list as $key => $list_item) {
																	$list_item = addslashes(trim($list_item));
																	db_query("insert into tags set tagtype = 'weblog', access = '$access', tag = '$list_item', ref = $insert_id, owner = " . $_SESSION['userid']);
																}
															}
														}
														if (run("users:type:get",$page_owner) == "person") {
															$messages[] = gettext("Your post has been added to your weblog.");
														}
														// define('redirect_url',url . $_SESSION['username'] . "/weblog/");
														define('redirect_url',url . run("users:id_to_name", $page_owner) . "/weblog/");
													}
													break;
				// Edit a weblog post
					case "weblogs:post:edit":	if (
														logged_on
														&& isset($_REQUEST['edit_weblog_title'])
														&& isset($_REQUEST['new_weblog_post'])
														&& isset($_REQUEST['edit_weblog_access'])
														&& isset($_REQUEST['edit_weblog_post_id'])
														&& isset($_REQUEST['edit_weblog_keywords'])
													) {
														$id = (int) $_REQUEST['edit_weblog_post_id'];
														$title = addslashes($_REQUEST['edit_weblog_title']);
														$body = addslashes($_REQUEST['new_weblog_post']);
														$access = addslashes($_REQUEST['edit_weblog_access']);
														$exists = db_query("select count(ident) as post_exists, owner 
																					from weblog_posts
																					where ident = $id
																					group by ident")
																					or die(mysql_error());
														$owner = $exists[0]->owner;
														$exists = $exists[0]->post_exists;
														
														if (!run("permissions:check", array("weblog:edit", $owner))) {
															$exists = false;
														}
														
														if ($exists) {
															db_query("update weblog_posts
																		set title = '$title',
																			body = '$body',
																			access = '$access'
																		where ident = $id");
															db_query("delete from tags where tagtype = 'weblog' and ref = $id");
															if ($_REQUEST['edit_weblog_keywords'] != "") {
																$value = $_REQUEST['edit_weblog_keywords'];
																$value = str_replace("\n","",$value);
																$value = str_replace("\r","",$value);
																$keyword_list = explode(",",$value);
																sort($keyword_list);
																if (sizeof($keyword_list) > 0) {
																	foreach($keyword_list as $key => $list_item) {
																		$list_item = addslashes(trim($list_item));
																		db_query("insert into tags set tagtype = 'weblog', access = '$access', tag = '$list_item', ref = $id, owner = $owner");
																	}
																}
															}
                                                                                               
															$messages[] = gettext("The weblog post has been modified."); // gettext variable
														}
														
													}
													break;
				// Delete a weblog post
					case "delete_weblog_post":	if (
														logged_on
														&& isset($_REQUEST['delete_post_id'])
													) {
														$id = (int) $_REQUEST['delete_post_id'];
														$post_info = db_query("select * from weblog_posts where ident = $id");
														$post_info = $post_info[0];
														if (run("permissions:check", array("weblog:edit", $post_info->owner))) {
															db_query("delete from weblog_posts where ident = $id");
															db_query("delete from weblog_comments where post_id = $id");
															db_query("delete from tags where tagtype = 'weblog' and ref = $id");
                                                            $modified2 = gettext("The selected weblog post was deleted."); // gettext variable - NOT SURE ABOUT THIS POSITION!!!
															$messages[] = "$modified2";
														} else {
                                                            $messages[] = gettext("You do not appear to have permissions to delete this weblog post. It was not deleted."); // gettext variable
														}
														global $redirect_url;
														$redirect_url = url . run("users:id_to_name",$post_info->weblog) . "/weblog/";
														define('redirect_url',$redirect_url);
													}
													break;
				// Create a weblog comment
					case "weblogs:comment:add":	if (
														isset($_REQUEST['post_id'])
														&& isset($_REQUEST['new_weblog_comment'])
														&& isset($_REQUEST['postedname'])
														&& isset($_REQUEST['owner'])
													) {
														$post_id = (int) $_REQUEST['post_id'];
														$where = run("users:access_level_sql_where",$_SESSION['userid']);
														$post = db_query("select ident from weblog_posts where ($where) and ident = $post_id");
														if (sizeof($post) > 0) {
															
															$post_id = (int) $_REQUEST['post_id'];
															$body = addslashes($_REQUEST['new_weblog_comment']);
															$postedname = addslashes($_REQUEST['postedname']);
															$owner = (int) $_SESSION['userid'];
															$posted = time();
															db_query("insert into weblog_comments
																		set body = '$body',
																			posted = $posted,
																			postedname = '$postedname',
																			owner = $owner,
																			post_id = $post_id");
                                                                                                       $messages[] = gettext("Your comment has been added."); // gettext variable
															
														}
													}
												break;
				// Delete a weblog comment
					case "weblog_comment_delete":	if (
															logged_on
															&& isset($_REQUEST['weblog_comment_delete'])
														) {
															$comment_id = (int) $_REQUEST['weblog_comment_delete'];
															$commentinfo = db_query("select weblog_comments.*, weblog_posts.owner as postowner,
																					 weblog_posts.ident as postid
																					 from weblog_comments
																					 left join weblog_posts on weblog_posts.ident = weblog_comments.post_id
																					 where weblog_comments.ident = $comment_id");
															$commentinfo = $commentinfo[0];
															if ($_SESSION['userinfo'] == $commentinfo->owner
																|| $_SESSION['userinfo'] == $comentinfo->postowner) {
																	db_query("delete from weblog_comments where ident = $comment_id");
																	$messages[] = gettext("Your comment was deleted.");
																	$redirect_url = url . run("users:id_to_name",$commentinfo->postowner) . "/weblog/" . $commentinfo->postid . ".html";
																	define('redirect_url',$redirect_url);
															}
														}
												break;
				
			}
			
		}

?>