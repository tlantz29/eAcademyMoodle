<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function sisupdation($category)
{
   global $DB;
   $select="id=$category->id";
   $cat=$DB->get_record_select('course_categories', $select);
   $query=array('TITLE'=>$cat->name,'MOODLE_COURSEID'=>$cat->id,'SIS_ID'=>$cat->sis_courseid);
   if($cat->sis_courseid){
   define("XMLRPC_DEBUG", 1);
   require_once ("Set_Site.php");
   list($success, $response) = XMLRPC_request(
                                         $site,
                                        $location,
                                        'siscatupdate',
                                     array(XMLRPC_prepare($query)));

      if($response)
       return $response;
   }
  
}

function siscourseupdation($data)
{
   global $DB;
   $select="id=$data->id";
   $cat=$DB->get_record_select('course', $select);
   $query=array('id'=>$data->id,'fullname'=>$data->fullname,'shortname'=>$data->shortname,'startdate'=>$data->startdate,'sis_id'=>$cat->sis_periodid);
   if($cat->sis_periodid){
   define("XMLRPC_DEBUG", 1);
   require_once ("Set_Site.php");
   list($success, $response) = XMLRPC_request(
                                         $site,
                                        $location,
                                        'siscourseupdate',
                                     array(XMLRPC_prepare($query)));

    if($response)
       return $response;
   }
}

function role($role)
{
    global $DB;
    foreach ($role as $user => $uvalue) {
        foreach($uvalue as $uvalkey => $uvalvalue)
           {
           if($uvalkey==roles)
               {
               foreach($uvalvalue as $rolekey=>$rolvalue)
                    {
                                if($rolvalue[text]==Student)
                                    {
                                       # $student[]=array();
                                        $select="id=".$uvalue['userid']."";
                                        $user=$DB->get_record_select('user',$select);
                                        $selectcourse="id=".$uvalue['courseid'] ."";
                                        $course=$DB->get_record_select('course',$selectcourse);
                                        foreach($uvalue['enrolments'] as $enrolkey)
                                              {
                                                     $dates=explode(",",$enrolkey['period']);
                                                     if($dates[1])
                                                     $startdate=strtotime($dates[1])+12*3600;
                                                     if($dates[3])
                                                     $enddate= strtotime($dates[3])+12*3600;
                                                 else {
                                                     $enddate="null";
                                                 }
                                        }
                                      $student[]=array('sis_id'=>$course->sis_periodid,'username'=>$user->username,'role'=>$rolekey,'timestart'=>date("Y-m-d", $startdate),'timeend'=>date("Y-m-d",$enddate));
                                      break;

                                    
                                    }
                                    elseif($rolvalue[text]==Teacher)
                                    {
                                      # $teacher[]=array();
                                       $select="id=".$uvalue['userid']."";
                                       $user=$DB->get_record_select('user',$select);
                                       $selectcourse="id=".$uvalue['courseid'] ."";
                                       $course=$DB->get_record_select('course',$selectcourse);
                                       foreach($uvalue['enrolments'] as $enrolkey)
                                              {
                                                     $dates=explode(",",$enrolkey['period']);
                                                      if($dates[1])
                                                     $startdate=strtotime($dates[1])+12*3600;
                                                      if($dates[3])
                                                     $enddate= strtotime($dates[3])+12*3600;
                                                        else {
                                                     $enddate="null";
                                                 }
                                        }
                                    }
                                    $teacher[]=array('sis_id'=>$course->sis_periodid,'username'=>$user->username,'role'=>$rolekey,'timestart'=>date("Y-m-d", $startdate),'timeend'=>date("Y-m-d",$enddate));
                                    break;
                                   
                              }
                         }
                    }
      
      }
            $details[]=array('student'=>$student,'teacher'=>$teacher);
            define("XMLRPC_DEBUG", 1);
            require_once ("Set_Site.php");
            list($success, $response) = XMLRPC_request(
                                                 $site,
                                                $location,
                                                'roleassign',
                                             array(XMLRPC_prepare($details)));
                             
            if($response)
              return $response;
}


function unenrol($param,$user)
{
   global $DB;
   global $CFG;
   $sqlcourse="SELECT * FROM `{$CFG->prefix}course` WHERE `id`=$param->courseid";
   $periods=$DB->get_record_sql($sqlcourse);
   $context=get_context_instance(CONTEXT_COURSE,$param->courseid);
   $sql="SELECT * FROM {$CFG->prefix}role_assignments WHERE contextid=$context->id AND userid=$user->userid AND (roleid=5 OR roleid=3)";
   $record=$DB->get_records_sql($sql);
   foreach($record as $roleid)
   {
       if($roleid->roleid==3)
           {
           $userdetails=$DB->get_record('user', array('id'=>$roleid->userid), '*', MUST_EXIST);
           $teacher[]=array('role'=>$roleid->roleid,'user'=>$userdetails->username);
       }
       elseif($roleid->roleid==5)
           {
            $userdetails=$DB->get_record('user', array('id'=>$roleid->userid), '*', MUST_EXIST);
            $student[]=array('role'=>$roleid->roleid,'user'=>$userdetails->username);
       }
   }
   $details[]=array('teacher'=>$teacher,'student'=>$student,'sis_id'=>$periods->sis_periodid);
   if($periods->sis_periodid)
           {

                   define("XMLRPC_DEBUG", 1);
                   require_once ("Set_Site.php");
                   list($success,$response) = XMLRPC_request(
                                                                $site,
                                                                $location,
                                                                'unenrol',
                                                             array(XMLRPC_prepare($details)));

                                           
           } 
}
?>
