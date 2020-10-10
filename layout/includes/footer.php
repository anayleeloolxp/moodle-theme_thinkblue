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

$footerblocksetting = get_config('theme_thinkblue', 'footerblocks');

// Setting is set to no footer blocks layout.
if ($footerblocksetting === '0columns') {
    echo $OUTPUT->render_from_template('theme_thinkblue/footer', $templatecontext);
}

// Setting is set to one columns layout.
if ($footerblocksetting === '1columns') {
    $footerblock1columns = true;

    $footerleftblockshtml = $OUTPUT->blocks('footer-left');

    $templatecontext['footerleftblocks'] = $footerleftblockshtml;
    $templatecontext['footerblock1columns'] = $footerblock1columns;

    echo $OUTPUT->render_from_template('theme_thinkblue/footer', $templatecontext);
}

// Setting is set to two columns layout.
if ($footerblocksetting === '2columns') {
    $footerblock2columns = true;

    $footerleftblockshtml = $OUTPUT->blocks('footer-left');
    $footerrightblockshtml = $OUTPUT->blocks('footer-right');

    $templatecontext['footerleftblocks'] = $footerleftblockshtml;
    $templatecontext['footerrightblocks'] = $footerrightblockshtml;
    $templatecontext['footerblock2columns'] = $footerblock2columns;

    echo $OUTPUT->render_from_template('theme_thinkblue/footer', $templatecontext);
}

// Setting is set to three columns layout.
if ($footerblocksetting === '3columns') {
    $footerblock3columns = true;

    $footerleftblockshtml = $OUTPUT->blocks('footer-left');
    $footermiddleblockshtml = $OUTPUT->blocks('footer-middle');
    $footerrightblockshtml = $OUTPUT->blocks('footer-right');

    $templatecontext['footerleftblocks'] = $footerleftblockshtml;
    $templatecontext['footermiddleblocks'] = $footermiddleblockshtml;
    $templatecontext['footerrightblocks'] = $footerrightblockshtml;
    $templatecontext['footerblock3columns'] = $footerblock3columns;

    echo $OUTPUT->render_from_template('theme_thinkblue/footer', $templatecontext);
}
