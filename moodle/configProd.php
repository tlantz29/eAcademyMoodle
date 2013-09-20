<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodle_running_prod';
$CFG->dbuser    = 'root';
$CFG->dbpass    = '123456';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbsocket' => 0,
);

$CFG->wwwroot   = 'http://test-moodle.wiueacademy.org';
$CFG->dataroot  = 'D:\\server\\moodledata_running';
$CFG->admin     = 'admin';
$CFG->mnetkeylifetime     = 365;
$CFG->directorypermissions = 0777;

$CFG->passwordsaltmain = '0li~^`[@ QgH8Fba5P2^Ly vh@a2>';

require_once(dirname(__FILE__) . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
