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
 * Theme Think Blue - Layout file.
 *
 * @package    theme_thinkblue
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author     Leeloo LXP <info@leeloolxp.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// MODIFICATION START.

global $PAGE, $DB;

// MODIFICATION END.

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);

require_once($CFG->libdir . '/behat/lib.php');

// MODIFICATION Start: Require own locallib.php.

require_once($CFG->dirroot . '/theme/thinkblue/locallib.php');

$leeloosettings = theme_thinkblue_general_leeloosettings();

// MODIFICATION END.

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {

    $navdraweropen = false;
}

$extraclasses = [];

if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}

if (is_siteadmin()) {
    $extraclasses[] = 'adminloggedin';
}

global $USER;

if (!isset($USER->id) && isset($USER->id) == '') {
    $extraclasses[] = 'notloggedinuser';
} else {

    if ($USER->id == 1 || $USER->id == 0) {
        $extraclasses[] = 'notloggedinuser';
    } else {

        $extraclasses[] = 'loggedinuser';
    }
}

if (isset($leeloosettings->general_settings->layouttype) && isset($leeloosettings->general_settings->layouttype) != '') {
    if ($leeloosettings->general_settings->layouttype == 'boxed') {
        $extraclasses[] = 'layout_boxed';
    } else {
        $extraclasses[] = 'layout_fullwidth';
    }
} else {
    $extraclasses[] = 'layout_fullwidth';
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);

$blockshtml = $OUTPUT->blocks('side-pre');

if ($PAGE->pagetype == 'site-index') {
    $contentblockshtml = $OUTPUT->blocks('content');

    $abovecontentblockshtml = $OUTPUT->blocks('abovecontent');
} else {

    $contentblockshtml = '';

    $abovecontentblockshtml = '';
}

$hasblocks = strpos($blockshtml, 'data-block=') !== false;

$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();

// MODIFICATION START: Setting 'catchshortcuts'.

// Initialize array.

$catchshortcuts = array();

// If setting is enabled then add the parameter to the array.

if ($leeloosettings->advanced_settings->catchendkey == true) {
    $catchshortcuts[] = 'end';
}

// If setting is enabled then add the parameter to the array.

if ($leeloosettings->advanced_settings->catchcmdarrowdown == true) {
    $catchshortcuts[] = 'cmdarrowdown';
}

// If setting is enabled then add the parameter to the array.

if ($leeloosettings->advanced_settings->catchctrlarrowdown == true) {
    $catchshortcuts[] = 'ctrlarrowdown';
}

// MODIFICATION END.

// MODIFICATION START: Setting 'darknavbar'.

if ($leeloosettings->design_settings->darknavbar == 1) {
    $darknavbar = true;
} else {

    $darknavbar = false;
}

// MODIFICATION END.

// MODIFICATION START: Setting 'navdrawerfullwidth'.

$navdrawerfullwidth = @$leeloosettings->additional_layout_settings->navdrawerfullwidth;

// MODIFICATION END.

$templatecontext = [

    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),

    'output' => $OUTPUT,

    'contentblocks' => $contentblockshtml,

    'abovecontentblocks' => $abovecontentblockshtml,

    'sidepreblocks' => $blockshtml,

    'hasblocks' => $hasblocks,

    'bodyattributes' => $bodyattributes,

    'navdraweropen' => $navdraweropen,

    'regionmainsettingsmenu' => $regionmainsettingsmenu,

    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),

    // MODIFICATION START: Add Think Blue realated values to the template context.

    'catchshortcuts' => json_encode($catchshortcuts),

    'navdrawerfullwidth' => $navdrawerfullwidth,

    'darknavbar' => $darknavbar,

    // MODIFICATION END.

];

$nav = $PAGE->flatnav;

// MODIDFICATION START.

// Use the returned value from theme_thinkblue_get_modified_flatnav_defaulthomepageontop as the template context.

$templatecontext['flatnavigation'] = theme_thinkblue_process_flatnav($nav);

// If setting showsettingsincourse is enabled.

if ($leeloosettings->course_layout_settings->showsettingsincourse == 1) {
    // Context value for requiring incoursesettings.js.

    $templatecontext['incoursesettings'] = true;

    // Add the returned value from theme_thinkblue_get_incourse_settings to the template context.

    $templatecontext['node'] = theme_thinkblue_get_incourse_settings();

    // Add the returned value from theme_thinkblue_get_incourse_activity_settings to the template context.

    $templatecontext['activitynode'] = theme_thinkblue_get_incourse_activity_settings();
}

