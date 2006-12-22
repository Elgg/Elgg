<?php
global $CFG;
$sitename = sitename;
$run_result .= <<< END

<div class="helpfiles">
<h3>Why would you want to have a blog?</h3>
<p>Your blog can serve multiple purposes:</p>
<ul>
<li>You can use it to record your personal reflections,ideas, things to do</li>
<li>You can host conversations with your teachers, students, instructors, friends</li>
<li>You can embed files into your blog and discuss them</li>
<li>Let people know what you are thinking, studying - what you thought about that last assignment or the latest book you have read!</li>
</ul>

<h5>How to do it?</h5>
<h4>>> Select 'Post a new entry'</h4>
<img src="{$CFG->wwwroot}help/images/blog_start.jpg" alt="Post a blog entry" title="Post a blog entry" width="369" height="99" border="0" />
<p><b>Step one:</b> Now you will see the 'Add a new post' page. First you give your blog post a title then fill in some content.</p>
<img src="{$CFG->wwwroot}help/images/blog_two.jpg" alt="Title and content" title="Title and content" width="437" height="147" border="0" />
<p><b>Step two:</b> <i>(optional)</i> Once you finished writing the content - you might want to add some keywords (tags). These keywords can be individual words or phrase - <b>separated by a comma</b>.</p>
<img src="{$CFG->wwwroot}help/images/blog_three.jpg" alt="Title and content" title="Title and content" width="424" height="126" border="0" />
<p><b>Step three:</b> <i>(optional - default is public)</i> Like all things in <b>your</b> landscape you can control who gets to read your blog post. Use the 'Access Restrictions' to do this.</p>
<img src="{$CFG->wwwroot}help/images/blog_four.jpg" alt="Access" title="Access" width="163" height="102" border="0" />
<p><i>Note: You can create your own access restrictions at 'Your Network' - 'Access controls'</i></p>
<p><b>Step four:</b> <i>(optional)</i> If you would like to embed a file into you blog post use the 'Embed a file..' option. Images less than 400pixel by 400 pixel will display in the blog post, all other files will be linked to.</p>
<img src="{$CFG->wwwroot}help/images/blog_five.jpg" alt="Embed an image" title="Embed an image" width="485" height="67" border="0" />
<p><b>Step five:</b> Now just press 'Post' and you have created your first blog post!</p>
<h3>Other functions</h3>
<p><b>RSS feed:</b> this is so people can follow your blog posts using an RSS aggregator. (<i>Note: only the posts you mark as public</i>)</p>
<p><b>Archive:</b> this is all your blog posts stored by month.</p>
<p><b>Friends blogs:</b> this displays all the posts made by your friends.</p>
<p><b>View all posts:</b> this will display all the blog posts in the system.</p>
<h3>Troubleshooting</h3>
<h4>I can't find any files to embed?</h4>
<p>Check you have actually uploaded some files to your file respository. You cannot upload a file from your own computer - at the moment you must first have stored it in your personal file repository.</p>
<h4>Why are my keywords not working?</h4>
<p>Your keywords will only create links if the keyword exists somewhere else in the system. If not you are the first and you will need to wait until someone else uses it. Also make sure you have separated with commas.</p>
<h4>The RSS feed page is all code?</h4>
<p>This is what an RSS feed looks like - it is designed to be interpreted by an RSS reader.</p>
<h4>Does this blog have an HTML editor?</h4>
<p>At present no - we are testing different ones just now. However, the most common request was to create links. To do this you just need to type the link, or paste it in and the system automatically turns it into an active link.</p>

</div>
END;
?>