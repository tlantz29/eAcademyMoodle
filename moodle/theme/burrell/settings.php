<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_burrell/logo';
	$title = get_string('logo','theme_burrell');
	$description = get_string('logodesc', 'theme_burrell');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_burrell/headercolor';
	$title = get_string('headercolor','theme_burrell');
	$description = get_string('headercolordesc', 'theme_burrell');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_burrell/linkcolor';
	$title = get_string('linkcolor','theme_burrell');
	$description = get_string('linkcolordesc', 'theme_burrell');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_burrell/linkhover';
	$title = get_string('linkhover','theme_burrell');
	$description = get_string('linkhoverdesc', 'theme_burrell');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}