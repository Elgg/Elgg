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


<div class="user"><p style="margin-top: 0px; padding-top: 0px"><a href="{{url}}{{username}}/weblog/"><img src="{{url}}_icons/data/{{usericon}}"/></a><br /><a href="{{url}}{{username}}/weblog/">{{fullname}}</a></p></div><div class="Post"><h5>{{title}}</h5>
  <p>{{body}}</p>
  <h3>
	  $postedby {{username}}
      | {{commentslink}}
  </h3>
  {{comments}}
</div>
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
<h5>$comments</h5>
<ol>
{{comments}}
</ol>	
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
	<p style="border: 0px; border-bottom: 1px; border-style: solid; border-color: #000000">
		<small>$postedby {{postedname}} on {{posted}}</small>
	</p>
</li>
	
END;

	$template['css'] .= <<< END
	
		.weblogdateheader		{
			
										font-size: 0.6ems;
									}
	
END;
		
?>