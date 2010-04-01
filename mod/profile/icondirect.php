<?php

	/**
	 * Elgg profile icon cache/bypass
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// This should provide faster access to profile icons by not loading the
	// engine but directly grabbing the file from the user's profile directory.
	// The speedup was broken in Elgg 1.7 because of a change in directory structure.
	// The link to this script is provided in profile_usericon_hook(). To work
	// in 1.7 forward, the link has to be updated to provide more information.
	// The profile icon filename should also be changed to not use username.

	// To see previous code, see svn history.

	// At the moment, this does not serve much of a purpose other than provide
	// continuity. It currently just includes icon.php which uses the engine.

	// see #1989 and #2035

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	require_once(dirname(__FILE__).'/icon.php');
