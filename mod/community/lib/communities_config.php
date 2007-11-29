<?php

/*
 * communities_config.php
 *
 * Created on Apr 9, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
/**
 * Defines how many members show in the listings
 * Type: numeric
 */
define('COMMUNITY_MEMBERS_PER_ROW', 2);

/**
 * Defines the icon size for the listings
 * Type: numeric
 */
define('COMMUNITY_ICON_SIZE', 70);

/**
 * Defines the default context to be used to handle communities
 * Type: string
 * Sugested values are:
 *        - network  (default)
 *        - community
 */
define('COMMUNITY_CONTEXT', 'network');

/**
 * Defines if you want to show the communities in a compact view
 * (owned and not owned communities in a page)
 * Type: boolean
 */
define('COMMUNITY_COMPACT_VIEW', false);

 /**
  * Defines if its allowed to a community have members of type 'community'
  * Type: boolean
  */
define('COMMUNITY_ALLOW_COMMUNITY_TYPE_MEMBERS',false);
?>
