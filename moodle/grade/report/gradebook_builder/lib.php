<?php

require_once $CFG->dirroot . '/grade/report/lib.php';

class grade_report_gradebook_builder extends grade_report {
    function process_data($data) {
        global $DB;

        $options = $this->get_save_options();
        $contextlevel = $data->contextlevel;

        if (!isset($options[$contextlevel])) {
            // Naturally assume this template is for the user
            $contextlevel = CONTEXT_USER;
            $data->template = null;
        }

        $template = new stdClass;

        $template->name = $data->name;
        $template->data = $data->data;

        $template->contextlevel = $contextlevel;
        $template->instanceid = $this->determine_instanceid($contextlevel);

        if (empty($data->template)) {
            $id = $DB->insert_record('gradereport_builder_template', $template);
            $template->id = $id;
        } else {
            $template->id = $data->template;
            $DB->update_record('gradereport_builder_template', $template);
        }

        // Saved template, let them confirm it
        redirect(new moodle_url('/grade/report/gradebook_builder/preview.php', array(
            'id' => $this->course->id,
            'template' => $template->id
        )));
    }

    function build_gradebook($courseid, $template) {
        global $DB, $CFG;

        require_once $CFG->dirroot . '/course/lib.php';
        require_once $CFG->libdir . '/modinfolib.php';

        $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
        if (self::is_gradebook_established($courseid)) {
            return 'items';
        }

        $obj = json_decode($template->data);

        $aggregation = $obj->aggregation;

        if (!self::get_aggregation_label($aggregation)) {
            return 'invalid_aggregation';
        }

        $course_item = grade_item::fetch(array(
            'itemtype' => 'course',
            'courseid' => $courseid
        ));

        $course_cat = $course_item->load_parent_category();
        $course_cat->aggregation = $aggregation;
        $course_cat->update();

        foreach ($obj->categories as $grade_category) {
            $category = new grade_category(array('courseid' => $courseid), false);
            $category->apply_default_settings();
            $category->apply_forced_settings();

            $category->fullname = $grade_category->name;
            $category->aggregation = $aggregation;
            $category->parent = $course_cat->id;
            $category->insert();

            $cat_item = $category->load_grade_item();
            $cat_item->aggregationcoef = $grade_category->weight;
            $cat_item->update();

            foreach ($grade_category->items as $grade_item) {
                if ($grade_item->itemtype == 'manual') {
                    $item = self::build_manual_item($courseid, $category, $grade_item);
                } else {
                    $item = self::build_mod_item($course, $category, $grade_item);
                }
            }
        }

        rebuild_course_cache($course->id);

        return true;
    }

    function default_course_module($course, $item) {
        global $DB;

        $newcm = new stdClass;
        $newcm->course = $course->id;
        $newcm->module = $DB->get_field('modules', 'id', array('name' => $item->itemmodule));
        $newcm->section = 1;
        $newcm->instance = 0;
        $newcm->visible = 1;
        $newcm->visibleold = 1;
        $newcm->groupmode = $course->groupmode;
        $newcm->groupmembersonly = 0;
        $newcm->groupingid = 0;
        $newcm->score = 0;
        $newcm->ident = 0;
        $newcm->completion = 0;
        $newcm->completionview = 0;
        $newcm->completionexpected = 0;
        $newcm->availablefrom = 0;
        $newcm->availableuntil = 0;
        $newcm->showavailability = 0;
        $newcm->showdescription = 0;

        $newcm->id = add_course_module($newcm);
        return $newcm;
    }

