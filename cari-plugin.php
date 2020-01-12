<?php

/*
Plugin Name: Cari Plugin
Description: Un plugin de newletters informant du ramassage des ordures pour la ville de Saint-Denis (Réunion)
Version: 0.1
Author: Bazire Tanguy
*/

class Cari_Plugin
{
    private $_transcripteur;
    private $_newletters;

    public function __construct()
    {
        $loader = require 'vendor/autoload.php';
        $loader->addPsr4('', plugin_dir_path( __FILE__ ));

        $this->_transcripteur = new Cari_Transcripteur();
        $this->_newletters = new Cari_Newletters();

        new Cari_Subscriber();

        // Cari_Newletters
        register_activation_hook(__FILE__, array($this->_newletters, 'install'));
        register_uninstall_hook(__FILE__, array($this->_newletters, 'uninstall'));

        // Cari_Transcripteur
        register_activation_hook(__FILE__, array($this->_transcripteur, 'install'));
        register_uninstall_hook(__FILE__, array($this->_transcripteur, 'uninstall'));

        add_action('init', array($this, 'load_style_plugin'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('rest_api_init', function() { new Cari_Api(); });
    }

    public function load_style_plugin() {
        wp_register_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');
        wp_register_style('template', plugins_url('css/template.css', __FILE__));
    }

    public function add_admin_menu() 
    {
        $hook = add_menu_page('Cari Plugin Newletters', 'Cari Plugin', 'manage_options', 'cari', array($this, 'menu_html'));
        add_action('load-'.$hook, array($this, 'process_action'));
    }

    public function process_action()
    {
        if (isset($_POST['send_street_listing'])) {
            add_action('cari_transcripteur_form_display', array($this->_transcripteur, 'send_street_listing'));
        } else if (isset($_POST['send_touring'])) {
            add_action('cari_transcripteur_form_display', array($this->_transcripteur, 'send_touring'));
        }
    }

    public function menu_html()
    {
        wp_enqueue_style('bootstrap');
        wp_enqueue_style('template');
        
        ?><div class="cari-container-settings container text-center">
            <header class="page-header">
                <h1><?php echo get_admin_page_title() ?></h1>
                <code>[cari_subscriber_newletters]</code>
            </header><br />
            
            <section>
                <h2>Transcripteur EXCEL ► BDD</h2>

                <form method="post" action="">
                    <div class="form-group">
                        <input type="hidden" name="send_street_listing" value="1"/>
                        <input type="submit" name="submit" class="form-control button button-primary" value="Inserer la liste des rues">
                    </div>
                </form>
            
                <form method="post" action="">
                    <div class="form-group">
                        <input type="hidden" name="send_touring" value="1"/>
                        <input type="submit" name="submit" class="form-control button button-primary" value="Inserer la liste des tournées">
                    </div>
                </form>

                <?php do_action('cari_transcripteur_form_display'); ?>
            </section>
                
            <section><br />
                <form method="post" action="options.php">
                    <div class="form-group">
                        <?php settings_fields('cari_newsletter_settings') ?>
                        <?php do_settings_sections('cari_newsletter_settings') ?>
                    </div>

                    <input type="submit" name="submit" class="form-control button button-primary" value="Enregistrer les modifications">
                </form>
            </section>
        </div><?php
    }
}

new Cari_Plugin();