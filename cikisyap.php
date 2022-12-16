<?php
require 'vendor/autoload.php';
require_once 'config/helper.php';
session_start();
session_destroy();
header("Location:index.php");