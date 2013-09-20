<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_ligonier/logo';
	$title = get_string('logo','theme_ligonier');
	$description = get_string('logodesc', 'theme_ligonier');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_ligonier/headercolor';
	$title = get_string('headercolor','theme_ligonier');
	$description = get_string('headercolordesc', 'theme_ligonier');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_ligonier/linkcolor';
	$title = get_string('linkcolor','theme_ligonier');
	$description = get_string('linkcolordesc', 'theme_ligonier');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_ligonier/linkhover';
	$title = get_string('linkhover','theme_ligonier');
	$description = get_string('linkhoverdesc', 'theme_ligonier');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}