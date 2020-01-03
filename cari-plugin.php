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
        $loader = require 'vendor/autoload.php';
        $loader->addPsr4('', plugin_dir_path( __FILE__ ));

        // Cari_Newletters
        register_activation_hook(__FILE__, array('Cari_Newletters', 'install'));
        register_uninstall_hook(__FILE__, array('Cari_Newletters', 'uninstall'));

        // Cari_Transcripteur
        register_activation_hook(__FILE__, array('Cari_Transcripteur', 'install'));
        register_uninstall_hook(__FILE__, array('Cari_Transcripteur', 'uninstall'));
    }
}

new Cari_Plugin();