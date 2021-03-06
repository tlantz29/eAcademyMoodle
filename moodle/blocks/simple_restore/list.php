<?php

require_once '../../config.php';
require_once 'lib.php';
global $USER;
$courseid = required_param('id', PARAM_INT);
$restore_to = optional_param('restore_to', 0, PARAM_INT);

$name = optional_param('name', null, PARAM_RAW);
$action = optional_param('action', null, PARAM_TEXT);
$file = optional_param('fileid', null, PARAM_RAW);

// Needed for admins, as they need to query the courses
$shortname = optional_param('shortname', null, PARAM_TEXT);

if(!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('no_course', 'block_simple_restore', '', $courseid);
}

require_login();

$context = get_context_instance(CONTEXT_COURSE, $courseid);
require_capability('block/simple_restore:canrestore', $context);

// Chosen a file
if ($file and $action and $name) {
    $filename = simple_restore_utils::prep_restore($file, $name, $courseid);
    redirect(new moodle_url('/blocks/simple_restore/restore.php', array(
        'contextid' => $context->id,
        'filename' => $filename,
        'restore_to' => $restore_to
    )));
}

$blockname = get_string('pluginname', 'block_simple_restore');
$heading = simple_restore_utils::heading($restore_to);

$base_url = new moodle_url('/blocks/simple_restore/list.php', array(
    'id' => $courseid, 'restore_to' => $restore_to
));

$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->navbar->add($blockname);
$PAGE->set_title($blockname.': '.$heading);
$PAGE->set_heading($blockname.': '.$heading);
$PAGE->set_url($base_url);

$system = get_context_instance(CONTEXT_SYSTEM);

$is_admin = has_capability('moodle/restore:userinfo', $system);

if (empty($shortname) and $is_admin) {
    require_once 'list_form.php';

    $form = new list_form();

    if ($form->is_cancelled()) {
        redirect(new moodle_url('/course/view.php', array('id' => $courseid)));
    } else if ($data = $form->get_data()) {
        $warn = $OUTPUT->notification(simple_restore_utils::_s('no_filter'));
    }

    $form->set_data(array('id' => $courseid));

    echo $OUTPUT->header();
    echo $OUTPUT->heading(simple_restore_utils::_s('adminfilter'));

    if (!empty($warn)) {
        echo $warn;
    }

    echo $OUTPUT->box_start();
    $form->display();
    echo $OUTPUT->box_end();

    echo $OUTPUT->footer();
    die;
}

echo $OUTPUT->header();

$data = new stdClass;
$data->restore_to = $restore_to;
$data->courseid = $courseid;
// Admins can filter by shortname
$userln = $USER->lastname;
$userfn = $USER->firstname;
$userdist = $USER->districtid;
$username= $userfn . '_' . $userln . '_' . $userdist;

//$username="LANTZ";
$queryrole  = $DB->get_records('role_assignments',array('userid'=>$USER->id));
//foreach($queryrole as $qrole){ }
$qrole1=$queryrole->roleid;
if ($is_admin) {
    $data->shortname = $shortname;
	}
	else {
	$shortname=$username;
	$data->shortname = $shortname;
		}
		
//$short = substr($shortname, 0, 3);
//if ($short== "") {
//$shortname=$username;
//$data->shortname = $shortname;
//}
$data->lists = array();

events_trigger('simple_restore_backup_list', $data);

$display_list = function($in, $list) {
    echo $list->html;

    return $in || !empty($list->backups);
};

// Obey handled order
usort($data->lists, function($a, $b) {
    if ($a->order == $b->order) return 0;
    return $a->order < $b->order ? -1 : 1;
});

$successful = array_reduce($data->lists, $display_list, false);

if (!$successful) {
    echo $OUTPUT->notification(simple_restore_utils::_s('empty_backups'));
    echo $OUTPUT->continue_button(
        new moodle_url('/course/view.php', array('id' => $courseid))
    );
}

echo $OUTPUT->footer();
