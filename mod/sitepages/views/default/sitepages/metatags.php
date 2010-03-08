<?php
/**
 * Meta tags
 **/
 
$meta_details = elgg_get_entities(array('type' => 'object', 'subtype' => 'seo', 'limit' => 1));
if($meta_details){
	foreach($meta_details as $md){
		 $metatags = $md->title;
		 $description = $md->description;
	 }
}
 
?>

<meta name="description" content="<?php echo $description; ?>." />
<meta name="keywords" content="<?php echo $metatags; ?>" />