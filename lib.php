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
 * Theme Think Blue - Library
 *
 * @package    theme_thinkblue
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author     Leeloo LXP <info@leeloolxp.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/theme/thinkblue/locallib.php');

$leeloosettings = theme_thinkblue_general_leeloosettings();

global $leeloosettings;

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_thinkblue_get_main_scss_content($theme) {

    global $CFG;

    $scss = '';

    $filename = !empty($leeloosettings->general_settings->preset) ? $leeloosettings->general_settings->preset : null;

    $fs = get_file_storage();

    $context = context_system::instance();

    if ($filename == 'default.scss') {
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.

        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.

        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_thinkblue', 'preset', 0, '/', $filename))) {
        // This preset file was fetched from the file area for theme_thinkblue and not theme_boost (see the line above).

        $scss .= $presetfile->get_content();
    } else {

        // Safety fallback - maybe new installs etc.

        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.

    $pre = file_get_contents($CFG->dirroot . '/theme/thinkblue/scss/pre.scss');

    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.

    $post = file_get_contents($CFG->dirroot . '/theme/thinkblue/scss/post.scss');

    // Combine them together.

    return $pre . "\n" . $scss . "\n" . $post;
}

/**
 * Override to add CSS values from settings to pre scss file.
 *
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return array
 */
function theme_thinkblue_get_pre_scss($theme) {

    global $CFG;

    // MODIFICATION START.

    require_once($CFG->dirroot . '/theme/thinkblue/locallib.php');

    // MODIFICATION END.

    $leeloosettings = theme_thinkblue_general_leeloosettings();

    $scss = '';

    $configurable = [];

    $configurablecolors = [
        'brandcolor' => '#626672',
        'menu_color' => '#575757',
        'menu_section_background' => '#fff',
        'menu_active_color' => '#00aff0',
        'menu_background_active' => '#f5f6fa',
        'hamburger_menu_color' => '#00aff0',
        'buttons_background_color' => '#00aff0',
        'buttons_text_color' => '#fff',
        'headings_color' => '#00aff0',
        'text_color' => '#575757',
        'footer_one_background_color' => '#00aff0',
        'footer_one_text_color' => '#fff',
        'footer_two_background_color' => '#00aff0',
        'footer_two_text_color' => '#fff',
        'footer_three_background_color' => '#00aff0',
        'footer_three_text_color' => '#fff',
        'basecolor' => '#030303',
        'cattitlecolor' => '#5f6474',
        'copyrightbackground' => '#eff0f5',
        'pagefooterbackground' => '#f5f6fa',
        'owlnavbtnbackground' => '#fff',
        'owlnavbtncolor' => '#f0f0f8',
        'sectionblockbackground' => '#f5f6fa',
        'menubackgroundhover' => '#f8f9fa',
        'menucolorhover' => '#727272',
        'brandbackground' => '#fff',
        'dropdownmenubackground' => 'f5f6fa',
        'dropdownitemcolor' => '#212529',
        'dropdownitemcolorhover' => '#16181b',
        'dropdownitembackgroundhover' => '#f8f9fa',
        'dropdownitemcoloractive' => '#eff0f5',
        'dropdownitembackgroundactive' => '#626672',
        'directionbtnbackground' => '#626672',
        'directionbtncolor' => '#fff',
        'courseboxbackground' => '#787878',
        'courseboxcolor' => '#fff',
        'footer2head' => '#000',
        'footer3head' => '#000',
        'footeratag' => '#030303',
        'footercolor' => '#787878',
        'footer2btnbackground' => '#eff0f5',
        'footer2btncolor' => '#030303',
        'light_overlay_background_color' => '#fff',
        'light_overlay_text_color' => '#000',
        'dark_overlay_background_color' => '#000',
        'dark_overlay_text_color' => '#fff',

    ];

    foreach ($configurablecolors as $colorsetting => $colorsettingval) {
        if (isset($leeloosettings->general_settings->$colorsetting) && isset($leeloosettings->general_settings->$colorsetting) != '') {
            if ($leeloosettings->general_settings->$colorsetting != '') {
                $scss .= '$' . $colorsetting . ': ' . $leeloosettings->general_settings->$colorsetting . ";\n";
            } else {
                $scss .= '$' . $colorsetting . ': ' . $colorsettingval . ";\n";
            }
        } else {
            $scss .= '$' . $colorsetting . ': ' . $colorsettingval . ";\n";
        }
    }

    // Prepend pre-scss.

    if (!empty($leeloosettings->advanced_settings->scsspre)) {
        $scss .= @$leeloosettings->advanced_settings->scsspre;
    }
    return $scss;
}

/**
 * Implement pluginfile function to deliver files which are uploaded in theme settings
 *
 * @param stdClass $course course object
 * @param stdClass $cm course module
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool
 */
function theme_thinkblue_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {

    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('thinkblue');

        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }

        if ($filearea === 'favicon') {
            return $theme->setting_file_serve('favicon', $args, $forcedownload, $options);
        } else if (s($filearea === 'logo' || $filearea === 'backgroundimage')) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else if ($filearea === 'loginbackgroundimage') {
            return $theme->setting_file_serve('loginbackgroundimage', $args, $forcedownload, $options);
        } else if ($filearea === 'fontfiles') {
            return $theme->setting_file_serve('fontfiles', $args, $forcedownload, $options);
        } else if ($filearea === 'imageareaitems') {
            return $theme->setting_file_serve('imageareaitems', $args, $forcedownload, $options);
        } else if ($filearea === 'additionalresources') {
            return $theme->setting_file_serve('additionalresources', $args, $forcedownload, $options);
        } else {

            send_file_not_found();
        }
    } else {

        send_file_not_found();
    }
}

