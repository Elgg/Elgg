<?php

// $username = $_SESSION['username'];
$post = get_record('weblog_posts','ident',$parameter);

global $CFG;
global $page_owner;
$page_owner = $post->weblog;

$username = user_info('username', $post->weblog);

if (!run("permissions:check", array("weblog:edit",$post->owner))) {
    exit(__gettext("Access Denied"));
}

$editPost = __gettext("Edit a post");
$postTitle = __gettext("Post title:");
$postBody = __gettext("Post body:");
$Keywords = __gettext("Keywords (Separated by commas):"); // gettext variable
$keywordDesc = __gettext("Keywords commonly referred to as 'Tags' are words that represent the weblog post you have just made. This will make it easier for others to search and find your posting."); // gettext variable
$accessRes = __gettext("Access restrictions:"); // gettext variable
$postButton = __gettext("Save Post"); // gettext

//Getting the field from the context extension
$extensionContext = trim(optional_param('extension','weblog'));

if(array_key_exists($extensionContext,$CFG->weblog_extensions)){
  $extraType  = $CFG->weblog_extensions[$extensionContext]['type'];
  $extraField = $CFG->weblog_extensions[$extensionContext]['field'];
  $extraValue = $CFG->weblog_extensions[$extensionContext]['values'];

  $extraSelected = "";
  if ($tags = get_records_select('tags',"tagtype = ? and ref = ? and owner = ?",array('weblog',$post->ident,$_SESSION['userid']),'tag ASC')) {
    $first = true;
    foreach($tags as $key => $tag) {
      if(!in_array($tag->tag,$extraValue) && trim($tag->tag)!=$extraType){
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


$body = <<< END

<form method="post" name="elggform" action="{$CFG->wwwroot}{$username}/weblog/{$post->ident}.html" onsubmit="return submitForm();">

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

    // Add the weblog toolbar
    $buttons = run("display:content:toolbar");
    if(!empty($buttons)){
      $body.=$buttons;
    }

    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $postBody,
                                'contents' => display_input_field(array("new_weblog_post",stripslashes($post->body),"weblogtext"))
                            )
                            );

    // Allow plugins to add an icon selection
    $icon_selection = run("display:icon:select", array('edit_weblog_icon', $post));
        if (!empty($icon_selection)) {
        $body .= $icon_selection;
    }

    if(isset($extraField) && isset($extraValue)){
      $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $Keywords . "<br />" . $keywordDesc,
                                'contents' =>  display_input_field(array("edit_weblog_keywords",$keywords,"mediumtext","weblog",$post->ident))
                            )
                            );
    }
    else{
      $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $Keywords . "<br />" . $keywordDesc,
                                'contents' =>  display_input_field(array("edit_weblog_keywords","","keywords","weblog",$post->ident))
                            )
                            );
    }

    $body .= templates_draw(array(
                                'context' => 'databoxvertical',
                                'name' => $accessRes,
                                'contents' => run("display:access_level_select",array("edit_weblog_access",$post->access))
                            )
                            );

    $body .= run("weblogs:posts:edit:fields",array($_SESSION['userid'], $post->ident));
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
