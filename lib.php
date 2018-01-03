<?php
function block_modguideform_images() {
    return array(html_writer::tag('img', '', array('alt' => get_string('red', 'block_modguideform'), 'src' => "pix/dog1.jpg")),
                html_writer::tag('img', '', array('alt' => get_string('blue', 'block_modguideform'), 'src' => "pix/dog2.jpg")),
                html_writer::tag('img', '', array('alt' => get_string('green', 'block_modguideform'), 'src' => "pix/dog3.jpg")));
}

function block_modguideform_print_page($modguideform, $return = false) {
    global $OUTPUT, $COURSE;
    $display = $OUTPUT->heading($modguideform->pagetitle);
    $display .= $OUTPUT->box_start();
    if($modguideform->displaydate) {
        $display .= html_writer::start_tag('div', array('class' => 'modguideform displaydate'));
        $display .= userdate($modguideform->displaydate);
        $display .= html_writer::end_tag('div');
    }
    $display .= clean_text($modguideform->displaytext);

    //close the box
    $display .= $OUTPUT->box_end();
    if ($modguideform->displaypicture) {
        $display .= $OUTPUT->box_start();
        $images = block_modguideform_images();
        $display .= $images[$modguideform->picture];
        $display .= html_writer::start_tag('p');
        $display .= clean_text($modguideform->description);
        $display .= html_writer::end_tag('p');
        $display .= $OUTPUT->box_end();
    }


    if($return) {
        return $display;
    } else {
        echo $display;
    }
}