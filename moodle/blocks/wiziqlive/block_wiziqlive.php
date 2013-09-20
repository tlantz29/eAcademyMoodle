<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php 
/*
 * wiziq.com Module
 * WiZiQ's Live Class modules enable Moodle users to use WiZiQ�s web based virtual classroom equipped with real-time collaboration tools 
 * Basic page for  WiZiQ block in moodle. 
 */
 /**
 * @package mod
 * @subpackage wiziq
 * @author preeti chauhan(preetic@wiziq.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
    	
class block_wiziqlive extends block_base {

    function init() {
        $this->title = get_string('modulename', 'wiziq');
    }

    function get_content() {
        global $USER, $CFG, $SESSION, $COURSE,$DB,$str,$role;
        $cal_m = optional_param( 'cal_m', 0, PARAM_INT );
        $cal_y = optional_param( 'cal_y', 0, PARAM_INT );

        require_once($CFG->dirroot.'/calendar/lib.php');
		require_once($CFG->dirroot.'/mod/wiziq/locallib.php');
$courseID=$this->page->course->id;
        if ($this->content !== NULL) {
            return $this->content;
        }
         // Reset the session variables
        //calendar_session_vars($this->page->course);
        $this->content = new stdClass;
        $this->content->text = '';

        if (empty($this->instance)) { // Overrides: use no course at all
        
            $courseshown = false;
            $filtercourse = array();
            $this->content->footer = '';

        } else { // for having role of user in class
if(!empty($USER->id))			
$role=wiziq_GetUserRole($courseID);
else
$role=6;
//------------------finding the capability of role-------------
$wiziq_capability=wiziq_hascapability($courseID);
$courseshown = $courseID;
if($courseshown!=1)
{
$str='<a href='.$CFG->wwwroot.'/mod/wiziq/managecontent.php?course='.$courseshown.'>Manage or Upload Content</a>';	
}
if($role=='6' || $role=='4' || $role=='5' || $wiziq_capability==false)// Role 6-guest, 4-Non-Editing Teacher, 5-Student
{
$courseshown = $courseID;
            $this->content->footer = '<div class="gotocal"><a href="'.$CFG->wwwroot.
                                     '/calendar/view.php?view=upcoming&amp;course='.$courseshown.'">'.
                                      get_string('gotocalendar', 'calendar').'</a>...</div>
									  <a href='.$CFG->wwwroot.'/mod/wiziq/index.php?course='.$courseshown.'>WiZiQ Classes</a>...';
            $context = get_context_instance(CONTEXT_COURSE, $courseshown);
       	
}

if($role=='2' || $role=='3' || $role=='1' || $wiziq_capability==true) // Role 2-course creator, 3-Teacher, 1-Admin
{
	$courseshown = $courseID;
            $this->content->footer = '<div class="gotocal"><a href="'.$CFG->wwwroot.
                                     '/calendar/view.php?view=upcoming&amp;course='.$courseshown.'">'.
                                      get_string('gotocalendar', 'calendar').'</a>...</div>
			<a href='.$CFG->wwwroot.'/mod/wiziq/wiziq_list.php?course='.$courseshown.'>WiZiQ Classes</a>...<br/>'.$str;
            $context = get_context_instance(CONTEXT_COURSE, $courseshown);
}

            if ($courseshown == SITEID) {
                // Being displayed at site level. This will cause the filter to fall back to auto-detecting
                // the list of courses it will be grabbing events from.
               $filtercourse = calendar_get_default_courses();
            } else {
                // Forcibly filter events to include only those from the particular course we are in.
                $filtercourse = array($courseshown => $this->page->course);
            }
        }

        // We 'll need this later
       // calendar_set_referring_course($courseshown);

        // Be VERY careful with the format for default courses arguments!
        // Correct formatting is [courseid] => 1 to be concise with moodlelib.php functions.
list($courses, $group, $user) = calendar_set_filters($filtercourse);
         //calendar_set_filters($courses, $group, $user, $filtercourse, $groupeventsfrom, false);
        //$events = calendar_get_upcoming($courses, $group, $user,
                                       // get_user_preferences('calendar_lookahead', CALENDAR_UPCOMING_DAYS),
                                       // get_user_preferences('calendar_maxevents', CALENDAR_UPCOMING_MAXEVENTS));
$defaultlookahead = CALENDAR_DEFAULT_UPCOMING_LOOKAHEAD;
        if (isset($CFG->calendar_lookahead)) {
            $defaultlookahead = intval($CFG->calendar_lookahead);
        }
        $lookahead = get_user_preferences('calendar_lookahead', $defaultlookahead);

        $defaultmaxevents = CALENDAR_DEFAULT_UPCOMING_MAXEVENTS;
        if (isset($CFG->calendar_maxevents)) {
            $defaultmaxevents = intval($CFG->calendar_maxevents);
        }
        $maxevents = get_user_preferences('calendar_maxevents', $defaultmaxevents);
        $events = calendar_get_upcoming($courses, $group, $user, $lookahead, $maxevents);

        if (!empty($this->instance)) {
			$this->content->text='<div >'; 
            $this->content->text =$this->content->text . calendar_get_block_upcoming($events,
                                   'view.php?view=day&amp;course='.$courseshown.'&amp;');
			$this->content->text=$this->content->text .'</div>'; 
        }

        if (empty($this->content->text)) {
            $this->content->text = '<div class="post">'.
                                   get_string('noupcomingevents', 'calendar').'</div>';
        }

        return $this->content;
    }
}

?>
