Support policy
##############

As of Elgg 2.0, each minor release receives bug and security fixes only until the next minor release.

.. contents:: Contents
   :depth: 2
   :local:

Long Term Support Releases
==========================

Within each major version, the last minor release is designated for long term support ("LTS") and will
receive bug fixes until 1 year after the release of the next major version and security fixes until the 2nd
following major version release.

E.g. 2.3 is the last minor release within 2.x. It will receive bug fixes until 1 year aftr 3.0 is released and
security fixes until 4.0 is released.

.. seealso::

   - :doc:`releases`
   - :doc:`/contribute/issues`

Bugs
----

When bugs are found, a good faith effort will be made to patch the LTS release, but **not all fixes
will be back-ported.** E.g. some fixes may depend on new APIs, break backwards compatibility, or require
significant refactoring.

.. important::

	 If a fix risks stability of the LTS branch, it will not be included.

Security issues
---------------

When a security issue is found every effort will be made to patch the LTS release.

.. attention::

	Please report any security issue to **security @ elgg . org**

Timeline
========

Below is a table outlining the specifics for each release (future dates are tentative):

+----------+----------------------+--------------------+------------------------+
| Version  | First stable release | Bug fixes through  | Security fixes through |
+==========+======================+====================+========================+
| 1.12     | July 2015            | April 2019         | April 2019             |
+----------+----------------------+--------------------+------------------------+
| 2.0      | December 2015        | March 2016         |                        |
+----------+----------------------+--------------------+------------------------+
| 2.1      | March 2016           | June 2016          |                        |
+----------+----------------------+--------------------+------------------------+
| 2.2      | June 2016            | November 2016      |                        |
+----------+----------------------+--------------------+------------------------+
| 2.3 LTS  | November 2016        | April 2020         | **Until 4.0**          |
+----------+----------------------+--------------------+------------------------+
| 3.0 LTS  | April 2019           |                    |                        |
+----------+----------------------+--------------------+------------------------+
| 4.0      | TBD                  |                    |                        |
+----------+----------------------+--------------------+------------------------+
