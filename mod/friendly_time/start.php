<?php
/**
 * Display relative times in Javascript
 *
 * @package        Lorea
 * @subpackage     FriendlyTime
 * @homepage       https://lorea.org/plugin/friendly_time
 * @copyright      2011-2013 Lorea Faeries <federation@lorea.org>
 * @license        COPYING, http://www.gnu.org/licenses/agpl
 *
 * Copyright 2011-2013 Lorea Faeries <federation@lorea.org>
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License
 * as published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see
 * <http://www.gnu.org/licenses/>.
 */

elgg_register_event_handler('init', 'system', 'friendly_time_init');

/**
 * Friendly time plugin initialization functions.
 */
function friendly_time_init() {

	// Extend JS
	elgg_extend_view('js/elgg', 'friendly_time/js');

}
