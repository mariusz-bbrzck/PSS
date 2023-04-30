<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\Utils;
use core\ParamUtils;
use app\forms\LogowanieForm;
use PDOException;
use core\RoleUtils;


class LogowanieCtrl {
    
    private $form;
    private $id; 
    
	
    public function __construct(){
		
		$this->form = new LogowanieForm();		
	}


 


    public function validate() {
     
        $this->form->login = ParamUtils::getFromRequest('login');
        $this->form->pass = ParamUtils::getFromRequest('pass');

        if (App::getMessages()->isError())
            return false;

        if (!isset($this->form->login))
            return false;

        // 1. sprawdzenie czy wartości wymagane nie są puste
        if (empty(trim($this->form->login))) {
            Utils::addErrorMessage('Nie wprowadzono loginu');
        }
        if (empty(trim($this->form->pass))) {
            Utils::addErrorMessage('Nie wprowadzono hasła');
        }

        if (App::getMessages()->isError())
            return false;


        return !App::getMessages()->isError();
    }
    

    public function action_logowanieWidok() {
        $this->generateView();
    }

    public function action_logowanie() {
		        
        if ($this->validate()) {
            //zalogowany => przekieruj na główną akcję (z przekazaniem messages przez sesję)
            $this->form->pass =  md5($this->form->pass);

           
            $isAccount = App::getDB()->has("user", [
                "AND" => [
                    "OR" => [
                        "username" => $this->form->login
                        
                    ],
                    "password" => $this->form->pass
                ]
            ]);
            

            $record = App::getDB()->get("user", "*", [
                "username" => $this->form->login
            ]);

            $this->id = $record['ID_user'];

            $role = App::getDB()->select("user_role", [
                "[><]role" => ["role_ID_role" => "ID_role"]
            ], [
                "user_role.user_ID_user",
                "user_role.role_ID_role",
                "role.role_name",
            ],[
                "user_role.user_ID_user" => $this->id
            ]);



  /////     
        //   $_SESSION["mariusz"] = $role['user_ID_user'];
        if ($isAccount) {
            
            RoleUtils::addRole('user');
           $_SESSION["role"] = "user";
            foreach ($role as &$p) {

                if($p["role_name"] == "admin"){
                    $_SESSION["role"] = $p["role_name"];
                    RoleUtils::addRole($p["role_name"]);    
                }
            }

            $_SESSION["success"] = $this->form->login;
            $_SESSION["ID_user"] = $this->id;
            
            App::getRouter()->redirectTo('home');

        } else {
            Utils::addErrorMessage('Błędne dane logowania.');
            $this->generateView();
        }
    }

   
}

    public function action_logowanieWymagane() {
        Utils::addInfoMessage('Zaloguj się aby korzystać z koszyka.');
        $this->generateView();
    }


    public function action_wylogowanie() {
	    session_destroy();
        App::getRouter()->redirectTo('logowanieWidok');
        
    }
    
    public function generateView() {
      

        App::getSmarty()->assign('form', $this->form); 
        App::getSmarty()->display('logowanie.html');
        
    }

    
    
}

