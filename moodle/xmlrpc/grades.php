<?php

            
            global $CFG;
            $data = new stdClass();
            $link=mysql_connect ("localhost" ,"root" ,"123456") or die("could not connect");
            mysql_select_db("moodle_running",$link)or die ("could not open db".mysql_error());
            $periods     =  mysql_query("SELECT * FROM `mdl_course` WHERE `sis_periodid`!=0 and id!=1");
          
            
            while($key = mysql_fetch_array($periods))
            {
                /************************ prepare Assignment grades array *******************************************/
              /*  $sql_assignment             = mysql_query("SELECT * FROM mdl_assignment  WHERE course =$key[id]") ;
               while($assignments = mysql_fetch_array($sql_assignment))

                {
                    $sql_gradedassignment   = mysql_query("SELECT * FROM mdl_assignment_submissions   WHERE assignment = $assignments[id]");
                    
                         while($gradedassignment = mysql_fetch_array($sql_gradedassignment))
                	{
                             if($gradedassignment['assignment'])
                   		 {
                                    $student                    = mysql_fetch_array(mysql_query( "SELECT * FROM mdl_user where id=$gradedassignment[userid]"));
                                    $student_name         =  addslashes($student['username']);
                                    $districtid           =  $student['districtid'];
                                    $assignment_id        =  $gradedassignment['assignment'];
                                    $course_period_id     =  $key['sis_periodid']; 
                                    $points               =  $gradedassignment['grade'];
                                    $comment              =  addslashes($gradedassignment['submissioncomment']);
                                    $module               =  "assign";
                                if($points!=-1)            
                                 echo $newcategory = mysql_query("insert into grades(student_name,districtid,assignment_id,course_period_id,points,comment,module)
                                      values('".$student_name."',$districtid,$assignment_id,$course_period_id,$points,'".$comment."','".$module."' )");
                      		 echo '<br>';
                    		}
			}
                }*/
              /************************ prepare Quiz grades array *******************************************/
               $sql_quiz               = mysql_query(" SELECT * FROM mdl_quiz  WHERE course = $key[id] ") ;
                while($quiz = mysql_fetch_array($sql_quiz))
                    { 
                        $sql_gradedquiz = mysql_query("SELECT  *  FROM mdl_quiz_grades   WHERE quiz  = $quiz[id] ");
                        
                        while($gradedquiz = mysql_fetch_array($sql_gradedquiz))
                        {
                            if($gradedquiz['quiz']  )
                                {
                                $student               =   mysql_fetch_array(mysql_query( "SELECT * FROM mdl_user where id=$gradedquiz[userid]"));
                                $student_name         =  $student['username'];
                                $districtid           =  $student['districtid'];
                                $assignment_id        =  $gradedquiz['quiz'];
                                $course_period_id     =  $key['sis_periodid']; 
                                $points               =  $gradedquiz['grade'];
                                $comment              =  "";
                                $module               =  "quiz";
                                if($points!=-1)            
                                 echo $newcategory = mysql_query("insert into grades(student_name,districtid,assignment_id,course_period_id,points,comment,module)
                                      values('".$student_name."',$districtid,$assignment_id,$course_period_id,$points,'".$comment."','".$module."' )");
                      		echo '<br>';
                                }
                        }
                    }
              /************************ prepare Lesson grades array *******************************************/
                $sql_lesson                     = mysql_query("SELECT * FROM mdl_lesson  WHERE course = $key[id] ") ;
                while($lesson = mysql_fetch_array($sql_lesson))
                    {
                        $sql_gradedlesson       = mysql_query("SELECT  *  FROM mdl_lesson_grades   WHERE lessonid  = $lesson[id]");
                        while($gradedlesson = mysql_fetch_array($sql_gradedlesson))
                        {
                            if($gradedlesson['lessonid']  )
                                {
                                $student              =   mysql_fetch_array(mysql_query( "SELECT * FROM mdl_user where id=$gradedlesson[userid]"));
                                $student_name         =  $student['username'];
                                $districtid           =  $student['districtid'];
                                $assignment_id        =  $gradedlesson['lessonid'];
                                $course_period_id     =  $key['sis_periodid']; 
                                $points               =  $gradedlesson['grade'];
                                $comment              =  "";
                                $module               =  "lesson";
                                if($points!=-1)            
                                 echo $newcategory = mysql_query("insert into grades(student_name,districtid,assignment_id,course_period_id,points,comment,module)
                                      values('".$student_name."',$districtid,$assignment_id,$course_period_id,$points,'".$comment."','".$module."' )");
                      		echo '<br>';
                                }
                        }
                    }
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
      
            
            ?>