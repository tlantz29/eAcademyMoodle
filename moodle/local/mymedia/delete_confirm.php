<?php

// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * My Media version file
 *
 * @package    local
 * @subpackage mymedia
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//require_once(dirname(dirname(dirname(__FILE__))) . '/local/kaltura/locallib.php');

//function local_kaltura_delete_video($connection, $entry_id) {
//return $connection->media->delete($entry_id);
//}
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/local/kaltura/locallib.php');

$entry_id   = required_param('entry_id', PARAM_TEXT);
//$name       = required_param('name', PARAM_TEXT);
//$tags       = required_param('tags', PARAM_TEXT);
//$desc       = required_param('desc', PARAM_TEXT);
//$gshare     = required_param('gshare', PARAM_INT);
//$share      = required_param('share', PARAM_SEQUENCE);

$kaltura = new kaltura_connection();
$connection = $kaltura->get_connection(true, KALTURA_SESSION_LENGTH);
//require_login();

//global $USER;

//$context = get_context_instance(CONTEXT_USER, $USER->id);
//require_capability('local/mymedia:view', $context, $USER);
function show_confirm()
{
var r=confirm("Press a button");
if (r==true)
{
alert("You pressed OK!");
}
else
{
alert("You pressed Cancel!");
}
} 


//local_kaltura_delete_video($connection,$entry_id);
header ("location: http://roll-moodle.wiueacademy.org/local/mymedia/mymedia.php/");

