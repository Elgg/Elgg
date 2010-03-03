/**
	 * Elgg readme
	 * 
	 * @package ElggMessages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Dave Tosh <dave@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
*/

Install: drop the plugin into your mod folder, that is it.

Notes:

Each message has a series of metadata which is used to control how the message displays.

The metadata toggles are:

hiddenFrom - used to 'delete' from the sentbox
hiddenTo - used to 'delete' from the inbox
readYet - 0 means no, 1 means yes it has been read

This is actually a tricky little plugin as there is only ever one instance of a message, how it is viewed 
depends on who is looked at and in what context.