<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function update_user123($user)
        {

$query_info=array('username'=>$user->username,'firstname'=>$user->firstname,'lastname'=>$user->lastname,'email'=>$user->email,'phone'=>$user->phone1);
             define("XMLRPC_DEBUG", 1);
            require_once ("Set_Site.php");
                    list($success, $response) = XMLRPC_request(
                                                                     $site,
                                                                     $location,
                                                                     'updateuser',
                                                                     array(XMLRPC_prepare($query_info)));
                                                     
                    if($response)
                                {
                        return $response;
                                }
       
}

function update_user12($userne)
        {
            global $DB;
            $select="id=$userne->id";
            $user=$DB->get_record_select('user', $select);
            $query_info=array('username'=>$user->username,'firstname'=>$user->firstname,'lastname'=>$user->lastname,'email'=>$user->email,'phone'=>$user->phone1);
            define("XMLRPC_DEBUG", 1);
            require_once ("Set_Site.php");
            list($success, $response) = XMLRPC_request(
                                                        $site,
                                                        $location,
                                                        'updateuser',
                                                        array(XMLRPC_prepare($query_info)));
                                                        
            if($response)
               {
                       return $response;
                }

}
?>
