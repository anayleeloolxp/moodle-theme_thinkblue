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
 * @return bool result
 */
function xmldb_theme_thinkblue_install() {
    global $DB, $CFG;

    $time = time();

    $otherobject = (object) array('blockname' => 'tb_slider', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => -10, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_m_slots', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => -9, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_headings', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => -8, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_top_cats', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => -7, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_up_courses', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => -6, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_f_courses', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => -5, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_a_courses', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => -4, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_in_courses', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => -3, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_c_courses', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => -2, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_my_courses', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => -1, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_testimonials', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => 0, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_clients', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => 1, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_teachers', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => 2, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    $otherobject = (object) array('blockname' => 'tb_faq', 'parentcontextid' => 2, 'showinsubcontexts' => 0, 'requiredbytheme' => 0, 'pagetypepattern' => 'site-index', 'subpagepattern' => '', 'defaultregion' => 'abovecontent', 'defaultweight' => 3, 'configdata' => '', 'timecreated' => $time, 'timemodified' => $time);
    $DB->insert_record('block_instances', $otherobject);

    set_config('insertcustomnodesusers', 'Home|/?redirect=0||||||||"calendar"
    Leeloo LXP Dashboard|/local/staticpage/view.php?page=srm|||||||leeloossourl|"calendar"
    Leeloo LXP Social|/local/staticpage/view.php?page=social|||||||leeloossourl|"calendar"
    Blog|/blog/index.php?userid=2||||||||"calendar"
    {editingtoggle}|/course/view.php?id={courseid}&sesskey={sesskey}&edit={editingtoggle}|||editingteacher|admin,manager|OR|fa-pencil|editing|participants', 'local_boostnavigation');

    set_config('removemyhomenode', '1', 'local_boostnavigation');
    set_config('removehomenode', '1', 'local_boostnavigation');
    set_config('removecalendarnode', '1', 'local_boostnavigation');
    set_config('removeprivatefilesnode', '1', 'local_boostnavigation');
    set_config('removemycoursesnode', '1', 'local_boostnavigation');
    set_config('removebadgescoursenode', '1', 'local_boostnavigation');
    set_config('removecompetenciescoursenode', '1', 'local_boostnavigation');
    set_config('removegradescoursenode', '1', 'local_boostnavigation');
    set_config('removeparticipantscoursenode', '1', 'local_boostnavigation');

    set_config('cleanhtml', '2', 'local_staticpage');

    get_enabled_auth_plugins(true); // fix the list of enabled auths
    if (empty($CFG->auth)) {
        $authsenabled = array();
    } else {
        $authsenabled = explode(',', $CFG->auth);
    }

    $auth = 'leeloolxp_tracking_sso';

    if (!in_array($auth, $authsenabled)) {
        $authsenabled[] = $auth;
        $authsenabled = array_unique($authsenabled);
        set_config('auth', implode(',', $authsenabled));
    }

    return true;
}
