<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_bssd/logo';
	$title = get_string('logo','theme_bssd');
	$description = get_string('logodesc', 'theme_bssd');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_bssd/headercolor';
	$title = get_string('headercolor','theme_bssd');
	$description = get_string('headercolordesc', 'theme_bssd');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_bssd/linkcolor';
	$title = get_string('linkcolor','theme_bssd');
	$description = get_string('linkcolordesc', 'theme_bssd');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_bssd/linkhover';
	$title = get_string('linkhover','theme_bssd');
	$description = get_string('linkhoverdesc', 'theme_bssd');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}