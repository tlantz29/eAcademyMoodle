<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function assignment($assignment_details)
{
            global $CFG;
           
            global $DB;
            $select1="id=$assignment_details->course";
            $course_period=$DB->get_record_select('course',$select1);
            $course_period_id=$course_period->sis_periodid;
            $select2="id=$course_period->category";
            $course1=$DB->get_record_select('course_categories', $select2);
            $catids=explode('/',$course1->path) ;
            $count=count($catids);
            if($count==2)
                    $selectcat="id=$catids[1]";
            if($count>=3)
                 $selectcat="id=$catids[2]";
            $course=$DB->get_record_select('course_categories', $selectcat);
            if($course->sis_courseid)
                    $course_id=$course->id;
            $context="contextlevel=50 AND instanceid=$assignment_details->course";
            $context_details=$DB->get_record_select('context', $context);
//            $role="roleid=3 AND contextid=$context_details->id";
//            $role_details=get_record_select('role_assignments', $role);
//            $user4="id=$role_details->userid";
//            $user=get_record_select('user', $user4);
            if($assignment_details->modulename=='quiz' && $assignment_details->timeopen  && $course_id && $course_period_id)
               {
                            $query_info=array('assigned_date'=>$assignment_details->timeopen,'due_date'=>$assignment_details->timeclose,'course_period_id'=>$course_period_id,'title'=>$assignment_details->name,'points'=>$assignment_details->grade,'description'=>$assignment_details->intro,'course_id'=>$course_id,'moodle_assignment_id'=>$assignment_details->instance,'module'=>quiz);
               }
            if($assignment_details->modulename=='assignment' && $assignment_details->timeavailable  && $course_id && $course_period_id)
               {
              
                            $query_info=array('assigned_date'=>$assignment_details->timeavailable,'due_date'=>$assignment_details->timedue,'course_period_id'=>$course_period_id,'title'=>$assignment_details->name,'points'=>$assignment_details->grade,'description'=>$assignment_details->intro,'course_id'=>$course_id,'moodle_assignment_id'=>$assignment_details->id,'module'=>assign);

//                            print_r($query_info);exit;

                            }
            if($assignment_details->modulename=='lesson' && $assignment_details->available && $assignment_details->practice==0  && $course_id && $course_period_id)
               {
                            $query_info=array('assigned_date'=>$assignment_details->available,'due_date'=>$assignment_details->deadline,'course_period_id'=>$course_period_id,'title'=>$assignment_details->name,'points'=>$assignment_details->grade,'course_id'=>$course_id,'moodle_assignment_id'=>$assignment_details->instance,'module'=>lesson,'description'=>"");
               }
                        
            define("XMLRPC_DEBUG", 1);
            require_once ("Set_Site.php");
            list($success, $response) = XMLRPC_request(
                                                             $site,
                                                             $location,
                                                             'assignment',
                                                             array(XMLRPC_prepare($query_info)));
xmlrpc_debug_print();exit;
                                             
            if($response)
                {
             return;

                }
               
  }


  function updateassignment($assignment_details)
          { 
                 global $DB;
                 
                $select1="id=$assignment_details->course";
                $course_period=$DB->get_record_select('course',$select1);
                $course_period_id=$course_period->sis_periodid;
                $select2="id=$course_period->category";
                $course1=$DB->get_record_select('course_categories', $select2);
                $catids=explode('/',$course1->path) ;
                $count=count($catids);
                if($count==2)
                        $selectcat="id=$catids[1]";
                if($count>=3)
                     $selectcat="id=$catids[2]";
                $course=$DB->get_record_select('course_categories', $selectcat);
                if($course->sis_courseid)
                        $course_id=$course->sis_courseid;
                $context="contextlevel=50 AND instanceid=$assignment_details->course";
                $context_details=$DB->get_record_select('context', $context);
//                $role="roleid=3 AND contextid=$context_details->id";
//                $role_details=get_record_select('role_assignments', $role);
//                $user4="id=$role_details->userid";
//                $user=get_record_select('user', $user4);
                if($assignment_details->modulename=='quiz'  && $course_id && $course_period_id)
                   {
                                $user=array('course_id'=>$course_id,'moodle_assignment_id'=>$assignment_details->instance,'module'=>quiz)    ;
                                $query=array('assigned_date'=>$assignment_details->timeopen,'due_date'=>$assignment_details->timeclose,'course_period_id'=>$course_period_id,'title'=>$assignment_details->name,'points'=>$assignment_details->grade,'description'=>$assignment_details->introeditor[text]);
                                $query_info=array('user'=>$user,'query'=>$query);
                   }
                if($assignment_details->modulename=='assignment'  && $course_id && $course_period_id)
                   {
                                $user=array('course_id'=>$course_id,'moodle_assignment_id'=>$assignment_details->instance,'module'=>assign)    ;
                                $query=array('assigned_date'=>$assignment_details->timeavailable,'due_date'=>$assignment_details->timedue,'course_period_id'=>$course_period_id,'title'=>$assignment_details->name,'points'=>$assignment_details->grade,'description'=>$assignment_details->introeditor[text]);
                                $query_info=array('user'=>$user,'query'=>$query);
                   }
                if($assignment_details->modulename=='lesson'  && $assignment_details->practice==0 && $course_id && $course_period_id)
                   {
                                $user=array('course_id'=>$course_id,'moodle_assignment_id'=>$assignment_details->instance,'module'=>lesson)    ;
                                $query=array('assigned_date'=>$assignment_details->available,'due_date'=>$assignment_details->deadline,'course_period_id'=>$course_period_id,'title'=>$assignment_details->name,'points'=>$assignment_details->grade,'description'=>"");
                                $query_info=array('user'=>$user,'query'=>$query);

                   }

               define("XMLRPC_DEBUG", 1);
                require_once ("Set_Site.php");
                list($success, $response) = XMLRPC_request(
                                                                 $site,
                                                                 $location,
                                                                 'updateassign',
                                                                 array(XMLRPC_prepare($query_info)));
                                                 
                                                 
                if($response)
                     {
                      return;
                    }
                   
      
          }

 function assignment_grade($val)
     {
        foreach($val as $value)
                {
                     global $CFG;
                     global $DB;
                     $select="id=$value->userid";
                     $student=$DB->get_record_select('user', $select);
                    
                     $select2="id=$value->assignment";
                     $assignment_details=$DB->get_record_select('assignment', $select2);

                     $select3="id=$assignment_details->course";
                     $course_period=$DB->get_record_select('course',$select3);
                      
                     $course_period_id=$course_period->sis_periodid;
                     $select2="id=$course_period->category";
                     $course1=$DB->get_record_select('course_categories', $select2);
                     $catids=explode('/',$course1->path) ;
                     $count=count($catids);
                        if($count==2)
                                $selectcat="id=$catids[1]";
                        if($count>=3)
                             $selectcat="id=$catids[2]";
                        $course=$DB->get_record_select('course_categories', $selectcat);
                        if($course->sis_courseid)
                                $course_id=$course->id;
                         if($course_id && $course_period_id){
                             $query=array('student_name'=>$student->username,'assignment_id'=>$value->assignment,'course_period_id'=>$course_period_id,'points'=>$value->grade,'comment'=>$value->submissioncomment);
                             $query_info[]=$query;
                                               }
                                    }
                define("XMLRPC_DEBUG", 1);
                require_once ("Set_Site.php");
                list($success, $response) = XMLRPC_request(
                                                                 $site,
                                                                 $location,
                                                                 'assgngrade',
                                                                 array(XMLRPC_prepare($query_info)));
            //xmlrpc_debug_print();  exit;
                                                
           if($response)
                {
                    return;
                }

 }

 function gradertable($details){
     global $CFG;
     global $DB;
     
     foreach($details as $key => $value)
     {
         if($key=='id'){
             $courseid=$value;
               }
         if($key!='id' && $key!='sesskey' && $key!='report')
             {
                    $arrr=explode("_",$key);
//                    $selectginfo="id=$arrr[2]";
                     $itemid = $arrr[2];
//                    $gradeinf=$DB->get_record_select('grade_items', $selectginfo);
//                    
//                    if($arrr[0]!='oldgrade' && $gradeinf->categoryid)
//                    {
                        $oldvalue = $details->{'old'.$key};
						//echo $oldvalue."-".$value."<br>";
                        if ($oldvalue != $value && $value!="" )
                        $user[]= array('user'=>$arrr[1],'itemid'=>$itemid,'newgrd'=>$value);

//                    }
             }

     }
	 
     $select3           = "id=$courseid";
     $course_period     = $DB->get_record_select('course',$select3);
     $course_period_id  = $course_period->sis_periodid;
//     $select2="id=$course_period->category";
//     $course1=$DB->get_record_select('course_categories', $select2);
//     $catids=explode('/',$course1->path) ;
//     $count=count($catids);
//     if($count==2)
//     {
//          $selectcat="id=$catids[1]";
//     }
//     if($count>=3)
//     {
//           $selectcat="id=$catids[2]";
//     }
//    $course=$DB->get_record_select('course_categories', $selectcat);
//    if($course->sis_courseid)
//    {
//           $course_id=$course->id;
//    }
     
     if( $course_period_id)
         {
            foreach($user  as $index)
            {
        //         print_r($index);echo"<br>";
                //$select        =   "id=".$index['user'];
//                $student       =   $DB->get_record_select('user', $select);
                $userid =$index['user'];
                 $student = $DB->get_record('user', array('id' => $userid), 'username, districtid');
                $selectrec     =   "id=".$index['itemid'];
                $recd          =   $DB->get_record_select('grade_items', $selectrec);
        //         $selectrslt    =   "itemid=$recd->id AND userid=$student->id";
        //         $rslt          =   $DB->get_record_select('grade_grades',$selectrslt);
                 $rslt->finalgrade = $index['newgrd'];
                if($recd->itemmodule=='assignment' && $rslt->finalgrade){
                    $query=array('student_name'=>$student->username,'districtid'=>$student->districtid,'assignment_id'=>$recd->iteminstance,'course_period_id'=>$course_period_id,'points'=>$rslt->finalgrade,'comment'=>"");
                    $query_info[]=array('mod'=>assignment,'querry'=>$query);
                }
                if($recd->itemmodule=='lesson' && $rslt->finalgrade){
                    $query=array('student_name'=>$student->username,'districtid'=>$student->districtid,'assignment_id'=>$recd->iteminstance,'course_period_id'=>$course_period_id,'points'=>$rslt->finalgrade);
                    $query_info[]=array('mod'=>lesson,'querry'=>$query);
                }
                if($recd->itemmodule=='quiz' && $rslt->finalgrade){
                    $selectq="id=$recd->iteminstance";
                    $quiz=$DB->get_record_select('quiz', $selectq);
                    $query=array('student_name'=>$student->username,'districtid'=>$student->districtid,'assignment_id'=>$quiz->id,'course_period_id'=>$course_period_id,'points'=>$rslt->finalgrade,'updated_grade'=>$quiz->grade);
                    $query_info[]=array('mod'=>quiz,'querry'=>$query);
                }
            }
                define("XMLRPC_DEBUG", 1);
                require_once ("Set_Site.php");
                list($success, $response) = XMLRPC_request(
                                                                 $site,
                                                                 $location,
                                                                 'grader',
                                                                 array(XMLRPC_prepare($query_info)));
      //XMLRPC_debug_print();  exit;                                           
      if($response)
                {
                    return;
                }
 }
 }




