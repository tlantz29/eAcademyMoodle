<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_hempfield/logo';
	$title = get_string('logo','theme_hempfield');
	$description = get_string('logodesc', 'theme_hempfield');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_hempfield/headercolor';
	$title = get_string('headercolor','theme_hempfield');
	$description = get_string('headercolordesc', 'theme_hempfield');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_hempfield/linkcolor';
	$title = get_string('linkcolor','theme_hempfield');
	$description = get_string('linkcolordesc', 'theme_hempfield');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_hempfield/linkhover';
	$title = get_string('linkhover','theme_hempfield');
	$description = get_string('linkhoverdesc', 'theme_hempfield');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}