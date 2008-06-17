<?php

    global $template;
    global $template_definition;
    
    $template_definition[] = array(
                                    'id' => 'rsspost',
                                    'name' => __gettext("Feed Post"),
                                    'description' => __gettext("A template for each post in a feed."),
                                    'glossary' => array(
                                                            '{{title}}' => __gettext('Post title'),
                                                            '{{body}}' => __gettext('The text of the post'),
                                                            '{{usericon}}' => __gettext('An icon for the user'),
                                                            '{{fullname}}' => __gettext('The feed name'),
                                                            '{{tagline}}' => __gettext('A short description of the feed'),
                                                            '{{link}}' => __gettext('A link to the post'),
                                                            '{{sitelink}}' => __gettext('A link to the host site'),
                                                            '{{feedlink}}' => __gettext('A link to the host feed'),
                                                            '{{controls}}' => __gettext('Buttons to subscribe or unsubscribe')
                                                            
                                                        )
                                    );
    
    $postedby = __gettext("Added");
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