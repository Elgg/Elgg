<?php

if (isset($parameter)) {

    $post = $parameter;

    global $post_authors;
    global $individual;
    global $CFG;

    //if (!isset($post_authors[$post->owner])) {

        $author = "";

        $stuff = get_record('users','ident',$post->owner);

        $author->fullname = htmlspecialchars(user_name($post->owner), ENT_COMPAT, 'utf-8');

        if ($stuff->icon == -1 || $post->owner == -1) {
            $author->icon = 0;
        } else {
            if ($icon = get_record('icons','ident',$stuff->icon)) {
                $author->icon = $icon->ident;
            } else {
                $author->icon = 0;
            }
        }

        $post_authors[$post->owner] = $author;

    //}
    //if (!isset($post->authors[$post->weblog])) {
    if ($post->weblog != $post->owner) {
        $community = "";

        $stuff2 = get_record('users','ident',$post->weblog);

        $community->fullname = htmlspecialchars($stuff2->name, ENT_COMPAT, 'utf-8');

        if (empty($stuff2->icon) || $stuff2->icon == -1) {
            $community->icon = 0;
        } else {
            if ($icon = get_record('icons','ident',$stuff2->icon)) {
                $community->icon = $icon->ident;
            } else {
                $community->icon = 0;
            }
        }

        $post_authors[$post->weblog] = $community;
    }
    //}

    $postedby = __gettext("Posted by");
    $commentsStr = __gettext("Comments");

    $date = gmdate("H:i",$post->posted);

    $username = user_info('username', $post->owner);


    // Allow plugins to set special icons
    $specialicon = run("weblogs:posts:geticon",$post);

    // If there is no special icon for this post, set to the default
    if ($specialicon == NULL) {
        $usericon = $post_authors[$post->owner]->icon;
        if ($usericon == "default.png") {
            $usericon = $post_authors[$post->weblog]->icon;
        }
    } else {
        $usericon = $specialicon;
    }

    // Allow plugins to set the name on the post
    $specialname = run("weblogs:posts:getname",$post);
    if (empty($specialname)) {
        $fullname = $post_authors[$post->owner]->fullname;
    } else {
        $fullname = $specialname;
    }

    $title = get_access_description($post->access);
    $title .= htmlspecialchars($post->title, ENT_COMPAT, 'utf-8');

    if ($post->owner != $post->weblog) {

        if ($post_authors[$post->owner]->icon == -1) {
            $usericon = $post_authors[$post->weblog]->icon;
        }

        $fullname .= " @ " . $post_authors[$post->weblog]->fullname;
        $username = user_info('username', $post->weblog);
    }

    //Getting the field from the context extension
    $extensionContext = trim(optional_param('extension','weblog'));
    if(array_key_exists($extensionContext,$CFG->weblog_extensions)
        && isset($CFG->weblog_extensions[$extensionContext]['type'])
        && isset($CFG->weblog_extensions[$extensionContext]['field'])
        && isset($CFG->weblog_extensions[$extensionContext]['values'])){
        $extraType  = $CFG->weblog_extensions[$extensionContext]['type'];
        $extraField = $CFG->weblog_extensions[$extensionContext]['field'];
        $extraValue = $CFG->weblog_extensions[$extensionContext]['values'];
        $filter = $extraType;
    }

    $body = run("weblogs:text:process", $post->body);
    $More = __gettext("More");
    $Keywords = __gettext("Keywords:");
    $anyComments = __gettext("comment(s)");

    $postTitle = "<a href=\"".url.$username."/$extensionContext/{$post->ident}.html\">$title</a>";
    $body = str_replace("{{more}}","<a href=\"" . url .$username."/$extensionContext/{$post->ident}.html\">$More ...</a>",$body);
    $keywords = display_output_field(array("","keywords","weblog","weblog",$post->ident,$post->owner));

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
        $Edit = __gettext("Edit");
        $returnConfirm = __gettext("Are you sure you want to permanently delete this weblog post?");
        $Delete = __gettext("Delete");
        $links = <<< END
                    | <a href="{$CFG->wwwroot}{$username}/$extensionContext/edit/{$post->ident}">$Edit</a> |
                    <a href="{$CFG->wwwroot}mod/blog/action_redirection.php?action=delete_weblog_post&amp;delete_post_id={$post->ident}&amp;extension={$extensionContext}" onclick="return confirm('$returnConfirm')">$Delete</a>
END;
    } else {
        $links = "";
    }
    $links .= run("weblog:post:links",$post);

    if (!isset($_SESSION['comment_cache'][$post->ident]) || (time() - $_SESSION['comment_cache'][$post->ident]->created > 120)) {
        $numcomments = count_records('weblog_comments','post_id',$post->ident);
        $_SESSION['comment_cache'][$post->ident]->created = time();
        $_SESSION['comment_cache'][$post->ident]->data = $numcomments;
    }
    $numcomments = $_SESSION['comment_cache'][$post->ident]->data;

    $comments = "| <a href=\"".url.$username."/$extensionContext/{$post->ident}.html\">$numcomments $anyComments</a>";

    if (isset($individual) && ($individual == 1)) {
        // looking at an individual post and its comments

        $commentsbody = "";

        if ($post->ident > 0) {
            // if post exists and is visible

            //which page of comments to display (page numbers are 0-based)
            $page = optional_param('commentpage', 0, PARAM_INT);
            $perpage = 20; // set to 0/false to disable paging
            $offset = $page * $perpage;
            $thispageurl = $CFG->wwwroot . $username . "/$extensionContext/" . $post->ident . ".html";

            if ($comments = get_records('weblog_comments','post_id',$post->ident,'posted ASC')) {
                $numcomments = count($comments);
                $pagelinks = '';
                if (!empty($perpage) && $numcomments > $perpage) {
                    $comments = array_slice($comments, $offset, $perpage);
                    $numpages = ceil($numcomments / $perpage);
                    $pagelinks = __gettext("Page: ");
                    for ($i = 1; $i <= $numpages; $i++) {
                        $pagenum = $i - 1;
                        if ($pagenum != $page) {
                            $pageurl = $thispageurl . (($pagenum) ? '.' . $pagenum : '');
                            $pagelinks .= ' <a href="' . $pageurl . '">' . $i . '</a>' ;
                        } else {
                            $pagelinks .= ' ' . $i . ' ';
                        }

                    }
                    $thispageurl .= '.' . $page;
                }

                foreach($comments as $comment) {
                    $commentmenu = "";
                    if (logged_on && ($comment->owner == $_SESSION['userid'] || run("permissions:check",array("weblog:edit",$post->owner)))) {
                        $returnConfirm = __gettext("Are you sure you want to permanently delete this weblog comment?");
                        $Delete = __gettext("Delete");
                        $commentmenu = <<< END
                        <a href="{$CFG->wwwroot}mod/blog/action_redirection.php?action=weblog_comment_delete&amp;weblog_comment_delete={$comment->ident}&amp;extension={$extensionContext}" onclick="return confirm('$returnConfirm')">$Delete</a>
END;
                    }
                    $comment->postedname = htmlspecialchars($comment->postedname, ENT_COMPAT, 'utf-8');

                    // turn commentor name into a link if they're a registered user
                    // add rel="nofollow" to comment links if they're not
                    if ($comment->owner > 0) {
                        $commentownerusername = user_info('username', $comment->owner);
                        $comment->postedname = '<a href="' . url . $commentownerusername . '/">' . $comment->postedname . '</a>';
                        $comment->icon = '<a href="' . url . $commentownerusername . '/">' . user_icon_html($comment->owner,50) . "</a>";
                        $comment->body = run("weblogs:text:process", array($comment->body, false));
                    } else {
                        $comment->icon = "<img src=\"" . $CFG->wwwroot . "_icons/data/default.png\" width=\"50\" height=\"50\" align=\"left\" alt=\"\" />";
                        $comment->body = run("weblogs:text:process", array($comment->body, true));
                    }

                    $commentsbody .= templates_draw(array(
                                                          'context' => 'weblogcomment',
                                                          'postedname' => $comment->postedname,
                                                          'body' => '<a name="cmt' . $comment->ident . '" id="cmt' . $comment->ident . '"></a>' . $comment->body,
                                                          'posted' => strftime("%A, %d %B %Y, %H:%M %Z",$comment->posted),
                                                          'usericon' => $comment->icon,
                                                          'permalink' => $thispageurl . "#cmt" . $comment->ident,
                                                          'links' =>  $commentmenu
                                                          )
                                                    );

                }
                $commentsbody = templates_draw(array(
                                                     'context' => 'weblogcomments',
                                                     'paging' => $pagelinks,
                                                     'comments' => $commentsbody,
                                                     'comments_str' => $commentsStr
                                                     )
                                               );

            }

            $run_result .= templates_draw(array(
                                                'context' => 'weblogpost',
                                                'date' => $date,
                                                'username' => $username,
                                                'usericon' => user_icon_html(user_info_username("ident",$username)),
                                                'body' => $body,
                                                'fullname' => $fullname,
                                                'title' => $postTitle,
                                                'comments' => $commentsbody,
                                                'links' => $links,
                                                'postedby' => $postedby
                                                )
                                          );

            if (logged_on || (!$CFG->disable_publiccomments && user_flag_get("publiccomments",$post->owner)) ) {
                $run_result .= run("weblogs:comments:add",$post);
            } else {
                $run_result .= "<p>" . __gettext("You must be logged in to post a comment.") . "</p>";
            }

                $run_result .= run("weblogs:interesting:form",$post->ident);

        } else {
            // post is missing or prohibited

            $run_result .= templates_draw(array(
                                                'context' => 'weblogpost',
                                                'date' => "",
                                                'username' => "",
                                                'usericon' => user_icon_html(-1),
                                                'body' => $body,
                                                'fullname' => "",
                                                'title' => $postTitle,
                                                'comments' => "",
                                                'links' => $links,
                                                'postedby' => $postedby
                                                )
                                          );
        }
    } else {

        $run_result .= templates_draw(array(
                                            'context' => 'weblogpost',
                                            'date' => $date,
                                            'username' => $username,
                                            'usericon' => user_icon_html(user_info_username("ident",$username)),
                                            'body' => $body,
                                            'fullname' => $fullname,
                                            'title' => $postTitle,
                                            //'commentslink' => $comments,
                                            'links' => $comments . $links,
                                            'postedby' => $postedby
                                            )
                                      );
    }
}

?>
