<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function lessongrade($lesson,$grade,$user)
        {


             global $DB;
             $select="id=$lesson->course";
             $course_period=$DB->get_record_select('course',$select);
             $course_period_id=$course_period->sis_periodid;
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
             if($course_id && $course_period_id)
                    {
                            $points=($grade->earned/$grade->total)*$lesson->grade;
                            $query_info=array('student_name'=>$user->username,'assignment_id'=>$lesson->id,'course_period_id'=>$course_period_id,'points'=>$points);
                            define("XMLRPC_DEBUG", 1);
                            require_once ("Set_Site.php");
                            list($success, $response) = XMLRPC_request(
                                                                             $site,
                                                                             $location,
                                                                             'lesson_grading',
                                                                             array(XMLRPC_prepare($query_info)));
                                                            
                            if($response)
                                        {
                                          return;
                                        }
                        
    }
  
    
}



function lesson_withgrade($lesson,$grade,$user)
{
        define("XMLRPC_DEBUG", 1);
        global $CFG;
        global $DB;
        ############# creation of quiz in sis database if it doen not exist ################################      
            $select                          = "id=$lesson->id";
            $lesson_details                  = $DB->get_record_select('lesson',$select);
            $lesson_details->modulename      = "lesson";
          
            $select1                         = "id=$lesson_details->course";
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
            $course                         = $DB->get_record_select('course_categories', $selectcat);
            if($course->sis_courseid)
                    $course_id              = $course->id;
            $context                        = "contextlevel=50 AND instanceid=$lesson_details->course";
            $context_details                = $DB->get_record_select('context', $context);
//            if(!$lesson_details->instance)
//                $lesson_details->instance = $lesson_details->id;

            if ($lesson_details->modulename == 'lesson' && $lesson_details->available && $lesson_details->practice == 0 && $course_id && $course_period_id) {
                    $query_info = array('assigned_date' => $lesson_details->available, 'due_date' => $lesson_details->deadline, 'course_period_id' => $course_period_id, 'title' => $lesson_details->name, 'points' => $lesson_details->grade, 'course_id' => $course_id, 'moodle_assignment_id' => $lesson_details->id, 'module' => lesson, 'description' => "");
                }
                                         
     
   ############# grading of students in sis database if it does not exist ################################ 
       
             $select            =   "id=$lesson->course";
             $course_period     =   $DB->get_record_select('course',$select);
             $course_period_id  =   $course_period->sis_periodid;
             $select5           =   "id=$course_period->category";
             $course1           =   $DB->get_record_select('course_categories', $select5);
             $catids            =   explode('/',$course1->path) ;
             $count             =   count($catids);
             if($count==2)
                    $selectcat  =   "id=$catids[1]";
            if($count>=3)
                 $selectcat     =   "id=$catids[2]";
			if($count>=4)
                 $selectcat="id=$catids[3]";
			if($count>=5)
                 $selectcat="id=$catids[3]";
            $course             =   $DB->get_record_select('course_categories', $selectcat);
            if($course->sis_courseid)
                    $course_id  =   $course->id;
            
            $points             =   ($grade->earned/$grade->total)*$lesson->grade;
            $query_grade        =   array('student_name'=>$user->username,'assignment_id'=>$lesson->id,'course_period_id'=>$course_period_id,'points'=>$points);
            $query_info         =   array('assignment'=>$query_assgn,'grade'=>$query_grade);
            require_once ("Set_Site.php");
            list($success, $response) = XMLRPC_request(
                                                 $site,
                                                 $location,
                                                 'quiz_withgrade',
                                                 array(XMLRPC_prepare($query_info)));
              
    
//       XMLRPC_debug_print ();exit;
       if($response)
                    {
                       return $response;
                    }
}
?>
