<?php
class block_modguideform extends block_base {
    public function init() {
        $this->title = get_string('modguideform', 'block_modguideform');
    }

    public function applicable_formats() {
        return array('course-view' => true);
    }

    public function instance_allow_multiple() {
        return false;
    }

    function has_config() {return true;}

    public function hide_header() {
        return false;
    }

    public function get_content() {
        if ($this->content !== null) {
        return $this->content;
        }

        $this->content         =  new stdClass;
        $this->content->text   = 'The content of our modguideform block!';
        global $COURSE, $DB, $PAGE;

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        }


        $context = context_course::instance($COURSE->id);

        if (has_capability('block/modguideform:managepages', $context)) {
            $url = new moodle_url('/blocks/modguideform/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
            $this->content->footer = html_writer::link($url, get_string('addpage', 'block_modguideform'));
        } else {
            $this->content->footer = '';
        }

        // Check to see if we are in editing mode and that we can manage pages.
        $canmanage = has_capability('block/modguideform:managepages', $context) && $PAGE->user_is_editing($this->instance->id);
        $canview = has_capability('block/modguideform:viewpages', $context);

        // This is the new code.
        if ($modguideformpages = $DB->get_records('block_modguideform', array('blockid' => $this->instance->id))) {
            $this->content->text .= html_writer::start_tag('ul');
            foreach ($modguideformpages as $modguideformpage) {
                if ($canmanage) {
                    $pageparam = array('blockid' => $this->instance->id,
                        'courseid' => $COURSE->id,
                        'id' => $modguideformpage->id);
                    $editurl = new moodle_url('/blocks/modguideform/view.php', $pageparam);
                    $editpicurl = new moodle_url('/pix/t/edit.png');
                    $edit = html_writer::link($editurl, html_writer::tag('img', '', array('src' => $editpicurl, 'alt' => get_string('edit'))));
                    $deleteparam = array('id' => $modguideformpage->id, 'courseid' => $COURSE->id);
                    $deleteurl = new moodle_url('/blocks/modguideform/delete.php', $deleteparam);
                    $deletepicurl = new moodle_url('/pix/t/delete.png');
                    $delete = html_writer::link($deleteurl, html_writer::tag('img', '', array('src' => $deletepicurl, 'alt' => get_string('delete'))));
                } else {
                    $edit = '';
                    $delete = '';
                }
                $pageurl = new moodle_url('/blocks/modguideform/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $modguideformpage->id, 'viewpage' => true));
                $this->content->text .= html_writer::start_tag('li');
                if ($canview) {
                    $this->content->text .= html_writer::link($pageurl, $modguideformpage->pagetitle);
                } else {
                    $this->content->text .= html_writer::tag('div', $modguideformpage->pagetitle);
                }
                $this->content->text .= $edit;
                $this->content->text .= $delete;
                $this->content->text .= html_writer::end_tag('li');
            }
            $this->content->text .= html_writer::end_tag('ul');
        }

        // The other code.
        $url = new moodle_url('/blocks/modguideform/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
        $this->content->footer = html_writer::link($url, get_string('addpage', 'block_modguideform'));
        return $this->content;
    }

    public function specialization() {
        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaulttitle', 'block_modguideform');
            } else {
                $this->title = $this->config->title;
            }

            if (empty($this->config->text)) {
                $this->config->text = get_string('defaulttext', 'block_modguideform');
            }
        }
    }

    public function instance_config_save($data,$nolongerused = false) {
        if(get_config('modguideform', 'Allow_HTML') == '1') {
            $data->text = strip_tags($data->text);
        }

        // And now forward to the default implementation defined in the parent class
        return parent::instance_config_save($data,$nolongerused);
    }

    public function instance_delete() {
        global $DB;
        $DB->delete_records('block_modguideform', array('blockid' => $this->instance->id));
    }
}
?>