<?php

	global $template;
	global $template_definition;
	
	$template_definition[] = array(
		'id' => 'weblogpost',
		'name' => gettext("Weblog Post"),
		'description' => gettext("A template for each weblog post."),
		'glossary' => array(
			'{{title}}' => gettext('Post title'),
			'{{body}}' => gettext('The text of the post'),
			'{{username}}' => gettext('The username of the person making the post'),
			'{{usericon}}' => gettext('Their user icon'),
			'{{fullname}}' => gettext('Their full name'),
			'{{date}}' => gettext('The time and date of the post'),
			'{{commentslink}}' => gettext('A link to any comments'),
			'{{trackbackslink}}' => gettext('A link to any trackbacks'),
			'{{comments}}' => gettext('A list of comments, if any')
		)
	);
	
	$postedby = gettext("Posted by");
	$template['weblogpost'] = <<< END


<div class="weblog_posts"><!-- Holds all aspects of a blog post -->
	<div class="entry"><!-- Open class entry -->
		<div class="user"><!-- Open class user -->
			<a href="{{url}}{{username}}/weblog/"><img src="{{url}}_icons/data/{{usericon}}"/></a><br /><a href="{{url}}{{username}}/weblog/">{{fullname}}</a>
		</div><!-- Close class user -->
		<div class="weblog_title"><h3>{{title}}</h3></div>
		<div class="post"><!-- Open class post -->
			{{body}}
			<div class="info"><!-- Open class info -->
			<p>
				$postedby {{username}}
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
		'name' => gettext("Weblog Comments"),
		'description' => gettext("A placeholder for weblog comments."),
		'glossary' => array(
			'{{comments}}' => gettext('The list of comments themselves')
		)
	);
	
	$comments = gettext("Comments");

	$template['weblogcomments'] = <<< END

<hr noshade="noshade" />
<div id="comments"><!-- start comments div -->
	<h4>$comments</h4>
	<ol>
		{{comments}}
	</ol>
</div><!-- end comments div -->
END;

	$template_definition[] = array(
		'id' => 'weblogcomment',
		'name' => gettext("Individual weblog comment"),
		'description' => gettext("A template for each individual weblog comment. (Displayed one after the other, embedded in the comment placeholder.)"),
		'glossary' => array(
			'{{body}}' => gettext('Post body'),
			'{{postedname}}' => gettext('The name of the person making the comment'),
			'{{weblogcomment}}' => gettext('When the comment was posted')
		)
	);

	$template['weblogcomment'] = <<< END

<li>
	{{body}}
	<p>
		$postedby {{postedname}} on {{posted}}
	</p>
</li>

END;

	$template['css'] .= <<< END
	
		.weblogdateheader {
			font-size: 0.6em;
		}
	
END;

?>