function assignment_withgrade($assignmentid,$val)
 {
			//print_R($val);exit;
           define("XMLRPC_DEBUG", 1);
           global $CFG;
           global $DB;
        ############# creation of assignment in sis database if it doen not exist ################################      
            $select                          = "id=$assignmentid";
            $assignment_details              = $DB->get_record_select('assignment',$select);
            $assignment_details->modulename  = "assignment";
			
            $select1          = "id=$assignment_details->course";
            $course_period    = $DB->get_record_select('course',$select1);
            $course_period_id = $course_period->sis_periodid;
            $select2          = "id=$course_period->category";
            $course1          = $DB->get_record_select('course_categories', $select2);
            $catids           = explode('/',$course1->path) ;
            $count=count($catids);
            if($count==2)
                    $selectcat="id=$catids[1]";
            if($count>=3)
                 $selectcat="id=$catids[2]";
            $course=$DB->get_record_select('course_categories', $selectcat);
            if($course->sis_courseid)
                    $course_id=$course->id;
            $context="contextlevel=50 AND instanceid=$assignment_details->course";
            $context_details=$DB->get_record_select('context', $context);
//            if(!$assignment_details->instance)
//                $assignment_details->instance = $assignment_details->id;

//            if($assignment_details->modulename=='quiz' && $assignment_details->timeopen  && $course_id && $course_period_id)
//               {
//                            $query_assgn=array('assigned_date'=>$assignment_details->timeopen,'due_date'=>$assignment_details->timeclose,'course_period_id'=>$course_period_id,'title'=>$assignment_details->name,'points'=>$assignment_details->grade,'description'=>$assignment_details->intro,'course_id'=>$course_id,'moodle_assignment_id'=>$assignment_details->id,'module'=>quiz);
//               }
            if($assignment_details->modulename=='assignment' && $assignment_details->timeavailable  && $course_id && $course_period_id)
               {
              
                            $query_assgn=array('assigned_date'=>$assignment_details->timeavailable,'due_date'=>$assignment_details->timedue,'course_period_id'=>$course_period_id,'title'=>$assignment_details->name,'points'=>$assignment_details->grade,'description'=>$assignment_details->description,'course_id'=>$course_id,'moodle_assignment_id'=>$assignment_details->id,'module'=>assign);
               }
//            if($assignment_details->modulename=='lesson' && $assignment_details->available && $assignment_details->practice==0  && $course_id && $course_period_id)
//               {
//                            $query_assgn=array('assigned_date'=>$assignment_details->available,'due_date'=>$assignment_details->deadline,'course_period_id'=>$course_period_id,'title'=>$assignment_details->name,'points'=>$assignment_details->grade,'course_id'=>$course_id,'moodle_assignment_id'=>$assignment_details->id,'module'=>lesson,'description'=>"");
//               }
                             
      
   ############# grading of students in sis database if it doen not exist ################################            
    foreach($val as $value)
    {
              global $CFG;
             $select               = "id=$value->userid";
             $student              = $DB->get_record_select('user', $select);
             $select2              = "id=$value->assignment";
             $assignment_details   = $DB->get_record_select('assignment', $select2);

             $select3              = "id=$assignment_details->course";
             $course_period        = $DB->get_record_select('course',$select3);
             $course_period_id     = $course_period->sis_periodid;
             $select2              = "id=$course_period->category";
             $course1              = $DB->get_record_select('course_categories', $select2);
             $catids               = explode('/',$course1->path) ;
             $count                = count($catids);
                if($count==2)
                        $selectcat = "id=$catids[1]";
                if($count>=3)
                     $selectcat    = "id=$catids[2]";
                $course            = $DB->get_record_select('course_categories', $selectcat);
                if($course->sis_courseid)
                        $course_id=$course->id;
                 if($course_id && $course_period_id && $value->grade!=-1){
                     $query=array('student_name'=>$student->username,'districtid'=>$student->districtid,'assignment_id'=>$value->assignment,'course_period_id'=>$course_period_id,'points'=>$value->grade,'comment'=>$value->submissioncomment);
                     $query_grade[]=$query;
                                       }
            }
        $query_info=array('assignment'=>$query_assgn,'grade'=>$query_grade);
        require_once ("Set_Site.php");
        list($success, $response) = XMLRPC_request(
                                                         $site,
                                                         $location,
                                                         'assgnwithgrade',
                                                         array(XMLRPC_prepare($query_info)));
   
   #XMLRPC_debug_print();  exit;
   if($response)
        {  
            return;
        }
        
       
 }
?>
