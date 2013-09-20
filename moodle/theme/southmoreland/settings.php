<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_southmoreland/logo';
	$title = get_string('logo','theme_southmoreland');
	$description = get_string('logodesc', 'theme_southmoreland');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_southmoreland/headercolor';
	$title = get_string('headercolor','theme_southmoreland');
	$description = get_string('headercolordesc', 'theme_southmoreland');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_southmoreland/linkcolor';
	$title = get_string('linkcolor','theme_southmoreland');
	$description = get_string('linkcolordesc', 'theme_southmoreland');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_southmoreland/linkhover';
	$title = get_string('linkhover','theme_southmoreland');
	$description = get_string('linkhoverdesc', 'theme_southmoreland');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}