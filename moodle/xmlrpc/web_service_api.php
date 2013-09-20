<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
global $CFG;
global $id;
$xmlrpc_methods['subject']=subject;
$xmlrpc_methods['periods']=periods;
$xmlrpc_methods['course']=course;
$xmlrpc_methods['userid']=userid;
$xmlrpc_methods['subjectupdate']=subjectupdate;
$xmlrpc_methods['courseupdate']=courseupdate;
$xmlrpc_methods['periodsupdate']=periodsupdate;
$xmlrpc_methods['fetchsubject']=fetchsubject;
$xmlrpc_methods['fetchcourse']=fetchcourse;
$xmlrpc_methods['fetchperiod']=fetchperiod;
$xmlrpc_methods['updateschedule']=updateschedule;
$xmlrpc_methods['schedule']=schedule;
$xmlrpc_methods['massschedule']=massschedule;
$xmlrpc_methods['masssdrops']=masssdrops;
$xmlrpc_methods['delete_period']=delete_period;
$xmlrpc_methods['del_course']=del_course;
$xmlrpc_methods['del_subject']=del_subject;
$xmlrpc_methods['adduser']=adduser;
$xmlrpc_methods['updateuser']=updateuser;
$xmlrpc_methods['updateadmin']=updateadmin;
$xmlrpc_methods['updateteacher']=updateteacher;
$xmlrpc_methods['GetUsers']=GetUsers;
$xmlrpc_methods['grant_access']=grant_access;
$xmlrpc_methods['fetchuser']=fetchuser;
$xmlrpc_methods['active']=active;
$xmlrpc_methods['inactive']=inactive;
$xmlrpc_methods['fetchstudents']=fetchstudents;
$xmlrpc_methods['fetchstaff']=fetchstaff;
$xmlrpc_methods['fetchcourses']=fetchcourses;
$xmlrpc_methods['insert_sisid']=insert_sisid;
$xmlrpc_methods['schedule_stu']=schedule_stu;
$xmlrpc_methods['logout']=logout;
$xmlrpc_methods['usermnet']=usermnet;//add_subgroup_group
$xmlrpc_methods['add_group']=add_group;
$xmlrpc_methods['update_subgroup_group']=update_subgroup_group;
$xmlrpc_methods['add_subgroup_group']=add_subgroup_group;
$xmlrpc_methods['fetchassignments']=fetchassignments;
$xmlrpc_methods['fetchlessons']=fetchlessons;
$xmlrpc_methods['fetchquizes']=fetchquizes;
$xmlrpc_methods['fetchassignment_grades']=fetchassignment_grades;

function subject($query=0)
{
    global $DB,$CFG;
    $data = new stdClass();
    $data_course_group= new stdClass();
    $data->name =  $query['name'];
    $data->sis_courseid = $query['sisid'];
	$data->parent = $query['school_id'];
    $data->sortorder = 999;
    $data->timemodified=time();
	//$data->depth="3";
	
	//$path1 = $query['school_id'];
	//$path2 = $query['sisid'];
	//$dirtext = "/1/";
	//$data->path=$dirtext;
    $newcategory->id = $DB->insert_record('course_categories', $data);

     #---insert into course_group-------------#

    #----fetch course group-----------------#
    //$school_id=$query['school_id'];
     //$sql="SELECT g.id FROM {$CFG->prefix}groups g WHERE g.sis_school_id ='".$school_id."' ";
    //$group_id= $DB->get_records_sql($sql);

    //foreach($group_id as $group=>$value){
    // $group=$group;
	$newcategory->context = get_context_instance(CONTEXT_COURSECAT, $newcategory->id);
    mark_context_dirty($newcategory->context->path);
    fix_course_sortorder();

    XMLRPC_response(XMLRPC_prepare($newcategory->id), KD_XMLRPC_USERAGENT);
    }
    #----------------------------------------#
    //$data_course_group->course_id=$newcategory->id;
    //$data_course_group->group_id=$group;

     //$course_group_id = $DB->insert_record('course_group', $data_course_group);
     #---insert into course_group-------------#
    




function course($query=array())
{
    global $CFG;
    global $DB;
    $data = new stdClass();
    $data1 = new stdClass();
    $data_course_group= new stdClass();
    $data1->parent1=$query['parent1'];
    $data->name=$query['name'];
    $data->description=$query['description'];
    $data->sis_courseid=$query['id'];
    $data->sortorder = 999;
    $data->timemodified=time();
     $sql = "SELECT cc.id FROM {$CFG->prefix}course_categories cc WHERE cc.sis_courseid = $data1->parent1";
 

     $data->parent = $DB->get_field_sql($sql);

    $newcat->id = $DB->insert_record('course_categories', $data);

    #---insert into course_group-------------#
      #----fetch course group-----------------#
      //$school_id=$query['school_id'];
     //$sql="SELECT g.id FROM {$CFG->prefix}groups g WHERE g.sis_school_id ='".$school_id."' ";
     //$group_id= $DB->get_records_sql($sql);

    //foreach($group_id as $group=>$value){
    // $group=$group;
    //}
    #----------------------------------------#
    //$data_course_group->course_id=$newcat->id;
    //$data_course_group->group_id=$group;//$group; courese_group

     //$course_group_id = $DB->insert_record('course_group', $data_course_group);
     #---insert into course_group-------------#

    $newcat->context = get_context_instance(CONTEXT_COURSECAT, $newcat->id);
    mark_context_dirty($newcat->context->path);
    fix_course_sortorder(); // Required to build course_categories.depth and .path.

    XMLRPC_response(XMLRPC_prepare($newcat->id), KD_XMLRPC_USERAGENT);
}


function  periods($query=0)
{
    global $CFG;
    global $DB;
    require_once($CFG->dirroot.'/course/lib.php');
    $data = new stdClass();
    $data1 = new stdClass();
    $data2 = new stdClass();
    $data->category = $query['category'];
    $data->fullname=$query['fullname'];
    $data->shortname=$query['shortname'];
    $data->sis_periodid=$query['sisid'];
    $data->visible=1;
    $data->format='Topics';
    $data->numsections='36';
    $data->startdate=$query['startdate'];
    $data->enrollable='0';
    $data->enrolstartdate=$query['enrolstartdate'];
    //$data->enrolenddate= $query['enrolenddate'];
    $data->timemodified=time();
    $course = create_course($data);
    $context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);
    foreach($query['username'] as $key=> $value)
        {
              
            $data2->username=$value;
            if(($data2->username)!="")
            $sql = "SELECT s.id FROM {$CFG->prefix}user s WHERE s.username = '$data2->username'";
            $data1->userid = $DB->get_field_sql($sql);
            if(($data1->userid)!="")
                    {
                         enrol_try_internal_enrol($course->id, $data1->userid, $CFG->creatornewroleid,$timestart = $data->enrolstartdate);
                    }
         }

    XMLRPC_response(XMLRPC_prepare($course->id), KD_XMLRPC_USERAGENT);
}

function subjectupdate($query=0)
{
   
    global $CFG;
    global $DB;
    $data = new stdClass();
    $data->name=$query['value']['TITLE'];
    $data->sis_courseid=$query['sisid'];
    $sql="SELECT cc.id FROM {$CFG->prefix}course_categories cc WHERE cc.depth=3 AND cc.sis_courseid = $data->sis_courseid";
    $data->id = $DB->get_field_sql($sql);
	//$data->parent = $
    $new= $DB->update_record('course_categories', $data);
    XMLRPC_response(XMLRPC_prepare($new), KD_XMLRPC_USERAGENT);
}


