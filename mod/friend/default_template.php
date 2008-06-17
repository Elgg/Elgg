<?php
/*
 * default_template.php
 *
 * Created on Apr 16, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
 global $template;
 global $template_definition;

 $template_definition[] = array(
        'id' => 'friends_friends',
        'name' => __gettext("Friends page"),
        'description' => __gettext("A template for the friends page."),
        'glossary' => array(
            '{{friends}}' => __gettext('Friends list')
        )
    );

 $template_definition[] = array(
        'id' => 'friends_friend',
        'name' => __gettext("Friend visualization"),
        'description' => __gettext("A template for friend display"),
        'glossary' => array(
            '{{name}}' => __gettext("Friend's name"),
            '{{link}}' => __gettext("Friend's profile page"),
            '{{icon}}' => __gettext("Friend's icon"),
            '{{menu}}' => __gettext("Friend's functions")
        )
    );

 templates_add_context('friends_friends', "<div id=\"networktable\"><table width=\"80%\" cellspacing=\"5\"><tr>{{friends}}</tr></table></div>", false);
 templates_add_context('friends_friend', 'mod/friend/templates/friends_friend.html');
?>
