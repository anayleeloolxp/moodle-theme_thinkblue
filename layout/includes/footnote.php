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
 * Theme Think Blue - Layout file for footnote.
 *
 * @package    theme_thinkblue
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author     Leeloo LXP <info@leeloolxp.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/thinkblue/locallib.php');
$leeloosettings = theme_thinkblue_general_leeloosettings();

$footnotesetting = $leeloosettings->additional_layout_settings->footnote;

// Only proceed if text area does not only contains empty tags.
if (!html_is_blank($footnotesetting)) {
    // Use format_string function to enable multilanguage filtering.
    $footnotesetting = format_text($footnotesetting);

    $templatecontext['footnotesetting'] = $footnotesetting;

    echo $OUTPUT->render_from_template('theme_thinkblue/footnote', $templatecontext);
}
