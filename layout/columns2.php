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

global $PAGE;

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

if (isset($leeloosettings->design_settings->darknavbar) && $leeloosettings->design_settings->darknavbar == 1) {
    $darknavbar = true;
} else {

    $darknavbar = false;
}

// MODIFICATION END.

// MODIFICATION START: Setting 'navdrawerfullwidth'.

if (isset($leeloosettings->additional_layout_settings->navdrawerfullwidth)) {
    $navdrawerfullwidth = @$leeloosettings->additional_layout_settings->navdrawerfullwidth;
} else {
    $navdrawerfullwidth = 0;
}

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

// Render columns2.mustache from thinkblue.

echo $OUTPUT->render_from_template('theme_thinkblue/columns2', $templatecontext);

// MODIFICATION END.

/* ORIGINAL START.

echo $OUTPUT->render_from_template('theme_boost/columns2', $templatecontext);

ORIGINAL END. */
