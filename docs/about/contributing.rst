Contributing
############

This document contains information on how to contribute to the Elgg project.

Translations
============
We use `Transifex <https://www.transifex.com/organization/elgg>`_ to manage official translations of Elgg core, docs, and plugins. Register for an account there to start contributing.

https://www.transifex.com/projects/p/elgg-core/


Documentation
=============
Some tips on writing documentation that fits stylistically with the rest of Elgg’s docs.

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
Focus on fixing the topic at hand. If they want to contribute, they will come to this section to learn how.


Prefer absolute dates over relative ones
----------------------------------------
It is not easy to tell when a particular sentence or paragraph was written, so relative dates quickly become meaningless. Absolute dates also give the reader a good indication of whether a project has been abandoned, or whether some advice might be out of date.

Examples:

* Write “August 2013” rather than “recently.” Alternatively, write “recently (as of September 2013).”
* Write “October 2013” rather than “soon”. Alternatively, write “soon (as of September 2013).”
