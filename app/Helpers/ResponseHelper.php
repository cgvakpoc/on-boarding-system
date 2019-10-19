<?php

if (!function_exists('http_200')) {
	function http_200($status, $msg, $data)
	{
		return response()->json(['success' => $status, 'message' => $msg, 'data' => $data], 200);
	}
}

if (!function_exists('http_201')) {
	function http_201($msg, $data)
	{
		return response()->json(['success' => true, 'message' => $msg, 'data' => $data], 201);
	}
}

if (!function_exists('error_401')) {
	function error_401($msg, $data = [])
	{
		return response()->json(['success' => false, 'message' => $msg, 'data' => $data], 401);
	}
}

if (!function_exists('error_404')) {
	function error_404()
	{
		return response()->json(['success' => false, 'message' => 'No data found', 'data' => ''], 404);
	}
}