function courseupdate($query=0)
{
    
     global $CFG;
     global $DB;
     $data = new stdClass();
     if($query['value']['TITLE'])
     $data->name=$query['value']['TITLE'];
     if($query['value']['DESCRIPTION'])
     $data->description=$query['value']['DESCRIPTION'];
     if($data->name || $data->description)
      {
          $data->sis_courseid=$query['sisid'];
          $sql="SELECT cc.id FROM {$CFG->prefix}course_categories cc WHERE cc.depth=4 AND cc.sis_courseid = $data->sis_courseid";
          $data->id = $DB->get_field_sql($sql);
          $new= $DB->update_record('course_categories', $data);
        }

    XMLRPC_response(XMLRPC_prepare(array($new->name)), KD_XMLRPC_USERAGENT);
}

function periodsupdate($query=0)
{
    global $CFG;
    global $DB;
    require_once($CFG->dirroot.'/course/lib.php');
    require_once($CFG->dirroot.'/enrol/locallib.php');
    $data = new stdClass();
    $data1 = new stdClass();
    $data2 = new stdClass();
    $data3=new stdClass();

    $data->sis_periodid=$query['sisid'];
    $sql="SELECT c.id FROM {$CFG->prefix}course c WHERE c.sis_periodid = $data->sis_periodid ";
    $query['id'] = $DB->get_field_sql($sql,$params=null,IGNORE_MISSING);
    $data=(object)$query;
    $new = update_course($data);
    $context = get_context_instance(CONTEXT_COURSE, $data->id, MUST_EXIST);
    $data2->contextid=$context->id;
    $data3->contextid=$data2->contextid;
    $count=0;
    foreach($query['oldusername'] as $uname=>$uval)
        {
            if($uval && $uval!=$query['username'][$count])
            {
                  $sql1="SELECT e.id FROM {$CFG->prefix}enrol e WHERE e.courseid=$data->id AND e.enrol='manual'";
                  $enrolid=$DB->get_record_sql($sql1,$params=null,IGNORE_MISSING);
                  $user->username=$uval;
                  $sql = "SELECT s.id FROM {$CFG->prefix}user s WHERE s.username = '$user->username'";
                  $user->userid = $DB->get_field_sql($sql);
                  $ra = $DB->get_record('role_assignments', array('contextid'=>$context->id,'userid'=>$user->userid,'roleid'=>3), '*', IGNORE_MISSING);
                  $ue = $DB->get_record('user_enrolments', array('enrolid'=>$enrolid->id,'userid'=>$user->userid), '*', IGNORE_MISSING);
                  $val=$DB->delete_records('role_assignments',array('id'=>$ra->id));
                  $val1=$DB->delete_records('user_enrolments',array('id'=>$ue->id));
            }
            else
            {
                $data2->username=$query['username'][$count];
              
                $sql = "SELECT s.id FROM {$CFG->prefix}user s WHERE s.username = '$data2->username'";
                   if(($data2->username)!="")                
                   {
                       $data2->userid = $DB->get_field_sql($sql);
                       $sql1="SELECT e.id FROM {$CFG->prefix}enrol e WHERE e.courseid=$data->id AND e.enrol='manual'";
                       $enrolid=$DB->get_record_sql($sql1,$params=null,IGNORE_MISSING);
                       $ue = $DB->get_record('user_enrolments', array('enrolid'=>$enrolid->id,'userid'=>$data2->userid), '*', IGNORE_MISSING);
                   }
                
                if(($data2->userid)!="" && (!$uval || ($ue->id=="")))
                {
                        $role= enrol_try_internal_enrol($data->id, $data2->userid, $CFG->creatornewroleid,$timestart = $data->enrolstartdate);
                }
            }
            $count++;
    }
$row=0;
foreach($query['oldusername'] as $uname=>$uval)
        {
            if($uval && $uval!=$query['username'][$row])
            {
                      $data2->username=$query['username'][$row];
                      $sql = "SELECT s.id FROM {$CFG->prefix}user s WHERE s.username = '$data2->username'";
                       if(($data2->username)!="")    
                           $data2->userid = $DB->get_field_sql($sql);
                      if(($data2->userid)!="")
                         {
                   $role= enrol_try_internal_enrol($data->id, $data2->userid, $CFG->creatornewroleid,$timestart = $data->enrolstartdate);
                         }

            }
$row++;
        }
    XMLRPC_response(XMLRPC_prepare(array($data->id)), KD_XMLRPC_USERAGENT);
}


function updateschedule($query=0)
{
   global $CFG;
   global $DB;
   require_once($CFG->dirroot.'/lib/enrollib.php');
   require_once($CFG->dirroot.'/enrol/locallib.php');
   $flag = 1;
   $data = new stdClass();
   $data1 = new stdClass();
   $data2 = new stdClass();
   $roleid=5;
   $year = date('Y');
   $month = date('m');
   $day = date('d');
   $timeStamp = mktime(18,30,0,$month,$day,$year);
   $current=$timeStamp;
   $data1->username=$query['username'];
   $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$data1->username."'";
   $data->userid= $DB->get_field_sql($sql);
   foreach ($query as $course_period_id => $start_dates) {
   foreach ($start_dates as $key1 => $columns)
     {
              $sql1="SELECT c.id FROM {$CFG->prefix}course c WHERE c.sis_periodid=$course_period_id";
              $instance_id= $DB->get_field_sql($sql1);
              if($instance_id)
                {
                  foreach($columns as $key =>$column)
                    {
                      if($column)
                          {
                             if($key==START_DATE)
                             {
                                  $date= $column;
                                  $srt_date= strftime("%Y/%m/%d", strtotime($date));
                                  list($year, $month, $day) = split('/', $srt_date);
                                  $data->timestart = mktime(0,0,0,$month,$day,$year);
                             }
                             if($key==END_DATE)
                              {
                                   $date= $column;
                                   $srt_date= strftime("%Y/%m/%d", strtotime($date));
                                   list($year, $month, $day) = split('/', $srt_date);
                                   $data->timeend = mktime(0,0,0,$month,$day,$year);
                        
                              }

                                   $data1->instance_id=$instance_id;
                                   $sql3="SELECT e.* FROM {$CFG->prefix}enrol e WHERE e.courseid=$data1->instance_id AND e.enrol='manual'";
                                   $enrolid=$DB->get_record_sql($sql3,$params=null,IGNORE_MISSING);
                                  
                                   if($data->timeend<=$current && $data->timeend)
                                   {
                                       if ($enrol_manual = enrol_get_plugin('manual'))
                                             {
                                            
                                            $enrol_manual->unenrol_user($enrolid, $data->userid);

                                            }
                                   }
                                   else
                                     {

                                        $sql4="SELECT ue.id
                                              FROM {$CFG->prefix}user_enrolments ue
                                              JOIN {$CFG->prefix}enrol e ON (e.id = ue.enrolid)
                                              WHERE ue.userid = $data->userid AND e.courseid = $data1->instance_id";
                                           $data->id= $DB->get_field_sql($sql4);
                               if($data->id)
                               {
                                        $data->timemodified= time();
                                        $new->id = $DB->update_record('user_enrolments',$data);
                               }
                               elseif(!$data->id)
                                 {
                                    if ($enrol_manual = enrol_get_plugin('manual'))
                                             {
         
                                                $enrol_manual->enrol_user($enrolid, $data->userid, $roleid = $roleid , $timestart = $data->timestart, $timeend=0 );

                                            }
                                 }
                               unset($data->id);
                       }
                  }
              }
          }
      }
  }
XMLRPC_response(XMLRPC_prepare(array($flag)), KD_XMLRPC_USERAGENT);
}


