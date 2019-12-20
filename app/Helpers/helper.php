<?php
 
if (!function_exists('convert_date')) {
    function convert_date($date){
      return date('Y-m-d', strtotime($date));
   }
}

if(!function_exists('store_files')) {
  function store_files($path,$file){
    $file_name = [];
    if(!is_array($file)){
      $name = time().$file->getClientOriginalName();
      $directory = $file->move($path,$name);
      $file_name[0] = '/public/uploads/'.$name;
      return $file_name;
    }  
    
    foreach($file as $key => $files){
        $name = time().$files->getClientOriginalName();
        $directory = $files->move($path,$name);
        $file_name[$key] = '/public/uploads/'.$name;
    }
    return $file_name;
  }
}

if(!function_exists('success_200')){
  function success_200($success,$data=[],$message=''){
    response()->json([
      'success' =>  $success,
      'data'    =>  $data,
      'message' =>  $message
    ],200)->send();
  }
}

if(!function_exists('error_404')){
  function error_404($success,$message){
    response()->json([
      'success' =>  $success,
      'message' =>  $message
    ],404)->send();
  }
}

if(!function_exists('bad_request')){
  function bad_request($success,$message){
    response()->json([
      'success' =>  $success,
      'message' =>  $message
    ],400)->send();
  }
}
?>