<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_dog/logo';
	$title = get_string('logo','theme_dog');
	$description = get_string('logodesc', 'theme_dog');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_dog/headercolor';
	$title = get_string('headercolor','theme_dog');
	$description = get_string('headercolordesc', 'theme_dog');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_dog/linkcolor';
	$title = get_string('linkcolor','theme_dog');
	$description = get_string('linkcolordesc', 'theme_dog');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_dog/linkhover';
	$title = get_string('linkhover','theme_dog');
	$description = get_string('linkhoverdesc', 'theme_dog');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}