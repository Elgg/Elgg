Release Guidelines + Roadmap
############################

Release guidelines cover what to expect when upgrading Elgg.

Release Guidelines
==================

TODO: Consider just linking to semver.org

Bugfix Releases (1.9.x)
-----------------------
Bugfix release are to make sure the latest version of Elgg is stable, secure, and bug free.  The higher the third digit, the more tested and stable the release is. Since bugfix release focus on fixing bugs and not making major changes, themes and plugins should work from bugfix release to bugfix release.


Minor Releases (1.x.0)
----------------------
New features are introduced in Elgg every few months in minor new feature releases.  These versions are identified by the second digit (1.**8**.0) and aren't as mature as bugfix release, but are considered stable and useable.  Though these releases try to be backward compatible, features are added, unused code removed, and overall improvements are made, so plugins and themes might need to be updated to make use of the new and improved features.


Major Releases (x.0.0)
----------------------
Every few years Elgg undergoes serious changes and a major new feature release is made.  These releases are opportunities for Elgg developers to make dramatic improvements.  Themes and plugins from older versions are not expected to work without modification on different major releases.


Release Candidates/Betas
------------------------
TODO


Roadmap
=======

We do not publish detailed roadmaps, but it’s possible to get a sense for our general direction by utilizing the following resources:

* Our `feedback and planning group <http://community.elgg.org/groups/profile/211069/feedback-and-planning>`_ is used to host early discussion about what will be worked on next.
* Our `Github milestones <https://github.com/Elgg/Elgg/issues/milestones>`_ represent a general direction for the future releases of Elgg. This is the closest thing to a traditional roadmap that we have.
* `Github pull requests <https://github.com/elgg/elgg/pulls>`_ will give you a good idea of what’s currently being developed, but nothing is sure until the PR is actually checked in.
* We use the `developer blog <http://blog.elgg.org>`_ to post announcements of features that have recently been checked in to our development branch, which gives the surest indication of what features will be available in the next release.
* Our `about/values` doc covers our general values that guide our direction and affect every change we submit.


FAQ
===

When will feature X be implemented?
-----------------------------------
We cannot promise when features will get implemented because new features are checked into Elgg only when someone is motivated enough to implement the feature and submit a pull request. The best we can do is tell you what features existing developers have expressed interest in working on.

The best way to ensure a feature gets implemented is to discuss it with the core team and implement it yourself. See our `about/contributing` guide if you’re interested. We love new contributors!

Do not rely on future enhancements if you’re on the fence as to whether to use Elgg. Evaluate it given the current feature set, since upcoming features will almost certainly not materialize within your timeline.

When is version X.Y.Z going to be released?
-------------------------------------------
The next version will be released when the core team feels it’s ready and has time to cut the release. See the `Roadmap`_ section for resources on getting a better idea of when this will happen.
