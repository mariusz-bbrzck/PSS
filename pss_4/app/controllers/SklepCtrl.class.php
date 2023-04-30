<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\Utils;
use core\Validator;
use core\ParamUtils;
use app\forms\WyszukiwanieForm;

class SklepCtrl {

    private $id;
    private $produkty;
    private $strona; 
 
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

    public function action_sklepFiltr()
    {
        $search_params = [];
        $searchText = ParamUtils::getFromPost("searchText");
        if (strlen($searchText) > 0) {
            $search_params['product_name[~]'] = '%' . $searchText . '%';
        }
        $num_params = sizeof($search_params);
        if ($num_params > 1) {
            $where = ["AND" => &$search_params];
        } else {
            $where = &$search_params;
        }
        $how_many = App::getDB()->count("product", [
            "[>]product_name" => ["id_product_name" => "id_product_name"]
        ], [
            "id_product"
        ], $where);
    
        $this->produkty = App::getDB()->select("product", [
            "[>]product_name" => ["id_product_name" => "id_product_name"]
        ], [
            "product_name.product_name",
            "product.product_prize",
            "product.img",
            "product.ID_product"
        ], $where);
        App::getSmarty()->assign('produkty', $this->produkty);
        App::getSmarty()->display("sklep.html");
    }
    
    public function action_sklepStronnicowanie() {
        
        $this->strona = ParamUtils::getFromGet("strona", 1);
        $produkty_na_stronie = 1;
        
        $poczatek = ($this->strona - 1) * $produkty_na_stronie;
        $koniec = $poczatek + $produkty_na_stronie - 1;
        $search_params = [];
        $this->id = ParamUtils::getFromPost("id");
        if (strlen($this->id) > 0) {
            $search_params['product_name[~]'] = $this->id . '%';
        }
        $num_params = sizeof($search_params);
        if ($num_params > 1) {
            $where = ["AND" => &$search_params];
        } else {
            $where = &$search_params;
        }
        $wszystkie_produkty = App::getDB()->select("product", [
            "product_name",
            "product_prize",
            "img",
            "ID_product"
        ], $where);
        $this->produkty = array_slice($wszystkie_produkty, $poczatek, $produkty_na_stronie);
        $liczba_stron = ceil(count($wszystkie_produkty) / $produkty_na_stronie);
        App::getSmarty()->assign('product', $this->produkty);
        App::getSmarty()->assign('strona', $this->strona);
        App::getSmarty()->assign('liczba_stron', $liczba_stron);
        App::getSmarty()->display("sklep.html");
    }
}