    // TODO: Is there a better way to handle this?
    function default_mod_quiz($module) {
        if (!class_exists('mod_quiz_display_options')) {
            global $CFG;
            require_once $CFG->dirroot . '/mod/quiz/locallib.php';
        }

        $quiz = get_config('quiz');
        $module->introformat = 0;
        $module->timeopen = 0;
        $module->timeclose = 0;
        $module->preferredbehaviour = $quiz->preferredbehaviour;
        $module->attempts = $quiz->attempts;
        $module->attemptonlast = $quiz->attemptonlast;
        $module->grademethod = $quiz->grademethod;
        $module->decimalpoints = $quiz->decimalpoints;
        $module->questiondecimalpoints = $quiz->questiondecimalpoints;
        $module->questionsperpage = $quiz->questionsperpage;
        $module->shufflequestions = $quiz->shufflequestions;
        $module->shuffleanswers = $quiz->shuffleanswers;
        $module->sumgrades = 0.00000;
        $module->timecreated = time();
        $module->timelimit = $quiz->timelimit;
        $module->quizpassword = $quiz->password;
        $module->subnet = $quiz->subnet;
        $module->browsersecurity = $quiz->browsersecurity;
        $module->delay1 = $quiz->delay1;
        $module->delay2 = $quiz->delay2;
        $module->showuserpicture = $quiz->showuserpicture;
        $module->showblocks = $quiz->showblocks;

        // No feedback
        $module->feedbackboundarycount = -1;

        $review_options = array(
            'attempt', 'correctness', 'marks', 'specificfeedback',
            'generalfeedback', 'rightanswer', 'overallfeedback'
        );

        $additional_options = array(
            'during' => mod_quiz_display_options::DURING,
            'immediately' => mod_quiz_display_options::IMMEDIATELY_AFTER,
            'open' => mod_quiz_display_options::LATER_WHILE_OPEN,
            'closed' => mod_quiz_display_options::AFTER_CLOSE,
        );

        foreach ($review_options as $review) {
            $field = 'review' . $review;
            foreach ($additional_options as $whenname => $when) {
                $modfield = $field . $whenname;
                $module->$modfield = ($quiz->$field & $when) ? 1 : 0;
            }
        }
    }

    function default_graded_module($course, $item) {
        $cm = self::default_course_module($course, $item);

        $module = new stdClass;
        $module->course = $course->id;
        $module->name = $item->name;
        $module->intro = '';
        $module->grade = $item->grademax;
        $module->coursemodule = $cm->id;
        $module->section = 1;

        $add_instance = $item->itemmodule . '_add_instance';
        if (!function_exists($add_instance)) {
            global $CFG;
            $lib_file = $CFG->dirroot . '/mod/' . $item->itemmodule . '/lib.php';
            if (!file_exists($lib_file)) {
                print_error('no_lib_file', 'gradereport_gradebook_builder',
                    '', $item->itemmodule);
            }
            require_once $lib_file;
        }

        $helper_function = 'default_mod_' . $item->itemmodule;
        self::$helper_function($module);

        $module->id = $add_instance($module);
        $cm->instance = $module->id;
        $cm->section = add_mod_to_section($module);

        global $DB;
        $DB->update_record('course_modules', $cm);

        return $module;
    }

    function build_mod_item($course, $category, $item) {
        try {
            $instance = self::default_graded_module($course, $item);

            $grade_item = grade_item::fetch(array(
                'courseid' => $course->id,
                'itemtype' => 'mod',
                'itemmodule' => $item->itemmodule,
                'iteminstance' => $instance->id
            ));
        } catch (Exception $e) {
            $grade_item = self::build_manual_item($course, $category, $item);
        }

        $grade_item->aggregationcoef = isset($item->weight) ? $item->weight : 0;
        $grade_item->grademax = (float)$item->grademax;
        $grade_item->set_parent($category->id);

        return $grade_item;
    }

    function build_manual_item($courseid, $category, $item) {
        $grade_item = new grade_item(array(
            'courseid' => $courseid,
            'itemtype' => 'manual',
            'categoryid' => $category->id
        ), false);

        $grade_item->itemname = $item->name;
        $grade_item->aggregationcoef = isset($item->weight) ? $item->weight : 0;
        $grade_item->grademax = (float)$item->grademax;
        $grade_item->insert();

        return $grade_item;
    }

    function process_action($target, $action) {
    }

    function __construct($courseid, $gpr, $context, $template = null) {
        parent::__construct($courseid, $gpr, $context);

        $csn = preg_replace('/(.+?) for .*/', '${1}', $this->course->shortname);

        if (!$template) {
            $template = new stdClass;
            $template->id = null;
            $template->name = $csn;
            $template->contextlevel = CONTEXT_USER;
            $template->instanceid = $this->determine_instanceid(CONTEXT_USER);
            $template->data = '{}';
        } else {
        $template->name = $csn;
        }

        $this->template = $template;
    }

