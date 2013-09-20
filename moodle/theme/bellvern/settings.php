<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_bellvern/logo';
	$title = get_string('logo','theme_bellvern');
	$description = get_string('logodesc', 'theme_bellvern');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_bellvern/headercolor';
	$title = get_string('headercolor','theme_bellvern');
	$description = get_string('headercolordesc', 'theme_bellvern');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_bellvern/linkcolor';
	$title = get_string('linkcolor','theme_bellvern');
	$description = get_string('linkcolordesc', 'theme_bellvern');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_bellvern/linkhover';
	$title = get_string('linkhover','theme_bellvern');
	$description = get_string('linkhoverdesc', 'theme_bellvern');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}