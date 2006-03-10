<?php

	global $template;
	global $template_definition;
	
	$template_definition[] = array(
									'id' => 'rsspost',
									'name' => gettext("Feed Post"),
									'description' => gettext("A template for each post in a feed."),
									'glossary' => array(
															'{{title}}' => gettext('Post title'),
															'{{body}}' => gettext('The text of the post'),
															'{{usericon}}' => gettext('An icon for the user'),
															'{{fullname}}' => gettext('The feed name'),
															'{{tagline}}' => gettext('A short description of the feed'),
															'{{link}}' => gettext('A link to the post'),
															'{{sitelink}}' => gettext('A link to the host site'),
															'{{feedlink}}' => gettext('A link to the host feed'),
															'{{controls}}' => gettext('Buttons to subscribe or unsubscribe')
															
														)
									);
	
	$postedby = gettext("Added");
	$template['rsspost'] = <<< END


<div class="feeds">
<div class="feed_content">
<h5><a href="{{link}}">{{title}}</a></h5>
  <p>{{body}}</p>
   <div class="via"><p>via <a href="{{sitelink}}">{{fullname}}</a></p></div>
</div>
</div>
<div class="clearing"></div><br />	
END;
		
?>