function schedule($query_info=0)
{

     global $CFG;
     global $DB;
     require_once($CFG->dirroot.'/lib/enrollib.php');
     require_once($CFG->dirroot.'/enrol/locallib.php');
     $data1->student_name=$query_info['student_name'];
     $data1->course_period_id=$query_info['course_period_id']; 
     $sql1="SELECT c.id FROM {$CFG->prefix}course c WHERE c.sis_periodid=$data1->course_period_id";
     $data1->instance_id= $DB->get_field_sql($sql1);
     $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$data1->student_name."'";
     $date = $query_info['start_date'];
     list($month, $day, $year) = split('/', $date);
     $timeStamp = mktime(0,0,0,$month,$day,$year);
     $data->roleid= 5;
     $data->userid= $DB->get_field_sql($sql);
     $data->timestart= $timeStamp;
     $sql3="SELECT e.* FROM {$CFG->prefix}enrol e WHERE e.courseid=$data1->instance_id AND e.enrol='manual'";
     $enrolid=$DB->get_record_sql($sql3,$params=null,IGNORE_MISSING);
     if ($enrol_manual = enrol_get_plugin('manual'))
         {
            $enrol_manual->enrol_user($enrolid, $data->userid, $roleid = $data->roleid, $timestart = $data->timestart, $timeend = 0, $status = NULL);
            $flag[]=1;
        }

     
     XMLRPC_response(XMLRPC_prepare($flag), KD_XMLRPC_USERAGENT);
}



function massschedule($query_info=0)
{
    global $CFG;
    global $DB;
    require_once($CFG->dirroot.'/lib/enrollib.php');
    require_once($CFG->dirroot.'/enrol/locallib.php');
    $newcatid->id=$query_info['student_name[]'];

   foreach ($query_info['student_name[]'] as $row)
   {
     $data1->student_name=$row;
     $data1->course_period_id=$query_info['course_period_id'];
       
     $sql1="SELECT c.id FROM {$CFG->prefix}course c WHERE c.sis_periodid=$data1->course_period_id";
     $data1->instance_id= $DB->get_field_sql($sql1);
     $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$data1->student_name."'";
     
     $date = $query_info['start_date'];
     list($month, $day, $year) = split('/', $date);
     $timeStamp = mktime(0,0,0,$month,$day,$year);

     $data->roleid= 5;
     $data->userid= $DB->get_field_sql($sql);
     $data->timestart= $timeStamp;
     $sql3="SELECT e.* FROM {$CFG->prefix}enrol e WHERE e.courseid=$data1->instance_id AND e.enrol='manual'";
     $enrolid=$DB->get_record_sql($sql3,$params=null,IGNORE_MISSING);
     if($data->userid!='')
     {
            if ($enrol_manual = enrol_get_plugin('manual'))
                {
                        $enrol_manual->enrol_user($enrolid, $data->userid, $roleid = $data->roleid, $timestart = $data->timestart, $timeend = 0, $status = NULL);
                        $flag[]=1;
                 }
     }
  }
   XMLRPC_response(XMLRPC_prepare(array($newcatid->id)), KD_XMLRPC_USERAGENT);
}


function masssdrops($query_info=0)
{
    global $CFG;
    global $DB;
    require_once($CFG->dirroot.'/lib/enrollib.php');
    require_once($CFG->dirroot.'/enrol/locallib.php');
   foreach ($query_info['student_name[]'] as $row)
   {
     $data1->student_name=$row;
     $data1->course_period_id=$query_info['course_period_id'];
     $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$data1->student_name."'";
     $sql1="SELECT c.id FROM {$CFG->prefix}course c WHERE c.sis_periodid=$data1->course_period_id";
     $data1->instance_id= $DB->get_field_sql($sql1);
     $data2->userid= $DB->get_field_sql($sql);   
     $data->timeend= $query_info['end_date'];
     $data->timemodified= time();
     $sql3="SELECT e.* FROM {$CFG->prefix}enrol e WHERE e.courseid=$data1->instance_id AND e.enrol='manual'";
     $enrolid=$DB->get_record_sql($sql3,$params=null,IGNORE_MISSING);
     if($data->timeend<=time())
     {
             if ($enrol_manual = enrol_get_plugin('manual'))
                {
                        $enrol_manual->unenrol_user($enrolid, $data2->userid);
                        $flag[]=1;
                 }
     }
     else
     {
             $sql7 = "SELECT ue.id
                  FROM {$CFG->prefix}user_enrolments ue
                  JOIN {$CFG->prefix}enrol e ON (e.id = ue.enrolid)
                  WHERE ue.userid = $data2->userid AND e.courseid = $data1->instance_id";
                $record5=$DB->get_field_sql($sql7);
                $data->id=$record5;
           $flag[]=$DB->update_record('user_enrolments',$data);

       }

   } 
   XMLRPC_response(XMLRPC_prepare($flag), KD_XMLRPC_USERAGENT);
}

/*************************** Course, Subject, Period Deletion Function ****************************************/
function delete_period($query=0)
{
   global $CFG;
   global $DB;
   $data = new stdClass();
   $data->sis_periodid=$query['sis_periodid'];
   $sql="SELECT c.id,c.category FROM {$CFG->prefix}course c WHERE c.sis_periodid = $data->sis_periodid";
   $courseorid->id = $DB->get_field_sql($sql);
   $del= delete_course($courseorid->id);
  fix_course_sortorder(); 
   XMLRPC_response(XMLRPC_prepare($del), KD_XMLRPC_USERAGENT);
}


function del_course($query=0)
{
   global $CFG;
   global $DB;
   $data = new stdClass();
   $data->sis_courseid=$query['sis_courseid'];
   $sql="SELECT cc.id FROM {$CFG->prefix}course_categories cc WHERE cc.depth=4 AND cc.sis_courseid = $data->sis_courseid";
   $categorieid->id = $DB->get_field_sql($sql);

   $del= $DB->delete_records('course_categories',array('id'=>$categorieid->id));
   //$del= delete_course($courseorid);
   XMLRPC_response(XMLRPC_prepare($del), KD_XMLRPC_USERAGENT);
}

function del_subject($query=0)
{
   global $CFG;
   global $DB;
   $data = new stdClass();
   $data->sis_courseid=$query['sis_courseid'];
   $sql="SELECT cc.id FROM {$CFG->prefix}course_categories cc WHERE cc.depth=3 AND cc.sis_courseid = $data->sis_courseid";
   $categorieid->id = $DB->get_field_sql($sql);

   $del= $DB->delete_records('course_categories',array('id'=>$categorieid->id));
   XMLRPC_response(XMLRPC_prepare($del), KD_XMLRPC_USERAGENT);
}

/************************************************************************************************/
#--------------------------------User Addition Function--------------------------------#

function adduser($query=array())
{
   global $CFG;
   global $DB;
   $data = new stdClass();
   $data1 = new stdClass();
   $data_group = new stdClass();
   $data->confirmed=1;
    $selectsite="select mh.id from  {$CFG->prefix}mnet_host mh where mh.wwwroot='".$query['site']."'";
   $mnethost=$DB->get_field_sql($selectsite);
     
   $data->mnethostid=$mnethost;
   $data->auth="mnet";
   $data->username= addslashes($query['username']);
   $data->firstname= addslashes($query['firstname']);
   $data->lastname=addslashes($query['lastname']);
   $data->email=addslashes($query['email']);
   $data->phone1=$query['phone'];
//   $data->sis_school_id=$query['sis_school_id'];
   if($query['profile']==1){
   $data->maildisplay=1;
   }
   $data->timemodified=time();
   $data->timecreated=time();
   $data->districtid =$query['home_school'];
   $res=$DB->insert_record('user', $data);
   $uid=$res;
   foreach($query['sis_school_name'] as $group_name){
   // fetch gropu_id of user
   $sql="SELECT u.id FROM {$CFG->prefix}groups u WHERE u.name ='".trim($group_name)."' ";
   $group_id= $DB->get_field_sql($sql);

   //insert into grop_member
   $data_group->groupid=$group_id;
   $data_group->userid =$uid;
   $data_group->timeadded=time();
   $res=$DB->insert_record('groups_members', $data_group);
   }
//   if($query['profile']!=1)
//   {
//      XMLRPC_response(XMLRPC_prepare($uid), KD_XMLRPC_USERAGENT);
//   }
//   else
//   {
   if($uid!='')
   {
//   $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$query['username']."'";
   if($query['profile']==102)
     $data1->roleid=10;
    elseif($query['profile']==1 || $query['profile']==99)
     $data1->roleid=1;
     elseif($query['profile']==2)
     $data1->roleid=15;
   $data1->contextid=1;
   $data1->userid=$uid;//$DB->get_field_sql($sql);
//   $data1->timestart=$query['timestart'];
   $data1->timemodified=time();
   $data1->modifierid=2;
//   $data1->enrol= 'manual';
   $newcatid->id = $DB->insert_record('role_assignments', $data1);
   }
   XMLRPC_response(XMLRPC_prepare($uid), KD_XMLRPC_USERAGENT);
//   }



}

