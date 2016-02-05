Support policy
==============

As of Elgg 1.9, each minor release receives bug fixes for 3 months (until the next minor/major release)
and security/critical fixes for 15 months. These will be released on an as-needed basis.

.. seealso::

   :doc:`releases`

Below is a table outlining the specifics for each release (future dates are tentative):

+----------+----------------------+-------------------+------------------------+
| Version  | First stable release | Bug fixes through | Security fixes through |
+==========+======================+===================+========================+
| 1.8      | September 2011       | August 2014       | September 2015         |
+----------+----------------------+-------------------+------------------------+
| 1.9      | September 2014       | January 2015      | January 2016           |
+----------+----------------------+-------------------+------------------------+
| 1.10     | January 2015         | April 2015        | April 2016             |
+----------+----------------------+-------------------+------------------------+
| 1.11     | April 2015           | July 2015         | July 2016              |
+----------+----------------------+-------------------+------------------------+
| 1.12 LTS | July 2015            | **Until 3.0**     | **Until 4.0**          |
+----------+----------------------+-------------------+------------------------+
| 2.0      | December 2015        | March 2016        | March 2017             |
+----------+----------------------+-------------------+------------------------+
| 2.1      | March 2016           | June 2016         | June 2017              |
+----------+----------------------+-------------------+------------------------+
| 2.2      | June 2016            | September 2016    | September 2017         |
+----------+----------------------+-------------------+------------------------+
| 2.3 LTS  | September 2016       | **Until 4.0**     | **Until 5.0**          |
+----------+----------------------+-------------------+------------------------+
| 3.0      | December 2016        |                   |                        |
+----------+----------------------+-------------------+------------------------+
| 4.0      | December 2017        |                   |                        |
+----------+----------------------+-------------------+------------------------+

Long Term Support Releases
--------------------------

Within each major version, the last minor release is designated for long term support ("LTS") and will
receive bug fixes until the 2nd following major version release, and security fixes until the 3rd
following major version release.

E.g. 1.12 is the last minor release within 1.x. It will receive bug fixes until 3.0 is released and security
fixes until 4.0 is released.

When bugs are found, a good faith effort will be made to patch the LTS release, but **not all fixes
will be back-ported.** E.g. some fixes may depend on new APIs, break backwards compatibility, or require
significant refactoring. If a fix risks stability of the LTS branch, it will not be included.
