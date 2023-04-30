<?php
require_once 'init.php';
use core\App;
header("Location: ". App::getConf()->app_url);
include App::getConf()->root_path.App::getConf()->action_script;
