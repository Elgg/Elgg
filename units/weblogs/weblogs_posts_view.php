<?php
	
	if (isset($parameter)) {
		
		$post = $parameter;
		
		global $post_authors;
		global $individual;
		
		$url = url;
		
		if (!isset($post_authors[$post->owner])) {
			
			$author = "";
			
			$stuff = db_query("select users.* from users where ident = ".$post->owner);
			$stuff = $stuff[0];
			
			$author->fullname = stripslashes($stuff->name);
			
			if ($stuff->icon == -1 || $post->owner == -1) {
				$author->icon = "default.png";
			} else {
				$icon = db_query("select filename from icons where ident = ".$stuff->icon);
				$author->icon = $icon[0]->filename;
			}
			
			$post_authors[$post->owner] = $author;
			
		}
		if (!isset($post->authors[$post->weblog])) {
			$community = "";
			
			$stuff2 = db_query("select users.* from users where ident = ".$post->weblog);
			$stuff2 = $stuff2[0];
			
			$community->fullname = stripslashes($stuff2->name);
			
			if (!$stuff2->icon || $stuff2->icon == -1) {
				$community->icon = "default.png";
			} else {
				$icon = db_query("select filename from icons where ident = ".$stuff2->icon);
				$community->icon = $icon[0]->filename;
			}
			
			$post_authors[$post->weblog] = $community;
		}
		
		$date = gmdate("H:i",$post->posted);
		$username = run("users:id_to_name",$post->owner);
		$usericon = $post_authors[$post->owner]->icon;
		if ($usericon == "default.png") {
			$usericon = $post_authors[$post->weblog]->icon;
		}
		$fullname = $post_authors[$post->owner]->fullname;
		if ($post->access != "PUBLIC") {
			if ($post->access == "LOGGED_IN") {
				$title = "[" . gettext("Logged in users") . "]";
			} else if (substr_count($post->access, "user") > 0) {
				$title = "[" . gettext("Private") . "]";
			} else {
				$title = "[" . gettext("Restricted") . "]";
			}
		} else {
			$title = "";
		}
		
		$title .= " " . stripslashes($post->title);
		
		if ($post->owner != $post->weblog) {
			
			if ($post_authors[$post->owner]->icon == -1) {
				$usericon = $post_authors[$post->weblog]->icon;
			}
			$fullname .= " @ " . $post_authors[$post->weblog]->fullname;
			$username = run("users:id_to_name",$post->weblog);
		}
		
		$body = run("weblogs:text:process",stripslashes($post->body));
		$More = gettext("More");
		$Keywords = gettext("Keywords:");
		$anyComments = gettext("comment(s)");
		$body = str_replace("{{more}}","<a href=\"" . url . "/".$username."/weblog/{$post->ident}.html\">$More ...</a>",$body);
		$keywords = run("display:output_field", array("","keywords","weblog","weblog",$post->ident,$post->owner));
		if ($keywords) {
			$body .= <<< END
			<div class="weblog_keywords">
			<p>
				$Keywords {$keywords}
			</p>
			</div>
END;
		}
		// if ($post->owner == $_SESSION['userid'] && logged_on) {
			if (run("permissions:check",array("weblog:edit",$post->owner))) {
				$Edit = gettext("Edit");
				$returnConfirm = gettext("Are you sure you want to permanently delete this weblog post?");
				$Delete = gettext("Delete");
				$body .= <<< END
			
			<div class="blog_edit_functions">
				<p>
					[<a href="{$url}_weblog/edit.php?action=edit&amp;weblog_post_id={$post->ident}&amp;owner={$post->owner}">$Edit</a>]
					[<a href="{$url}_weblog/action_redirection.php?action=delete_weblog_post&amp;delete_post_id={$post->ident}" onClick="return confirm('$returnConfirm')">$Delete</a>]
				</p>
			</div>
			
END;
		}
				
		if (!isset($_SESSION['comment_cache'][$post->ident]) || (time() - $_SESSION['comment_cache'][$post->ident]->created > 120)) {
			$numcomments = db_query("select count(*) as numcomments from weblog_comments where post_id = " . $post->ident);
			$_SESSION['comment_cache'][$post->ident]->created = time();
			$_SESSION['comment_cache'][$post->ident]->data = $numcomments[0]->numcomments;
		}
		$numcomments = $_SESSION['comment_cache'][$post->ident]->data;
		
		$comments = "<a href=\"".url.$username."/weblog/{$post->ident}.html\">$numcomments $anyComments</a>";		
		
		if (isset($individual) && ($individual == 1)) {
			// looking at an individual post and its comments
			
			$commentsbody = "";
			
			if ($post->ident > 0) {
				// if post exists and is visible
				
				$comments = db_query("select * from weblog_comments where post_id = " . $post->ident . " order by posted asc");
				
				if (sizeof($comments) > 0) {
					foreach($comments as $comment) {
						$commentmenu = "";
						if (logged_on && ($comment->owner == $_SESSION['userid'] || $post->owner == $_SESSION['userid'])) {
							$Edit = gettext("Edit");
							$returnConfirm = gettext("Are you sure you want to permanently delete this weblog comment?");
							$Delete = gettext("Delete");
							$commentmenu = <<< END
				<p>
						[<a href="{$url}_weblog/action_redirection.php?action=weblog_comment_delete&amp;weblog_comment_delete={$comment->ident}" onClick="return confirm('$returnConfirm')">$Delete</a>]
				</p>
END;
						}
						$comment->postedname = stripslashes($comment->postedname);
						
						// turn commentor name into a link if they're a registered user
						if ($comment->owner > 0) {
							$commentownerusername = run("users:id_to_name",$comment->owner);
							$comment->postedname = '<a href="' . url . $commentownerusername . '/">' . $comment->postedname . '</a>';
						}
						
						$commentsbody .= run("templates:draw", array(
												'context' => 'weblogcomment',
												'postedname' => $comment->postedname,
												'body' => run("weblogs:text:process",stripslashes($comment->body)) . $commentmenu,
												'posted' => strftime("%A, %e %B %Y, %R %Z",$comment->posted)
											)
											);
						
					}
					$commentsbody = run("templates:draw", array(
											'context' => 'weblogcomments',
											'comments' => $commentsbody
										)
										);
					
				}
				
				$run_result .= run("templates:draw", array(
										'context' => 'weblogpost',
										'date' => $date,
										'username' => $username,
										'usericon' => $usericon,
										'body' => $body,
										'fullname' => $fullname,
										'title' => $title,
										'comments' => $commentsbody
									)
									);
			
				if (logged_on || run("users:flags:get",array("publiccomments",$post->owner))) {
					$run_result .= run("weblogs:comments:add",$post);
				} else {
					$run_result .= "<p>" . gettext("You must be logged in to post a comment.") . "</p>";
				}
				
				$run_result .= run("weblogs:interesting:form",$post->ident);
				
			} else {
				// post is missing or prohibited
				
				$run_result .= run("templates:draw", array(
										'context' => 'weblogpost',
										'date' => "",
										'username' => "",
										'usericon' => "default.png",
										'body' => $body,
										'fullname' => "",
										'title' => $title,
										'comments' => ""
									)
									);
			}
			
		} else {
			
			$run_result .= run("templates:draw", array(
									'context' => 'weblogpost',
									'date' => $date,
									'username' => $username,
									'usericon' => $usericon,
									'body' => $body,
									'fullname' => $fullname,
									'title' => $title,
									'commentslink' => $comments
								)
								);		
		}
	}

?>