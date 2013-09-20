<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_jeannette/logo';
	$title = get_string('logo','theme_jeannette');
	$description = get_string('logodesc', 'theme_jeannette');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_jeannette/headercolor';
	$title = get_string('headercolor','theme_jeannette');
	$description = get_string('headercolordesc', 'theme_jeannette');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_jeannette/linkcolor';
	$title = get_string('linkcolor','theme_jeannette');
	$description = get_string('linkcolordesc', 'theme_jeannette');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_jeannette/linkhover';
	$title = get_string('linkhover','theme_jeannette');
	$description = get_string('linkhoverdesc', 'theme_jeannette');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}