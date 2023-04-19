<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\Utils;
use core\Validator;
use core\ParamUtils;


class KoszykCtrl {

    private $id;
    private $produkty;
    

    public function validateEdit() {
 
        $this->id = ParamUtils::getFromCleanURL(1);
        return !App::getMessages()->isError();
    }

    public function action_dodajProdukt() {
		   
        $this->produkty = App::getDB()->select("shopBasket", [
            "[><]product" => ["product_ID_product" => "ID_product"]
        ], [
            "product.ID_product",
            "product.product_name",
            "product.product_prize",
            
            
        ], [
            "shopBasket.user_ID_user" => $_SESSION["ID_user"]
        ]);
		        
 
        
        if ($this->validateEdit()) {
            

            $add=true;
            foreach ($this->produkty as &$p) {
                if($p["ID_product"]==$this->id)
                    $add=false;
                
            }

            if($add){
            try {
           
                App::getDB()->insert("shopBasket", [
                    "product_ID_product" => $this->id,
                    "user_ID_user" =>  $_SESSION["ID_user"],
                    "quantity" =>  1
                    
                   
                        
                ]);
            } catch (\PDOException $e) {
                Utils::addErrorMessage('Wystąpił błąd podczas odczytu rekordu');
                if (App::getConf()->debug)
                    Utils::addErrorMessage($e->getMessage());
            }
        }else{
            App::getDB()->update("shopBasket", [
                "quantity[+]" => 1
               
            ], [
                "product_ID_product" => $this->id
            ]);

        }
       }
        App::getRouter()->redirectTo('sklep');
}

public function generateView() {

    App::getSmarty()->display('sklep.html');
    
}

public function action_koszyk() {

     
    
    $this->produkty = App::getDB()->select("shopBasket", [
        "[><]product" => ["product_ID_product" => "ID_product"]
    ], [
        "product.ID_product",
        "product.product_name",
        "product.product_prize",
        "shopBasket.quantity",
        "product.img",
        "shopBasket.user_ID_user"
        
    ], [
        "shopBasket.user_ID_user" => $_SESSION["ID_user"]
    ]);

    App::getSmarty()->assign('product', $this->produkty);    
    App::getSmarty()->display("koszyk.html");
    
}


public function action_zamow() {

    $count = App::getDB()->count("shopBasket");

    if($count != 0){

    App::getDB()->insert("order", [
    ]);
    $id = App::getDB()->id();

        $this->data = App::getDB()->select("shopBasket", [
            "[><]product" => ["product_ID_product" => "ID_product"]
        ], [
            "product.ID_product",
            "product.product_name",
            "product.product_prize",
            "shopBasket.user_ID_user",
            "shopBasket.quantity"
        ], [
            "shopBasket.user_ID_user" => $_SESSION["ID_User"]
        ]);
        
      

        foreach ($this->data as &$ilosc) {

            App::getDB()->insert("order", [
                "product_ID_product" => $p["ID_poduct"],
                "user_ID_user" =>  $_SESSION["ID_User"],
                "quantity" =>  $ilosc["quantity"],
                "ID_order" =>  $id
                 
            ]);


          
        }
    App::getSmarty()->assign('product', $this->produkty);           
    App::getSmarty()->display('koszyk.html');
    }
    
}





}

