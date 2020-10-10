<?php
// This file is part of Moodle - http://moodle.org/
//
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
 * Theme Think Blue - Upgrade script
 *
 * @package    theme_thinkblue
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author     Leeloo LXP <info@leeloolxp.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Function to upgrade theme_thinkblue
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_theme_thinkblue_upgrade($oldversion) {
    global $DB;

    if ($oldversion < 2018051701) {
        // The setting "theme_thinkblue|navdrawericons" has been deleted because this functionality was
        // integrated into core.
        // Set the config to null.
        set_config('navdrawericons', null, 'theme_thinkblue');

        // The setting "theme_thinkblue|nawdrawerfullwidth" has been renamed to navdrawerfullwidth.
        // If the setting is configured.
        if ($oldnavdrawerfullwidth = get_config('theme_thinkblue', 'nawdrawerfullwidth')) {
            // Set the value of the setting to the new setting.
            set_config('navdrawerfullwidth', $oldnavdrawerfullwidth, 'theme_thinkblue');
            // Drop the old setting.
            set_config('nawdrawerfullwidth', null, 'theme_thinkblue');
        }

        upgrade_plugin_savepoint(true, 2018051701, 'theme', 'thinkblue');
    }

    if ($oldversion < 2018121700) {
        // The setting "theme_thinkblue|incoursesettingsswitchtorole" has been renamed because the setting was
        // upgraded with another option.
        // Therefore set the old config to null.
        set_config('incoursesettingsswitchtorole', null, 'theme_thinkblue');

        upgrade_plugin_savepoint(true, 2018121700, 'theme', 'thinkblue');
    }

    if ($oldversion < 2020030800) {

        // The setting "theme_thinkblue|imageareaitemslinks" has been renamed to imageareaitemsattributes.
        // If the setting is configured.
        if ($oldimageareaitemslinks = get_config('theme_thinkblue', 'imageareaitemslink')) {
            // Set the value of the setting to the new setting.
            set_config('imageareaitemsattributes', $oldimageareaitemslinks, 'theme_thinkblue');
            // Drop the old setting.
            set_config('imageareaitemslink', null, 'theme_thinkblue');
        }

        upgrade_plugin_savepoint(true, 2020030800, 'theme', 'thinkblue');
    }

    return true;
}
