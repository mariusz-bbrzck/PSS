<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\Utils;


class HelloCtrl {
    
    public function action_hello() {
		        
        $variable = 123;
        
        
        App::getSmarty()->assign("value",$variable);        
        App::getSmarty()->display("index.html");
        
    }
    

    
}
