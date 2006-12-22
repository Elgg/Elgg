<?php

    global $CFG;
    $sitename = sitename;
    $run_result .= <<< END

<div class="helpfiles">
<h2>Why would you want to have a file repository?</h2>
<ul>
<li>Use this to upload and store all your photos, coursework, articles to read etc.</li>
<li>You can attach access privilages to all of these items to control who you share them with OR you can keep them private if you want!</li>
<li>Embed items from your repository into your weblog to record your reflections.</li>
<li>You create the directory structure - manage your files how you want to!</li>
</ul>

<h3>How to do it?</h3>
<h4>>> Select 'Add a file or a folder'</h4>
<img src="{$CFG->wwwroot}help/images/files_one.jpg" alt="Start" title="Start" width="365" height="95" border="0" />

<p><b>Step one:</b> Now you will be in the root directory of your file repository. If you like you can upload all your files here. First you need to browse for the file you want to upload using the first part of the form.</p>
<img src="{$CFG->wwwroot}help/images/files_two.jpg" alt="Browse for file" title="Browse for file" width="419" height="72" border="0" />
<p><b>Step two:</b> Now enter a title <i>(recommended)</i> and short description <i>(optional)</i>.</p>
<img src="{$CFG->wwwroot}help/images/files_three.jpg" alt="Add a title and description" title="Add a title and description" width="380" height="92" border="0" />
<p><b>Step 3:</b> <i>(optional - default is public)</i> You can now set the 'Access Restriction' you want for this file.</p>
<img src="{$CFG->wwwroot}help/images/files_four.jpg" alt="Access" title="Access" width="350" height="80" border="0" />
<p><b>Step 4:</b> <i>(optional)</i> Now you might want to add some keywords (tags).</p>
<img src="{$CFG->wwwroot}help/images/files_six.jpg" alt="Keywords" title="Keywords" width="453" height="89" border="0" />
<p><b>Step 5:</b> Make sure you check the disclaimer which says you acknowledge you have the right to upload the particular file.</p>
<img src="{$CFG->wwwroot}help/images/files_five.jpg" alt="disclaimer" title="disclaimer" width="455" height="43" border="0" />
<p><b>Step 6:</b> Click Upload and you are done!</p>
<h5>Want to create a folder?</h5>
<p><b>>> Select 'Add a file or a folder'</b></p>
<img src="{$CFG->wwwroot}help/images/files_one.jpg" alt="Start" title="Start" width="365" height="95" border="0" />
<p><b>Step one:</b> You need to give the folder a name - then set the Access restrictions and finally, if you want, put in some keywords (remember to separate with a comma!)</p>
<img src="{$CFG->wwwroot}help/images/files_seven.jpg" alt="disclaimer" title="disclaimer" width="500" height="200" border="0" />

<p><i>Note: you can create as may sub-directories as you want - just click on the folder for which you want to create a sub-directory in and then follow the instructions to 'Create a folder' above.</i></p>
<p><i>Note: to upload a file into a folder instead of the root directory just click on the folder you wish to upload the file to and then follow the instructions 'How to do it?' above.</i></p>
<h3>Other functions</h3>
<p><b>RSS feed:</b> this is so people can follow your file uploads using an RSS aggregator. (<i>Note: only the files you mark as public</i>)</p>
<p><b>Delete:</b> once you have uploaded a file or created a folder you can also delete them - <b>warning!</b> once a file has been deleted it is gone forever.</p>
<p><b>Edit:</b> once you have uploaded a file you can edit its properties at a later date - this is handy if you want to change the description or access restriction.</p>
<h3>Troubleshooting</h3>
<h4>Why is my file not uploading?</h4>
<p>PHP has a default file size limit on files being uploaded - this is 5mb, check that your file is smaller than this. (It is worth noting that on some servers PHP's limit is 2mb - this is worth checking with your administrator) Make sure the file type is allowed, your administrator will let you know what file extensions are supported.</p>
</div>
END;

?>