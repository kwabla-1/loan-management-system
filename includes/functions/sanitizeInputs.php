<?php 
    function sanitize_input($value){
		$sanitized_value = "";
		$sanitized_value = strip_tags($value);
		$sanitized_value = str_replace(" ", '', $sanitized_value);
		$sanitized_value = ucfirst(strtolower($sanitized_value));
		return $sanitized_value;
	}
?>