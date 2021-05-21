Don't Modify Core
=================

.. warning:: 

    In general, you shouldn't modify non-config files that come with third-party software like Elgg.
   
The best way to customize the behavior of Elgg is to :doc:`install Elgg as a composer dependency</intro/install>`
and use a :doc:`plugin<plugins>` to store modifications specific to your application,
and alter behavior through the rich Elgg plugin API.

If you'd like to share customizations between sites or even publish your changes
as a reusable package for the community, create a :doc:`plugin<plugins>`
using the same plugin APIs and file structure.

It makes it hard to get help
----------------------------

When you don't share the same codebase as everyone else,
it's impossible for others to know what is going on in your system
and whether your changes are to blame. This can frustrate those who offer help
because it can add considerable noise to the support process.

It makes upgrading tricky and potentially disastrous
----------------------------------------------------

You will certainly want or need to upgrade Elgg to take advantage of

 * security patches
 * new features
 * new plugin APIs
 * new stability improvements
 * performance improvements
 
If you've modified core files, then you must be very careful when upgrading that
your changes are not overwritten and that they are compatible with the new Elgg code.
If your changes are lost or incompatible, then the upgrade may remove features 
you've added and even completely break your site.

This can also be a slippery slope. Lots of modifications can lead you to an
upgrade process so complex that it's practically impossible.
There are lots of sites stuck running old versions software due to taking this path.

It may break plugins
--------------------

You may not realize until much later that your "quick fix" broke
seemingly unrelated functionality that plugins depended on.

Summary
-------

- Resist the temptation
   Editing existing files is quick and easy, but doing so heavily risks the
   maintainability, security, and stability of your site.
- When receiving advice, consider if the person telling you to modify core will
  be around to rescue you if you run into trouble later!
- Apply these principle to software in general. 
   If you can avoid it, don't modify third party plugins either, for the same reasons:
   Plugin authors release new versions, too, and you will want those updates.