#-------------------------------------------------------#
#--------------------------------User Updation Function--------------------------------#

function updateuser($query=array())
{
   global $CFG;
   global $DB;
   $data = new stdClass();
   //    if($query['email']!='')
//      $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$query['username']."' and email='".$query['email']."' and districtid=$query[home_school]";
//   else
   $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$query['username']."' and email='".$query['email']."'";
  
   $data->id= $DB->get_field_sql($sql);
   $uid= $data->id;
  # $data->mnethostid=3;
   if($query['firstname']!='')
   $data->firstname=$query['firstname'];
   if($query['lastname']!='')
   $data->lastname=$query['lastname'];
   if($query['email']!='')
   $data->email=$query['email'];
   if($query['phone']!='')
   $data->phone1=$query['phone'];
   if($query['home_school']!='')
   $data->districtid=$query['home_school'];
//   if($query['sis_school_id']!='')
//   $data->sis_school_id=$query['sis_school_id'];
   $data->timemodified=time();
   $success = $DB->update_record('user', $data);

    $sql="SELECT g.groupid FROM {$CFG->prefix}groups_members g WHERE g.userid ='".$uid."' ";
   $group_id_all= $DB->get_records_sql($sql);

  if(count($query['sis_school_name'])>0)
  {
   #-----for group updation------#
  foreach($query['sis_school_name'] as $group_name){
   
   $sql="SELECT u.id FROM {$CFG->prefix}groups u WHERE u.name ='".trim($group_name)."' ";
   $group_id= $DB->get_field_sql($sql);
   $user_group_id[]=$group_id;
   }
   #---------delete of group that is unchecked-----------------#
	#---This has been commented out because it was removing users from groups assigned in Moodle---#
   // foreach($group_id_all as $group_id=>$value){
   //     if(!in_array($group_id,$user_group_id))
   //             $del= $DB->delete_records('groups_members',array('userid'=>$uid,'groupid'=>$group_id));
   //    
   // $user_id_db[]=$group_id;

   // }
   #---------delete  end -----------------#
   #---insert of group that that is unchecked and not already in db-------#
    foreach($user_group_id as $group_id){

         if(!in_array($group_id,$user_id_db)){
   //insert into grop_member
   $data_group->groupid=$group_id;
   $data_group->userid =$uid;
   $data_group->timeadded=time();
   $res=$DB->insert_record('groups_members', $data_group);
         }
   }
   
  }
    #---insert of group that is new-------#
   #-------------------#
  #------profile update-------------------#
   
   if($query['profile']!='')
    {

   $sql1="SELECT ra.id FROM {$CFG->prefix}role_assignments ra WHERE ra.userid=$uid";
   $id=$DB->get_field_sql($sql1);
   $data1->id=$id;
        if($query['profile']==0)
         $data1->roleid=5;
        elseif($query['profile']==1 || $query['profile']==99)
         $data1->roleid=1;
         elseif($query['profile']==2)
         $data1->roleid=3;
//       $data1->roleid=$query['profile'];
       $data1->contextid=1;
       $data1->userid=$uid;
//       $data1->timestart=$query['timestart'];
       $data1->timemodified=time();
       $data1->modifierid=2;
//       $data1->enrol= 'manual';
//       $DB->insert_record('role_assignments', $data1);
       $DB->update_record('role_assignments', $data1);
    }
   #------profile update-------------------#
   XMLRPC_response(XMLRPC_prepare($uid), KD_XMLRPC_USERAGENT);

}

function updateadmin($query=0)
{
   global $CFG;
   global $DB;
   $data = new stdClass();
   $data1 = new stdClass();
    if($query['email']!='')
    //    if($query['email']!='')
//      $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$query['username']."' and email='".$query['email']."' and districtid=$query[home_school]";
//   else
   $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$query['username']."' and email='".$query['email']."'";
   $data->id=$DB->get_field_sql($sql);
   $uid= $data->id;
//    $data->mnethostid=3;
   if($query['firstname']!='')
   $data->firstname=$query['firstname'];
   if($query['lastname']!='')
   $data->lastname=$query['lastname'];
   if($query['email']!='')
   $data->email=$query['email'];
   if($query['phone']!='')
   $data->phone1=$query['phone'];
   $data->maildisplay=1;
   $data->timemodified=time();
   if($query['home_school']!='')
   $data->districtid=$query['home_school'];
   $sql1="SELECT ra.id FROM {$CFG->prefix}role_assignments ra WHERE ra.userid=$data->id AND ra.roleid=1 AND ra.contextid=1";
   $role_id=$DB->get_field_sql($sql1);
   if($role_id=='')
    {
       $data1->roleid=1;
       $data1->contextid=1;
       $data1->userid=$DB->get_field_sql($sql);
       $data1->timestart=$query['timestart'];
       $data1->timemodified=time();
       $data1->modifierid=2;
       $data1->enrol= 'manual';
       $DB->insert_record('role_assignments', $data1);
    }
   $success = $DB->update_record('user', $data);
#-------------------update group--------------------------------------------------------#
     $sql="SELECT g.groupid FROM {$CFG->prefix}groups_members g WHERE g.userid ='".$uid."' ";
   $group_id_all= $DB->get_records_sql($sql);

if(count($query['sis_school_name'])>0)
  {
   #-----for group updation------#
  foreach($query['sis_school_name'] as $group_name){

   $sql="SELECT u.id FROM {$CFG->prefix}groups u WHERE u.name ='".trim($group_name)."' ";
   $group_id= $DB->get_field_sql($sql);
   $user_group_id[]=$group_id;
   }
   #---------delete of group that is unchecked-----------------#
   foreach($group_id_all as $group_id=>$value){
        if(!in_array($group_id,$user_group_id))
                $del= $DB->delete_records('groups_members',array('userid'=>$uid,'groupid'=>$group_id));

   $user_id_db[]=$group_id;

   }
   #---------delete  end -----------------#
   #---insert of group that that is unchecked and not already in db-------#
    foreach($user_group_id as $group_id){

         if(!in_array($group_id,$user_id_db)){
   //insert into grop_member
   $data_group->groupid=$group_id;
   $data_group->userid =$uid;
   $data_group->timeadded=time();
   $res=$DB->insert_record('groups_members', $data_group);
         }
   }
  }
    #---insert of group that is new-------#
   #-------------------update group--------------------------------------------------------#
 

   XMLRPC_response(XMLRPC_prepare($success), KD_XMLRPC_USERAGENT);

}

