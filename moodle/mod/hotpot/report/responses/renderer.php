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
 * Render the responses report for a given HotPot activity
 *
 * @package   mod-hotpot
 * @copyright 2010 Gordon Bateson <gordon.bateson@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// get parent class
require_once($CFG->dirroot.'/mod/hotpot/report/renderer.php');

/**
 * mod_hotpot_report_responses_renderer
 *
 * @copyright 2010 Gordon Bateson
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since     Moodle 2.0
 */
class mod_hotpot_report_responses_renderer extends mod_hotpot_report_renderer {
    public $mode = 'responses';

    public $tablecolumns = array(
        'picture', 'fullname', 'grade', 'selected', 'attempt',
        'timemodified','status', 'duration', 'penalties', 'score'
    );

    public $filterfields = array(
        'realname'=>0, // 'lastname'=>1, 'firstname'=>1, 'username'=>1,
        'grade'=>1, 'timemodified'=>1, 'status'=>1, 'duration'=>1, 'penalties'=>1, 'score'=>1
    );

    public $has_questioncolumns = true;

    /**
     * add_response_to_rawdata
     *
     * @param xxx $table (passed by reference)
     * @param xxx $attemptid
     * @param xxx $column
     * @param xxx $response
     */
    function add_response_to_rawdata(&$table, $attemptid, $column, $response)  {
        $text = '';

        static $str = null;
        if (is_null($str)) {
            $str = (object)array(
                'correct' => get_string('correct', 'hotpot'),
                'wrong'   => get_string('wrong', 'hotpot'),
                'ignored' => get_string('ignored', 'hotpot'),
                'score'   => get_string('score', 'hotpot'),
                'hintsclueschecks' => get_string('clues', 'hotpot').','.get_string('hints', 'hotpot').','.get_string('checks', 'hotpot')
            );
        }

        // correct
        if ($value = $response->correct) {
            $value = $table->set_legend($column, $value);
            $text .= html_writer::tag('li', $value, array('class'=>'correct'));
        }

        // wrong
        if ($value = $response->wrong) {
            $values = array();
            foreach (explode(',', $value) as $v) {
                $values[] = $table->set_legend($column, $v);
            }
            $text .= html_writer::tag('li', implode(',', $values), array('class'=>'wrong'));
        }

        // ignored
        if ($value = $response->ignored) {
            $values = array();
            foreach (explode(',', $value) as $v) {
                $values[] = $table->set_legend($column, $v);
            }
            $text .= html_writer::tag('li', implode(',', $values), array('class'=>'ignored'));
        }

        // numeric
        if (is_numeric($response->score)) {
            $value = $response->score.'%';
            $text .= html_writer::tag('li', $value, array('class'=>'score'));

            $hints = empty($response->hints) ? 0 : $response->hints;
            $clues = empty($response->clues) ? 0 : $response->clues;
            $checks = empty($response->checks) ? 0 : $response->checks;

            $value = '('.$hints.','.$clues.','.$checks.')';
            $text .= html_writer::tag('li', $value, array('class'=>'hintsclueschecks'));
        }

        if ($text) {
            $text = html_writer::tag('ul', $text, array('class'=>'response'));
        }

        $table->rawdata[$attemptid]->$column = $text;
    }
}
