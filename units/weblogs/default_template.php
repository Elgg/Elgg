<?php

    global $template;
    global $template_definition;
    
    $template_definition[] = array(
        'id' => 'weblogpost',
        'name' => __gettext("Weblog Post"),
        'description' => __gettext("A template for each weblog post."),
        'glossary' => array(
            '{{title}}' => __gettext('Post title'),
            '{{body}}' => __gettext('The text of the post'),
            '{{username}}' => __gettext('The username of the person making the post'),
            '{{usericon}}' => __gettext('Their user icon'),
            '{{fullname}}' => __gettext('Their full name'),
            '{{date}}' => __gettext('The time and date of the post'),
            '{{commentslink}}' => __gettext('A link to any comments'),
            '{{trackbackslink}}' => __gettext('A link to any trackbacks'),
            '{{comments}}' => __gettext('A list of comments, if any'),
        )
    );
    
    $postedby = __gettext("Posted by");
    $template['weblogpost'] = <<< END


<div class="weblog_posts"><!-- Holds all aspects of a blog post -->
    <div class="entry"><!-- Open class entry -->
        <div class="user"><!-- Open class user -->
            <a href="{{url}}{{username}}/weblog/"><img alt="" src="{{url}}_icon/user/{{usericon}}"/></a><br /><a href="{{url}}{{username}}/weblog/">{{fullname}}</a>
        </div><!-- Close class user -->
        <div class="weblog_title"><h3>{{title}}</h3></div>
        <div class="post"><!-- Open class post -->
            {{body}}
            <div class="info"><!-- Open class info -->
            <p>
                $postedby {{fullname}}
                 | {{commentslink}}
            </p>
            </div><!-- Close class info -->
        </div><!-- Close class post -->
        {{comments}}
    </div><!-- Close class entry -->
</div><!-- Close weblog_posts -->
<div class="clearing"></div>    
END;

    $template_definition[] = array(
        'id' => 'weblogcomments',
        'name' => __gettext("Weblog Comments"),
        'description' => __gettext("A placeholder for weblog comments."),
        'glossary' => array(
            '{{comments}}' => __gettext('The list of comments themselves'),
            '{{paging}}' => __gettext('The list of page links when there are lots of comments')
        )
    );
    
    $comments = __gettext("Comments");

    $template['weblogcomments'] = <<< END

<hr noshade="noshade" />
<div id="comments"><!-- start comments div -->
    <h4>$comments</h4>
    <div>{{paging}}</div>
    <ol>
        {{comments}}
    </ol>
    <div>{{paging}}</div>
</div><!-- end comments div -->
END;

    $template_definition[] = array(
        'id' => 'weblogcomment',
        'name' => __gettext("Individual weblog comment"),
        'description' => __gettext("A template for each individual weblog comment. (Displayed one after the other, embedded in the comment placeholder.)"),
        'glossary' => array(
            '{{body}}' => __gettext('Post body'),
            '{{postedname}}' => __gettext('The name of the person making the comment'),
            '{{weblogcomment}}' => __gettext('When the comment was posted'),
            '{{usericon}}' => __gettext('The usericon of the person making the comment, if available'),
            '{{permalink}}' => __gettext('A permalink to the comment')
        )
    );

    $template['weblogcomment'] = <<< END

<li>
    {{body}}
    <div class="comment_owner">
    <p>
        {{usericon}}{{postedname}} on {{posted}} <a href="{{permalink}}">#</a>
    </p>
    </div>
</li>

END;

    $template['css'] .= <<< END
    
        .weblogdateheader {
            font-size: 0.6em;
        }
    
END;

?>