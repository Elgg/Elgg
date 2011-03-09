<?php
/**
 * FOAF pageshell
 *
 * @package Elgg
 * @subpackage Core
 *
 * // @todo removed below because blog is a plugin
 * <foaf:weblog rdf:resource="<?php echo elgg_get_site_url(); ?>blog/<?php echo $owner->username; ?>" />
 */

header("Content-Type: text/xml");
// echo $vars['body'];

echo "<?xml version='1.0'?>\n";

if (!$owner = elgg_get_page_owner_entity()) {
	if (!elgg_is_logged_in()) {
		exit;
	} else {
		$owner = elgg_get_logged_in_user_entity();
	}
}

?>
<rdf:RDF
	xml:lang="en"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
	xmlns:foaf="http://xmlns.com/foaf/0.1/"
	xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
	xmlns:dc="http://purl.org/dc/elements/1.1/">

	<rdf:Description rdf:about="">
		<rdf:type rdf:resource="http://xmlns.com/foaf/0.1/PersonalProfileDocument"/>
		<foaf:maker rdf:resource="#me"/>
		<foaf:primaryTopic rdf:resource="#me"/>
	</rdf:Description>

	<foaf:Person rdf:about="#me">
		<foaf:nick><?php echo $owner->username; ?></foaf:nick>
		<foaf:name><?php echo $owner->name; ?></foaf:name>
		<foaf:homepage rdf:resource="<?php echo $owner->getURL(); ?>" />
		<foaf:depiction rdf:resource="<?php echo elgg_format_url($owner->getIconURL('large')); ?>" />
		<?php
			echo $vars['body'];
		?>
	</foaf:Person>
</rdf:RDF>
