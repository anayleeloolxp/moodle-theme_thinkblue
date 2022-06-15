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

/**
 * Function to upgrade theme_thinkblue
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_theme_thinkblue_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2022053001) {
        $table = new xmldb_table('tb_game_points');
        $dbman->rename_table($table, 'theme_thinkblue_points', true, true);
        upgrade_plugin_savepoint(true, 2022053001, 'theme', 'thinkblue');
    }

    if ($oldversion < 2022053002) {

        // Define table theme_thinkblue_points to be dropped.
        $table = new xmldb_table('theme_thinkblue_points');

        // Conditionally launch drop table for theme_thinkblue_points.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Define table theme_thinkblue_points to be created.
        $table = new xmldb_table('theme_thinkblue_points');

        // Adding fields to table theme_thinkblue_points.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('useremail', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('oldpointsdata', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('pointsdata', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('needupdategame', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');

        // Adding keys to table theme_thinkblue_points.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for theme_thinkblue_points.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Thinkblue savepoint reached.
        upgrade_plugin_savepoint(true, 2022053002, 'theme', 'thinkblue');
    }

    return true;
}
