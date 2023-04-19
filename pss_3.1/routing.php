<?php

use core\App;
use core\Utils;

App::getRouter()->setDefaultRoute('home'); #default action
App::getRouter()->setLoginRoute('loginWymagane'); #action to forward if no permissions


Utils::addRoute('hello', 'HelloCtrl');
Utils::addRoute('home', 'HomeCtrl');
Utils::addRoute('rejestracja', 'RejestracjaCtrl');
Utils::addRoute('rejestracjaWidok', 'RejestracjaCtrl');
Utils::addRoute('logowanie', 'LogowanieCtrl');
Utils::addRoute('logowanieWidok', 'LogowanieCtrl');
//Utils::addRoute('logowanieWymagane', 'LogowanieCtrl');'
Utils::addRoute('sklepFiltr', 'SklepCtrl');
Utils::addRoute('sklepStronnicowanie', 'SklepCtrl');
Utils::addRoute('wylogowanie', 'LogowanieCtrl');
Utils::addRoute('sklep', 'SklepCtrl');
Utils::addRoute('dodajProdukt', 'KoszykCtrl');
Utils::addRoute('koszyk', 'KoszykCtrl',['user']);
Utils::addRoute('usunProdukt', 'KoszykCtrl',['user']);
Utils::addRoute('zamow', 'KoszykCtrl',['user']);
Utils::addRoute('lista', 'ListaCtrl',['admin']);
Utils::addRoute('lista_dodaj', 'ListaCtrl',['admin']);
Utils::addRoute('lista_usun', 'ListaCtrl',['admin']);







