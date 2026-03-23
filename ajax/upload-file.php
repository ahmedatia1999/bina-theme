<?php

add_action('init', function(){

    $ajax = new bina_AJAX(['editor', 'administrator']);
    
    $file = $_FILES['fileToUpload'] ?? false;
    
    if(!$file){
        $ajax->error('عفوا، خطأ في جلب الملف');
    }
    
    $file_result = upload_file($file);

    if($file_result->moved){
        $result['description'] = "تم الرفع";
        $result['data'] = array(
            'url'   => $file_result->url,
            'options' => array(
                'reset' => true
            )
        );
        $ajax->success($result);
    }else{
        $ajax->error('حدث خطأ في رفع الملف');
    }



}, 1);


