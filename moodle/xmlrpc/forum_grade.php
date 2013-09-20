<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function forumgrade($forum,$grade,$user)
        {


             global $DB;
             $select="id=$forum->course";
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
                            $points=($grade->earned/$grade->total)*$forum->grade;
                            $query_info=array('student_name'=>$user->username,'assignment_id'=>$forum->id,'course_period_id'=>$course_period_id,'points'=>$points);
                            define("XMLRPC_DEBUG", 1);
                            require_once ("Set_Site.php");
                            list($success, $response) = XMLRPC_request(
                                                                             $site,
                                                                             $location,
                                                                             'forum_grading',
                                                                             array(XMLRPC_prepare($query_info)));
                                                            
                            if($response)
                                        {
                                          return;
                                        }
                        
    }
  
    
}



function forum_withgrade($forum,$grade,$user)
{
        define("XMLRPC_DEBUG", 1);
        global $CFG;
        global $DB;
        ############# creation of quiz in sis database if it doen not exist ################################      
            $select                          = "id=$forum->id";
            $forum_details                  = $DB->get_record_select('forum',$select);
            $forum_details->modulename      = "forum";
          
            $select1                         = "id=$forum_details->course";
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
            $context                        = "contextlevel=50 AND instanceid=$forum_details->course";
            $context_details                = $DB->get_record_select('context', $context);
//            if(!$forum_details->instance)
//                $forum_details->instance = $forum_details->id;

            if ($forum_details->modulename == 'forum' && $forum_details->assesstimefinish && $course_id && $course_period_id) {
                    $query_info = array('assigned_date' => $forum_details->assesstimestart, 'due_date' => $forum_details->assesstimefinish, 'course_period_id' => $course_period_id, 'title' => $forum_details->name, 'points' => $forum_details->scale, 'course_id' => $course_id, 'moodle_assignment_id' => $forum_details->id, 'module' => forum, 'description' => "");
                }
                                         
     
   ############# grading of students in sis database if it does not exist ################################ 
       
             $select            =   "id=$forum->course";
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
            
            $points             =   ($grade->earned/$grade->total)*$forum->scale;
            $query_grade        =   array('student_name'=>$user->username,'assignment_id'=>$forum->id,'course_period_id'=>$course_period_id,'points'=>$points);
            $query_info         =   array('assignment'=>$query_assgn,'grade'=>$query_grade);
            require_once ("Set_Site.php");
            list($success, $response) = XMLRPC_request(
                                                 $site,
                                                 $location,
                                                 'forum_withgrade',
                                                 array(XMLRPC_prepare($query_info)));
              
    
      XMLRPC_debug_print ();exit;
       if($response)
                    {
                       return $response;
                    }
}
?>
