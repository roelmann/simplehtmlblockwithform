<?php

require_once('../../config.php');
require_once('modguideform_form.php');

global $DB, $OUTPUT, $PAGE;

// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
// Next look for optional variables.
$id = optional_param('id', 0, PARAM_INT);
$viewpage = optional_param('viewpage', false, PARAM_BOOL);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_modguideform', $courseid);
}

require_login($course);
require_capability('block/modguideform:managepages', context_course::instance($courseid));
$PAGE->set_url('/blocks/modguideform/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_modguideform'));
$settingsnode = $PAGE->settingsnav->add(get_string('modguideformsettings', 'block_modguideform'));
$editurl = new moodle_url('/blocks/modguideform/view.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('editpage', 'block_modguideform'), $editurl);
$editnode->make_active();

$modguideform = new modguideform_form();

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$toform['id'] = $id;

$modguideform->set_data($toform);

if($modguideform->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $courseurl = new moodle_url('/course/view.php', array('id' => $id));
    redirect($courseurl);
} else if ($fromform = $modguideform->get_data()) {
    // We need to add code to appropriately act on and store the submitted data
    if ($fromform->id != 0) {
        if (!$DB->update_record('block_modguideform', $fromform)) {
            print_error('updateerror', 'block_modguideform');
        }
    } else {
        if (!$DB->insert_record('block_modguideform', $fromform)) {
            print_error('inserterror', 'block_modguideform');
        }
    }    // redirect($courseurl);
    print_object($fromform);
} else {
    // form didn't validate or this is the first display
    $site = get_site();
    echo $OUTPUT->header();
    if ($id) {
        $modguideformpage = $DB->get_record('block_modguideform', array('id' => $id));
        if($viewpage) {
            block_modguideform_print_page($modguideformpage);
        } else {
            $modguideform->set_data($modguideformpage);
            $modguideform->display();
        }
    } else {
        $modguideform->display();
    }
    echo $OUTPUT->footer();
}
?>