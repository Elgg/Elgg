<?php
/*
 * default_template.php
 *
 * Created on Apr 9, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
 global $template;
 global $template_definitions;

$template_definition[] = array(
        'id' => 'community_members',
        'name' => __gettext("Comminity Members admin page"),
        'description' => __gettext("A template for the community members admin page."),
        'glossary' => array(
            '{{members}}' => __gettext('Community members list')
        )
    );

$template_definition[] = array(
        'id' => 'community_member',
        'name' => __gettext("Community member visualization"),
        'description' => __gettext("A template for the community member display"),
        'glossary' => array(
            '{{name}}' => __gettext("Member's name"),
            '{{link}}' => __gettext("Member's profile page"),
            '{{icon}}' => __gettext("Member's icon"),
            '{{functions}}' => __gettext("Member's functions")
        )
    );

$template_definition[] = array(
        'id' => 'community_membership',
        'name' => __gettext("Community membership visualization"),
        'description' => __gettext("A template for the community membership display"),
        'glossary' => array(
            '{{name}}' => __gettext("Member's name"),
            '{{link}}' => __gettext("Member's profile page"),
            '{{icon}}' => __gettext("Member's icon"),
            '{{functions}}' => __gettext("Member's functions")
        )
    );

$template_definition[] = array(
        'id' => 'community_create',
        'name' => __gettext("Community creation"),
        'description' => __gettext("A template for the community creation form"),
        'glossary' => array(
            '{{title}}' => __gettext("Page title"),
            '{{form_fields}}' => __gettext("Form fields")
        )
    );

templates_add_context('community_members', "<div id=\"networktable\"><table width=\"80%\" cellspacing=\"5\"><tr>{{members}}</tr></table></div>", false);
templates_add_context('community_member', 'mod/community/templates/community_member_view.html');
templates_add_context('community_membership', 'mod/community/templates/community_membership_view.html');
templates_add_context('community_create', 'mod/community/templates/community_create.html');

?>
