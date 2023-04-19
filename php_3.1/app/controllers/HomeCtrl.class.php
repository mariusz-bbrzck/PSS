<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\Utils;


class HomeCtrl {
    
    public function action_home() {
		        
        $variable = 123;
        
        
        App::getSmarty()->assign("value",$variable);        
        App::getSmarty()->display("home.html");
        
    }
    
}
