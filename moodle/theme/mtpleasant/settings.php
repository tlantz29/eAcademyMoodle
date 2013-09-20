<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_mtpleasant/logo';
	$title = get_string('logo','theme_mtpleasant');
	$description = get_string('logodesc', 'theme_mtpleasant');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_mtpleasant/headercolor';
	$title = get_string('headercolor','theme_mtpleasant');
	$description = get_string('headercolordesc', 'theme_mtpleasant');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_mtpleasant/linkcolor';
	$title = get_string('linkcolor','theme_mtpleasant');
	$description = get_string('linkcolordesc', 'theme_mtpleasant');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_mtpleasant/linkhover';
	$title = get_string('linkhover','theme_mtpleasant');
	$description = get_string('linkhoverdesc', 'theme_mtpleasant');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}