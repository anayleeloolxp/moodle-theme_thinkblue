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
 * Theme Think Blue - Layout file for footer.
 *
 * @package    theme_thinkblue
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author     Leeloo LXP <info@leeloolxp.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/thinkblue/locallib.php');

$leeloosettings = theme_thinkblue_general_leeloosettings();
$footerblocksetting = @$leeloosettings->footer_layout_settings->footerblocks;
$footerblock = @$leeloosettings->footer_block;

$statsenabled = @$leeloosettings->footer_stats->enable_footer_stats;
$copyenabled = @$leeloosettings->social_copyright_settings->enable_footer_social_copyright;

$footerstatshtml = '';
$footercopyhtml = '';

if ($statsenabled) {
    $statstitle = @$leeloosettings->footer_stats->footer_stats_title;
    $statsdescription = @$leeloosettings->footer_stats->description;
    $statsvals = @$leeloosettings->footer_stats->statsval;

    $buttontest = @$leeloosettings->footer_stats->button_test;
    $buttonlink = @$leeloosettings->footer_stats->button_link;

    $footerstats = true;
    $footerstatshtml .= '<h2>' . $statstitle . '</h2>';
    $footerstatshtml .= '<p>' . $statsdescription . '</p>';

    $footerstatshtml .= '<div class="statsall"><div class="container-fluid"><div class="row-fluid">';

    $statscount = count($statsvals);
    $statclass = 12 / $statscount;

    foreach ($statsvals as $stat) {
        $footerstatshtml .= '<div class="statsin col-12 col-lg-' . $statclass . '">';
        $footerstatshtml .= '<h2>' . $stat->stats . '</h2>';
        $footerstatshtml .= '<p>' . $stat->stats_for . '</p>';
        $footerstatshtml .= '</div>';
    }

    $footerstatshtml .= '</div></div></div>';

    $footerstatshtml .= '<div class="stat-button"><a href="' . $buttonlink . '">' . $buttontest . '</a></div>';

    $templatecontext['footerstats'] = $footerstats;
    $templatecontext['footerstatshtml'] = $footerstatshtml;
}

if ($copyenabled) {
    $socialtitle = @$leeloosettings->social_copyright_settings->title;
    $socialdescription = @$leeloosettings->social_copyright_settings->description;
    $socialdetails = @$leeloosettings->social_copyright_settings->details;
    $copyrighttext = @$leeloosettings->social_copyright_settings->copyright_text;

    $footercopy = true;
    $footercopyhtml .= '<h2>' . $socialtitle . '</h2>';
    $footercopyhtml .= '<p>' . $socialdescription . '</p>';

    $footercopyhtml .= '<div class="socialdetails">' . $socialdetails . '</div>';
    $footercopyhtml .= '<div class="copyright-foo">' . $copyrighttext . '</div>';

    $templatecontext['footercopy'] = $footercopy;
    $templatecontext['footercopyhtml'] = $footercopyhtml;
}

// Setting is set to no footer blocks layout.
if ($footerblocksetting === '0columns') {
    echo $OUTPUT->render_from_template('theme_thinkblue/footer', $templatecontext);
}

// Setting is set to one columns layout.
if ($footerblocksetting === '1columns') {
    $footerblock1columns = true;

    $templatecontext['footerleftblocks'] = '<h2>' . $footerblock[0]->footer_title . '</h2>' . $footerblock[0]->footer_detail;
    $templatecontext['footerblock1columns'] = $footerblock1columns;

    echo $OUTPUT->render_from_template('theme_thinkblue/footer', $templatecontext);
}

// Setting is set to two columns layout.
if ($footerblocksetting === '2columns') {
    $footerblock2columns = true;

    $templatecontext['footerleftblocks'] = '<h2>' . $footerblock[0]->footer_title . '</h2>' . $footerblock[0]->footer_detail;
    $templatecontext['footerrightblocks'] = '<h2>' . $footerblock[1]->footer_title . '</h2>' . $footerblock[1]->footer_detail;
    $templatecontext['footerblock2columns'] = $footerblock2columns;

    echo $OUTPUT->render_from_template('theme_thinkblue/footer', $templatecontext);
}

// Setting is set to three columns layout.
if ($footerblocksetting === '3columns') {
    $footerblock3columns = true;

    $templatecontext['footerleftblocks'] = '<h2>' . $footerblock[0]->footer_title . '</h2>' . $footerblock[0]->footer_detail;
    $templatecontext['footermiddleblocks'] = '<h2>' . $footerblock[1]->footer_title . '</h2>' . $footerblock[1]->footer_detail;
    $templatecontext['footerrightblocks'] = '<h2>' . $footerblock[2]->footer_title . '</h2>' . $footerblock[2]->footer_detail;
    $templatecontext['footerblock3columns'] = $footerblock3columns;

    echo $OUTPUT->render_from_template('theme_thinkblue/footer', $templatecontext);
}

// Setting is set to three columns layout.
if ($footerblocksetting === '4columns') {
    $footerblock4columns = true;

    $templatecontext['footerleftblocks'] = '<h2>' . $footerblock[0]->footer_title . '</h2>' . $footerblock[0]->footer_detail;
    $templatecontext['footermiddlelblocks'] = '<h2>' . $footerblock[1]->footer_title . '</h2>' . $footerblock[1]->footer_detail;
    $templatecontext['footermiddlerblocks'] = '<h2>' . $footerblock[2]->footer_title . '</h2>' . $footerblock[2]->footer_detail;
    $templatecontext['footerrightblocks'] = '<h2>' . $footerblock[3]->footer_title . '</h2>' . $footerblock[3]->footer_detail;
    $templatecontext['footerblock4columns'] = $footerblock4columns;

    echo $OUTPUT->render_from_template('theme_thinkblue/footer', $templatecontext);
}
