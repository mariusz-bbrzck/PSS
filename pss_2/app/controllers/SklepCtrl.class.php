<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\Utils;
use core\Validator;
use core\ParamUtils;


class SklepCtrl {

    private $id;
    private $produkty;
    
    public function action_sklep() {
		   
        
        $this->produkty = App::getDB()->select("product", [
            "product_name",
            "product_prize",
            "img",
            "ID_product"
            
        ]);

        App::getSmarty()->assign('product', $this->produkty);   
        App::getSmarty()->display("sklep.html");
    }
}

