<?php

require_once($CFG->dirroot.'/user/filters/lib.php');

if (!defined('MAX_BULK_USERS')) {
    define('MAX_BULK_USERS', 2000);
}
/*
 * in this page function with custom extention are used only for sis
 */
function add_selection_all($ufiltering) {
    global $SESSION, $DB, $CFG;

    list($sqlwhere, $params) = $ufiltering->get_sql_filter("id<>:exguest AND deleted <> 1", array('exguest'=>$CFG->siteguest));

    $rs = $DB->get_recordset_select('user', $sqlwhere, $params, 'fullname', 'id,'.$DB->sql_fullname().' AS fullname');
    foreach ($rs as $user) {
        if (!isset($SESSION->bulk_users[$user->id])) {
            $SESSION->bulk_users[$user->id] = $user->id;
        }
    }
    $rs->close();
}

function get_selection_data($ufiltering) {
    global $SESSION, $DB, $CFG;
    #---------------------------groupwise user filter for sis_manager--------------------------------#
    include('datalib.php');
    $school_id=get_group_id();
        if($school_id!=false){
            $group_id_filter = " AND gm.groupid=$school_id";  }
         else
             $group_id_filter="";
    #--------------------------groupwise user filter for sis_manager---------------------------------#
    // get the SQL filter
    list($sqlwhere, $params) = $ufiltering->get_sql_filter("u.id<>:exguest AND u.deleted <> 1 ".$group_id_filter, array('exguest'=>$CFG->siteguest));
//print_r($params);exit;
     #--------------------------groupwise user filter for sis_manager---------------------------------#
    $total  = $DB->count_records_select_custom('{user} u LEFT JOIN {groups_members} gm
                                   ON u.id = gm.userid ', "u.id<>:exguest AND u.deleted <> 1 ".$group_id_filter, array('exguest'=>$CFG->siteguest));
 #--------------------------groupwise user filter for sis_manager---------------------------------#
//    print_r($total);exit;
     #--------------------------groupwise user filter for sis_manager---------------------------------#
    $acount = $DB->count_records_select_custom('{user} u LEFT JOIN {groups_members} gm
                                   ON u.id = gm.userid ', $sqlwhere, $params);
     #--------------------------groupwise user filter for sis_manager---------------------------------#
//    print_r($acount);exit;
    $scount = count($SESSION->bulk_users);

    $userlist = array('acount'=>$acount, 'scount'=>$scount, 'ausers'=>false, 'susers'=>false, 'total'=>$total);
     #--------------------------groupwise user filter for sis_manager---------------------------------#
    $userlist['ausers'] = $DB->get_records_select_menu_custom('{user} u LEFT JOIN {groups_members} gm
                                   ON u.id = gm.userid ', $sqlwhere, $params, 'fullname', 'u.id,'.$DB->sql_fullname($first='u.firstname', $last='u.lastname').' AS fullname', 0, MAX_BULK_USERS);
 #--------------------------groupwise user filter for sis_manager---------------------------------#
//print_r($userlist['ausers']);exit;
    if ($scount) {
        if ($scount < MAX_BULK_USERS) {
            $in = implode(',', $SESSION->bulk_users);
        } else {
            $bulkusers = array_slice($SESSION->bulk_users, 0, MAX_BULK_USERS, true);
            $in = implode(',', $bulkusers);
        }
        #---------------------------groupwise user filter for sis_manager--------------------------------#
        $userlist['susers'] = $DB->get_records_select_menu_custom('{user} u LEFT JOIN {groups_members} gm
                                   ON u.id = gm.userid ', "u.id IN ($in)", null, 'fullname', 'u.id,'.$DB->sql_fullname($first='u.firstname', $last='u.lastname').' AS fullname');
   #---------------------------groupwise user filter for sis_manager--------------------------------#
        }

    return $userlist;
}
