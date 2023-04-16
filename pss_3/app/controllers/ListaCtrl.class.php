<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\Utils;
use core\ParamUtils;

class ListaCtrl {

    private $data;
    private $rola = array();
    
    public function action_lista() {
		        

        $this->data = App::getDB()->select("user", [
            "ID_user",
            "username",
            "password",
            "email",
            
    ]     
    ); 
    foreach ($this->data as &$p) {

        $record = App::getDB()->select("user_role", [            
            "[><]user" => ["user_ID_user" => "ID_user"],
            "[><]role" => ["role_ID_role" => "ID_role"]
        ], [
            "user.ID_user",
            "role.role_name"
        ],[
            "user_ID_user" => $p["ID_user"]
        ]); 

        foreach ($record as &$p)
            array_push($this->rola, $p);
    }

        App::getSmarty()->assign("data",$this->data); 
        App::getSmarty()->assign("rola",$this->rola);        
        App::getSmarty()->display("lista.html");
        
    }
    
}