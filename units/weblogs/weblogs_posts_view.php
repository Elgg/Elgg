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
			
			if ($stuff->icon == -1) {
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
				
				if ($stuff2->icon == -1) {
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
		$title = stripslashes($post->title);
		
		if ($post->owner != $post->weblog) {
			
			if ($post_authors[$post->owner]->icon == -1) {
				$usericon = $post_authors[$post->weblog]->icon;
			}
			$fullname .= " @ " . $post_authors[$post->weblog]->fullname;
			$username = run("users:id_to_name",$post->weblog);
		}

		$body = run("weblogs:text:process",stripslashes($post->body));
		$body = str_replace("{{more}}","<a href=\"".url.$username."/weblog/{$post->ident}.html\">More ...</a>",$body);
		$keywords = run("display:output_field", array("","keywords","weblog","weblog",$post->ident,$post->owner));
		if ($keywords) {
			$body .= <<< END
			<p class="weblog_keywords">
				<small>Keywords: {$keywords}</small>
			</p>
END;
		}
		if ($post->owner == $_SESSION['userid'] && logged_on) {
			$body .= <<< END
			
			<p>
				<small>
					[<a href="{$url}_weblog/edit.php?action=edit&weblog_post_id={$post->ident}">Edit</a>]
					[<a href="{$url}_weblog/action_redirection.php?action=delete_weblog_post&delete_post_id={$post->ident}" onClick="return confirm('Are you sure you want to permanently delete this weblog post?')">Delete</a>]
				</small>
			</p>
			
END;
		}
				
		if (!isset($_SESSION['comment_cache'][$post->ident]) || (time() - $_SESSION['comment_cache'][$post->ident]->created > 120)) {
			$numcomments = db_query("select count(ident) as numcomments from weblog_comments where post_id = " . $post->ident);
			$_SESSION['comment_cache'][$post->ident]->created = time();
			$_SESSION['comment_cache'][$post->ident]->data = $numcomments[0]->numcomments;
		}
		$numcomments = $_SESSION['comment_cache'][$post->ident]->data;

		$comments = "<a href=\"".url.$username."/weblog/{$post->ident}.html\">$numcomments comment(s)</a>";		

		if (isset($individual) && ($individual == 1)) {
			
			$comments = db_query("select * from weblog_comments where post_id = " . $post->ident . " order by posted asc");
			
			$commentsbody = "";
						
			if (sizeof($comments) > 0) {
				foreach($comments as $comment) {
					$commentmenu = "";
					if (logged_on && ($comment->owner == $_SESSION['userid'] || $post->owner == $_SESSION['userid'])) {
						$commentmenu = <<< END
			<p>
				<small>
					[<a href="{$url}_weblog/action_redirection.php?action=weblog_comment_delete&weblog_comment_delete={$comment->ident}" onClick="return confirm('Are you sure you want to permanently delete this weblog comment?')">Delete</a>]
				</small>
			</p>
END;
					}
					$commentsbody .= run("templates:draw", array(
											'context' => 'weblogcomment',
											'postedname' => stripslashes($comment->postedname),
											'body' => run("weblogs:text:process",stripslashes($comment->body)) . $commentmenu,
											'posted' => gmdate("l, F jS, Y",$comment->posted) . " at " . gmdate("H:i",$comment->posted)
											
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

			$run_result .= run("weblogs:comments:add",$post);
			
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