<?php

/*
Plugin Name: Cari Plugin
Description: Un plugin de newletters informant du ramassage des ordures pour la ville de Saint-Denis (Réunion)
Version: 0.1
Author: Bazire Tanguy
*/

class Cari_Plugin
{
    public function __construct()
    {
        require 'vendor/autoload.php';

        /*$mail = new Entity\Mail([
            'mail' => 'tanguy731@hotmail.fr',
            'lieu' => 10
        ]);

        $reader = new TableReader\ListReader();
        $data = $reader->read();*/
    }
}

new Cari_Plugin();