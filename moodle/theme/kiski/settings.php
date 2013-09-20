<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_kiski/logo';
	$title = get_string('logo','theme_kiski');
	$description = get_string('logodesc', 'theme_kiski');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_kiski/headercolor';
	$title = get_string('headercolor','theme_kiski');
	$description = get_string('headercolordesc', 'theme_kiski');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_kiski/linkcolor';
	$title = get_string('linkcolor','theme_kiski');
	$description = get_string('linkcolordesc', 'theme_kiski');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_kiski/linkhover';
	$title = get_string('linkhover','theme_kiski');
	$description = get_string('linkhoverdesc', 'theme_kiski');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}