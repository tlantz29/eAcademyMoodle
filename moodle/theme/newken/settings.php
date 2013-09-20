<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_newken/logo';
	$title = get_string('logo','theme_newken');
	$description = get_string('logodesc', 'theme_newken');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_newken/headercolor';
	$title = get_string('headercolor','theme_newken');
	$description = get_string('headercolordesc', 'theme_newken');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_newken/linkcolor';
	$title = get_string('linkcolor','theme_newken');
	$description = get_string('linkcolordesc', 'theme_newken');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_newken/linkhover';
	$title = get_string('linkhover','theme_newken');
	$description = get_string('linkhoverdesc', 'theme_newken');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}