<?php
require_once('../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_modguideform', $courseid);
}

require_login($course);
require_capability('block/modguideform:managepages', context_course::instance($courseid));

if(! $modguideformpage = $DB->get_record('block_modguideform', array('id' => $id))) {
    print_error('nopage', 'block_modguideform', '', $id);
}

$site = get_site();
$PAGE->set_url('/blocks/modguideform/view.php', array('id' => $id, 'courseid' => $courseid));
$heading = $site->fullname . ' :: ' . $course->shortname . ' :: ' . $modguideformpage->pagetitle;
$PAGE->set_heading($heading);
if (!$confirm) {
    $optionsno = new moodle_url('/course/view.php', array('id' => $courseid));
    $optionsyes = new moodle_url('/blocks/modguideform/delete.php', array('id' => $id, 'courseid' => $courseid, 'confirm' => 1, 'sesskey' => sesskey()));
    echo $OUTPUT->confirm(get_string('deletepage', 'block_modguideform', $modguideformpage->pagetitle), $optionsyes, $optionsno);
} else {
    if (confirm_sesskey()) {
        if (!$DB->delete_records('block_modguideform', array('id' => $id))) {
            print_error('deleteerror', 'block_modguideform');
        }
    } else {
        print_error('sessionerror', 'block_modguideform');
    }
    $url = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($url);
}
echo $OUTPUT->header();
echo $OUTPUT->footer();