<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_franklin/logo';
	$title = get_string('logo','theme_franklin');
	$description = get_string('logodesc', 'theme_franklin');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_franklin/headercolor';
	$title = get_string('headercolor','theme_franklin');
	$description = get_string('headercolordesc', 'theme_franklin');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_franklin/linkcolor';
	$title = get_string('linkcolor','theme_franklin');
	$description = get_string('linkcolordesc', 'theme_franklin');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_franklin/linkhover';
	$title = get_string('linkhover','theme_franklin');
	$description = get_string('linkhoverdesc', 'theme_franklin');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}