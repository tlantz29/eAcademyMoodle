<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_monessen/logo';
	$title = get_string('logo','theme_monessen');
	$description = get_string('logodesc', 'theme_monessen');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_monessen/headercolor';
	$title = get_string('headercolor','theme_monessen');
	$description = get_string('headercolordesc', 'theme_monessen');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_monessen/linkcolor';
	$title = get_string('linkcolor','theme_monessen');
	$description = get_string('linkcolordesc', 'theme_monessen');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_monessen/linkhover';
	$title = get_string('linkhover','theme_monessen');
	$description = get_string('linkhoverdesc', 'theme_monessen');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}