function updateteacher($query=0)
{
   global $CFG;
   global $DB;
   $data = new stdClass();
   $data1 = new stdClass();
     //    if($query['email']!='')
    //      $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$query['username']."' and email='".$query['email']."' and districtid=$query[home_school]";
    //   else
   $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$query['username']."' and email='".$query['email']."'";
 
   $data->id=$DB->get_field_sql($sql);
   $uid= $data->id;
//   $data->mnethostid=3;
   if($query['firstname']!='')
   $data->firstname=$query['firstname'];
   if($query['lastname']!='')
   $data->lastname=$query['lastname'];
   if($query['email']!='')
   $data->email=$query['email'];
   if($query['phone']!='')
   $data->phone1=$query['phone'];
   $data->maildisplay=2;
   $data->timemodified=time();
   if($query['home_school']!='')
   $data->districtid=$query['home_school'];
   $sql1="SELECT ra.id FROM {$CFG->prefix}role_assignments ra WHERE ra.userid=$data->id AND ra.roleid=1 AND ra.contextid=1";
   $role_id=$DB->get_field_sql($sql1);
   if($role_id!='')
    {
      $DB->delete_records('role_assignments',array('id'=>$role_id));
    }
  $success = $DB->update_record('user', $data);

  #-------------------update group--------------------------------------------------------#
     $sql="SELECT g.groupid FROM {$CFG->prefix}groups_members g WHERE g.userid ='".$uid."' ";
   $group_id_all= $DB->get_records_sql($sql);

if(count($query['sis_school_name'])>0)
  {
   #-----for group updation------#
  foreach($query['sis_school_name'] as $group_name){

   $sql="SELECT u.id FROM {$CFG->prefix}groups u WHERE u.name ='".trim($group_name)."' ";
   $group_id= $DB->get_field_sql($sql);
   $user_group_id[]=$group_id;
   }
   #---------delete of group that is unchecked-----------------#
   foreach($group_id_all as $group_id=>$value){
        if(!in_array($group_id,$user_group_id))
                $del= $DB->delete_records('groups_members',array('userid'=>$uid,'groupid'=>$group_id));

   $user_id_db[]=$group_id;

   }
   #---------delete  end -----------------#
   #---insert of group that that is unchecked and not already in db-------#
    foreach($user_group_id as $group_id){

         if(!in_array($group_id,$user_id_db)){
   //insert into grop_member
   $data_group->groupid=$group_id;
   $data_group->userid =$uid;
   $data_group->timeadded=time();
   $res=$DB->insert_record('groups_members', $data_group);
         }
   }
  }
    #---insert of group that is new-------#
   #-------------------update group--------------------------------------------------------#

   XMLRPC_response(XMLRPC_prepare($success), KD_XMLRPC_USERAGENT);
}
#-------------------------------------------------------#

function fetchuser($query=0)
{
   global $CFG;
   global $DB;
   $data = new stdClass();
   $username             = addslashes($query['username']);
   $data->username      = str_replace('\\','\\\\\\', $username);   
   $data->email         = $query['email'];
   $data->districtid    = $query['districtid'];
    $selectsite="select mh.id from  {$CFG->prefix}mnet_host mh where mh.wwwroot='".$query['site']."'";
   $mnethost=$DB->get_field_sql($selectsite);

   $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username='".$data->username."'  AND u.districtid=$data->districtid AND u.deleted!=1 AND u.auth='mnet' AND u.mnethostid='".$mnethost."'";
   $data->id = $DB->get_field_sql($sql);
   
   XMLRPC_response(XMLRPC_prepare($data->id), KD_XMLRPC_USERAGENT);
}
function GetUsers($query_info=0)
{
     global $CFG;
     global $DB;
     $selectsite="select mh.id from  {$CFG->prefix}mnet_host mh where mh.wwwroot='".$query_info['site']."'";
     $mnethost=$DB->get_field_sql($selectsite);
     $data=new stdClass();
     $data=$DB->get_records('user');
 
     foreach($data as $Users_List)
              {
                  if($Users_List->mnethostid ==$mnethost )
                  { 
                          $ar[]= array($Users_List->id=>$Users_List->username);
                  }
              }
 
      XMLRPC_response(XMLRPC_prepare($ar), KD_XMLRPC_USERAGENT);
}

function grant_access($query_info=0)
{
   global $CFG;
   global $DB;
   $data = new stdClass();
   $data1 = new stdClass();
   foreach ($query_info as $key => $value)
    {
            
                     if($key==site){

                              $selectsite="select mh.id from  {$CFG->prefix}mnet_host mh where mh.wwwroot='".$value['site']."'";
                              $mnethost=$DB->get_field_sql($selectsite);
                     }
                  
                         foreach($value as $key1=>$value1)
                              {
                                 if($key1=='data'){
                                             $data->confirmed=1;
                                             $data->mnethostid=$mnethost;
                                             $data->auth='mnet';
                                            $data->username=$value1 ['USERNAME'];
                                            $sql="SELECT u.username FROM {$CFG->prefix}user u WHERE u.username = '$data->username' AND u.auth='mnet' AND u.mnethostid='".$mnethost."'";
                                            $record = $DB->get_field_sql($sql);
                                            $val.=$record.",";
                                            if(!$record)
                                                     {
                                                   $data->firstname=$value1[FIRST_NAME];
                                                   $data->lastname=$value1[LAST_NAME];
                                                   $data->email=$value1[EMAIL];
                                                   $data->phone1=$value1[PHONE];
                                                   $data->timemodified=time();
                                                   $insert->id=$DB->insert_record('user', $data);
                                                  }
                            }
         }
    }
      
      XMLRPC_response(XMLRPC_prepare(array($val)), KD_XMLRPC_USERAGENT);
}

function active($query=0)
{
    global $CFG;
    global $DB;
    $selectsite="select mh.id from  {$CFG->prefix}mnet_host mh where mh.wwwroot='".$query['site']."'";
    $mnethost=$DB->get_field_sql($selectsite);
   $sql="SELECT u.username  FROM {$CFG->prefix}user u WHERE u.username = '".$query['username']."' AND u.auth='mnet' AND u.mnethostid='".$mnethost."'";
   $record = $DB->get_field_sql($sql);
    if($record)
            {
              $selectaccess="SELECT sa.id FROM {$CFG->prefix}mnet_sso_access_control sa WHERE sa.username='$record' AND sa.mnet_host_id='$mnethost'";
              $access=$DB->get_field_sql($selectaccess);
              if($access){
                            $data=new stdClass();
                            $data->id=$access;
                            $data->username=$record;
                            $data->mnet_host_id=$mnethost;
                            $data->accessctrl=allow;
                            $result=$DB->update_record('mnet_sso_access_control',$data);
    }
              else{
                           $data=new stdClass();
                            $data->id=$access;
                            $data->username=$record;
                            $data->mnet_host_id=$mnethost;
                            $data->accessctrl=allow;
                            $result=$DB->insert_record('mnet_sso_access_control',$data);
              }

    }
    XMLRPC_response(XMLRPC_prepare($result), KD_XMLRPC_USERAGENT);
}

function inactive($query=0)
{
    global $CFG;
    global  $DB;
      $selectsite="select mh.id from  {$CFG->prefix}mnet_host mh where mh.wwwroot='".$query['site']."'";
    $mnethost=$DB->get_field_sql($selectsite);
   $sql="SELECT u.username  FROM {$CFG->prefix}user u WHERE u.username = '".$query['username']."' AND u.auth='mnet' AND u.mnethostid='".$mnethost."'";
   $record = $DB->get_field_sql($sql);
    if($record)
            {
          $selectaccess="SELECT sa.id FROM {$CFG->prefix}mnet_sso_access_control sa WHERE sa.username='$record' AND sa.mnet_host_id='$mnethost'";
           $access=$DB->get_field_sql($selectaccess);
              if($access){
                            $data=new stdClass();
                            $data->id=$access;
                            $data->username=$record;
                            $data->mnet_host_id=$mnethost;
                            $data->accessctrl=deny;
                            $result=$DB->update_record('mnet_sso_access_control',$data);
              }
              else{
                           $data=new stdClass();
                            $data->id=$access;
                            $data->username=$record;
                            $data->mnet_host_id=$mnethost;
                            $data->accessctrl=deny;
                            $result=$DB->insert_record('mnet_sso_access_control',$data);
              }

     }
    XMLRPC_response(XMLRPC_prepare($result), KD_XMLRPC_USERAGENT);

}

