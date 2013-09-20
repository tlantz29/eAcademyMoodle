<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

// logo image setting
	$name = 'theme_gbsalem/logo';
	$title = get_string('logo','theme_gbsalem');
	$description = get_string('logodesc', 'theme_gbsalem');
	$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
	$settings->add($setting);

	// link color setting
	$name = 'theme_gbsalem/headercolor';
	$title = get_string('headercolor','theme_gbsalem');
	$description = get_string('headercolordesc', 'theme_gbsalem');
	$default = '#E2472F';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
	
	// link color setting
	$name = 'theme_gbsalem/linkcolor';
	$title = get_string('linkcolor','theme_gbsalem');
	$description = get_string('linkcolordesc', 'theme_gbsalem');
	$default = '#0b4a5b';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);

	// link hover color setting
	$name = 'theme_gbsalem/linkhover';
	$title = get_string('linkhover','theme_gbsalem');
	$description = get_string('linkhoverdesc', 'theme_gbsalem');
	$default = '#666666';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}