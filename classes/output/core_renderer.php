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
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_thinkblue
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author     Leeloo LXP <info@leeloolxp.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_thinkblue\output;

use action_link;
use action_menu;
use action_menu_filler;
use action_menu_link_secondary;
use context_course;
use context_header;
use help_icon;
use html_writer;
use moodle_url;
use navigation_node;
use pix_icon;
use single_button;
use stdClass;
use user_picture;
use curl;
use completion_info;

/**
 * Extending the core_renderer interface.
 *
 * @copyright 2017 Kathrin Osswald, Ulm University kathrin.osswald@uni-ulm.de
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package theme_thinkblue
 * @category output
 */
class core_renderer extends \core_renderer {
    /**
     * Get leeloo settings
     * @return \core_renderer::edit_button Moodle edit button.
     */
    public function getleeloosettings() {
        global $CFG;
        require_once($CFG->dirroot . '/theme/thinkblue/locallib.php');
        return theme_thinkblue_general_leeloosettings();
    }
    /**
     * Compare rewards
     *
     * @param string $email email
     * @return array data
     */
    public function compare_rewards($email) {

        global $DB;

        $gamepoints = $DB->get_record('theme_thinkblue_points', array('useremail' => $email));

        if (!empty($gamepoints)) {
            $newjson = json_decode($gamepoints->pointsdata);
            $oldjson = json_decode($gamepoints->oldpointsdata);

            $points = $newjson->total_points - $oldjson->total_points;
            $xps = $newjson->total_xps - $oldjson->total_xps;

            $changetype = '';
            if (!empty($points) || !empty($xps)) {
                $changetype = 'minor';
            }

            $rewardsvardata = [];

            if (!empty($newjson->rewards_data)) {
                foreach ($newjson->rewards_data as $key => $value) {
                    $keyyy = array_search($value->name, array_column($oldjson->rewards_data, 'name'));
                    if (is_int($keyyy) || $oldjson->rewards_data == 0) {
                        $changetype = 'major';
                        $value->diff = $value->totalcount - $oldjson->rewards_data[$keyyy]->totalcount;
                    } else if (!is_int($keyyy) && $oldjson->rewards_data != 0) {
                        $changetype = 'major';
                        $value->diff = $value->totalcount;
                    }
                    $rewardsvardata[] = $value;
                }
            }

            $response = array(
                'points' => $points,
                'xps' => $xps,
                'rewards_data' => $rewardsvardata,
                'change_type' => $changetype
            );
        } else {
            $response = array(
                'points' => 0,
                'xps' => 0,
                'rewards_data' => [],
                'change_type' => ''
            );
        }

        return $response;
    }
    /**
     * Override to display an edit button again by calling the parent function
     * in core/core_renderer because theme_boost's function returns an empty
     * string and therefore displays nothing.
     *
     * MODIFICATION: This renderer function is copied and modified from /theme/boost/classes/output/core_renderer.php
     *
     * @param moodle_url $url The current course url.
     * @return \core_renderer::edit_button Moodle edit button.
     */
    public function edit_button(moodle_url $url) {
        // MODIFICATION START.
        // If setting editbuttonincourseheader ist checked give out the edit on / off button in course header.
        if (@$this->getleeloosettings()->course_layout_settings->courseeditbutton == '1') {
            return \core_renderer::edit_button($url);
        }
    }
    /**
     * Override to add additional class for the random login image to the body.
     *
     * Returns HTML attributes to use within the body tag. This includes an ID and classes.
     *
     * KIZ MODIFICATION: This renderer function is copied and modified from /lib/outputrenderers.php
     *
     * @since Moodle 2.5.1 2.6
     * @param string|array $additionalclasses Any additional classes to give the body tag,
     * @return string
     */
    public function body_attributes($additionalclasses = array()) {
        global $CFG, $USER;

        $reqstartquiz = optional_param('startattempt', 0, PARAM_RAW);
        if ($reqstartquiz == 1) {
            $quizid = optional_param('id', 0, PARAM_RAW);
            $usersession = $USER->sesskey;
            $urltogo = $CFG->wwwroot .
                '/mod/quiz/startattempt.php?_qf__mod_quiz_preflight_check_form=1&cmid=' . $quizid . '&sesskey=' . $usersession;
            redirect($urltogo);
        }

        require_once($CFG->dirroot . '/theme/thinkblue/locallib.php');
        if (!is_array($additionalclasses)) {
            $additionalclasses = explode(' ', $additionalclasses);
        }
        // MODIFICATION START.
        // Only add classes for the login page.
        if ($this->page->bodyid == 'page-login-index') {
            $additionalclasses[] = 'loginbackgroundimage';
            // Generating a random class for displaying a random image for the login page.
            $additionalclasses[] = theme_thinkblue_get_random_loginbackgroundimage_class();
        }
        // MODIFICATION END.
        return ' id="' . $this->body_id() . '" class="' . $this->body_css_classes($additionalclasses) . '"';
    }
    /**
     * Override to be able to use uploaded images from admin_setting as well.
     *
     * Returns the moodle_url for the favicon.
     *
     * KIZ MODIFICATION: This renderer function is copied and modified from /lib/outputrenderers.php
     *
     * @since Moodle 2.5.1 2.6
     * @return moodle_url The moodle_url for the favicon
     */
    public function favicon() {
        // MODIFICATION START.
        if (!empty(@$this->getleeloosettings()->general_settings->favicon)) {
            return @$this->getleeloosettings()->general_settings->favicon;
        } else {
            return $this->image_url('favicon', 'theme');
        }
    }
    /**
     * Gamified header.
     *
     * Returns the html.
     *
     * @return moodle_url The moodle_url for the favicon
     */
    public function gamification_header() {
        global $USER, $SITE;
        $gamheader = new stdClass();
        $html = '';
        @$gamificationdata = theme_thinkblue_gamification_data(base64_encode($USER->email));

        global $DB, $CFG;

        if ($USER->id && !is_siteadmin($USER) && count((array)($gamificationdata))) {

            $leelooview = optional_param('view', null, PARAM_RAW);

            $actuallink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
                "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            if ($leelooview == 'dashboard') {
                $gamheader->dashactive = true;
            } else if ($leelooview == 'hero') {
                $gamheader->heroactive = true;
            } else if ($leelooview == 'skills') {
                $gamheader->skillsactive = true;
            } else if (strpos($actuallink, 'leeloolxp-smart-dashboard') !== false) {
                $gamheader->arenaactive = true;
            }

            if (
                strpos($actuallink, 'leeloolxp-smart-dashboard') !== false
                && @$this->getleeloosettings()->general_settings->edition == 1
            ) {
                $gamheader->boardheader = true;
            }

            $gamheader->showsrm = true;

            $userpicture = new user_picture($USER, array('size' => 50, 'class' => ''));
            $src = $userpicture->get_url($this->page);
            $gamheader->avatar = $src;
            $gamheader->fullnameuser = fullname($USER);

            $gamheader->points = $gamificationdata->total_points;
            $gamheader->current_level = $gamificationdata->current_level;
            $gamheader->next_level_percent = $gamificationdata->next_level_percent;

            $gamheader->userteam = $gamificationdata->userteam;

            $gamheader->get_logo_url = $this->get_logo_url();
            $gamheader->sitename = format_string(
                $SITE->fullname,
                true,
                ['context' => context_course::instance(SITEID), "escape" => false]
            );

            $gamheader->currencyhtml = '';

            $gamheader->letsgotxt = get_string('letsgo', 'theme_thinkblue');

            $enrolledcourses = enrol_get_my_courses();

            $enrolledcourseslist = array();
            $count = 0;
            foreach ($enrolledcourses as $key => $enrolledcourse) {

                $completion = new completion_info($enrolledcourse);

                $completionok = [
                    COMPLETION_COMPLETE,
                    COMPLETION_COMPLETE_PASS,
                ];

                $enrolledcoursears = array();

                $arcount = 0;
                $oldsection = '';
                foreach ($completion->get_activities() as $ar) {

                    if ($ar->visible == 1) {
                        $activityname = $ar->name;
                        $activitysection = $ar->section;

                        if ($ar->modname == 'quiz') {
                            $quizid = $ar->get_course_module_record()->instance;
                            $quizdata = $DB->get_record('quiz', array('id' => $quizid), '*', MUST_EXIST);

                            if (isset($quizdata->quiztype)) {
                                $activityiconurl = $CFG->wwwroot . '/local/leeloolxptrivias/pix/' . $quizdata->quiztype . '.png';
                            } else {
                                $activityiconurl = $ar->get_icon_url() . '?default';
                            }
                        } else {
                            $activityiconurl = $ar->get_icon_url();
                        }

                        $activityurl = new moodle_url($ar->url, array('forceview' => 1));

                        $completeclass = '';
                        $hascompletion = $completion->is_enabled($ar);
                        if ($hascompletion) {
                            $completeclass = 'incomplete';
                        }

                        $completiondata = $completion->get_data(
                            $ar,
                            true
                        );
                        if (in_array(
                            $completiondata->completionstate,
                            $completionok
                        )) {
                            $completeclass = 'completed active';
                        }

                        if ($completeclass == 'incomplete') {

                            if (($activitysection == $oldsection || $oldsection == 0) && $arcount <= 5) {
                                $oldsection = $activitysection;

                                $enrolledcoursears[0]['activitysectionname'] = $ar->get_section_info()->name;
                                $enrolledcoursears[0]['activities'][$arcount]['activityname'] = $activityname;
                                $enrolledcoursears[0]['activities'][$arcount]['activitysection'] = $activitysection;
                                $enrolledcoursears[0]['activities'][$arcount]['activityiconurl'] = $activityiconurl;
                                $enrolledcoursears[0]['activities'][$arcount]['activityurl'] = $activityurl;
                                $enrolledcoursears[0]['activities'][$arcount]['completeclass'] = $completeclass;

                                $arcount++;
                            }
                        }
                    }
                }

                @$enrolledcourseslist[$count]->id = $enrolledcourse->id;
                $enrolledcourseslist[$count]->fullname = $enrolledcourse->fullname;
                $enrolledcourseslist[$count]->image = theme_thinkblue_course_image($enrolledcourse);
                $enrolledcourseslist[$count]->url = new moodle_url('/course/view.php', array('id' => $enrolledcourse->id));

                $enrolledcourseslist[$count]->enrolledcoursears = $enrolledcoursears;
                $count++;
            }

            $gamheader->enrolledcourseslist = $enrolledcourseslist;

            $currencycount = 0;
            if (!empty($gamificationdata->rewards_data)) {
                foreach ($gamificationdata->rewards_data as $currency) {
                    if ($currency->type == 'currencies' && $currencycount < 2) {
                        $gamheader->currencyhtml .= '<div class="col-srm">
                            <div class="srm-top-left-div srm-top-left-div-third">
                                <span class="round-span">
                                    <img src="' . $currency->image . '">
                                </span>
                                <span class="box-span">' . $currency->totalcount . '</span>
                                <span class="box-icn-span">
                                    <!--<img src="' . $currency->image . '">-->
                                </span>
                            </div>
                        </div>';
                        $currencycount++;
                    }
                }
            }

            $gamepointscheck = $DB->get_record('theme_thinkblue_points', array('useremail' => $USER->email));
            if (isset($gamepointscheck->needupdategame) && isset($gamepointscheck->needupdategame) != '') {
                $comparerewards = $this->compare_rewards($USER->email);

                if ($comparerewards['change_type'] == 'major') {

                    $rewardsdata = array();

                    if ($comparerewards['points'] != 0) {
                        $rewardsdata[0]->icon = 'https://leeloolxp.com/leeloo_assets/assets/img/1633644604-Jadea.png';
                        $rewardsdata[0]->text = $comparerewards['points'] . ' ' . get_string('neurons', 'theme_thinkblue');
                        if ($comparerewards['points'] > 0) {
                            $rewardsdata[0]->class = 'active';
                        } else {
                            $rewardsdata[0]->class = '';
                        }
                    }

                    if ($comparerewards['xps'] != 0) {
                        $rewardsdata[1]->icon = 'https://leeloolxp.com/leeloo_assets/assets/img/1633644604-Jadea.png';
                        $rewardsdata[1]->text = $comparerewards['xps'] . ' ' . get_string('xps', 'theme_thinkblue');
                        if ($comparerewards['xps'] > 0) {
                            $rewardsdata[1]->class = 'active';
                        } else {
                            $rewardsdata[1]->class = '';
                        }
                    }

                    $countrewardarr = count($rewardsdata);

                    if (!empty($comparerewards['rewards_data'])) {
                        foreach ($comparerewards['rewards_data'] as $singreward) {
                            if ($singreward->diff != 0) {
                                $rewardsdata[$countrewardarr]->icon = $singreward->image;
                                $rewardsdata[$countrewardarr]->text = $singreward->diff . ' ' . $singreward->name;
                                if ($comparerewards['xps'] > 0) {
                                    $rewardsdata[$countrewardarr]->class = 'active';
                                } else {
                                    $rewardsdata[$countrewardarr]->class = '';
                                }
                                $countrewardarr++;
                            }
                        }
                    }

                    if (!empty($rewardsdata)) {
                        $gamheader->showrewardspop = true;
                        $gamheader->rewardsdata = $rewardsdata;
                    }
                } else if ($comparerewards['change_type'] == 'minor') {

                    $rewardsdata = array();

                    if ($comparerewards['points'] != 0) {
                        $rewardsdata[0]->icon = 'https://leeloolxp.com/leeloo_assets/assets/img/1633644604-Jadea.png';
                        $rewardsdata[0]->text = $comparerewards['points'] . ' ' . get_string('neurons', 'theme_thinkblue');
                        if ($comparerewards['points'] > 0) {
                            $rewardsdata[0]->class = 'active';
                        } else {
                            $rewardsdata[0]->class = '';
                        }
                    }

                    if ($comparerewards['xps'] != 0) {
                        $rewardsdata[1]->icon = 'https://leeloolxp.com/leeloo_assets/assets/img/1633644604-Jadea.png';
                        $rewardsdata[1]->text = $comparerewards['xps'] . ' ' . get_string('xps', 'theme_thinkblue');;
                        if ($comparerewards['xps'] > 0) {
                            $rewardsdata[1]->class = 'active';
                        } else {
                            $rewardsdata[1]->class = '';
                        }
                    }

                    $gamheader->showrewardsnoti = true;
                    $gamheader->rewardsdata = $rewardsdata;
                }

                $data['id'] = $gamepointscheck->id;
                $data['needupdategame'] = 0;
                $data['oldpointsdata'] = $gamepointscheck->pointsdata;
                $DB->update_record('theme_thinkblue_points', $data);
            }

            $html = $this->render_from_template('theme_thinkblue/game_header', $gamheader);
        } else {

            if ($USER->id) {
                $userpicture = new user_picture($USER, array('size' => 50, 'class' => ''));
                $src = $userpicture->get_url($this->page);
                $gamheader->avatar = $src;
                $gamheader->fullnameuser = fullname($USER);

                $gamheader->get_logo_url = $this->get_logo_url();
                $gamheader->sitename = format_string(
                    $SITE->fullname,
                    true,
                    ['context' => context_course::instance(SITEID), "escape" => false]
                );

                $html = $this->render_from_template('theme_thinkblue/game_header', $gamheader);
            }
        }
        return $html;
    }
    /**
     * Override to display switched role information beneath the course header instead of the user menu.
     * We change this because the switch role function is course related and therefore it should be placed in the course context.
     *
     * MODIFICATION: This renderer function is copied and modified from /lib/outputrenderers.php
     *
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        // MODIFICATION START.
        global $USER, $COURSE, $CFG;
        // MODIFICATION END.
        if ($this->page->include_region_main_settings_in_header_actions() && !$this->page->blocks->is_block_present('settings')) {
            // Only include the region main settings if the page has requested it and it doesn't already have
            // the settings block on it. The region main settings are included in the settings block and
            // duplicating the content causes behat failures.
            $this->page->add_header_action(html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }
        $header = new stdClass();
        // MODIFICATION START.
        // Show the context header settings menu on all pages except for the profile page as we replace
        // it with an edit button there.
        if ($this->page->pagelayout != 'mypublic') {
            $header->settingsmenu = $this->context_header_settings_menu();
        }
        // MODIFICATION END.
        /* ORIGINAL START
        $header->settingsmenu = $this->context_header_settings_menu();
        ORIGINAL END. */
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        // MODIFICATION START.
        // Show the page heading button on all pages except for the profile page.
        // There we replace it with an edit profile button.
        if ($this->page->pagelayout != 'mypublic') {
            $header->pageheadingbutton = $this->page_heading_button();
        } else {
            // Get the id of the user for whom the profile page is shown.
            $userid = optional_param('id', $USER->id, PARAM_INT);
            // Check if the shown and the operating user are identical.
            $currentuser = $USER->id == $userid;
            if (($currentuser || is_siteadmin($USER)) &&
                has_capability('moodle/user:update', \context_system::instance())
            ) {
                $url = new moodle_url('/user/editadvanced.php', array(
                    'id' => $userid, 'course' => $COURSE->id,
                    'returnto' => 'profile'
                ));
                $header->pageheadingbutton = $this->single_button($url, get_string('editmyprofile', 'core'));
            } else if ((has_capability('moodle/user:editprofile', \context_user::instance($userid)) &&
                !is_siteadmin($USER)) || ($currentuser &&
                has_capability('moodle/user:editownprofile', \context_system::instance()))) {
                $url = new moodle_url('/user/edit.php', array(
                    'id' => $userid, 'course' => $COURSE->id,
                    'returnto' => 'profile'
                ));
                $header->pageheadingbutton = $this->single_button($url, get_string('editmyprofile', 'core'));
            }
        }

        // MODIFICATION END.
        /* ORIGINAL START
        $header->pageheadingbutton = $this->page_heading_button();
        ORIGINAL END. */
        $header->courseheader = $this->course_header();
        $header->headeractions = $this->page->get_header_actions();
        // MODIFICATION START:
        // Change this to add the result in the html variable to be able to add further features below the header.
        // Render from the own header template.
        $html = $this->render_from_template('theme_thinkblue/full_header', $header);
        // MODIFICATION END.
        // MODIFICATION START:
        // If the setting showhintcoursehidden is set, the visibility of the course is hidden and
        // a hint for the visibility will be shown.
        if (
            @$this->getleeloosettings()->course_layout_settings->showhintcoursehidden == 1 && $COURSE->visible == false &&
            $this->page->has_set_url() && $this->page->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
        ) {
            $html .= html_writer::start_tag('div', array('class' => 'course-hidden-infobox alert alert-warning'));
            $html .= html_writer::tag('i', null, array('class' => 'fa fa-exclamation-circle fa-3x fa-pull-left'));
            $html .= get_string('showhintcoursehiddengeneral', 'theme_thinkblue', $COURSE->id);
            // If the user has the capability to change the course settings, an additional link to the course settings is shown.
            if (has_capability('moodle/course:update', context_course::instance($COURSE->id))) {
                $html .= html_writer::tag('div', get_string(
                    'showhintcoursehiddensettingslink',
                    'theme_thinkblue',
                    array('url' => $CFG->wwwroot . '/course/edit.php?id=' . $COURSE->id)
                ));
            }
            $html .= html_writer::end_tag('div');
        }
        // MODIFICATION END.
        // MODIFICATION START:
        // If the setting showhintcourseguestaccess is set, a hint for users that view the course with guest access is shown.
        // We also check that the user did not switch the role. This is a special case for roles that can fully access the course
        // without being enrolled. A role switch would show the guest access hint additionally in that case and this is not
        // intended.
        if (
            @$this->getleeloosettings()->course_layout_settings->showhintcourseguestaccess == 1
            && is_guest(\context_course::instance($COURSE->id), $USER->id)
            && $this->page->has_set_url()
            && $this->page->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
            && !is_role_switched($COURSE->id)
        ) {
            $html .= html_writer::start_tag('div', array('class' => 'course-guestaccess-infobox alert alert-warning'));
            $html .= html_writer::tag('i', null, array('class' => 'fa fa-exclamation-circle fa-3x fa-pull-left'));
            $html .= get_string(
                'showhintcourseguestaccessgeneral',
                'theme_thinkblue',
                array('role' => role_get_name(get_guest_role()))
            );
            $html .= theme_thinkblue_get_course_guest_access_hint($COURSE->id);
            $html .= html_writer::end_tag('div');
        }
        // MODIFICATION END.
        // MODIFICATION START.
        // Only use this if setting 'showswitchedroleincourse' is active.
        if (@$this->getleeloosettings()->course_layout_settings->showswitchedroleincourse === 1) {
            // Check if the user did a role switch.
            // If not, adding this section would make no sense and, even worse,
            // user_get_user_navigation_info() will throw an exception due to the missing user object.
            if (is_role_switched($COURSE->id)) {
                // Get the role name switched to.
                $opts = \user_get_user_navigation_info($USER, $this->page);
                $role = $opts->metadata['rolename'];
                // Get the URL to switch back (normal role).
                $url = new moodle_url(
                    '/course/switchrole.php',
                    array(
                        'id' => $COURSE->id, 'sesskey' => sesskey(), 'switchrole' => 0,
                        'returnurl' => $this->page->url->out_as_local_url(false)
                    )
                );
                $html .= html_writer::start_tag('div', array('class' => 'switched-role-infobox alert alert-info'));
                $html .= html_writer::tag('i', null, array('class' => 'fa fa-user-circle fa-3x fa-pull-left'));
                $html .= html_writer::start_tag('div');
                $html .= get_string('switchedroleto', 'theme_thinkblue');
                // Give this a span to be able to address via CSS.
                $html .= html_writer::tag('span', $role, array('class' => 'switched-role'));
                $html .= html_writer::end_tag('div');
                // Return to normal role link.
                $html .= html_writer::start_tag('div');
                $html .= html_writer::tag(
                    'a',
                    get_string('switchrolereturn', 'core'),
                    array('class' => 'switched-role-backlink', 'href' => $url)
                );
                $html .= html_writer::end_tag('div'); // Return to normal role link: end div.
                $html .= html_writer::end_tag('div');
            }
        }
        // MODIFICATION END.
        return $html;
    }
    /**
     * Override to display course settings on every course site for permanent access
     *
     * This is an optional menu that can be added to a layout by a theme. It contains the
     * menu for the course administration, only on the course main page.
     *
     * MODIFICATION: This renderer function is copied and modified from /lib/outputrenderers.php.
     *
     * @return string
     */
    public function context_header_settings_menu() {
        $context = $this->page->context;
        $menu = new action_menu();
        $items = $this->page->navbar->get_items();
        $currentnode = end($items);
        $showcoursemenu = false;
        $showfrontpagemenu = false;
        $showusermenu = false;
        // We are on the course home page.
        // MODIFICATION START.
        // REASON: With the original code, the course settings icon will only appear on the course main page.
        // Therefore the access to the course settings and related functions is not possible on other
        // course pages as there is no omnipresent block anymore. We want these to be accessible
        // on each course page.
        if (($context->contextlevel == CONTEXT_COURSE || $context->contextlevel == CONTEXT_MODULE) && !empty($currentnode)) {
            $showcoursemenu = true;
        }
        // MODIFICATION END.
        // @codingStandardsIgnoreStart
        /* ORIGINAL START.
        if (($context->contextlevel == CONTEXT_COURSE) && !empty($currentnode) &&
        ($currentnode->type == navigation_node::TYPE_COURSE || $currentnode->type == navigation_node::TYPE_SECTION)) {
        $showcoursemenu = true;
        }
        ORIGINAL END. */
        // @codingStandardsIgnoreEnd
        $courseformat = course_get_format($this->page->course);
        // This is a single activity course format, always show the course menu on the activity main page.
        if (
            $context->contextlevel == CONTEXT_MODULE &&
            !$courseformat->has_view_page()
        ) {
            $this->page->navigation->initialise();
            $activenode = $this->page->navigation->find_active_node();
            // If the settings menu has been forced then show the menu.
            if ($this->page->is_settings_menu_forced()) {
                $showcoursemenu = true;
            } else if (!empty($activenode) && ($activenode->type == navigation_node::TYPE_ACTIVITY ||
                $activenode->type == navigation_node::TYPE_RESOURCE)) {
                // We only want to show the menu on the first page of the activity. This means
                // the breadcrumb has no additional nodes.
                if ($currentnode && ($currentnode->key == $activenode->key && $currentnode->type == $activenode->type)) {
                    $showcoursemenu = true;
                }
            }
        }
        // This is the site front page.
        if (
            $context->contextlevel == CONTEXT_COURSE &&
            !empty($currentnode) &&
            $currentnode->key === 'home'
        ) {
            $showfrontpagemenu = true;
        }
        // This is the user profile page.
        if (
            $context->contextlevel == CONTEXT_USER &&
            !empty($currentnode) &&
            ($currentnode->key === 'myprofile')
        ) {
            $showusermenu = true;
        }
        if ($showfrontpagemenu) {
            $settingsnode = $this->page->settingsnav->find('frontpage', navigation_node::TYPE_SETTING);
            if ($settingsnode) {
                // Build an action menu based on the visible nodes from this navigation tree.
                $skipped = $this->build_action_menu_from_navigation($menu, $settingsnode, false, true);
                // We only add a list to the full settings menu if we didn't include every node in the short menu.
                if ($skipped) {
                    $text = get_string('morenavigationlinks');
                    $url = new moodle_url('/course/admin.php', array('courseid' => $this->page->course->id));
                    $link = new action_link($url, $text, null, null, new pix_icon('t/edit', $text));
                    $menu->add_secondary_action($link);
                }
            }
        } else if ($showcoursemenu) {
            $settingsnode = $this->page->settingsnav->find('courseadmin', navigation_node::TYPE_COURSE);
            if ($settingsnode) {
                // Build an action menu based on the visible nodes from this navigation tree.
                $skipped = $this->build_action_menu_from_navigation($menu, $settingsnode, false, true);
                // We only add a list to the full settings menu if we didn't include every node in the short menu.
                if ($skipped) {
                    $text = get_string('morenavigationlinks');
                    $url = new moodle_url('/course/admin.php', array('courseid' => $this->page->course->id));
                    $link = new action_link($url, $text, null, null, new pix_icon('t/edit', $text));
                    $menu->add_secondary_action($link);
                }
            }
        } else if ($showusermenu) {
            // Get the course admin node from the settings navigation.
            $settingsnode = $this->page->settingsnav->find('useraccount', navigation_node::TYPE_CONTAINER);
            if ($settingsnode) {
                // Build an action menu based on the visible nodes from this navigation tree.
                $this->build_action_menu_from_navigation($menu, $settingsnode);
            }
        }
        return $this->render($menu);
    }
    /**
     * Override to use theme_thinkblue login template
     * Renders the login form.
     *
     * MODIFICATION: This renderer function is copied and modified from lib/outputrenderers.php
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form) {
        global $CFG, $SITE;
        $context = $form->export_for_template($this);
        // Override because rendering is not supported in template yet.
        if ($CFG->rememberusername == 0) {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabledonlysession');
        } else {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
        }
        $context->errorformatted = $this->error_text($context->error);
        $url = $this->get_logo_url();
        $context->logourl = $url;
        $context->siteurl = $CFG->wwwroot;
        $context->sitename = format_string(
            $SITE->fullname,
            true,
            ['context' => context_course::instance(SITEID), "escape" => false]
        );
        // MODIFICATION START.
        // Only if setting "loginform" is checked, then call own login.mustache.
        if (@$this->getleeloosettings()->design_settings->loginform == 1) {
            return @$this->render_from_template('theme_thinkblue/loginform', $context);
        } else {
            return $this->render_from_template('core/loginform', $context);
        }
    }
    /**
     * Implementation of user image rendering.
     *
     * MODIFICATION: This renderer function is copied and modified from lib/outputrenderers.php
     *
     * @param help_icon $helpicon A help icon instance
     * @return string HTML fragment
     */
    protected function render_help_icon(help_icon $helpicon) {
        $context = $helpicon->export_for_template($this);
        // MODIFICATION START.
        // ID needed for modal dialog.
        $context->linkid = $helpicon->component . '-' . $helpicon->identifier;
        // Fill body variable needed for modal mustache with text value.
        $context->body = $context->text;
        if (@$this->getleeloosettings()->design_settings->helptextmodal == 1) {
            return $this->render_from_template('theme_thinkblue/help_icon', $context);
        } else {
            return $this->render_from_template('core/help_icon', $context);
        }
    }
    /**
     * User menu renderer
     *
     * @param stdClass|stdObject $user User object
     * @param string $withlinks with links
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');
        if (is_null($user)) {
            $user = $USER;
        }
        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }
        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }
        $returnstr = "";
        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }
        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = " <a class='browse_link' href=\"$loginurl\">" . get_string('browse_header', 'theme_thinkblue') . '</a>';
            if (!$loginpage) {
                $returnstr .= " <a class='login_link' href=\"$loginurl\">" . get_string('login') . '</a>';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }
        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = " <a class='browse_link' href=\"$loginurl\">" . get_string('browse_header', 'theme_thinkblue') . '</a>';
            if (!$loginpage && $withlinks) {
                $returnstr .= " <a class='login_link' href=\"$loginurl\">" . get_string('login') . '</a>';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }
        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, $this->page);
        $opts->metadata['userfullname'] = $USER->firstname;
        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = $opts->metadata['userfullname'];
        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }
        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = \core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }
        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }
        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }
        $returnstr .= html_writer::span(
            html_writer::span($avatarcontents, $avatarclasses) .
                html_writer::span($usertextcontents, 'usertext mr-1'),
            'userbutton'
        );
        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;
        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_action_label(get_string('usermenu'));
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $key => $value) {
                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;
                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;
                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, '', null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }
                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }
                $idx++;
                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount - 1) {
                    $am->add($divider);
                }
            }
        }
        return html_writer::div(
            $this->render($am),
            $usermenuclasses
        );
    }
    /**
     * Context Header
     *
     * @param stdClass|stdObject $headerinfo User object
     * @param string $headinglevel with links
     */
    public function context_header($headerinfo = null, $headinglevel = 1) {
        global $DB, $USER, $CFG, $SITE;
        require_once($CFG->dirroot . '/user/lib.php');
        $context = $this->page->context;
        $heading = null;
        $imagedata = null;
        $subheader = null;
        $userbuttons = null;
        if ($this->should_display_main_logo($headinglevel)) {
            $sitename = format_string($SITE->fullname, true, array('context' => context_course::instance(SITEID)));
            return html_writer::div(html_writer::empty_tag('img', [
                'src' => $this->get_logo_url(null, 150), 'alt' => $sitename, 'class' => 'img-fluid'
            ]), 'logo');
        }
        // Make sure to use the heading if it has been set.
        if (isset($headerinfo['heading'])) {
            $heading = $headerinfo['heading'];
        }
        // The user context currently has images and buttons. Other contexts may follow.
        if (isset($headerinfo['user']) || $context->contextlevel == CONTEXT_USER) {
            if (isset($headerinfo['user'])) {
                $user = $headerinfo['user'];
            } else {
                // Look up the user information if it is not supplied.
                $user = $DB->get_record('user', array('id' => $context->instanceid));
            }
            // If the user context is set, then use that for capability checks.
            if (isset($headerinfo['usercontext'])) {
                $context = $headerinfo['usercontext'];
            }
            // Only provide user information if the user is the current user, or a user which the current user can view.
            // When checking user_can_view_profile(), either:
            // If the page context is course, check the course context (from the page object) or;
            // If page context is NOT course, then check across all courses.
            $course = ($this->page->context->contextlevel == CONTEXT_COURSE) ? $this->page->course : null;
            if (user_can_view_profile($user, $course)) {
                // Use the user's full name if the heading isn't set.
                if (!isset($heading)) {
                    $heading = fullname($user);
                }
                $imagedata = $this->user_picture($user, array('size' => 100));
                $enrolledcourses = count(enrol_get_users_courses($user->id));
                $commentsarr = $DB->get_record_sql("SELECT COUNT(*) postedcomments FROM {comments} WHERE userid = ?", [$user->id]);
                $comments = $commentsarr->postedcomments;
                $userbuttons = array(
                    'classes' => array(
                        'buttontype' => 'message',
                        'title' => '<span class="totalcount_ph">' .
                            $enrolledcourses . '</span> ' . get_string('classes', 'theme_thinkblue'),
                        'url' => '#',
                        'image' => 'classes',
                        'linkattributes' => array(),
                        'page' => $this->page,
                    ),
                    'entries' => array(
                        'buttontype' => 'message',
                        'title' => '<span class="totalcount_ph">' .
                            $comments . '</span> ' . get_string('entries', 'theme_thinkblue'),
                        'url' => '#',
                        'image' => 'entries',
                        'linkattributes' => array(),
                        'page' => $this->page,
                    ),
                    'comments' => array(
                        'buttontype' => 'message',
                        'title' => '<span class="totalcount_ph">' .
                            $comments . '</span> ' . get_string('comments', 'theme_thinkblue'),
                        'url' => '#',
                        'image' => 'comment',
                        'linkattributes' => array(),
                        'page' => $this->page,
                    ),
                );
            } else {
                $heading = null;
            }
        }
        $contextheader = new context_header($heading, $headinglevel, $imagedata, $userbuttons);
        return $this->render_context_header($contextheader);
    }

    /**
     * Context Header
     *
     * @param string $maxwidth maxwidth
     * @param string $maxheight maxheight
     * @return string HTML fragment
     */
    public function get_logo_url($maxwidth = null, $maxheight = 100) {
        if (isset($this->getleeloosettings()->general_settings->logoimage)) {
            return @$this->getleeloosettings()->general_settings->logoimage;
        } else {
            return 'https://leeloolxp.com/modules/mod_acadmic/images/Leeloo-lxp1.png';
        }
    }

    /**
     * This is an optional menu that can be added to a layout by a theme. It contains the
     * menu for the most specific thing from the settings block. E.g. Module administration.
     *
     * @return string
     */
    public function region_main_settings_menu() {
        $context = $this->page->context;
        $menu = new action_menu();

        if ($context->contextlevel == CONTEXT_MODULE) {

            $this->page->navigation->initialise();
            $node = $this->page->navigation->find_active_node();
            $buildmenu = false;
            // If the settings menu has been forced then show the menu.
            if ($this->page->is_settings_menu_forced()) {
                $buildmenu = true;
            } else if (!empty($node) && ($node->type == navigation_node::TYPE_ACTIVITY ||
                $node->type == navigation_node::TYPE_RESOURCE)) {

                $items = $this->page->navbar->get_items();
                $navbarnode = end($items);
                // We only want to show the menu on the first page of the activity. This means
                // the breadcrumb has no additional nodes.
                if ($navbarnode && ($navbarnode->key === $node->key && $navbarnode->type == $node->type)) {
                    $buildmenu = true;
                }
            }
            if ($buildmenu) {
                // Get the course admin node from the settings navigation.
                $node = $this->page->settingsnav->find('modulesettings', navigation_node::TYPE_SETTING);
                if ($node) {
                    // Build an action menu based on the visible nodes from this navigation tree.
                    $this->build_action_menu_from_navigation($menu, $node);
                }
            }
        } else if ($context->contextlevel == CONTEXT_COURSECAT) {
            // For course category context, show category settings menu, if we're on the course category page.
            if ($this->page->pagetype === 'course-index-category') {
                $node = $this->page->settingsnav->find('categorysettings', navigation_node::TYPE_CONTAINER);
                if ($node) {
                    // Build an action menu based on the visible nodes from this navigation tree.
                    $this->build_action_menu_from_navigation($menu, $node);
                }
            }
        } else {
            $items = $this->page->navbar->get_items();
            $navbarnode = end($items);

            if ($navbarnode && ($navbarnode->key === 'participants')) {
                $node = $this->page->settingsnav->find('users', navigation_node::TYPE_CONTAINER);
                if ($node) {
                    // Build an action menu based on the visible nodes from this navigation tree.
                    $this->build_action_menu_from_navigation($menu, $node);
                }
            }
        }

        if ($this->page->bodyid == 'page-mod-quiz-view') {
            $quizid = optional_param('id', 0, PARAM_RAW);
            $text = get_string('copyquizattempturl', 'theme_thinkblue');
            $url = new moodle_url('/mod/quiz/view.php', array('id' => $quizid, 'startattempt' => 1));
            $link = new action_link($url, $text, null, null, new pix_icon('t/copy', $text));
            $link->add_class('copyquizattempturl');
            $menu->add_secondary_action($link);
        }

        return $this->render($menu);
    }

    /**
     * Returns standard navigation between activities in a course.
     *
     * @return string the navigation HTML.
     */
    public function activity_navigation() {
        global $DB, $CFG;
        // First we should check if we want to add navigation.
        $context = $this->page->context;
        if (($this->page->pagelayout !== 'incourse' && $this->page->pagelayout !== 'frametop')
            || $context->contextlevel != CONTEXT_MODULE
        ) {
            return '';
        }

        // If the activity is in stealth mode, show no links.
        if ($this->page->cm->is_stealth()) {
            return '';
        }

        $currmoduleid = $this->page->cm->get_course_module_record()->id;

        // Get a list of all the activities in the course.
        $course = $this->page->cm->get_course();
        $modinfo = get_fast_modinfo($course);
        $completioninfo = new completion_info($course);
        $format = course_get_format($course);

        $sections = $format->get_sections();

        $navigationsections = [];

        $completionok = [
            COMPLETION_COMPLETE,
            COMPLETION_COMPLETE_PASS,
        ];

        foreach ($sections as $section) {
            $i = $section->section;
            if (!$section->uservisible) {
                continue;
            }
            $navigationsections[$i]['name'] = $section->name;

            if (!empty($modinfo->sections[$i])) {
                foreach ($modinfo->sections[$i] as $modnumber) {
                    $module = $modinfo->cms[$modnumber];
                    if (!$module->uservisible || !$module->visible || !$module->visibleoncoursepage) {
                        continue;
                    }

                    $completeclass = '';
                    $hascompletion = $completioninfo->is_enabled($module);
                    if ($hascompletion) {
                        $completeclass = 'incomplete';
                    }

                    $completiondata = $completioninfo->get_data(
                        $module,
                        true
                    );
                    if (in_array(
                        $completiondata->completionstate,
                        $completionok
                    )) {
                        $completeclass = 'completed';
                    }

                    if ($module->modname == 'quiz') {
                        $quizid = $module->get_course_module_record()->instance;
                        $quizdata = $DB->get_record('quiz', array('id' => $quizid), '*', MUST_EXIST);

                        if (isset($quizdata->quiztype)) {
                            $iconsrc = $CFG->wwwroot . '/local/leeloolxptrivias/pix/' . $quizdata->quiztype . '.png';
                        } else {
                            $iconsrc = $module->get_icon_url() . '?default';
                        }
                    } else {
                        $iconsrc = $module->get_icon_url();
                    }

                    if ($currmoduleid == $module->id) {
                        $currentclass = 'currentactivear';
                    } else {
                        $currentclass = '';
                    }

                    $navigationsections[$i]['modules'][$module->id]['name'] = $module->name . ' ' . $module->modname;
                    $navigationsections[$i]['modules'][$module->id]['icon'] = $iconsrc;
                    $navigationsections[$i]['modules'][$module->id]['link'] = new moodle_url($module->url, array('forceview' => 1));
                    $navigationsections[$i]['modules'][$module->id]['completeclass'] = $completeclass;
                    $navigationsections[$i]['modules'][$module->id]['currentclass'] = $currentclass;
                }
            }

            if (isset($navigationsections[$i]['modules']) && isset($navigationsections[$i]['modules']) != '') {
                $countmodules = count($navigationsections[$i]['modules']);
            } else {
                $countmodules = 0;
            }

            if ($countmodules == 0) {
                unset($navigationsections[$i]);
            }
        }

        $activityhtml = '';
        foreach ($navigationsections as $navigationsection) {

            $modulehtml = '';
            foreach ($navigationsection['modules'] as $module) {
                $completonclass = $module['completeclass'];
                $currentclass = $module['currentclass'];
                $modulehtml .= '<li class="' .
                    $completonclass . ' ' .
                    $currentclass . '"><a href="' .
                    $module['link'] . '" title="' .
                    $module['name'] . '"><img src="' .
                    $module['icon'] . '"></a></li>';
            }

            $activityhtml .= '<div class="d1"><h2>' . $navigationsection["name"] . '</h2><ul>' . $modulehtml . '</ul></div>';
        }

        $courseurlused = new moodle_url('/course/view.php', array('id' => $course->id));
        return '<div class="bottom_activity_navigation">
            <div class="page-bottom-bar">
                <div class="page-bottom-inn">
                    <div class="row">
                        <div class="col-sm-4">
                        <div class="bottom_activity-left">
                            <div class="home-ico">
                                <a href="' . $courseurlused . '">
                                    <img src="' . $CFG->wwwroot . '/theme/thinkblue/img/Duels-Gourav2_03.png">
                                </a>
                            </div>

                            <div class="home-ico-text">
                                <p>' . $this->page->cm->get_section_info()->name . '</p>
                                <p><a href="' . $courseurlused . '">' . $course->shortname . '</a></p>
                            </div>
                        </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="bottom_activity-right-main">
                                <div class="bottom_activity-right">
                                    ' . $activityhtml . '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bottom-mn-btn">
                    <button class="btn"><i class="fa fa-ellipsis-v"></i></button>
                </div>
            </div>
        </div>';
    }
}