function fetchstudents($role=0)
{
    global $CFG;
    global $DB;
    $select="roleid=$role[0]";
    $students=$DB->get_records_select('role_assignments', $select);
    foreach($students as $key)
        {
               $sql="SELECT * FROM {$CFG->prefix}user u WHERE u.id= $key->userid";
               $students_record[]=$DB->get_record_sql($sql);
     }
   foreach($students_record as $key1)
      {
        $query[]=array('USERNAME'=>$key1->username,'FIRST_NAME'=>$key1->firstname,'LAST_NAME'=>$key1->lastname,'EMAIL'=>$key1->email,'PASSWORD'=>$key1->password,'START_DATE'=>$key1->firstaccess);
    }
  XMLRPC_response(XMLRPC_prepare($query), KD_XMLRPC_USERAGENT);
}


function fetchstaff($role=0)
{
   global $CFG;
   global $DB;
   $select="roleid=$role[0]";
   $staffs=$DB->get_records_select('role_assignments', $select);
   foreach($staffs as $key)
       {
           $sql="SELECT * FROM {$CFG->prefix}user u WHERE u.id= $key->userid";
           $staffs_record[]=$DB->get_record_sql($sql);
      }
  foreach($staffs_record as $key1)
      {
             $query[]=array('USERNAME'=>$key1->username,'FIRST_NAME'=>$key1->firstname,'LAST_NAME'=>$key1->lastname,'EMAIL'=>$key1->email,'PASSWORD'=>$key1->password,'START_DATE'=>$key1->firstaccess,'PROFILE_ID'=>2,'PROFILE'=>teacher);
         }
   $select="roleid=1";
   $admin=$DB->get_records_select('role_assignments', $select);
   foreach($admin as $key)
       {
           $sql="SELECT * FROM {$CFG->prefix}user u WHERE u.id= $key->userid";
           $admin_record[]=$DB->get_record_sql($sql);
      }
  foreach($admin_record as $key1)
      {
         $query[]=array('USERNAME'=>$key1->username,'FIRST_NAME'=>$key1->firstname,'LAST_NAME'=>$key1->lastname,'EMAIL'=>$key1->email,'PASSWORD'=>$key1->password,'START_DATE'=>$key1->firstaccess,'PROFILE_ID'=>1,'PROFILE'=>admin);
    }
  XMLRPC_response(XMLRPC_prepare($query), KD_XMLRPC_USERAGENT);
}


function fetchcourses($query=0)
{
    global $CFG;
    global $DB;
    $sql="SELECT * FROM `{$CFG->prefix}course` WHERE `sis_periodid`=0 and id!=1";
    $periods=$DB->get_records_sql($sql);
    foreach($periods as $key){
        $sql5="SELECT cx.id FROM {$CFG->prefix}context cx WHERE cx.instanceid = $key->id AND cx.contextlevel=50";
        $contextid = $DB->get_field_sql($sql5);
        $sql6="SELECT ra.userid FROM {$CFG->prefix}role_assignments ra WHERE ra.roleid=3 AND ra.contextid=$contextid";
        $userid=$DB->get_records_sql($sql6);
        foreach($userid as $user){
          $sql7="SELECT u.username FROM {$CFG->prefix}user u WHERE u.id=$user->userid";
          $username[]=$DB->get_field_sql($sql7);
       }
//                 $sql7="SELECT u.username FROM {$CFG->prefix}user u WHERE u.id=$userid";
//        $username=get_field_sql($sql7);
        $sql2="SELECT * FROM {$CFG->prefix}course_categories cc WHERE cc.id=$key->category ";
        $courses=$DB->get_records_sql($sql2);
        foreach($courses as $value){
        $catids=explode('/',$value->path) ;
        for($i=0;$i<=2;$i++){
            switch ($i){
                    case 0: break;
                    case 1:
                        $sql3="SELECT * FROM {$CFG->prefix}course_categories cc WHERE cc.id=$catids[1]";
                               $subject=$DB->get_record_sql($sql3);
                               $sub=array('subject_id'=>$subject->id,'subject_name'=>$subject->name);
                                break;
                    case 2:
                                if($catids[2]){
                               $sql4="SELECT * FROM {$CFG->prefix}course_categories cc WHERE cc.id=$catids[2]";
                               $course=$DB->get_record_sql($sql4);
                               $cour=array('course_id'=>$course->id,'course_name'=>$course->name);
                                break;
                                }
                                else
                                    {
                               $sql4="SELECT * FROM {$CFG->prefix}course_categories cc WHERE cc.id=$catids[1]";
                               $course=$DB->get_record_sql($sql4);
                               $cour=array('course_id'=>$course->id,'course_name'=>$course->name);
                                break;
                                }
            }

        }
     }
     $period[]=array('TITLE'=>$key->fullname,'SHORT_NAME'=>$key->shortname,'START_DATE'=>$key->startdate,'MOODLE_COURSEID'=>$key->id,'username'=>$username,'subject'=>$sub,'course'=>$cour);

   }
      XMLRPC_response(XMLRPC_prepare($period), KD_XMLRPC_USERAGENT);

}


function insert_sisid($query=0)
{
     global $CFG;
     global $DB;
    foreach($query as  $key=>$val)
   {
    if($key=='subject'){
        foreach($val as $coloumn){
                    $sql="SELECT cc.sis_courseid FROM {$CFG->prefix}course_categories cc WHERE cc.id='".$coloumn['MOODLE_CATID']."'";
                    $sisid=$DB->get_field_sql($sql);
                    if(!$sisid){
                     $data->sis_courseid=$coloumn['SUBJECT_ID'];
                     $data-> id=$coloumn['MOODLE_CATID'];
                     $course_cat=$DB->update_record('course_categories', $data);
                    }
                }
            }
    if($key=='course'){
                 foreach($val as $coloumn){
                 $sql="SELECT cc.sis_courseid FROM {$CFG->prefix}course_categories cc WHERE cc.id='".$coloumn['MOODLE_CATID']."'";
                    $sisid=$DB->get_field_sql($sql);
                    if(!$sisid){
                     $data1->sis_courseid=$coloumn['COURSE_ID'];
                     $data1-> id=$coloumn['MOODLE_CATID'];
                     $course_cat1=$DB->update_record('course_categories', $data1);
                    }
                }
         }
   if($key=='periods'){
                 foreach($val as $coloumn){
                    $sql="SELECT c.sis_periodid FROM {$CFG->prefix}course c WHERE c.id='".$coloumn['MOODLE_COURSEID']."'";
                    $sisid=$DB->get_field_sql($sql);
                    if(!$sisid){
                     $data2->sis_periodid=$coloumn['COURSE_PERIOD_ID'];
                     $data2-> id=$coloumn['MOODLE_COURSEID'];
                     $course1=$DB->update_record('course', $data2);
                    }
                 }
        }
    }

    XMLRPC_response(XMLRPC_prepare($course1), KD_XMLRPC_USERAGENT);
}

function schedule_stu($query=0)
{
    global $CFG;
    global $DB;
    $roleid=5;
    $select="roleid=$roleid and contextid!=1";
    $students=$DB->get_records_select('role_assignments', $select);
    foreach($students as $key){
                $user="id=$key->userid";
                $username=$DB->get_record_select('user', $user);
                $course="contextlevel=50 and id=$key->contextid";
                $course_id=$DB->get_record_select('context', $course);
                $course_period="id=$course_id->instanceid";
                $cperiod=$DB->get_record_select('course', $course_period);
                $sql7 = "SELECT ue.*
                  FROM {$CFG->prefix}user_enrolments ue
                  JOIN {$CFG->prefix}enrol e ON (e.id = ue.enrolid)
                  WHERE ue.userid = $username->id AND e.courseid = $course_id->instanceid";
                $record5=$DB->get_record_sql($sql7);
         if($record5->id)
                $details[]=array('sis_id'=>$cperiod->sis_periodid,'username'=>$username->username,'timestart'=>$record5->timestart);
            }
            
            
     XMLRPC_response(XMLRPC_prepare($details), KD_XMLRPC_USERAGENT);
}