// MODIFICATION END.

/* ORIGINAL START.

$templatecontext['flatnavigation'] = $nav;

ORIGINAL END. */

$templatecontext['firstcollectionlabel'] = $nav->get_collectionlabel();

// MODIFICATION START: Handle additional layout elements.

// The output buffer is needed to render the additional layout elements now without outputting them to the page directly.

ob_start();

// Require additional layout files.

// Add footer blocks and standard footer.

require_once(__DIR__ . '/includes/footer.php');

// Get imageareaitems config.

if (!empty($leeloosettings->imageareaitems)) {
    // Add imagearea layout file.

    require_once(__DIR__ . '/includes/imagearea.php');
}

// Get footnote config.

$footnote = @$leeloosettings->additional_layout_settings->footnote;

if (!empty($footnote)) {
    // Add footnote layout file.

    require_once(__DIR__ . '/includes/footnote.php');
}

// Get output buffer.

$pagebottomelements = ob_get_clean();

// If there isn't anything in the buffer, set the additional layouts string to an empty string to avoid problems later on.

if ($pagebottomelements == false) {
    $pagebottomelements = '';
}

// Add the additional layouts to the template context.

$templatecontext['pagebottomelements'] = $pagebottomelements;

$courseid = $PAGE->course->id;

$modinfo = get_fast_modinfo($courseid);

$leeloocourse = theme_thinkblue_coursedata($courseid);

$templatecontext['coursesummary'] = $PAGE->course->summary;

$templatecontext['useremail'] = $USER->email;

$templatecontext['username'] = $USER->username;

$templatecontext['userfullname'] = fullname($USER);

$leelooproduct = $DB->get_record_sql('SELECT * FROM {tool_leeloo_courses_sync} WHERE enabled = 1 AND courseid = ' . $courseid);

if ($leelooproduct) {
    $isleelooproduct = true;

    $templatecontext['isleelooproduct'] = $isleelooproduct;

    $templatecontext['leelooproductprice'] = $leelooproduct->productprice;

    $productid = $leelooproduct->productid;

    $productalias = $leelooproduct->product_alias;

    $urlalias = $productid . '-' . $productalias;

    global $SESSION;

    $jsessionid = $SESSION->jsession_id;

    $context = get_context_instance(CONTEXT_COURSE, $courseid, MUST_EXIST);

    $enrolled = is_enrolled($context, $USER->id, '', true);

    if ($enrolled) {
        $userenrolled = true;

        $templatecontext['userenrolled'] = $userenrolled;
    } else if (!$jsessionid) {

        $showlogin = false;

        $templatecontext['showlogin'] = $showlogin;

        $templatecontext['loginurl'] = $CFG->wwwroot . '/login/index.php';
    } else {

        $showbuy = true;

        $templatecontext['showbuy'] = $showbuy;

        $templatecontext['buyurl'] = "https://leeloolxp.com/products-listing/product/$urlalias?session_id=$jsessionid";
    }
}

$templatecontext['course_title'] = $PAGE->course->fullname;

