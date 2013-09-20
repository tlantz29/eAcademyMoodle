<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_derry/logo';
	$title = get_string('logo','theme_derry');
	$description = get_string('logodesc', 'theme_derry');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_derry/headercolor';
	$title = get_string('headercolor','theme_derry');
	$description = get_string('headercolordesc', 'theme_derry');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_derry/linkcolor';
	$title = get_string('linkcolor','theme_derry');
	$description = get_string('linkcolordesc', 'theme_derry');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_derry/linkhover';
	$title = get_string('linkhover','theme_derry');
	$description = get_string('linkhoverdesc', 'theme_derry');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}