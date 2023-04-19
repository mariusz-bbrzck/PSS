<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\Utils;
use core\ParamUtils;
use app\forms\RejestracjaForm;
use PDOException;

class RejestracjaCtrl {
    
    private $form;
	
    public function __construct() {
        $this->form = new RejestracjaForm();		
    }

    public function validate() {
        $this->form->login = ParamUtils::getFromRequest('login');
        $this->form->pass = ParamUtils::getFromRequest('pass');
        $this->form->pass2 = ParamUtils::getFromRequest('pass2');
        $this->form->email = ParamUtils::getFromRequest('email');
        
        if (App::getMessages()->isError()) {
            return false;
        }

        if (empty(trim($this->form->login))) {
            Utils::addErrorMessage('Nie wprowadzono loginu');
        }

        if (empty(trim($this->form->pass))) {
            Utils::addErrorMessage('Nie wprowadzono hasła');
        }

        if (empty(trim($this->form->pass2))) {
            Utils::addErrorMessage('Nie wprowadzono drugiego hasła');
        }

        if (empty(trim($this->form->email))) {
            Utils::addErrorMessage('Nie wprowadzono emailu');
        }

        if (!filter_var($this->form->email, FILTER_VALIDATE_EMAIL)) {
            Utils::addErrorMessage('Nieprawidłowy format adresu email');
        }

        if ($this->form->pass != $this->form->pass2) {
            Utils::addErrorMessage('Hasła nie są takie same');
        }

        if (App::getMessages()->isError()) {
            return false;
        }

        return !App::getMessages()->isError();
    }
    

    public function action_rejestracjaWidok() {
        $this->generateView();
    }

    public function action_rejestracja() {
		        
        if ($this->validate()) {
            try {
                $exist = App::getDB()->has("user", [
                    "OR" => [
                        "username" => $this->form->login,
                        "email" => $this->form->email
                    ]
                ]);

                if (!$exist) {
                    $this->form->pass = md5($this->form->pass);

                    App::getDB()->insert("user", [
                        "username" => $this->form->login,
                        "password" => $this->form->pass,
                        "email" => $this->form->email,
                    ]);

                    App::getDB()->insert("user_role", [
                        "user_ID_user" => 1,   
                        "activ_date" => date("Y-m-d H:i:s")   
                    ]);
                } else {
                    Utils::addErrorMessage('Nazwa użytkownika lub adres email jest już zajęty');
                    $this->generateView(); 
                    exit(); 
                }
            } catch (\PDOException $e) {
                if (App::getConf()->debug) {
                    Utils::addErrorMessage($e->getMessage());
                }
            }
            App::getSmarty()->display('logowanie.html');
        } else {
            $this->generateView();
        }
    }

    public function generateView() {
        App::getSmarty()->assign('form', $this->form); 
        App::getSmarty()->display('rejestracja.html');
    }    
}