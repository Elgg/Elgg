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
															$messages[] = "Your post has been added to your weblog.";
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
														$exists = db_query("select count(ident) as post_exists
																					from weblog_posts
																					where ident = $id and
																					owner = ".$_SESSION['userid']);
														$exists = $exists[0]->post_exists;
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
																		db_query("insert into tags set tagtype = 'weblog', access = '$access', tag = '$list_item', ref = $id, owner = " . $_SESSION['userid']);
																	}
																}
															}
															$messages[] = "Your post has been modified.";
														}
														
													}
													break;
				// Delete a weblog post
					case "delete_weblog_post":	if (
														logged_on
														&& isset($_REQUEST['delete_post_id'])
													) {
														$id = (int) $_REQUEST['delete_post_id'];
														$post_info= db_query("select * from weblog_posts where ident = $id");
														if ($post_info[0]->owner == $_SESSION['userid']) {
															db_query("delete from weblog_posts where ident = $id");
															db_query("delete from weblog_comments where post_id = $id");
															db_query("delete from tags where tagtype = 'weblog' and ref = $id");
															$messages[] = "Your weblog post was deleted.";
														} else {
															$messages[] = "You do not appear to own this weblog post. It was not deleted.";
														}
														global $redirect_url;
														$redirect_url = url . run("users:id_to_name",$post_info[0]->weblog) . "/weblog/";
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
															$messages[] = "Your comment has been added.";
															
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
																	$messages[] = "Your comment was deleted.";
																	$redirect_url = url . run("users:id_to_name",$commentinfo->postowner) . "/weblog/" . $commentinfo->postid . ".html";
																	define('redirect_url',$redirect_url);
															}
														}
												break;
				
			}
			
		}

?>