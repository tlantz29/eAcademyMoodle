<?php

// $Id: item_edit_form.php,v 1.2 2008/09/21 12:57:49 danielpr Exp $

require_once $CFG->libdir . '/formslib.php';
//require_once $CFG->libdir . '/filelib.php';

class block_exaport_comment_edit_form extends moodleform {

    function definition() {
        global $CFG, $USER, $DB;
        $mform = & $this->_form;

        $this->_form->_attributes['action'] = $_SERVER['REQUEST_URI'];
        $mform->addElement('header', 'comment', get_string("addcomment", "block_exaport"));

        $mform->addElement('editor', 'entry', get_string("comment", "block_exaport"),null, array('rows' => 10));
        $mform->setType('entry', PARAM_TEXT);
        $mform->addRule('entry', get_string("commentshouldnotbeempty", "block_exaport"), 'required', null, 'client');
        //$mform->setHelpButton('entry', array('writing', 'richtext'), false, 'editorhelpbutton');

        $this->add_action_buttons(false, get_string('add'));

        $mform->addElement('hidden', 'action');
        $mform->setType('action', PARAM_ACTION);
        $mform->setDefault('action', 'add');

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'itemid');
        $mform->setType('itemid', PARAM_INT);
        $mform->setDefault('itemid', 0);

        $mform->addElement('hidden', 'userid');
        $mform->setType('userid', PARAM_INT);
        $mform->setDefault('userid', 0);
    }

}

class block_exaport_item_edit_form extends moodleform {

    function definition() {
        global $CFG, $USER, $DB;

        $type = $this->_customdata['type'];

        $mform = & $this->_form;

        $mform->addElement('header', 'general', get_string($type, "block_exaport"));

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', 0);

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('hidden', 'activityid');
        $mform->setType('activityid', PARAM_INT);
        // wird f�r das formular beim moodle import ben�tigt
        $mform->addElement('hidden', 'submissionid');
        $mform->setType('submissionid', PARAM_INT);

        $mform->addElement('hidden', 'action');
        $mform->setType('action', PARAM_ACTION);
        $mform->setDefault('action', '');

        $mform->addElement('hidden', 'compids');
        $mform->setType('compids', PARAM_TEXT);
        $mform->setDefault('compids','');

        $mform->addElement('text', 'name', get_string("title", "block_exaport"), 'maxlength="255" size="60"');
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string("titlenotemtpy", "block_exaport"), 'required', null, 'client');

        $mform->addElement('select', 'categoryid', get_string("category", "block_exaport"), array());
        $mform->addRule('categoryid', get_string("categorynotempty", "block_exaport"), 'required', null, 'client');
        $mform->setDefault('categoryid', 0);
        $this->category_select_setup();

        if ($type == 'link') {
            $mform->addElement('text', 'url', get_string("url", "block_exaport"), 'maxlength="255" size="60"');
            $mform->setType('url', PARAM_TEXT);
            $mform->addRule('url', get_string("urlnotempty", "block_exaport"), 'required', null, 'client');
        } elseif ($type == 'file') {
            if ($this->_customdata['action'] == 'add') {
                //$this->set_upload_manager(new upload_manager('attachment', true, false, $this->_customdata['course'], false, 0, true, true, false));
                //$mform->addElement('file', 'attachment', get_string("file", "block_exaport"));
                //$mform->addElement('filemanager', 'attachment', get_string('file', 'block_exaport'), null,
                //    array('subdirs' => true, 'maxfiles' => 50 ));

                $mform->addElement('filepicker', 'attachment', get_string('file', 'block_exaport'), null, null);
            } else {
                // filename for assignment import
                $mform->addElement('hidden', 'filename');
                $mform->setType('filename', PARAM_TEXT);
                $mform->setDefault('filename', '');
            }
        }

        //$mform->addElement('editor', 'intro', get_string("intro", "block_exaport"), array('rows' => 25));
        $mform->addElement('editor', 'intro', get_string('intro', 'block_exaport'), null,
                    array('maxfiles' => EDITOR_UNLIMITED_FILES));
        $mform->setType('intro', PARAM_TEXT);
        //$mform->setHelpButton('intro', array('writing', 'richtext'), false, 'editorhelpbutton');
        if ($type == 'note')
            $mform->addRule('intro', get_string("intronotempty", "block_exaport"), 'required', null, 'client');

        //$mform->addElement('format', 'format', get_string('format'));

        $this->add_action_buttons();
    }

    function category_select_setup() {
        global $CFG, $USER, $DB;
        $mform = & $this->_form;
        $categorysselect = & $mform->getElement('categoryid');
        $categorysselect->removeOptions();

        $conditions = array("userid" => $USER->id, "pid" => 0);
        $outercategories = $DB->get_records_select("block_exaportcate", "userid = ? AND pid = ?", $conditions, "name asc");
        $categories = array();
        if ($outercategories) {
            foreach ($outercategories as $curcategory) {
                $categories[$curcategory->id] = format_string($curcategory->name);

                $conditions = array("userid" => $USER->id, "pid" => $curcategory->id);
                $inner_categories = $DB->get_records_select("block_exaportcate", "userid = ? AND pid = ?", $conditions, "name asc");
                if ($inner_categories) {
                    foreach ($inner_categories as $inner_curcategory) {
                        $categories[$inner_curcategory->id] = format_string($curcategory->name) . '&rArr; ' . format_string($inner_curcategory->name);
                    }
                }
            }
        } else {
            $categories[0] = get_string("nocategories", "block_exaport");
        }
        $categorysselect->loadArray($categories);
    }

}
