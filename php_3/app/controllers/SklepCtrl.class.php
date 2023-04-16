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
    private $strona; 
    private $sum; 
    
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

    public function action_sklep_filtr() {
        // Pobierz wartość z formularza
        $this->id = ParamUtils::getFromPost("id");
    
        // Walidacja i przygotowanie warunków wyszukiwania
        $search_params = [];
        if (strlen($this->id) > 0) {
            $search_params['product_name[~]'] = $this->id . '%';
        }
    
        $num_params = sizeof($search_params);
        if ($num_params > 1) {
            $where = ["AND" => &$search_params];
        } else {
            $where = &$search_params;
        }
    
        // Pobierz produkty z bazy danych na podstawie warunków wyszukiwania
        $this->produkty = App::getDB()->select("product", [
            "product_name",
            "product_prize",
            "img",
            "ID_product"
        ], $where);
    
        // Przypisz produkty do Smarty i wyświetl widok
        App::getSmarty()->assign('product', $this->produkty);
        App::getSmarty()->display("sklep.html");
    }

}

