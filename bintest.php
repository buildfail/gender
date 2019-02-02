<?php
require "vendor/autoload.php";

$gender = new \Gender\Gender();

var_dump($gender->get("Jon"));
