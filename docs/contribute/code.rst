Writing Code
############

Pull requests
-------------

We love pull requests! Submitting a pull request is the best way to get code changes integrated into core.
Here’s how to get your patch accepted as quickly as possible:

Before submitting a pull request:

-  **By submitting a pull request you are agreeing to license the code
   under a `GPLv2 license`_ and `MIT license`_.**
-  For new features, `submit a feature request`_ or `talk to us`_ first and make
   sure the core team approves of your direction before spending lots of time on code.

Good PR checklist:

-  Clear, meaningful title
-  Commit messages are in the `standard commit message format`_
-  Detailed description
-  Includes relevant tests (unit, e2e, etc.)
-  Includes :doc:`documentation update <docs>`
-  Passes the continuous build
-  Is submitted against the correct branch:
   
   -  New features should be submitted against master. We do not introduce
      new features in bugfix branches.
   -  Bugfixes should be submitted against the latest non-master branch
      (unless the bug only appears in master).

.. _GPLv2 license: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
.. _MIT license: http://en.wikipedia.org/wiki/MIT_License
.. _talk to us: http://community.elgg.org/groups/profile/211069/feedback-and-planning
.. _standard commit message format: https://github.com/Elgg/Elgg/issues/5976
.. _submit a feature request: :doc:`/contribute/issues`