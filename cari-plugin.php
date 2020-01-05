<?php

/*
Plugin Name: Cari Plugin
Description: Un plugin de newletters informant du ramassage des ordures pour la ville de Saint-Denis (Réunion)
Version: 0.1
Author: Bazire Tanguy
*/

class Cari_Plugin
{
    protected $_transcripteur;
    protected $_newletters;

    public function __construct()
    {
        $loader = require 'vendor/autoload.php';
        $loader->addPsr4('', plugin_dir_path( __FILE__ ));

        $this->_transcripteur = new Cari_Transcripteur();
        $this->_newletters = new Cari_Newletters();

        // Cari_Newletters
        register_activation_hook(__FILE__, array('Cari_Newletters', 'install'));
        register_uninstall_hook(__FILE__, array('Cari_Newletters', 'uninstall'));

        // Cari_Transcripteur
        register_activation_hook(__FILE__, array('Cari_Transcripteur', 'install'));
        register_uninstall_hook(__FILE__, array('Cari_Transcripteur', 'uninstall'));

        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu() 
    {
        $hook = add_menu_page('Cari Plugin Newletters', 'Cari Plugin', 'manage_options', 'cari', array($this, 'menu_html'));
        add_action('load-'.$hook, array($this, 'process_action'));
    }

    public function process_action()
    {
        if (isset($_POST['send_street_listing'])) {
            $this->_transcripteur->send_street_listing();
        } else if (isset($_POST['send_touring'])) {
            $this->_newletters->send_touring();
        }
    }

    public function menu_html()
    {
        echo '<h1 style="padding: 10px">'.get_admin_page_title().'</h1>';
        
        ?><section style="margin-bottom: 20px;">
            <div style="border-bottom: 5px solid rgb(110, 100, 190); display: inline-block; padding: 10px; background-color: #E6E5E5; box-shadow: 2px 2px 1px black;">
                <h2>Transcripteur EXCEL ► BDD</h2>

                <div style="display: flex">
                    <form method="post" action="">
                        <input type="hidden" name="send_street_listing" value="1"/>
                        <input style="margin-right: 10px" type="submit" name="submit" id="submit" class="button button-primary" value="Inserer la liste des rues">
                    </form>
                
                    <form method="post" action="">
                        <input type="hidden" name="send_touring" value="1"/>
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="Inserer la liste des tournées">
                    </form>
                </div>
            </div>
        </section>

        <section style="margin-bottom: 20px;">
            <div style="border-bottom: 5px solid rgb(110, 100, 190); display: inline-block; padding: 10px; background-color: #E6E5E5; box-shadow: 2px 2px 1px black;">  
                <h2>Shortcodes WordPress</h2>
                <code>[nom_du_shortcode attribut1="valeur1" attribut2="valeur2"]</code>
            </div>
        </section>
              
        <section style="margin-bottom: 20px;">
            <div style="border-bottom: 5px solid rgb(110, 100, 190); display: inline-block; padding: 10px; background-color: #E6E5E5; box-shadow: 2px 2px 1px black;">  
                <form method="post" action="options.php">
                    <?php settings_fields('cari_newsletter_settings') ?>
                    <?php do_settings_sections('cari_newsletter_settings') ?>
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Enregistrer les modifications">
                </form>
            </div>
        </section><?php
    }
}

new Cari_Plugin();