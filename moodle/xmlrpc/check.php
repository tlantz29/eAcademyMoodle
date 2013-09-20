<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function check($userid)
{
    global $DB;
    global $CFG;
    $userid=$userid;
    $roleid=5;
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $date1=$year."-".$month."-".$day;
    $timeStamp = mktime(18,30,0,$month,$day,$year);
    $current=$timeStamp;
  
    /*************************************FOR SCHEDULING********************************************/
    $select="sis_periodid<>0";
    $data = $DB->get_records_select('course',$select);
    
    foreach ($data as $id => $value)
        {
            $select="contextlevel='50' AND instanceid=$id";
            $contextids=$DB->get_field_select('context', id, $select).",";
       
            $sql1="SELECT e.id FROM {$CFG->prefix}enrol e WHERE e.courseid=$id AND e.enrol='manual'";
            $enrolid=$DB->get_record_sql($sql1);
            $user->userid=$userid;
            $ra = $DB->get_record('role_assignments', array('contextid'=>$contextids->id,'userid'=>$user->userid,'roleid'=>3), '*', IGNORE_MISSING);
            $selectue="enrolid=$enrolid->id AND userid=$user->userid AND timeend<=$current AND timeend!='0'";
            $ue = $DB->get_record_select('user_enrolments',$selectue );
           
            if($ue->id){
            $val=$DB->delete_records('role_assignments',array('id'=>$ra->id));
            $val1=$DB->delete_records('user_enrolments',array('id'=>$ue->id));
            }
        }

}

/*************************************************FOR STUDENT DROP***********************************************/
function student($username)
{
    global $DB;
    $username=$username;
    $query_info['username'] =$username;
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $date1=$year."-".$month."-".$day;
    $query_info['end_date'] = $date1;
    define("XMLRPC_DEBUG", 1);
    require_once ("Set_Site.php");
    list($success, $response) = XMLRPC_request(
                                                $site,
                                                $location,
                                                'studentdrop',
                                                array(XMLRPC_prepare($query_info)));
    if($response)
        {
          global $CFG;
          $data = new stdClass();
          $data1 = new stdClass();
          $data2=new stdClass();
          $sql = "SELECT u.id,u.username,u.deleted,u.email FROM {$CFG->prefix}user u WHERE u.username = '".$response['username']."'";
          $record =$DB->get_record_sql($sql);
          $data->id=$record->id;
          $data->username=$record->username;
          $data->deleted=1;
          $data->email=$record->email;
          $new=$DB->update_record('user', $data);
                              $data1->userid=$record->id;
                              $data1->roleid=5;
                              $select="roleid=$data1->roleid AND userid=$data1->userid";
                              $results=$DB->get_records_select('role_assignments ', $select);
                      foreach ($results as $key => $value)
                          {
                                  $data2->id=$key;
                                  $select2="id=$data2->id";
                                 $delete=$DB->delete_records_select('role_assignments', $select2);

        }

/**********************************************************************************************************/
    }
}

function userdrops()
{
    global $CFG;
    global $DB;
    $query_info=array(1,2);
    define("XMLRPC_DEBUG", 1);
    require_once ("Set_Site.php");
    list($success, $response) = XMLRPC_request(
                                                $site,
                                                $location,
                                                'studentsdrop',
                                                array(XMLRPC_prepare($query_info)));


      if($response){
            $fields=substr($response,0,-1);
            $field=explode(",",$fields);
            foreach ($field as $key => $value) {
                    
                    $sql = "SELECT u.id FROM {$CFG->prefix}user u WHERE u.username ='".$value."' ";
                    $record = $DB->get_record_sql($sql);
                    if($record){
                            $data->deleted=1;
                            $data->timemodified=time();
                            $data->username=$value;
                            $data->id=$record->id;
                            $rec=$DB->update_record('user', $data);
                    }
            }
      }

 }
?>