/**
 * If setting is updated, use this callback to clear the theme_thinkblue' own application cache.
 */
function theme_thinkblue_reset_app_cache() {

    $themeboostcampuscache = cache::make('theme_thinkblue', 'imagearea');

    $themeboostcampuscache->delete('imageareadata');

    theme_reset_all_caches();
}

/**
 * The most flexibly setting, user is typing text
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_setting_configthinkblue extends admin_setting {

    /** @var mixed int means PARAM_XXX type, string is a allowed format in regex */
    public $paramtype;
    /** @var int default field size */
    public $size;

    /**
     * Config text constructor
     *
     * @param string $name unique ascii name, either 'mysetting' for settings that in config, or 'myplugin/mysetting' for ones in config_plugins.
     * @param string $visiblename localised
     * @param string $description long localised info
     * @param string $defaultsetting
     * @param mixed $paramtype int means PARAM_XXX type, string is a allowed format in regex
     * @param int $size default field size
     */
    public function __construct($name, $visiblename, $description, $defaultsetting, $paramtype = PARAM_RAW, $size = null) {
        $this->paramtype = $paramtype;
        if (!is_null($size)) {
            $this->size = $size;
        } else {
            $this->size = ($paramtype === PARAM_INT) ? 5 : 30;
        }
        parent::__construct($name, $visiblename, $description, $defaultsetting);
    }

    /**
     * Return the setting
     *
     * @return mixed returns config if successful else null
     */
    public function get_setting() {
        return $this->config_read($this->name);
    }

    /**
     * Write the setting
     * @param string data
     * @return mixed returns config if successful else null
     */
    public function write_setting($data) {
        if ($this->paramtype === PARAM_INT and $data === '') {
            // do not complain if '' used instead of 0
            $data = 0;
        }
        // $data is a string
        $validated = $this->validate($data);
        if ($validated !== true) {
            return $validated;
        }
        return ($this->config_write($this->name, $data) ? '' : get_string('errorsetting', 'admin'));
    }

    /**
     * Validate data before storage
     * @param string data
     * @return mixed true if ok string if error found
     */
    public function validate($data) {
        // allow paramtype to be a custom regex if it is the form of /pattern/
        if (preg_match('#^/.*/$#', $this->paramtype)) {
            if (preg_match($this->paramtype, $data)) {
                return true;
            } else {
                return get_string('validateerror', 'admin');
            }
        } else if ($this->paramtype === PARAM_RAW) {
            return true;
        } else {
            $cleaned = clean_param($data, $this->paramtype);
            if ("$data" === "$cleaned") {
                // implicit conversion to string is needed to do exact comparison
                return true;
            } else {
                return get_string('validateerror', 'admin');
            }
        }
    }

    /**
     * Return an XHTML string for the setting
     * @param string data
     * @param string query
     * @return string Returns an XHTML string
     */
    public function output_html($data, $query = '') {
        $default = $this->get_defaultsetting();
        return '<input type="hidden" size="' . $this->size . '" id="' . $this->get_id() . '" name="' . $this->get_full_name() . '" value="' . s($data) . '" />';
    }
}