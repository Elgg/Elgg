<?php

// $username = $_SESSION['username'];
$post = get_record('weblog_posts','ident',$parameter);

global $CFG;
global $page_owner;
$page_owner = $post->weblog;

$username = user_info('username', $post->weblog);

//Getting the field from the context extension
$extensionContext = trim(optional_param('extension','weblog'));

if (!($aver=run("permissions:check", array("weblog:edit",$post->owner,$post->weblog)))) {
    $messages[] = __gettext("Permission denied");
    $messages[] = __gettext("You can modify only your own content!");
    $redirect_url = url . user_info('username', $_SESSION['userid']) . "/$extensionContext/";
    header_redirect($redirect_url);
}


$contentType = blog_get_extension($extensionContext, 'type', __gettext('Post'));
$extraType = blog_get_extension($extensionContext, 'type');
$extraField = blog_get_extension($extensionContext, 'field');
$extraValue = blog_get_extension($extensionContext, 'values');
$extraTypes = blog_get_extension($extensionContext, 'extra_type', array());
$extraSelected = "";

if(is_array($CFG->weblog_extensions) && array_key_exists($extensionContext,$CFG->weblog_extensions) && is_array($CFG->weblog_extensions[$extensionContext])){
 
  if ($tags = get_records_select('tags',"tagtype = ? and ref = ?",array('weblog',$post->ident),'ident ASC')) {
    $first = true;
    foreach($tags as $key => $tag) {
      if(is_array($extraValue) && !in_array($tag->tag,$extraValue) && trim($tag->tag)!=$extraType && !in_array($tag->tag,$extraTypes) ){
        if (empty($first)) {
          $keywords .= ", ";
        }
        $keywords .= stripslashes($tag->tag);
        $first = false;
      }
      else{
        $extraSelected=$tag->tag;
      }
    }
  }
}


$editPost = sprintf(__gettext("Edit a %s"),$contentType);
$postTitle = sprintf(__gettext("%s title:"),$contentType);
$postBody = sprintf(__gettext("%s body:"),$contentType);
$Keywords = __gettext("Keywords (Separated by commas):"); 
$keywordDesc = __gettext("Keywords commonly referred to as 'Tags' are words that represent the weblog post you have just made. This will make it easier for others to search and find your posting.");
$accessRes = __gettext("Access restrictions:"); // gettext variable
$postButton = __gettext("Publish");


$body = <<< END

<form method="post" name="elggform" action="{$CFG->wwwroot}{$username}/{$extensionContext}/{$post->ident}.html" onsubmit="return submitForm();" enctype="multipart/form-data">

    <h2>$editPost</h2>
END;

    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $postTitle,
                                'contents' => display_input_field(array("edit_weblog_title",stripslashes($post->title),"text"))
                            )
                            );

    // Add the extension field if it is avaible
    $body .= run("weblog:posts:extrafield",array($extensionContext,$extraField, $extraValue,$extraSelected));

    // Add the fields before the post textarea
    //$body .= run("weblogs:posts:add:fields:before",array($_SESSION['userid'],$post));
    $body .= run("weblogs:posts:add:fields:before",array($page_owner,$post));

    // Add the weblog toolbar
    $buttons = run("display:content:toolbar");
    if(!empty($buttons)){
      $body.=$buttons;
    }

    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $postBody,
                                'contents' => display_input_field(array("new_weblog_post",$post->body,"weblogtext"))
                            )
                            );

    // Add the fields after the post textarea
    //$body .= run("weblogs:posts:add:fields:after",array($_SESSION['userid'],$post));
    $body .= run("weblogs:posts:add:fields:after",array($page_owner,$post));

    if(!empty($extraField) && !empty($extraValue)){
      $body .= templates_draw(array(
                                'context' => 'databox',
                                'name' => $Keywords,
                                'column1' => "<i>$keywordDesc</i>",
                                'column2' =>  display_input_field(array("edit_weblog_keywords",$keywords,"mediumtext","weblog",$post->ident))
                            )
                            );
    }
    else{
      $body .= templates_draw(array(
                                'context' => 'databox',
                                'name' => $Keywords,
                                'column1' => "<i>$keywordDesc</i>",
                                'column2' => display_input_field(array("edit_weblog_keywords","","keywords","weblog",$post->ident))
                            )
                            );
    }

    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $accessRes,
                                'contents' => run("display:access_level_select",array("edit_weblog_access",$post->access))
                            )
                            );

    //$body .= run("weblogs:posts:edit:fields",array($_SESSION['userid'], $post->ident));
    $body .= <<< END
    <p>
        <input type="hidden" name="action" value="weblogs:post:edit" />
        <input type="hidden" name="edit_weblog_post_id" value="{$post->ident}" />
        <input type="hidden" name="extension" value="{$extensionContext}" />
        <input type="submit" value="$postButton" />
    </p>

</form>
END;

$run_result .= $body;

?>
