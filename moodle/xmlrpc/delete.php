<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function deleteasgn($module)
{
           global $DB;
             $select1="id=$module->course";
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
			if($count>=4)
                 $selectcat="id=$catids[3]";
			if($count>=5)
                 $selectcat="id=$catids[3]";
            
            $course=$DB->get_record_select('course_categories', $selectcat);

            if($course->sis_courseid)
                    $course_id=$course->id;
            if($course_id && $course_period_id){
                     $query_info=array('assignment_id'=>$module->instance,'course_period_id'=>$course_period_id,'moodle_module'=>$module->module);

            define("XMLRPC_DEBUG", 1);

            require_once("Set_Site.php");
            list($success, $response) = XMLRPC_request(  $site,
                                                         $location,
                                                         'deleteassign',
                                                         array(XMLRPC_prepare($query_info)));
  XMLRPC_debug_print();
  

        }
 
}
?>