    function inject_js() {
        global $PAGE;

        $PAGE->requires->js('/grade/report/gradebook_builder/jquery.js');
        $PAGE->requires->js('/grade/report/gradebook_builder/app.js');
    }

    function output() {
        global $OUTPUT;

        $help = get_string('help', 'gradereport_gradebook_builder');
        $helplink = get_string('helplink', 'gradereport_gradebook_builder');
        $instructions = get_string('instructions', 'gradereport_gradebook_builder');
        $help_step_0 = get_string('help_step_0', 'gradereport_gradebook_builder');
        $help_step_1 = get_string('help_step_1', 'gradereport_gradebook_builder');
        $help_step_2 = get_string('help_step_2', 'gradereport_gradebook_builder');
        $help_step_3 = get_string('help_step_3', 'gradereport_gradebook_builder');
        $help_step_4 = get_string('help_step_4', 'gradereport_gradebook_builder');
        $help_step_5 = get_string('help_step_5', 'gradereport_gradebook_builder');
        $help_step_6 = get_string('help_step_6', 'gradereport_gradebook_builder');

        $step_1 = get_string('step_1', 'gradereport_gradebook_builder');
        $step_2 = get_string('step_2', 'gradereport_gradebook_builder');
        $step_3 = get_string('step_3', 'gradereport_gradebook_builder');
        $step_4 = get_string('step_4', 'gradereport_gradebook_builder');

        $add = get_string('add', 'gradereport_gradebook_builder');
        $tocategory = get_string('tocategory', 'gradereport_gradebook_builder');

        $container = html_writer::tag('div',
            html_writer::tag('div',
            html_writer::tag('div',
            $OUTPUT->single_select('index.php?id=' . $this->courseid,
            'template', $this->get_templates(), $this->template->id) .
            html_writer::tag('h3',
                html_writer::tag('span',
                $this->template->name,
                array('id' => 'template-toggle-input', 'class' => 'linky')),
                array('id' => 'template-name')),
            array('class' => 'span4')),
            array('class' => 'row')) .
            html_writer::tag('div',
                html_writer::tag('div','',
                array('class' => 'span4', 'id' => 'grade-categories')) .
                html_writer::tag('div',
                    $OUTPUT->heading($step_1, 3) .
                    html_writer::tag('form',
                    html_writer::empty_tag('input', array(
                        'type' => 'text',
                        'class' => 'input-medium',
                        'id' => 'category-name',
                        'placeholder' => 'Category Name'
                    )) . '&nbsp;' .
                    html_writer::tag('button', $add, array(
                        'type' => 'submit',
                        'class' => 'btn btn-primary',
                        'id' => 'add-category'
                    ))) .
                    html_writer::tag('form',
                    $OUTPUT->heading($step_2, 3) .
                    html_writer::tag('div',
                        html_writer::empty_tag('input', array(
                            'type' => 'text',
                            'class' => 'input-tiny',
                            'id' => 'grade-item-num-add',
                            'value' => '1'
                        )) . '&nbsp;' .
                        html_writer::select(
                            $this->get_graded_options(), 'grade_options', '',
                            null, array('id' => 'grade-itemtype')
                        ) . $tocategory .
                        html_writer::tag('select', '', array(
                            'id' => 'add-item-category'
                        )) . '&nbsp;' .
                        html_writer::tag('button', $add, array(
                            'type' => 'submit',
                            'class' => 'btn btn-primary',
                            'id' => 'add-item'
                        )),
                        array('class' => 'nowrap')
                    ),
                    array('id' => 'add-items', 'class' => 'well form-inline')
                ) . html_writer::tag('div',
                    $OUTPUT->heading($step_3, 3) .
                    html_writer::select(
                        $this->get_aggregations(), 'aggregations', '',
                        null, array('id' => 'grading-method')
                    ) . html_writer::tag('form',
                        $OUTPUT->heading('Category Weights', 3) .
                        html_writer::tag('fieldset', ''),
                            array('id' => 'category-weights')),
                    array('class' => 'well')
                ) . html_writer::tag('form',
                    html_writer::empty_tag('input', array(
                        'type' => 'hidden',
                        'name' => 'id',
                        'value' => $this->courseid
                    )) . html_writer::empty_tag('input', array(
                        'type' => 'hidden',
                        'name' => 'name',
                        'value' => $this->template->name
                    )) . html_writer::empty_tag('input', array(
                        'type' => 'hidden',
                        'name' => 'data',
                        'value' => $this->template->data
                    )) . html_writer::empty_tag('input', array(
                        'type' => 'hidden',
                        'name' => 'contextlevel',
                        'value' => $this->template->contextlevel
                    )) . html_writer::empty_tag('input', array(
                        'type' => 'hidden',
                        'name' => 'template',
                        'value' => $this->template->id
                    )) . html_writer::tag('span', $step_4, array('class' => 'bolder')) . html_writer::tag('button', 'Save to Gradebook', array(
                        'type' => 'submit',
                        'id' => 'save-button',
                        'class' => 'btn btn-large btn-primary'
                    )),
                    array('method' => 'post', 'id' => 'builder', 'class' => 'center')
                ) .
              html_writer::tag('div', 
                  html_writer::tag ('div', $instructions .
                          html_writer::tag ('a', $help, array('href' => $helplink, 'target' => '_blank', 'class' => 'help')),
                  array('class' => 'instructions help')) .
                  html_writer::tag ('div', $help_step_0,
                      array('class' => 'help_instructions')) .
                      html_writer::tag ('ol',
                          html_writer::tag ('li', $help_step_1) .
                          html_writer::tag('li', $help_step_2) .
                          html_writer::tag('li', $help_step_3) .
                          html_writer::tag ('li', $help_step_4) .
                          html_writer::tag ('li', $help_step_5) .
                          html_writer::tag ('li', $help_step_6)),
              array('id' => 'howto')),
            array('class' => 'span8')),
          array('class' => 'row')),
        array('class' => 'container', 'id' => 'builder-start'));

        $templates = html_writer::tag('div',
            html_writer::tag('table',
            html_writer::tag('thead',
            html_writer::tag('tr',
            html_writer::tag('th',
            html_writer::tag('h3',
            html_writer::tag('span', '') .
            html_writer::tag('span', 'X', array(
                'class' => 'label label-important remove remove-category-label'
            )))))) .
            html_writer::tag('tbody', ''),
            array('class' => 'table table-bordered table-striped')),
                array('id' => 'grade-category-tmpl')
            );

        $templates .= html_writer::tag('div',
            html_writer::tag('table',
            html_writer::tag('tr',
            html_writer::tag('td',
            html_writer::tag('span',
            html_writer::tag('span', 'X', array(
                'class' => 'label label-important remove remove-item-label'
            ))) .
            html_writer::tag('div',
                html_writer::empty_tag('input', array(
                    'class' => 'input-tiny',
                    'value' => '100'
                )) .
                html_writer::tag('span', 'Points', array(
                    'class' => 'add-on'
                )),
                array('class' => 'input-append point-blank pull-right')
            )))),
            array('id' => 'grade-item-tmpl'));

        $templates .= html_writer::tag('div',
            html_writer::tag('div',
            html_writer::tag('label',
            html_writer::empty_tag('span'),
            array('class' => 'control-label')) .
            html_writer::tag('div',
                html_writer::tag('div',
                html_writer::empty_tag('input', array(
                    'type' => 'text',
                    'class' => 'input-tiny',
                    'value' => '0'
                )) . html_writer::tag('span', '%', array(
                    'class' => 'add-on'
                )),
                array('class' => 'input-append')),
                array('class' => 'controls')),
            array('class' => 'control-group')),
            array('id' => 'category-weight-tmpl'));

        echo $container . $templates;
    }

