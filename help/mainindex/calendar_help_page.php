<?php
global $CFG;
$sitename = sitename;
$run_result .= <<< END

<div class="helpFiles">

    <h5>How to create an event</h5>
    <p><b>>> Select 'Post a new event'</b></p>
    <img src="{$CFG->wwwroot}help/images/event_start.jpg" alt="Post an event" title="Post an event" border="0" />
    <p><b>Step one:</b> Now you will see the 'Add a new event' page. First you give your event a title.</p>
    <img src="{$CFG->wwwroot}help/images/event_one.jpg" alt="Title" title="Title" border="0" />
    <p><b>Step two:</b> Now select the start and end date for the event.</p>
    <img src="{$CFG->wwwroot}help/images/event_two.jpg" alt="Start and End Date" title="Start and End Date" border="0" />
    <p><b>Step three:</b> <i>(optional)</i> Enter a location for the event.</p>
    <img src="{$CFG->wwwroot}help/images/event_three.jpg" alt="Location" title="Location" border="0" />
    <p><b>Step four:</b> <i>(optional)</i> Enter a description for the event.</p>
    <img src="{$CFG->wwwroot}help/images/event_four.jpg" alt="Description" title="Description" border="0" />
    <p><b>Step five:</b> <i>(optional)</i> You might want to add some keywords (tags). These keywords can be individual words or phrase - <b>separated by a comma</b>.</p>
    <img src="{$CFG->wwwroot}help/images/event_five.jpg" alt="Keywords" title="Keywords" border="0" />
    <p><b>Step six:</b> <i>(optional - default is public)</i> Like all things in <b>your</b> landscape you can control who gets to read your blog post. Use the 'Access Restrictions' to do this.</p>
    <img src="{$CFG->wwwroot}help/images/event_six.jpg" alt="Access" title="Access" border="0" />
    <p><i>Note: You can create your own access restrictions at 'Your Network' - 'Access controls'</i></p>
    <p><b>Step seven:</b> Now just press 'Save Event' and you have created your first event!</p>
    <h5>Other functions</h5>
    <p><b>RSS feed:</b> this is so people can follow your blog posts using an RSS aggregator. (<i>Note: only the posts you mark as public</i>)</p>
    <p><b>Archive:</b> this is all your events stored by month.</p>
    <p><b>Friends calendars:</b> this displays all the events made by your friends.</p>
    <p><b>Community calendars:</b> this displays all the events in a community.</p>
    <p><b>View calendars:</b> this displays all events you have access to view including your own events</p>
    <p><b>Import calendar:</b> allows a user to import from an iCal (.ics) file format</p>
    <h5>Troubleshooting</h5>
    <p><b>Why are my keywords not working?</b></p>
    <p>Your keywords will only create links if the keyword exists somewhere else in the system. If not you are the first and you will need to wait until someone else uses it. Also make sure you have separated with commas.</p>
    <p><b>The RSS feed page is all code?</b></p>
    <p>This is what an RSS feed looks like - it is designed to be interpreted by an RSS reader.</p>

</div>
END;
?>