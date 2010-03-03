<?php

require_once 'twitter.class.php';


$twitter = new Twitter('pokusnyucet2', '123456');

$withFriends = FALSE;
$channel = $twitter->load($withFriends);

?>

<ul>
<?foreach ($channel->status as $status): ?>
	<li><a href="http://twitter.com/<?=$status->user->screen_name?>"><?=$status->user->name?></a>:
	<?=$status->text?>
	<small>at <?=date("j.n.Y H:m", strtotime($status->created_at))?></small>
	</li>
<?endforeach?>
</ul>