    function determine_instanceid($contextlevel) {
        global $USER;

        switch ($contextlevel) {
            case CONTEXT_USER: return $USER->id;
            case CONTEXT_COURSECAT: return $this->course->category;
            case CONTEXT_SYSTEM: return 0;
        }
        print_error('undefined_context', 'gradereport_gradebook_builder');
    }

    function determine_label($contextlevel) {
        global $USER;

        switch ($contextlevel) {
            case CONTEXT_USER: return fullname($USER);
            case CONTEXT_SYSTEM: return get_string('coresystem');
            case CONTEXT_COURSECAT:
                global $DB;
                return $DB->get_field('course_categories', 'name', array(
                    'id' => $this->course->category
                ));
            default: '';
        }
    }

    function determine_context($contextlevel) {
        return get_context_instance(
            $contextlevel, $this->determine_instanceid($contextlevel)
        );
    }

    function get_aggregations() {
        $visibles = explode(',', get_config('moodle', 'grade_aggregations_visible'));
        $options = array();

        foreach ($visibles as $aggregation) {
            $options[$aggregation] = $this->get_aggregation_label($aggregation);
        }

        return $options;
    }

    function get_aggregation_label($aggregation) {
        $_s = function($key) { return get_string($key, 'grades'); };
        switch ($aggregation) {
            case GRADE_AGGREGATE_MEAN: return $_s('aggregatemean');
            case GRADE_AGGREGATE_WEIGHTED_MEAN: return $_s('aggregateweightedmean');
            case GRADE_AGGREGATE_WEIGHTED_MEAN2: return $_s('aggregateweightedmean2');
            case GRADE_AGGREGATE_EXTRACREDIT_MEAN: return $_s('aggregateextracreditmean');
            case GRADE_AGGREGATE_MEDIAN: return $_s('aggregatemedian');
            case GRADE_AGGREGATE_MIN: return $_s('aggregatemin');
            case GRADE_AGGREGATE_MAX: return $_s('aggregatemax');
            case GRADE_AGGREGATE_MODE: return $_s('aggregatemode');
            case GRADE_AGGREGATE_SUM: return $_s('aggregatesum');
            default: return null;
        }
    }

