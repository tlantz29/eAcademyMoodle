<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_eacademy/logo';
	$title = get_string('logo','theme_eacademy');
	$description = get_string('logodesc', 'theme_eacademy');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_eacademy/headercolor';
	$title = get_string('headercolor','theme_eacademy');
	$description = get_string('headercolordesc', 'theme_eacademy');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_eacademy/linkcolor';
	$title = get_string('linkcolor','theme_eacademy');
	$description = get_string('linkcolordesc', 'theme_eacademy');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_eacademy/linkhover';
	$title = get_string('linkhover','theme_eacademy');
	$description = get_string('linkhoverdesc', 'theme_eacademy');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}