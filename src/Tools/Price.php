<?php

// src/Tools/Price.php
namespace App\Tools;

use Symfony\Component\Config\FileLocator;

class Price
{
    private $services;
    private $tarifs;
    private $formule;

    private $libelleServices = ["b" => "Business", "p" => "Prestige", "v" => "Van Luxe"];
    private $libelleTarifs = ["i" => "Paris intramuros", "a" => "Transfert A&eacute;roports", "g" => "Transfert Gares", "m" => "Mise &agrave; disposition", "r" => "Hors Ile de France", "t" => "Shooping / Tourisme", "e" => "Ev&eacute;nement"];

    public function __construct($services = null , $tarifs = null , $formule = null) {
        $this->services = $services;
        $this->tarifs = $tarifs;
        $this->formule = $formule;
    }

    public function getPrices(){
        $configDirectories = [__DIR__.'/../Data'];

        $fileLocator = new FileLocator($configDirectories);
        $file = $fileLocator->locate('data.json', null, false);
        return json_decode(file_get_contents($file[0]));
    }

    public function findPrice(){
        $prix = $this->getPrices();

        foreach($prix as $index){
            $obj = get_object_vars($index);
            if($obj['services'] == $this->services && $obj['tarifs'] == $this->tarifs){
                foreach($obj['resa'] as $formuleResa){
                    $objResa = get_object_vars($formuleResa);
                    if($objResa['value'] == $this->formule){
                        return [
                            "prix" => $objResa['prix'],
                            "services" => $this->libelleServices[$this->services],
                            "tarifs" => $this->libelleTarifs[$this->tarifs],
                            "formule" => $objResa['name']
                        ];
                    }
                }
            }
        }
    }

}