    function get_save_options() {
        global $DB;

        $_s = function($key, $a=null) {
            return get_string($key, 'gradereport_gradebook_builder', $a);
        };

        $options = array(CONTEXT_USER => $_s('save_user'));

        $context = $this->determine_context(CONTEXT_COURSECAT);
        if (has_capability('moodle/grade:edit', $context)) {
            $name = $this->determine_label(CONTEXT_COURSECAT);
            $options[CONTEXT_COURSECAT] = $_s('save_category', $name);
        }

        $context = $this->determine_context(CONTEXT_SYSTEM);
        if (has_capability('moodle/grade:edit', $context)) {
            $options[CONTEXT_SYSTEM] = $_s('save_system');
        }

        return $options;
    }

    function get_templates() {
        global $USER, $DB;

        $levels = array(CONTEXT_USER, CONTEXT_COURSECAT, CONTEXT_SYSTEM);

        $options = array();
        // Gather templates at respective context levels
        foreach ($levels as $contextlevel) {
            $params = array(
                'contextlevel' => $contextlevel,
                'instanceid' => $this->determine_instanceid($contextlevel)
            );

            $templates = $DB->get_records_menu(
                'gradereport_builder_template', $params, 'name DESC', 'id,name'
            );

            if ($templates) {
                $label = $this->determine_label($contextlevel);
                $options[$label] = array($label => $templates);
            }
        }

        return $options;
    }

    function get_graded_options() {
        $list = get_config('grade_builder', 'acceptable_mods');
        $acceptable_mods = explode(',', $list);

        $mods = get_plugin_list('mod');

        $options = array(
            'manual' => get_string('manual_item', 'gradereport_gradebook_builder')
        );
        foreach ($mods as $mod => $dir) {
            if (in_array($mod, $acceptable_mods)) {
                $options[$mod] = get_string($mod, 'gradereport_gradebook_builder');
            }
        }

        return $options;
    }

    function is_gradebook_established($courseid = null) {
        $courseid = $courseid ? $courseid : $this->courseid;
        $items = grade_item::fetch_all(array('courseid' => $courseid));

        return count($items) > 1;
    }
}
