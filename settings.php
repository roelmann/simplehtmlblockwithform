<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_modguideform'),
            get_string('descconfig', 'block_modguideform')
        ));

$settings->add(new admin_setting_configcheckbox(
            'modguideform/Allow_HTML',
            get_string('labelallowhtml', 'block_modguideform'),
            get_string('descallowhtml', 'block_modguideform'),
            '0'
        ));
?>