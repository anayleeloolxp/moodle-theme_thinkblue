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
 * Theme Think Blue - Version file
 *
 * @package    theme_thinkblue
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author     Leeloo LXP <info@leeloolxp.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'theme_thinkblue';
$plugin->version = 2020032400;
$plugin->release = '1.0.0';
$plugin->requires = 2019111800;
$plugin->maturity = MATURITY_STABLE;
$plugin->cron = 60 * 5; // Cron 5mins.
$plugin->dependencies = array(
    'theme_boost' => 2019111800,
    'tool_leeloo_courses_sync' => 2019062700,
    'tool_leeloolxp_sync' => 2019062701,
    'block_tb_a_courses' => 2019010700,
    'block_tb_blog' => 2019111800,
    'block_tb_c_courses' => 2019010700,
    'block_tb_course_nav' => 2019052015,
    'block_tb_f_courses' => 2019010700,
    'block_tb_faq' => 2019010700,
    'block_tb_headings' => 2019010700,
    'block_tb_in_courses' => 2019010700,
    'block_tb_latestentry' => 2019111800,
    'block_tb_m_slots' => 2019010700,
    'block_tb_my_courses' => 2019010700,
    'block_tb_slider' => 2020051300,
    'block_tb_teachers' => 2019010700,
    'block_tb_testimonials' => 2019010700,
    'block_tb_top_cats' => 2019010700,
    'block_tb_up_courses' => 2019010700,
    'mod_regularvideo' => 2015051101,
    'mod_wespher' => 2020022102,
    'local_staticpage' => 2020021400,
    'local_boostnavigation' => 2020080400,
    'auth_leeloolxp_tracking_sso' => 2020092601,
);
