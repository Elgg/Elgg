<?php

    global $CFG;
    $sitename = sitename;
    $email = email;
    $run_result .= <<< END

<div class="helpfiles">
<h3>What is 'Your Network'?</h3>
<ul>
<li>It consists of friends you have a connection to, friends that have connected to you, communities you are a member of and communities you have set up.</li>
<li>This is where all your connections to other learners, instructors, family and friends happens!</li>
</ul>

<h3>Communities:</h3>
<p>This menu option displays all the comunities you are a member of. If you have not joined any communities then you will get a message saying that.</p>
<h3>Owned Communities:</h3>
<p>This will list all the communities you own. These are the communities that you have set up.</p>
<p>It is here that you can create new communities</p>
<h4>>> Here's how:</h4>
<p><b>Step 1:</b> You need to give the community a name as well as a username.</p>
<img src="{$CFG->wwwroot}help/images/community_one.jpg" alt="Create a community" title="Create a community" width="336" height="120" border="0" />
<p><b>Step 2:</b> Click '<b>create</b>' and that is it! You have now created a new comunity.</p>
<h3>Friends:</h3>
<p><b>Friends I have linked to</b> this page displays all the friends you have linked to. To remove a friend you just click on the small icon beside their name.</p>
<p><i>Note: To find some friends you could search on a subject you are interested in, invite some into the system or click on any keywords you may have.</i></p>
<h3>Friend of:</h3>
<p><b>Friends who have linked to me</b> this page displays all the people who have linked to you as a friend. If you wish to make one of them your friend just click on the small icon beside their name.</p>
<h3>FOAF:</h3>
<p>This is a protocol for passing information about you between systems.</p>
<h3>Access controls:</h3>
<p>This is one of the most important aspects of your landscape. It allows you to control who has access to your content.</p>
<ul>
<li>The default levels of access are 'public', 'logged in users', 'private'.</li>
<li>You can wrap access restrictions around any object - profile items, files, blog posts etc.</li>
<li>You create and control your access groups.</li>
</ul>
<h3>Invite a friend:</h3>
<p>This lets you invite in other people you think might be interested in the system.</p>
<h4>Troubleshooting</h4>
<h5>How do I meet people to be my friends?</h5>
<p>This can be done in a number of ways - you can use the search box and try searching on something you are interested in. You can try selecting one of your keywords and seeing who you can find. You could see if anyone has made you their friend and do the same in return</p>
<h5>Can I just make people my friend or should I ask them?</h5>
<p>This is up to you - if you make someone your friend it does not give you any special privilages regarding their site.</p>
<h5>I can't delete a community I created</h5>
<p>This is because we are not yet sure how to handle this situation. If we let everyone just delete communities they create - what happens to the content of the community? It could be very discouraging to join a community, actively participate only to see the owner pull the plug and all your contributions and connections are lost. We are currently thinking of ways to handle this situation.</p>
</div>
END;

?>