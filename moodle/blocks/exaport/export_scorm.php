<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2006 exabis internet solutions <info@exabis.at>
 *  All rights reserved
 *
 *  You can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This module is based on the Collaborative Moodle Modules from
 *  NCSA Education Division (http://www.ncsa.uiuc.edu)
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

require_once dirname(__FILE__) . '/inc.php';
require_once dirname(__FILE__) . '/lib/minixml.inc.php';
global $DB;


$courseid = optional_param("courseid", 0, PARAM_INT);
$confirm = optional_param("confirm", 0, PARAM_INT);
$viewid = optional_param("viewid", 0, PARAM_INT);
$identifier = 1000000; // Item identifier
$ridentifier = 1000000; // Ressource identifier

$context = get_context_instance(CONTEXT_SYSTEM);

require_login($courseid);
require_capability('block/exaport:use', $context);
require_capability('block/exaport:export', $context);

$conditions = array("id" => $courseid);
if (!$course = $DB->get_record("course", $conditions)) {
    error("That's an invalid course id");
}
$url = '/blocks/exabis_competences/export_scorm.php';
$PAGE->set_url($url);
block_exaport_print_header("exportimportexport");

if (!defined('FILE_APPEND')) {
    define('FILE_APPEND', 1);
}
if (!function_exists('file_put_contents')) {

    function file_put_contents($n, $d, $flag = false) {
        $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
        $f = @fopen($n, $mode);
        if ($f === false) {
            return 0;
        } else {
            if (is_array($d))
                $d = implode($d);
            $bytes_written = fwrite($f, $d);
            fclose($f);
            return $bytes_written;
        }
    }

}

function spch($text) {
    return htmlentities($text, ENT_QUOTES, "UTF-8");
}

function get_hash($itemid) {
    global $DB;

    if ($file_record = $DB->get_record_sql("select min(id), pathnamehash from {files} where itemid={$itemid} AND filename!='.' GROUP BY id, pathnamehash")) {
        return $file_record->pathnamehash;
    } else {
        return false;
    }
}

function spch_text($text) {
    $text = htmlentities($text, ENT_QUOTES, "UTF-8");
    $text = str_replace('&amp;', '&', $text);
    $text = str_replace('&lt;', '<', $text);
    $text = str_replace('&gt;', '>', $text);
    $text = str_replace('&quot;', '"', $text);
    return $text;
}

function titlespch($text) {
    return clean_param($text, PARAM_ALPHANUM);
}

function create_ressource(&$resources, $ridentifier, $filename) {
    // at an external ressource no file is needed inside resource
    $resource = & $resources->createChild('resource');
    $resource->attribute('identifier', $ridentifier);
    $resource->attribute('type', 'webcontent');
    $resource->attribute('adlcp:scormtype', 'asset');
    $resource->attribute('href', $filename);
    $file = & $resource->createChild('file');
    $file->attribute('href', $filename);
    return true;
}

function &create_item(&$pitem, $identifier, $titletext, $residentifier = '') {
    // at an external ressource no file is needed inside resource
    $item = & $pitem->createChild('item');
    $item->attribute('identifier', $identifier);
    $item->attribute('isvisible', 'true');
    if ($residentifier != '') {
        $item->attribute('identifierref', $residentifier);
    }
    $title = & $item->createChild('title');
    $title->text($titletext);
    return $item;
}

function export_file_area_name() {
    global $USER;
    return "exaport/temp/export/{$USER->id}";
}

function export_data_file_area_name() {
    global $USER;
    return "exaport/temp/exportdata/{$USER->id}";
}

function add_comments($table, $bookmarkid) {
    global $DB;

    $commentsContent = '';
    $conditions = array("itemid" => $bookmarkid);
    $comments = $DB->get_records($table, $conditions);
    $i = 1;
    if ($comments) {
        foreach ($comments as $comment) {
            $conditions = array("id" => $comment->userid);
            $user = $DB->get_record('user', $conditions);

            $commentsContent .= '
			<div id="comment">
				<div id="author"><!--###BOOKMARK_COMMENT(' . $i . ')_AUTHOR###-->' . fullname($user, $comment->userid) . '<!--###BOOKMARK_COMMENT(' . $i . ')_AUTHOR###--></div>
				<div id="date"><!--###BOOKMARK_COMMENT(' . $i . ')_TIME###-->' . userdate($comment->timemodified) . '<!--###BOOKMARK_COMMENT(' . $i . ')_TIME###--></div>
				<div id="content"><!--###BOOKMARK_COMMENT(' . $i . ')_CONTENT###-->' . spch_text($comment->entry) . '<!--###BOOKMARK_COMMENT(' . $i . ')_CONTENT###--></div>
			</div>';
            $i++;
        }
    }
    return $commentsContent;
}

function get_category_items($categoryid, $viewid=null, $type=null) {
    global $USER, $CFG, $DB;

    $itemQuery = "select i.*" .
            " FROM {block_exaportitem} AS i" .
            ($viewid ? " JOIN {block_exaportviewblock} AS vb ON vb.type='item' AND vb.viewid=" . $viewid . " AND vb.itemid=i.id" : '') .
            " where i.userid = $USER->id" .
            ($type ? " and i.type='$type'" : '') .
            " and i.categoryid = $categoryid" .
            " order by i.name desc";

    return $DB->get_records_sql($itemQuery);
}

function get_category_files($categoryid, $viewid=null, $type='file') {
    global $USER, $CFG, $DB;

    $itemQuery = "select i.*, f.filename AS filename, f.id, f.itemid" .
            " FROM {block_exaportitem} AS i" .
            ($viewid ? " JOIN {block_exaportviewblock} AS vb ON vb.type='item' AND vb.viewid=" . $viewid . " AND vb.itemid=i.id" : '') .
            " JOIN {files} AS f ON f.itemid = i.attachment" .
            " where i.userid = $USER->id" .
            ($type ? " and i.type='$type'" : '') .
            " and i.categoryid = $categoryid" .
			" and f.filename != '.' " .
            " order by i.name desc";

    return $DB->get_records_sql($itemQuery);
}

function get_category_content(&$xmlElement, &$resources, $id, $name, $exportpath, $export_dir, $identifier, &$ridentifier, $viewid) {
    global $USER, $CFG, $COURSE, $DB;

    $bookmarks = get_category_items($id, $viewid, 'link');

    $hasItems = false;

    if ($bookmarks) {
        $hasItems = true;
        foreach ($bookmarks as $bookmark) {
            unset($filecontent);
            unset($filename);

            $filecontent = '';
            $filecontent .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
            $filecontent .= '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
            $filecontent .= '<head>' . "\n";
            $filecontent .= '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n";
            $filecontent .= '  <title>' . spch(format_string($bookmark->name)) . '</title>' . "\n";
            $filecontent .= '<!-- ' . get_string("exportcomment", "block_exaport") . ' -->';
            $filecontent .= '</head>' . "\n";
            $filecontent .= '<body>' . "\n";
            $filecontent .= '  <h1 id="header">' . spch(format_string($bookmark->name)) . '</h1>' . "\n";
            $filecontent .= '  <div id="url"><a href="' . spch($bookmark->url) . '"><!--###BOOKMARK_EXT_URL###-->' . spch($bookmark->url) . '<!--###BOOKMARK_EXT_URL###--></a></div>' . "\n";
            $filecontent .= '  <div id="description"><!--###BOOKMARK_EXT_DESC###-->' . spch_text($bookmark->intro) . '<!--###BOOKMARK_EXT_DESC###--></div>' . "\n";
            $filecontent .= add_comments('block_exaportitemcomm', $bookmark->id);
            $filecontent .= '</body>' . "\n";
            $filecontent .= '</html>' . "\n";

            $filename = clean_param($bookmark->name, PARAM_ALPHANUM);
            $ext = ".html";
            $i = 0;
            if ($filename == "")
                $filepath = $export_dir . $filename . $i . $ext;
            else
                $filepath = $export_dir . $filename . $ext;
            if (is_file($exportpath . $filepath) || is_dir($exportpath . $filepath) || is_link($exportpath . $filepath)) {
                do {
                    $i++;
                    $filepath = $export_dir . $filename . $i . $ext;
                } while (is_file($exportpath . $filepath) || is_dir($exportpath . $filepath) || is_link($exportpath . $filepath));
            }

            file_put_contents($exportpath . $filepath, $filecontent);
            create_ressource($resources, 'RES-' . $ridentifier, $filepath);
            create_item($xmlElement, 'ITEM-' . $identifier, $bookmark->name, 'RES-' . $ridentifier);
            $identifier++;
            $ridentifier++;
        }
    }

    $files = get_category_files($id, $viewid, 'file');
    //!!
    if ($files) {
        $fs = get_file_storage();
        $hasItems = true;
        foreach ($files as $file) {
            unset($filecontent);
            unset($filename);

            $i = 0;
            $content_filename = $file->filename;
            if (is_file($exportpath . $export_dir . $content_filename) || is_dir($exportpath . $export_dir . $content_filename) || is_link($exportpath . $export_dir . $content_filename)) {
                do {
                    $i++;
                    $content_filename = $i . $file->filename;
                } while (is_file($exportpath . $export_dir . $content_filename) || is_dir($exportpath . $export_dir . $content_filename) || is_link($exportpath . $export_dir . $content_filename));
            }

            if ($file->attachment != '') {
                $hash = get_hash($file->attachment);

                $att = $fs->get_file_by_hash($hash);
                $test = $att->copy_content_to($exportpath . $export_dir . $content_filename);

                //copy($CFG->dataroot . "/" . block_exaport_file_area_name($file) . "/" . $file->attachment, $exportpath.$export_dir.$content_filename);
            }

            $filecontent = '';
            $filecontent .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
            $filecontent .= '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
            $filecontent .= '<head>' . "\n";
            $filecontent .= '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n";
            $filecontent .= '  <title>' . spch($file->name) . '</title>' . "\n";
            $filecontent .= '<!-- ' . get_string("exportcomment", "block_exaport") . ' -->';
            $filecontent .= '</head>' . "\n";
            $filecontent .= '<body>' . "\n";
            $filecontent .= '  <h1 id="header">' . spch($file->name) . '</h1>' . "\n";
            $filecontent .= '  <div id="url"><a href="' . spch($content_filename) . '"><!--###BOOKMARK_FILE_URL###-->' . spch($content_filename) . '<!--###BOOKMARK_FILE_URL###--></a></div>' . "\n";
            $filecontent .= '  <div id="description"><!--###BOOKMARK_FILE_DESC###-->' . spch_text($file->intro) . '<!--###BOOKMARK_FILE_DESC###--></div>' . "\n";
            $item = $DB->get_record('block_exaportitem',array("userid"=>$USER->id,"attachment"=>$file->itemid));
            $filecontent .= add_comments('block_exaportitemcomm', $item->id);
            $filecontent .= '</body>' . "\n";
            $filecontent .= '</html>' . "\n";

            $filename = clean_param($file->name, PARAM_ALPHANUM);
            $ext = ".html";
            $i = 0;
            if ($filename == "")
                $filepath = $export_dir . $filename . $i . $ext;
            else
                $filepath = $export_dir . $filename . $ext;
            if (is_file($exportpath . $filepath) || is_dir($exportpath . $filepath) || is_link($exportpath . $filepath)) {
                do {
                    $i++;
                    $filepath = $export_dir . $filename . $i . $ext;
                } while (is_file($exportpath . $filepath) || is_dir($exportpath . $filepath) || is_link($exportpath . $filepath));
            }
            file_put_contents($exportpath . $filepath, $filecontent);
            create_ressource($resources, 'RES-' . $ridentifier, $filepath);
            create_item($xmlElement, 'ITEM-' . $identifier, $file->name, 'RES-' . $ridentifier);
            $identifier++;
            $ridentifier++;
        }
    }

    $notes = get_category_items($id, $viewid, 'note');

    if ($notes) {
        $hasItems = true;
        foreach ($notes as $note) {
            unset($filecontent);
            unset($filename);

            $filecontent = '';
            $filecontent .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
            $filecontent .= '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
            $filecontent .= '<head>' . "\n";
            $filecontent .= '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n";
            $filecontent .= '  <title>' . spch($note->name) . '</title>' . "\n";
            $filecontent .= '<!-- ' . get_string("exportcomment", "block_exaport") . ' -->';
            $filecontent .= '</head>' . "\n";
            $filecontent .= '<body>' . "\n";
            $filecontent .= '  <h1 id="header">' . spch($note->name) . '</h1>' . "\n";
            $filecontent .= '  <div id="description"><!--###BOOKMARK_NOTE_DESC###-->' . spch_text($note->intro) . '<!--###BOOKMARK_NOTE_DESC###--></div>' . "\n";
            $filecontent .= add_comments('block_exaportitemcomm', $note->id);
            $filecontent .= '</body>' . "\n";
            $filecontent .= '</html>' . "\n";

            $filename = clean_param($note->name, PARAM_ALPHANUM);
            $ext = ".html";
            $i = 0;
            if ($filename == "")
                $filepath = $export_dir . $filename . $i . $ext;
            else
                $filepath = $export_dir . $filename . $ext;
            if (is_file($exportpath . $filepath) || is_dir($exportpath . $filepath) || is_link($exportpath . $filepath)) {
                do {
                    $i++;
                    $filepath = $export_dir . $filename . $i . $ext;
                } while (is_file($exportpath . $filepath) || is_dir($exportpath . $filepath) || is_link($exportpath . $filepath));
            }
            file_put_contents($exportpath . $filepath, $filecontent);
            create_ressource($resources, 'RES-' . $ridentifier, $filepath);
            create_item($xmlElement, 'ITEM-' . $identifier, $note->name, 'RES-' . $ridentifier);
            $identifier++;
            $ridentifier++;
        }
    }

    return $hasItems;
}

if ($confirm) {
    if (!confirm_sesskey()) {
        error('Bad Session Key');
    }

    if (!($exportdir = make_upload_directory(export_data_file_area_name()))) {
        error("Could not create temporary folder!");
    }

    // Delete everything inside
    remove_dir($exportdir, true);

    // Put a / on the end
    if (substr($exportdir, -1) != "/")
        $exportdir .= "/";


    // Create directory for data files
    $export_data_dir = $exportdir . "data";
    // Create directory for data files:
    mkdir($export_data_dir);
    if (substr($export_data_dir, -1) != "/")
        $export_data_dir .= "/";

    $sourcefiles = array();
    $sourcefiles[] = $export_data_dir;

    // copy all necessary files:
    copy("files/adlcp_rootv1p2.xsd", $exportdir . "adlcp_rootv1p2.xsd");
    $sourcefiles[] = $exportdir . "adlcp_rootv1p2.xsd";
    copy("files/ims_xml.xsd", $exportdir . "ims_xml.xsd");
    $sourcefiles[] = $exportdir . "ims_xml.xsd";
    copy("files/imscp_rootv1p1p2.xsd", $exportdir . "imscp_rootv1p1p2.xsd");
    $sourcefiles[] = $exportdir . "imscp_rootv1p1p2.xsd";
    copy("files/imsmd_rootv1p2p1.xsd", $exportdir . "imsmd_rootv1p2p1.xsd");
    $sourcefiles[] = $exportdir . "imsmd_rootv1p2p1.xsd";

    $parsedDoc = new MiniXMLDoc();

    $xmlRoot = & $parsedDoc->getRoot();

    // Root-Element MANIFEST
    $manifest = & $xmlRoot->createChild('manifest');
    $manifest->attribute('identifier', $USER->username . 'Export');
    $manifest->attribute('version', '1.1');
    $manifest->attribute('xmlns', 'http://www.imsproject.org/xsd/imscp_rootv1p1p2');
    $manifest->attribute('xmlns:adlcp', 'http://www.adlnet.org/xsd/adlcp_rootv1p2');
    $manifest->attribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    $manifest->attribute('xsi:schemaLocation', 'http://www.imsproject.org/xsd/imscp_rootv1p1p2 imscp_rootv1p1p2.xsd
					  http://www.imsglobal.org/xsd/imsmd_rootv1p2p1 imsmd_rootv1p2p1.xsd
					  http://www.adlnet.org/xsd/adlcp_rootv1p2 adlcp_rootv1p2.xsd');

    // Our Organizations
    $organizations = & $manifest->createChild('organizations');
    $organizations->attribute('default', 'DATA');

    // Our organization for the export structure
    $desc_organization = & $organizations->createChild('organization');
    $desc_organization->attribute('identifier', 'DATA');

    $title = & $desc_organization->createChild('title');
    $title->text(get_string("personal", "block_exaport"));

    // Our organization for the export structure
    $organization = & $organizations->createChild('organization');
    $organization->attribute('identifier', 'PORTFOLIO');

    // Our resources
    $resources = & $manifest->createChild('resources');

    // Root entry in organization
    $title = & $organization->createChild('title');
    $title->text(get_string("mybookmarks", "block_exaport"));

    $userdescriptions = $DB->get_records_select("block_exaportuser", "user_id = '$USER->id'");

    $description = '';
    if ($userdescriptions) {
        foreach ($userdescriptions as $userdescription) {
            $description = $userdescription->description;
        }
    }

    $filecontent = '';
    $filecontent .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
    $filecontent .= '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
    $filecontent .= '<head>' . "\n";
    $filecontent .= '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n";
    $filecontent .= '  <title>' . spch(fullname($USER, $USER->id)) . '</title>' . "\n";
    $filecontent .= '<!-- ' . get_string("exportcomment", "block_exaport") . ' -->';
    $filecontent .= '</head>' . "\n";
    $filecontent .= '<body>' . "\n";
    $filecontent .= '  <h1 id="header">' . spch(fullname($USER, $USER->id)) . '</h1>' . "\n";
    $filecontent .= '  <div id="description"><!--###BOOKMARK_PERSONAL_DESC###-->' . spch_text($description) . '<!--###BOOKMARK_PERSONAL_DESC###--></div>' . "\n";
    $filecontent .= '</body>' . "\n";
    $filecontent .= '</html>' . "\n";

    $filename = clean_param(fullname($USER, $USER->id), PARAM_ALPHANUM);
    $ext = ".html";
    $i = 0;
    if ($filename == "")
        $filepath = 'data/' . $filename . $i . $ext;
    else
        $filepath = 'data/' . $filename . $ext;
    if (is_file($exportdir . $filepath) || is_dir($exportdir . $filepath) || is_link($exportdir . $filepath)) {
        do {
            $i++;
            $filepath = 'data/' . $filename . $i . $ext;
        } while (is_file($exportdir . $filepath) || is_dir($exportdir . $filepath) || is_link($exportdir . $filepath));
    }

    file_put_contents($exportdir . $filepath, $filecontent);
    create_ressource($resources, 'RES-' . $ridentifier, $filepath);
    create_item($desc_organization, 'ITEM-' . $identifier, fullname($USER, $USER->id), 'RES-' . $ridentifier);
    $identifier++;
    $ridentifier++;

    //echo '<div class="block_exaport_export">';
    //echo "<h3>" . get_string("categories","block_exaport") . "</h3>";
    $owncats = $DB->get_records_select("block_exaportcate", "userid=$USER->id AND pid=0", null, "name ASC");
    $i = 0;
    if ($owncats) {
        foreach ($owncats as $owncat) {
            unset($item);
            $i++;

            $item = & $parsedDoc->createElement('item');
            $item->attribute('identifier', sprintf('B%04d', $i));
            $item->attribute('isvisible', 'true');
            $itemtitle = & $item->createChild('title');
            $itemtitle->text($owncat->name);

            // get everything inside this category:
            $mainNotEmpty = get_category_content($item, $resources, $owncat->id, $owncat->name, $exportdir, 'data/', $identifier, $ridentifier, $viewid);

            $innerowncats = $DB->get_records_select("block_exaportcate", "userid=$USER->id AND pid='$owncat->id'", null, "name ASC");
            if ($innerowncats) {
                foreach ($innerowncats as $innerowncat) {
                    unset($subitem);
                    $i++;

                    $subitem = & $parsedDoc->createElement('item');
                    $subitem->attribute('identifier', sprintf('B%04d', $i));
                    $subitem->attribute('isvisible', 'true');
                    $subitemtitle = & $subitem->createChild('title');
                    $subitemtitle->text($innerowncat->name);

                    $subNotEmpty = get_category_content($subitem, $resources, $innerowncat->id, $innerowncat->name, $exportdir, 'data/', $identifier, $ridentifier, $viewid);

                    if ($subNotEmpty) {
                        // if the subcategory is not empty:
                        //	-> append it to the maincategory
                        //  -> set the main category as not empty
                        $item->appendChild($subitem);
                        $mainNotEmpty = true;
                    }
                }
            }

            if ($mainNotEmpty) {
                // if the main category is not empty, append it to the xml-file
                $organization->appendChild($item);
            }
        }
    }

    // if there's need for metadata, put it in:
    //$metadata =& $organization->createChild('metadata');
    //$schema =& $metadata->createChild('schema');
    //$schema->text('ADL SCORM');
    //$schemaversion =& $metadata->createChild('schemaversion');
    //$schemaversion->text('1.2');
    // echo $parsedDoc->toString(); exit;

    if (file_put_contents($exportdir . 'imsmanifest.xml', $parsedDoc->toString(MINIXML_NOWHITESPACES)) === false) {
        error("Writing imsmanifest.xml failed!");
        exit();
    }
    $sourcefiles[] = $exportdir . "imsmanifest.xml";

    // create directory for the zip-file:
    if (!($zipdir = make_upload_directory(export_file_area_name()))) {
        error(get_string("couldntcreatetempdir", "block_exaport"));
        exit();
    }

    // Delete everything inside
    remove_dir($zipdir, true);

    // Put a / on the end
    if (substr($zipdir, -1) != "/")
        $zipdir .= "/";

    $zipname = clean_param($USER->username, PARAM_ALPHANUM) . strftime("_%Y_%m_%d_%H%M") . ".zip";

    // zip all the files:
    zip_files($sourcefiles, $zipdir . $zipname);

    remove_dir($exportdir);

    echo '<div class="block_eportfolio_center">';
    echo $OUTPUT->box_start();

    echo '<input type="submit" name="export" value="' . get_string("download", "block_exaport") . '"';
    echo ' onclick="window.open(\'' . $CFG->wwwroot . '/blocks/exaport/portfoliofile.php/' . export_file_area_name() . '/' . $zipname . '\');"/>';
    echo '</div>';

    echo $OUTPUT->box_end();
    $OUTPUT->footer($course);
    exit;
}

echo "<br />";
echo '<div class="block_eportfolio_center">';

$views = $DB->get_records('block_exaportview', array('userid' => $USER->id), 'name');

echo $OUTPUT->box_start();

echo '<p>' . get_string("explainexport", "block_exaport") . '</p>';
echo '<form method="post" class="block_eportfolio_center" action="' . $_SERVER['PHP_SELF'] . '" >';
echo '<fieldset>';

// views
if (block_exaport_feature_enabled('views')) {
    echo '<div style="padding-bottom: 15px;">' . get_string("exportviewselect", "block_exaport") . ': ';
    echo '<select name="viewid">';
    echo '<option></option>';
    foreach ($views as $view) {
        echo '<option value="' . $view->id . '">' . $view->name . '</option>';
    }
    echo '</select>';
    echo ' </div>';
}

echo '<input type="hidden" name="confirm" value="1" />';
echo '<input type="submit" name="export" value="' . get_string("createexport", "block_exaport") . '" />';
echo '<input type="hidden" name="sesskey" value="' . sesskey() . '" />';
echo '<input type="hidden" name="courseid" value="' . $courseid . '" />';
echo '</fieldset>';
echo '</form>';
echo '</div>';

echo $OUTPUT->box_end();
echo $OUTPUT->footer($course);
