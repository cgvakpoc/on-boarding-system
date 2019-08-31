<?php
 
if (!function_exists('convert_date')) {
    function convert_date($date){
      $convert_date = date('Y-m-d', strtotime($date));
      return $convert_date;
   }
}

if(!function_exists('store_files')) {
    function store_files($path,$file){
      $file_name = '';
      foreach($file as $files){
        $name = time().$files->getClientOriginalName();
        $directory = $files->move($path,$name);
        $file_name[] = '/public/uploads/'.$name;  
      } 
      return $file_name;
    }
}

if(!function_exists('success_200')){
  function success_200($success,$message='',$data=''){
    $result =  response()->json([
      'success' =>  $success,
      'message' =>  $message,
      'data'    =>  $data
    ],200)->send();
  }
}

if(!function_exists('error_404')){
  function error_404($success,$message){
    $result =  response()->json([
      'success' =>  $success,
      'message' =>  $message
    ],404)->send();
  }
}
?>