<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function quiz_grade($attempt)
{
        global $CFG;
        global $DB;
       
        $quiz_id=$attempt->quiz;
        $user_id=$attempt->userid;
        $select="id=$quiz_id";
        $quiz=$DB->get_record_select('quiz', $select);
        $select1="id=$quiz->course";
        $course_period=$DB->get_record_select('course', $select1);
        
        $course_period_id=$course_period->sis_periodid;
        $select2="contextlevel=50 and instanceid=$course_period->id";
        $context=$DB->get_record_select('context', $select2);
        
        $select3="roleid=5 and contextid=$context->id and userid=$user_id";
        $record=$DB->get_record_select('role_assignments', $select3);
        
        if($record)
        {
            
           $select4="id=$record->userid";
           $username=$DB->get_record_select('user', $select4);
//           $select5="id=$course_period->category";
//           $course=get_record_select('course_categories', $select5);
//           $course_id=$course->sis_courseid;
             $select5="id=$course_period->category";
             $course1=$DB->get_record_select('course_categories', $select5);
             $catids=explode('/',$course1->path) ;
              $count=count($catids);
            if($count==2)
                 $selectcat="id=$catids[1]";
            if($count>=3)
                 $selectcat="id=$catids[2]";
			if($count>=4)
                 $selectcat="id=$catids[3]";
			if($count>=5)
                 $selectcat="id=$catids[3]";	 
            $course=$DB->get_record_select('course_categories', $selectcat);
            if($course->sis_courseid)
                    $course_id=$course->id;
           $sql1="select qg.grade from {$CFG->prefix}quiz_grades qg where quiz=$quiz_id and userid=$record->userid";
           $grade=$DB->get_field_sql($sql1);
           if($course_id && $course_period_id)
             {
               $query_info=array('student_name'=>$username->username,'assignment_id'=>$quiz_id,'course_period_id'=>$course_period_id,'points'=>$grade,'updated_grade'=>$quiz->grade);
               require_once ("Set_Site.php");
               list($success, $response) = XMLRPC_request(
                                                 $site,
                                                 $location,
                                                 'quiz_grading',
                                                 array(XMLRPC_prepare($query_info)));
              if($response)
                    {
                       return $response;
                    }
            }
       }
}





function quiz_withgrade($attempt)
{
        define("XMLRPC_DEBUG", 1);
        global $CFG;
        global $DB;
        ############# creation of quiz in sis database if it doen not exist ################################      
            $select                          = "id=$attempt->quiz";
            $assignment_details              = $DB->get_record_select('quiz',$select);
            $assignment_details->modulename  = "quiz";
          
            $select1                         = "id=$assignment_details->course";
            $course_period                   = $DB->get_record_select('course',$select1);
            $course_period_id                = $course_period->sis_periodid;
            $select2                         = "id=$course_period->category";
            $course1                         = $DB->get_record_select('course_categories', $select2);
            $catids                          = explode('/',$course1->path) ;
            $count                           = count($catids);
            if($count==2)
                    $selectcat              = "id=$catids[1]";
            if($count>=3)
                 $selectcat                 = "id=$catids[2]";
			if($count>=4)
                 $selectcat="id=$catids[3]";
			if($count>=5)
                 $selectcat="id=$catids[3]";
            $course                         = $DB->get_record_select('course_categories', $selectcat);
            if($course->sis_courseid)
                    $course_id              = $course->id;
            $context                        = "contextlevel=50 AND instanceid=$assignment_details->course";
            $context_details                = $DB->get_record_select('context', $context);
//            if(!$assignment_details->instance)
//                $assignment_details->instance = $assignment_details->id;

            if($assignment_details->modulename=='quiz' && $assignment_details->timeopen  && $course_id && $course_period_id)
               {
                            $query_assgn=array('assigned_date'=>$assignment_details->timeopen,'due_date'=>$assignment_details->timeclose,'course_period_id'=>$course_period_id,'title'=>$assignment_details->name,'points'=>$assignment_details->grade,'description'=>$assignment_details->intro,'course_id'=>$course_id,'moodle_assignment_id'=>$assignment_details->id,'module'=>quiz);
               }
                                         
     
   ############# grading of students in sis database if it doen not exist ################################ 
       
        $quiz_id            = $attempt->quiz;
        $user_id            = $attempt->userid;
        $select             = "id=$quiz_id";
        $quiz               = $DB->get_record_select('quiz', $select);
        $select1            = "id=$quiz->course";
        $course_period      = $DB->get_record_select('course', $select1);
        $course_period_id   = $course_period->sis_periodid;
       // $select2            = "contextlevel=50 and instanceid=$course_period->id";
       //$context            = $DB->get_record_select('context', $select2);
       // $select3            = "roleid=5 and contextid=$context->id and userid=$user_id";
       // $record             = $DB->get_record_select('role_assignments', $select3);
	  //	print_R($record);exit;
        if($user_id)
        {
           $select4         = "id=$user_id";
           $username        = $DB->get_record_select('user', $select4);
           $select5         = "id=$course_period->category";
           $course1         = $DB->get_record_select('course_categories', $select5);
           $catids          = explode('/',$course1->path) ;
           $count=count($catids);
             if($count==2)
                    $selectcat="id=$catids[1]";
            if($count>=3)
                 $selectcat="id=$catids[2]";
				 
			if($count>=4)
                 $selectcat="id=$catids[3]";
			if($count>=5)
                 $selectcat="id=$catids[3]";
            $course         = $DB->get_record_select('course_categories', $selectcat);
            if($course->sis_courseid)
                    $course_id=$course->id;
           $sql1            = "select qg.grade from {$CFG->prefix}quiz_grades qg where quiz=$quiz_id and userid=$user_id";
           $grade           = $DB->get_field_sql($sql1);
           if($course_id && $course_period_id)
             {
               $query_grade=array('student_name'=>$username->username,'districtid'=>$username->districtid,'assignment_id'=>$quiz_id,'course_period_id'=>$course_period_id,'points'=>$grade,'updated_grade'=>$quiz->grade);
               $query_info=array('assignment'=>$query_assgn,'grade'=>$query_grade);
               require_once ("Set_Site.php");
               list($success, $response) = XMLRPC_request(
                                                 $site,
                                                 $location,
                                                 'quiz_withgrade',
                                                 array(XMLRPC_prepare($query_info)));
              
            }
       }
       
//       XMLRPC_debug_print ();exit;
       if($response)
                    {
                       return $response;
                    }
}
?>
