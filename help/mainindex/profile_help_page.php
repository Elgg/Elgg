<?php

	$sitename = sitename;
	$url = url;
	$run_result .= <<< END
<div class="helpfiles">
<h3>Why would you want to have a profile?</h3>
<p>Your profile serves two purposes:</p>
<ul>
<li><b>Firstly</b> it tells others a little bit about you</li>
<li><b>Secondly</b> the system automatically creates links from your profile to others who share the same interests, likes, dislikes, goals and skills!</li>
</ul>
<p>It not only creates links to other people but also resources - this helps you find information which could help with your studies and research!</p>

<h3>How to do it?</h3>

<h4>>> Select 'Edit this profile'</h4>
<img src="{$url}help/images/profile_to_do.jpg" alt="How to do it?" title="How to do it?" width="369" height="104" border="0" />

<p>Fill in the fields that you want, don't worry about information you don't want others to see, you can control this.</p> 
<img src="{$url}help/images/profile_field.jpg" alt="Example Profile field" title="Example Profile field" width="450" height="131" border="0" />

<p>Beside each field you will see an option that says "Access level" and it has a dropdown list of choices: Public, Logged in users, Private.
This controls who can see your profile items.</p>
<img src="{$url}help/images/profile_access.jpg" alt="Profile access options" title="Profile access options" width="450" height="170" border="0" />
<p>For example, if you don't want anyone to see your address set the 'Access Restriction' to Private and so on. (You can create your own access levels by going to 'Your Network' - 'Access controls')</p>

<p><i>Note: Any fields you leave blank will not show on your profile page.</i></p>

<p><i>Note: The profile field 'Brief description' is used to populate your sidebar profile - see image below.</i></p>
<img src="{$url}help/images/sidebar_profile.jpg" alt="Sidebar Profile" width="302" height="206" border="0" />
<p>Remember to scroll to the end and click on <b>Save your profile</b>!</p>
<img src="{$url}help/images/save.jpg" alt="Save your profile" title="Save your profile" width="249" height="48" border="0" />

</div>
END;

?>