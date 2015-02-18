<?php
namespace Elgg;

/**
 * Class formerly used to determine if access is being ignored.
 *
 * This exists now purely to be extended by ElggSession, so that elgg_get_access_object()
 * can return the session while being an instance of this.
 *
 * @access private
 * @see \ElggSession
 */
class Access {
}
