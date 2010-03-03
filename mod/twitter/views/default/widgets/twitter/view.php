<?php
    
     /**
	 * Elgg twitter view page
	 *
	 * @package ElggTwitter
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 
	 //some required params
	 
	 $username = $vars['entity']->twitter_username;
	 $num = $vars['entity']->twitter_num;
	 
    // if the twitter username is empty, then do not show
    if($username){
	 
?>

<div id="twitter_widget">
<ul id="twitter_update_list"></ul>
<p class="visit_twitter"><a href="http://twitter.com/<?php echo $username; ?>"><?php echo elgg_echo("twitter:visit"); ?></a></p>
<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $username; ?>.json?callback=twitterCallback2&count=<?php echo $num; ?>"></script>
<?php 
    } else {
        
      echo "<div class=\"contentWrapper\"><p>" . elgg_echo("twitter:notset") . ".</p></div>";
      
  }
?>
</div>