function logout($query_info=0){
 
    global $CFG, $SESSION, $USER, $DB;
   $sql = "SELECT s.id FROM {$CFG->prefix}user s WHERE s.username = '$query_info'";
          $data1->userid = $DB->get_field_sql($sql);
// echo   $userid=$username->id;
$val= session_kill_user( $data1->userid);
     XMLRPC_response(XMLRPC_prepare( $data1->userid), KD_XMLRPC_USERAGENT);
}

function usermnet($query_info=0)
{
       global $CFG,$DB;
       $selectsite="select mh.id from  {$CFG->prefix}mnet_host mh where mh.wwwroot='".$query_info['site']."'";
       $mnethost=$DB->get_field_sql($selectsite);
       $user=substr($query_info['users'],0,-1);
       $userar=explode(",", $user);
       foreach($userar as $key=>$value)
           {
            $sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username = '$value' ";
            $data=new stdClass();
            $data->id = $DB->get_field_sql($sql);
            $data->auth=mnet;
            $data->mnethostid=$mnethost;
            $result=$DB->update_record('user',$data);
       }
    XMLRPC_response(XMLRPC_prepare( $result), KD_XMLRPC_USERAGENT);
}
#------------------------------------------------------add group---------------------------------------#
function add_group($query=array()){
    global $CFG;
    global $DB;
   $data = new stdClass();

   $data->name         =$query['superdistrict_name'];//trim($query_info['superdistrict_name']);
   $data->timemodified =time();
   $data->timecreated  =time();
   $data->courseid     =1;
   $data->id=$DB->insert_record('groupings', $data);

      XMLRPC_response(XMLRPC_prepare($data->id), KD_XMLRPC_USERAGENT);
   
   
}
#------------------------------------------------------add group---------------------------------------#
# Instead of Groups Changed to Create School Category. Removed the Grouping feature.
#------------------------------------------------------add subgroup---------------------------------------#
function add_subgroup_group($query=array()){
    global $CFG;
    global $DB;
   $data = new stdClass();
   $data_for_groupchild = new stdClass();

   $data->name         =$query['district_name'];// groupingid 	groupid district_id
   $data->timemodified =time();
   $data->parent = "1";
   //$data->depth = "2";
   
   //$data->timecreated  =time();
   //$data->courseid     =1;
   $data->sis_courseid     =$query['district_id'];
   //$data->username = $query['superadmin_uname'];
   //$data->id=$DB->insert_record('groups', $data);
$newcategory->id=$DB->insert_record('course_categories', $data);
$newcategory->context = get_context_instance(CONTEXT_COURSECAT, $newcategory->id);
    mark_context_dirty($newcategory->context->path);
    fix_course_sortorder();
   
   //$sql="SELECT u.id FROM {$CFG->prefix}groupings u WHERE u.name = '".$query['superdistrict_name']."' ";
   //$grouping_id= $DB->get_field_sql($sql);
   //$data_for_groupchild->groupingid =$grouping_id;
   //$data_for_groupchild->groupid = $data->id;
   //$data_for_groupchild->timeadded  =time();
   
   //$data_for_groupchild->id=$DB->insert_record('groupings_groups', $data_for_groupchild);

   
   //$sql="SELECT u.id FROM {$CFG->prefix}user u WHERE u.username = '$data->username' ";
   //$data->uid = $DB->get_field_sql($sql);
   
   //insert into grop_member
   //$data_group->groupid=$data->id;
   //$data_group->userid =$data->uid ;
   //$data_group->timeadded=time();
   //$res=$DB->insert_record('groups_members', $data_group);
      XMLRPC_response(XMLRPC_prepare($newcategory->id), KD_XMLRPC_USERAGENT);


}
#------------------------------------------------------add subgroup---------------------------------------#
#------------------------------------------------------update subgroup---------------------------------------#
function update_subgroup_group($query=array()){
    global $CFG;
    global $DB;
   $data = new stdClass();
      
   $sql="SELECT u.id FROM {$CFG->prefix}course_categories u WHERE u.sis_courseid='".$query['district_id']."'";
   $data->id=$DB->get_field_sql($sql);
   $uid= $data->id;
 
   if($query['district_name']!='')
   $data->name=$query['district_name'];
  
   $data->timemodified=time();
  
  
  $success = $DB->update_record('course_categories', $data);
  XMLRPC_response(XMLRPC_prepare($data->id), KD_XMLRPC_USERAGENT); 

}
#------------------------------------------------------update subgroup---------------------------------------#

/********************************* fetch all type of assignmnets ***********************************************************************/

function fetchassignments($query=0)
{
    global $CFG;
    global $DB;
    $sql                            =   "SELECT * FROM `{$CFG->prefix}course` WHERE `sis_periodid`!=0 and id!=1";
    $periods                        =   $DB->get_records_sql($sql);
    foreach($periods as $key)
     {
        /************************ prepare Assignment array *******************************************/
        $sql_assignment             = "SELECT * FROM {$CFG->prefix}assignment  WHERE course = $key->id ";
        $assignments                = $DB->get_records_sql($sql_assignment);
        foreach($assignments as $assignment)
         {
//            $sql_gradedassignment   = "SELECT distinct assignment FROM {$CFG->prefix}assignment_submissions   WHERE assignment = $assignment->id ";
//            $gradedassignment       = $DB->get_field_sql($sql_gradedassignment);
//            if($gradedassignment)
            $assignment_val[]=array('TITLE'=>$assignment->name,'DESCRIPTION'=>$assignment->description,'COURSE_PERIOD_ID'=>$key->sis_periodid,'ASSIGNED_DATE'=>$assignment->timeavailable,'DUE_DATE'=>$assignment->timedue,'POINTS'=>$assignment->grade,'MDL_ID'=>$assignment->id,'MODULE'=>assign);
         }
       
       }
      XMLRPC_response(XMLRPC_prepare($assignment_val), KD_XMLRPC_USERAGENT);

}



/********************************* fetch all type of lessons ***********************************************************************/

function fetchlessons($query=0)
{
    global $CFG;
    global $DB;
    $sql                            =   "SELECT * FROM `{$CFG->prefix}course` WHERE `sis_periodid`!=0 and id!=1";
    $periods                        =   $DB->get_records_sql($sql);
    foreach($periods as $key)
     {
         /************************ prepare Lesson array *******************************************/
         $sql_lesson                = "SELECT * FROM {$CFG->prefix}lesson  WHERE course = $key->id ";
         $lessons                   = $DB->get_records_sql($sql_lesson  );
         foreach($lessons    as $lesson)
         {
//            $sql_gradedlesson       = "SELECT distinct lessonid  FROM {$CFG->prefix}lesson_grades   WHERE lessonid  = $lesson->id ";
//            $gradedlesson           = $DB->get_field_sql($sql_gradedlesson);
//            if($gradedlesson)
            $assignment_val[]       =   array('TITLE'=>$lesson->name,'DESCRIPTION'=>"",'COURSE_PERIOD_ID'=>$key->sis_periodid,'ASSIGNED_DATE'=>$lesson->available,'DUE_DATE'=>$lesson->deadline,'POINTS'=>$lesson->grade,'MDL_ID'=>$lesson->id,'MODULE'=>lesson);
         }
        
            
   }
      XMLRPC_response(XMLRPC_prepare($assignment_val), KD_XMLRPC_USERAGENT);

}


