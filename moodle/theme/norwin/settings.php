<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_norwin/logo';
	$title = get_string('logo','theme_norwin');
	$description = get_string('logodesc', 'theme_norwin');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_norwin/headercolor';
	$title = get_string('headercolor','theme_norwin');
	$description = get_string('headercolordesc', 'theme_norwin');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_norwin/linkcolor';
	$title = get_string('linkcolor','theme_norwin');
	$description = get_string('linkcolordesc', 'theme_norwin');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_norwin/linkhover';
	$title = get_string('linkhover','theme_norwin');
	$description = get_string('linkhoverdesc', 'theme_norwin');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}