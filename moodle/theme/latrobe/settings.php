<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_latrobe/logo';
	$title = get_string('logo','theme_latrobe');
	$description = get_string('logodesc', 'theme_latrobe');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_latrobe/headercolor';
	$title = get_string('headercolor','theme_latrobe');
	$description = get_string('headercolordesc', 'theme_latrobe');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_latrobe/linkcolor';
	$title = get_string('linkcolor','theme_latrobe');
	$description = get_string('linkcolordesc', 'theme_latrobe');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_latrobe/linkhover';
	$title = get_string('linkhover','theme_latrobe');
	$description = get_string('linkhoverdesc', 'theme_latrobe');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}