/********************************* fetch all type of Quizes ***********************************************************************/

function fetchquizes($query=0)
{
    global $CFG;
    global $DB;
    $sql                            =   "SELECT * FROM `{$CFG->prefix}course` WHERE `sis_periodid`!=0 and id!=1";
    $periods                        =   $DB->get_records_sql($sql);
    foreach($periods as $key)
     {
       
       /************************ prepare Quiz array *******************************************/
         $sql_quiz                  = "SELECT * FROM {$CFG->prefix}quiz  WHERE course = $key->id ";
         $quizs                     = $DB->get_records_sql($sql_quiz  );
         foreach($quizs    as $quiz)
         {
//            $sql_gradedquiz         = "SELECT distinct quiz  FROM {$CFG->prefix}quiz_attempts   WHERE quiz  = $quiz->id ";
//            $gradedquiz             = $DB->get_field_sql($sql_gradedquiz);
//            if($gradedquiz)
            $assignment_val[]       = array('TITLE'=>$quiz->name,'DESCRIPTION'=>$quiz->intro,'COURSE_PERIOD_ID'=>$key->sis_periodid,'ASSIGNED_DATE'=>$quiz->timeopen,'DUE_DATE'=>$quiz->timeclose,'POINTS'=>$quiz->grade,'MDL_ID'=>$quiz->id,'MODULE'=>quiz);
         }
        
         /************************ prepare Hotpot array *******************************************/
//         $sql_hotpot                = "SELECT * FROM {$CFG->prefix}hotpot  WHERE course = $key->id ";
//         $hotpots                   = $DB->get_records_sql($sql_hotpot  );
//         foreach($hotpots    as $hotpot)
//         {
//            $sql_gradedhotpot       = "SELECT distinct hotpot  FROM {$CFG->prefix}hotpot_attempts   WHERE hotpot  = $hotpot->id ";
//            $gradedhotpot           = $DB->get_field_sql($sql_gradedhotpot);
//            if($gradedhotpot)
//            $assignment_val[]       =   array('TITLE'=>$hotpot->name,'DESCRIPTION'=>$hotpot->summary,'COURSE_PERIOD_ID'=>$key->sis_periodid,'ASSIGNED_DATE'=>$hotpot->timeopen,'DUE_DATE'=>$hotpot->timeclose,'POINTS'=>$hotpot->grade,'MDL_ID'=>$hotpot->id,'MODULE'=>hotpot);
//         }
        
            
   }
      XMLRPC_response(XMLRPC_prepare($assignment_val), KD_XMLRPC_USERAGENT);

}


/**************************** fetch all type of assignment grades  ************************************************/
function fetchassignment_grades($queryinfo=0)
  {
            global $CFG;
            global $DB;
            $sql                            =   "SELECT * FROM `{$CFG->prefix}course` WHERE `sis_periodid`!=0 and id!=1 limit 100 offset 400 ";
            $periods                        =   $DB->get_records_sql($sql);
            foreach($periods as $key)
            {
                /************************ prepare Assignment grades array *******************************************/
                $sql_assignment             = "SELECT * FROM {$CFG->prefix}assignment  WHERE course = $key->id ";
                $assignments                = $DB->get_records_sql($sql_assignment);

                foreach($assignments   as $assignment)
                {
                    $sql_gradedassignment   = "SELECT * FROM {$CFG->prefix}assignment_submissions   WHERE assignment = $assignment->id ";
                    $gradedassignments      = $DB->get_records_sql($sql_gradedassignment);
			foreach($gradedassignments as $gradedassignment)
                	{
                             if($gradedassignment->assignment)
                   		 {
                       		 $select    =   "id=$gradedassignment->userid";
                     	 	 $student   =   $DB->get_record_select('user', $select);
                       		 $query     =   array('student_name'=>$student->username,'districtid'=>$student->districtid,'assignment_id'=>$gradedassignment->assignment,'course_period_id'=>$key->sis_periodid,'points'=>$gradedassignment->grade,'comment'=>$gradedassignment->submissioncomment,'module'=>assign);
                        	 $query_info[]=$query;
                    		}
			}
                }
              /************************ prepare Quiz grades array *******************************************/
              /*  $sql_quiz               = "SELECT * FROM {$CFG->prefix}quiz  WHERE course = $key->id ";
                $quizs                  = $DB->get_records_sql($sql_quiz  );
                foreach($quizs    as $quiz)
                    {
                        $sql_gradedquiz = "SELECT  *  FROM {$CFG->prefix}quiz_grades   WHERE quiz  = $quiz->id ";
                        $gradedquizs    = $DB->get_records_sql($sql_gradedquiz );
                        foreach($gradedquizs as $gradedquiz )
                        {
                            if($gradedquiz->quiz  )
                                {
                                $select         =   "id=$gradedquiz->userid";
                                $student        =   $DB->get_record_select('user', $select);
                                $query2         =   array('student_name'=>$student->username,'districtid'=>$student->districtid,'assignment_id'=>$gradedquiz->quiz  ,'course_period_id'=>$key->sis_periodid,'points'=>$gradedquiz->grade,'comment'=>"",'module'=>quiz);
                                $query_info[]   =   $query2;
                                }
                        }
                    }*/
              /************************ prepare Lesson grades array *******************************************/
              /*  $sql_lesson                     = "SELECT * FROM {$CFG->prefix}lesson  WHERE course = $key->id ";
                $lessons                        = $DB->get_records_sql($sql_lesson  );
                foreach($lessons    as $lesson)
                    {
                        $sql_gradedlesson       = "SELECT  *  FROM {$CFG->prefix}lesson_grades   WHERE lessonid  = $lesson->id ";
                        $gradedlessons          = $DB->get_records_sql($sql_gradedlesson );
                        foreach($gradedlessons as $gradedlesson)
                        {
                            if($gradedlesson->lessonid  )
                                {
                                $select         =   "id=$gradedlesson->userid";
                                $student        =   $DB->get_record_select('user', $select);
                                $points         =   number_format($gradedlesson->grade * $gradedlesson->grade / 100, 1);
                                $query3         =   array('student_name'=>$student->username,'districtid'=>$student->districtid,'assignment_id'=>$gradedlesson->lessonid  ,'course_period_id'=>$key->sis_periodid,'points'=>$gradedlesson->grade,'comment'=>"",'module'=>lesson);
                                $query_info[]   =   $query3;
                                }
                        }
                    }*/
                /************************ prepare Hotpot grades array *******************************************/
//                $sql_hotpot                     = "SELECT * FROM {$CFG->prefix}hotpot  WHERE course = $key->id ";
//                $hotpots                        = $DB->get_records_sql($sql_hotpot  );
//                foreach($hotpots    as $hotpot)
//                    {
//                        $sql_gradedhotpot       = "SELECT  *  FROM {$CFG->prefix}hotpot_attempts  WHERE hotpot  = $hotpot->id ";
//                        $gradedhotpots          = get_records_sql($sql_gradedhotpot );
//                        foreach($gradedhotpots as $gradedhotpot)
//                        {
//                            if($gradedhotpot->hotpot  )
//                                {
//                                $select         =   "id=$gradedhotpot->userid";
//                                $student        =   $DB->get_record_select('user', $select);
//                                $query4         =   array('student_name'=>$student->username,'districtid'=>$student->districtid,'assignment_id'=>$gradedhotpot->hotpot  ,'course_period_id'=>$key->sis_periodid,'points'=>$gradedhotpot->score,'comment'=>"",'module'=>hotpot);
//                                $query_info[]   =   $query4;
//                                }
//                        }
//                    }    
             /****************************************************************************************************/
            }
            XMLRPC_response(XMLRPC_prepare($query_info), KD_XMLRPC_USERAGENT);
   
     
 }

 ?>