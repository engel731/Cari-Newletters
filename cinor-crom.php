<?php

/*
Plugin Name: CINOR CROM
Description: Gestion des calendriers de ramassage des ordures ménagères (alerting et affichage)
Version: 0.1
Author: Cari
Author URI: https://www.cari.agency/
*/

class Cinor_Crom
{
    private $_transcripteur;
    private $_newletters;

    public function __construct()
    {
        $loader = require 'vendor/autoload.php';
        $loader->addPsr4('', plugin_dir_path( __FILE__ ));

        $this->_transcripteur = new Cinor_Crom_Transcripteur();
        $this->_newletters = new Cinor_Crom_Newletters();

        new Cinor_Crom_Subscriber();

        // Cinor_Crom
        register_activation_hook(__FILE__, array($this, 'install'));
        register_uninstall_hook(__FILE__, array($this, 'uninstall'));
        
        // Cinor_Crom_Newletters
        register_activation_hook(__FILE__, array($this->_newletters, 'install'));
        register_uninstall_hook(__FILE__, array($this->_newletters, 'uninstall'));

        // Cinor_Crom_Transcripteur
        register_activation_hook(__FILE__, array($this->_transcripteur, 'install'));
        register_uninstall_hook(__FILE__, array($this->_transcripteur, 'uninstall'));

        add_action('init', array($this, 'load_style_plugin'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('rest_api_init', function() { new Cinor_Crom_Api(); });

        add_action('cinor_crom_cron_dowload_touring', array($this->_transcripteur, 'send_touring'));
        add_action('cinor_crom_cron_send_newsletter', array($this->_newletters, 'send_newsletter'));
    }

    public function install() {
        if (!wp_next_scheduled('cinor_crom_cron_dowload_touring')) {
            wp_schedule_event(time(), 'daily', 'cinor_crom_cron_dowload_touring', array('true'));
        }

        if (!wp_next_scheduled('cinor_crom_cron_send_newsletter')) {
            wp_schedule_event(time(), 'daily', 'cinor_crom_cron_send_newsletter');
        }
    }

    public function uninstall() {
        $timestamp = wp_next_scheduled('cinor_crom_cron_dowload_touring');
        wp_unschedule_event($timestamp, 'cinor_crom_cron_dowload_touring');

        $timestamp = wp_next_scheduled('cinor_crom_cron_send_newsletter');
        wp_unschedule_event($timestamp, 'cinor_crom_cron_send_newsletter');
    }

    public function load_style_plugin() {
        wp_register_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');
        wp_register_style('template', plugins_url('css/template.css', __FILE__));
    }

    public function add_admin_menu() 
    {
        $hook = add_menu_page('CINOR CROM Newletters', 'CROM', 'manage_options', 'cinor_crom', array($this, 'menu_html'));
        add_action('load-'.$hook, array($this, 'process_action'));
    }

    public function process_action()
    {
        if (isset($_POST['send_street_listing'])) {
            add_action('cinor_crom_transcripteur_form_display', array($this->_transcripteur, 'send_street_listing'));
        } else if (isset($_POST['send_touring'])) {
            add_action('cinor_crom_transcripteur_form_display', array($this->_transcripteur, 'send_touring'));
        }
    }

    public function menu_html()
    {
        wp_enqueue_style('bootstrap');
        wp_enqueue_style('template');
        
        ?><div class="cinor-crom-container-settings container text-center">
            <header class="page-header">
                <h1><?php echo get_admin_page_title() ?></h1>
                <code>[cinor_crom]</code>
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

                <?php do_action('cinor_crom_transcripteur_form_display'); ?>
            </section>
                
            <section><br />
                <header>
                    <code>@type_dechet = [le bac gris, le bac jaune, les emcombrants, les déchets verts]</code>
                    <code>@date_passage = [Date du passage de la tournée]</code>
                </header><br />
                
                <form method="post" action="options.php">
                    <div class="form-group">
                        <?php settings_fields('cinor_crom_newsletter_settings') ?>
                        <?php do_settings_sections('cinor_crom_newsletter_settings') ?>
                    </div>

                    <input type="submit" name="submit" class="form-control button button-primary" value="Enregistrer les modifications">
                </form>
            </section>
        </div><?php
    }
}

new Cinor_Crom();