if (is_object($leeloocourse)) {
    if (isset($leeloocourse->course_data->estimated_time)) {
        $templatecontext['estimated_time'] = $leeloocourse->course_data->estimated_time;
    } else {
        $templatecontext['estimated_time'] = '';
    }

    if (isset($leeloocourse->course_data->stats_position) && $leeloocourse->course_data->stats_position == 'main') {
        $statsmain = true;

        $templatecontext['stats_main'] = $statsmain;
    } else {

        $statssidebar = true;

        $templatecontext['stats_sidebar'] = $statssidebar;
    }

    if (isset($leeloocourse->course_data->instructor_section_position) && $leeloocourse->course_data->instructor_section_position == 'main') {
        $teachermain = true;

        $templatecontext['teacher_main'] = $teachermain;
    } else {

        $teachersidebar = true;

        $templatecontext['teacher_sidebar'] = $teachersidebar;
    }

    if (isset($leeloocourse->course_data->about_course_section_position) && $leeloocourse->course_data->about_course_section_position == 'main') {
        $aboutmain = true;

        $templatecontext['about_main'] = $aboutmain;
    } else {

        $aboutsidebar = true;

        $templatecontext['about_sidebar'] = $aboutsidebar;
    }

    if (isset($leeloocourse->course_data->course_header_image) && $leeloocourse->course_data->course_header_image != '') {
        $showheaderimage = true;

        $templatecontext['showheaderimage'] = $showheaderimage;

        $courseheadimage = '<style>.page-top-main-banner{background-size: 100% 100%;background:url("' . $leeloocourse->course_data->course_header_image . '")</style>';

        $templatecontext['course_header_image'] = $courseheadimage;
    }

    if (isset($leeloocourse->course_data->course_header_description) && $leeloocourse->course_data->course_header_description != '') {
        $showheaderdes = true;

        $templatecontext['showheaderdes'] = $showheaderdes;

        $templatecontext['course_header_description'] = $leeloocourse->course_data->course_header_description;
    }

    if (isset($leeloocourse->course_data->course_video_url) && $leeloocourse->course_data->course_video_url != '') {
        $showvideo = true;

        $templatecontext['showvideo'] = $showvideo;

        $templatecontext['course_video'] = $leeloocourse->course_data->course_video_url;
    } else if (isset($leeloocourse->course_data->course_image) && $leeloocourse->course_data->course_image != '') {

        $showimage = true;

        $templatecontext['showimage'] = $showimage;

        $templatecontext['course_image'] = $leeloocourse->course_data->course_image;
    }

    if (isset($leeloocourse->course_data->course_objective) && $leeloocourse->course_data->course_objective != '') {
        $showobjective = true;

        $templatecontext['showobjective'] = $showobjective;

        $templatecontext['course_objective'] = $leeloocourse->course_data->course_objective;
    }

    if (isset($leeloocourse->course_data->course_instructor) && $leeloocourse->course_data->course_instructor != '') {
        $showinstuctor = true;

        $templatecontext['showinstuctor'] = $showinstuctor;

        $templatecontext['course_instructur']['fullname'] = $leeloocourse->course_data->fullname;

        $templatecontext['course_instructur']['email'] = $leeloocourse->course_data->email;

        $templatecontext['course_instructur']['description'] = $leeloocourse->course_data->description;

        $templatecontext['course_instructur']['job_title'] = $leeloocourse->course_data->job_title;

        $templatecontext['course_instructur']['user_image'] = $leeloocourse->course_data->user_image;
    }
}

$templatecontext['lang']['hours'] = get_string('hours', 'theme_thinkblue');

$templatecontext['lang']['videoslessons'] = get_string('videoslessons', 'theme_thinkblue');

$templatecontext['lang']['modules'] = get_string('modules', 'theme_thinkblue');

$templatecontext['lang']['learningactivities'] = get_string('learningactivities', 'theme_thinkblue');

$templatecontext['lang']['aboutcourse'] = get_string('aboutcourse', 'theme_thinkblue');

$templatecontext['lang']['courseobjective'] = get_string('courseobjective', 'theme_thinkblue');

$templatecontext['lang']['send'] = get_string('send', 'theme_thinkblue');

$templatecontext['lang']['courseoverview'] = get_string('courseoverview', 'theme_thinkblue');

$templatecontext['lang']['contactinst'] = get_string('contactinst', 'theme_thinkblue');

$templatecontext['lang']['subject'] = get_string('subject', 'theme_thinkblue');

$templatecontext['lang']['message'] = get_string('message', 'theme_thinkblue');

$templatecontext['lang']['coursesummary'] = get_string('coursesummary', 'theme_thinkblue');

$templatecontext['lang']['enrollnow'] = get_string('enrollnow', 'theme_thinkblue');

$templatecontext['lang']['userenrolled'] = get_string('userenrolled', 'theme_thinkblue');

$activities = 0;

foreach ($modinfo->sections as $section) {
    $activities += count($section);
}

$videos = 0;

foreach ($modinfo->cms as $cms) {
    if ($cms->modname == 'regularvideo') {
        $videos += 1;
    }
}

$templatecontext['modules'] = count($modinfo->sections);

$templatecontext['activities'] = $activities;

$templatecontext['videos'] = $videos;

$templatecontext['baseurl'] = $CFG->wwwroot;

// Render course.mustache from thinkblue.

echo $OUTPUT->render_from_template('theme_thinkblue/course', $templatecontext);

// MODIFICATION END.

/* ORIGINAL START.

echo $OUTPUT->render_from_template('theme_boost/columns2', $templatecontext);

ORIGINAL END. */
