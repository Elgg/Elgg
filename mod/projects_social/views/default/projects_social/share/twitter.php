<?php

if ($vars['entity']) {
	$url = $vars['entity']->getURL();
}
?>
<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $url; ?>" data-lang="<?php echo get_language(); ?>">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
