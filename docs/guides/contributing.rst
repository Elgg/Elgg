Contributing
############

This document contains information on how to contribute to the Elgg project.

Documentation
=============
Some tips on writing documentation that fits stylistically with the rest of Elgg’s docs.


Follow the existing document organization
-----------------------------------------

intro/*
^^^^^^^
This is everything that brand new users need to know (installation, features, license, etc.)

about/*
^^^^^^^
More detailed/meta/background information about the project (history, values, contributing, etc.)

guides/*
^^^^^^^^
API guides for plugin developers. Cookbook-style. Example heavy. Code snippet heavy. Broken down by services (actions, i18n, routing, db, etc.). This should only discuss the public API and its behavior, not implementation details or reasoning.

design/*
^^^^^^^^
Design docs for people who want to get a better understanding of how/why core is built the way it is. This should discuss internal implementation details of the various services, what tradeoffs were made, and the reasoning behind the final decision. Should be useful for people who want to contribute and for communication b/w core devs.



Make sure your use of the word Elgg is grammatically correct
------------------------------------------------------------
Elgg is not an acronym, so writing it in all caps (ELGG or E-LGG) is incorrect. Please don’t do this.

In English, Elgg does not take an article when used as a noun:
 * “I’m using Elgg to run my website”
 * “Install Elgg to get your community online”

When used as an adjective, the article applies to the main noun, so you should use one. For example “Go to the Elgg community website to get help.” This advice may not apply in languages other than English.



Avoid first person pronouns
---------------------------
Refer to the reader as “you.” Do not include yourself in the normal narrative. For example:

When <del>we’re</del><ins>you’re</ins> done installing Elgg, <del>we’ll </del>look for some plugins!

To refer to yourself (avoid this if possible), use your name and write in the third person. This clarifies to future readers/editors whose opinions are being expressed. For example:

<del>I think</del><ins>Evan thinks</ins> the best way to do X is to use Y.


Eliminate fluff
---------------
<del>If you want t</del><ins>T</ins>o use a third-party javascript library <del>within the Elgg framework</del>, <del>you should take care to </del>call <del>the </del>``elgg_register_js`` <del>function </del> to register it.


Do not remind the reader to contribute
--------------------------------------
Focus on fixing the topic at hand. Do not solicit bug fixes. If they want to contribute to the project, they will come to this section to learn how and don't need to be constantly reminded how needy we are.


Prefer absolute dates over relative ones
----------------------------------------
It is not easy to tell when a particular sentence or paragraph was written, so relative dates quickly become meaningless. Absolute dates also give the reader a good indication of whether a project has been abandoned, or whether some advice might be out of date.

Examples:

* Write “August 2013” rather than “recently.” Alternatively, write “recently (as of September 2013).”
* Write “October 2013” rather than “soon”. Alternatively, write “soon (as of September 2013).”


