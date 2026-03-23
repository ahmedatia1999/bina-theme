<?php


// content type JSON
header('Content-Type: application/json; charset=utf-8');

// no cache
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

/**
 * 
 * Selectors are in below to this class: (to select ajax action file)
 * 
 */



// AJAX class 

if(!class_exists('bina_AJAX')){
    class bina_AJAX {

        function __construct($roles = []){
            if(empty($roles))return;

            $is_admin = false;
            if(current_user_can('administrator')){
                $role = 'administrator';
                $is_admin = true;
            }elseif(current_user_can('editor')){
                $role = 'editor';
                $is_admin = true;
            }elseif(current_user_can('contributor')){
                $role = 'contributor';
            }

            if(!in_array($role, $roles)){
                $this->error('لا يسمح لك بهذا الإجراء');
            }


        }

        // on succuss

        function success($result){

            $result = $this->get_result_array($result);
            
            // print and exit;
            die(json_encode(array(
                'status'    => 'ok',
                'result'    => $result
            )));
        }


        
        // on error

        function error($result){
            $result = $this->get_result_array($result);
            
            // print and exit;
            die(json_encode(array(
                'status'    => 'error',
                'result'    => $result
            )));
        }

        
        // prepare result

        function get_result_array($result = false){

            // get description

            $description = false; // default value
            if($result && !is_array($result)) $description = $result;
            if(isset($result['description'])) $description = $result['description'];

            // get extra resutl data
            $data = array();
            if(isset($result['data'])) $data = $result['data'];
            
            $result = array(
                'description'   => $description,
                'data'          => $data,
            );

            return $result;
        }



    
    /**
     * 
     * 
     * End
     */


    } // end if

} // end class



/////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////    SELECTORS    ///////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////




$action = $_GET['action'] ?? '';



// upload file
if($action == 'upload_file') require_once THEME_DIR . '/ajax/upload-file.php';
