<?php

class block_modguideform_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_text', get_string('blockstring', 'block_modguideform'));
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_RAW);

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_modguideform'));
        $mform->setDefault('config_title', 'default value');
        $mform->setType('config_title', PARAM_TEXT);

    }
}