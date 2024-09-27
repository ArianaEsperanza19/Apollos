<?php

function autocargar($classname){
	include 'config/otros.php';
	#include 'Controllers/' . $classname . '.php';
}

spl_autoload_register('autocargar');