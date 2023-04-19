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
        ]); 
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
    
    public function action_dodaj() {
        $login = ParamUtils::getFromPost("login");
        $password = ParamUtils::getFromPost("password");
        $email = ParamUtils::getFromPost("email");
    
        try {
            // sprawdzenie, czy użytkownik o podanym loginie lub emailu już istnieje w bazie danych
            $exist = App::getDB()->has("user", [
                "OR" => [
                    "username" => $login,
                    "email" => $email
                ]
            ]);
    
            if ($exist) {
                Utils::addErrorMessage("Użytkownik o podanym loginie lub emailu już istnieje");
                return;
            }
    
            // dodanie nowego użytkownika do bazy danych
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            App::getDB()->insert("user", [
                "username" => $login,
                "password" => $hashedPassword,
                "email" => $email
            ]);
    
            Utils::addInfoMessage("Użytkownik został dodany");
        } catch (\PDOException $e) {
            if (App::getConf()->debug) {
                Utils::addErrorMessage($e->getMessage());
            }
        }
    }

    public function action_usun() {
        $id_user = ParamUtils::getFromPost("id_user");
        try {
            App::getDB()->delete("user", [
                "ID_user" => $id_user
            ]);
            Utils::addInfoMessage("Użytkownik został usunięty");
        } catch (\PDOException $e) {
            if (App::getConf()->debug) {
                Utils::addErrorMessage($e->getMessage());
            }
        }
        $this->action_lista(); // przekierowanie do akcji wyświetlającej listę użytkowników
    }
}