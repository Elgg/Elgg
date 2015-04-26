Release Policy
##############

What to expect when upgrading Elgg.

In general, we adhere to `semantic versioning`_.

.. _semantic versioning: http://semver.org

Follow the blog to `stay up to date on the latest releases`__.

__ https://community.elgg.org/blog/all

Bugfix Releases (1.9.x)
-----------------------
Every few weeks.

Bugfix releases are made regularly to make sure Elgg stays stable, secure, and bug-free.
The higher the third digit, the more tested and stable the release is.
Since bugfix release focus on fixing bugs and not making major changes,
themes and plugins should work from bugfix release to bugfix release.


Feature Releases (1.x.0)
------------------------
Every few months.

New features are introduced in Elgg every few months in minor new feature releases.
These versions are identified by the second digit (1.**8**.0).
These releases aren't as mature as bugfix release, but are considered stable and useable.
Though these releases try to be backward compatible,
features are added, unused code removed, and overall improvements are made,
so plugins and themes might need to be updated to make use of the new and improved features.


Major Releases (x.0.0)
----------------------
Every few years.

Elgg, as all software, inevitably undergoes serious changes and a major new feature release is made.
These releases are opportunities for the core team to make dramatic improvements to the underlying platform.
Themes and plugins from older versions are not expected to work without modification on different major releases.


Release Candidates/Betas
------------------------
Before feature releases and major releases, the core team will typically offer a release candidate or beta.
These are meant for testing only and should not be considered production quality.
The core team makes these releases available to get some real-world testing and feedback on the release.
That said, the API in release candidates is considered stable, so
once a release candidate is made available, you should feel comfortable writing plugins against any new APIs.