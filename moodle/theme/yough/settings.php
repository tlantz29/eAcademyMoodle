<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_yough/logo';
	$title = get_string('logo','theme_yough');
	$description = get_string('logodesc', 'theme_yough');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_yough/headercolor';
	$title = get_string('headercolor','theme_yough');
	$description = get_string('headercolordesc', 'theme_yough');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_yough/linkcolor';
	$title = get_string('linkcolor','theme_yough');
	$description = get_string('linkcolordesc', 'theme_yough');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_yough/linkhover';
	$title = get_string('linkhover','theme_yough');
	$description = get_string('linkhoverdesc', 'theme_yough');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}