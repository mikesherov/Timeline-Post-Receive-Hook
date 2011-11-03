<?php

//no more __autoload, and in PHP5.3 we have callbacks
spl_autoload_register(function ($class_name) {
	$class_name = str_replace('_','/',$class_name);
	require_once 'classes/'.$class_name . '.php';
});