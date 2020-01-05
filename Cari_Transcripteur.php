<?php 

class Cari_Transcripteur
{
    public function __construct() {
        
    }

    public function send_touring() 
    {

    }

    public function send_street_listing() 
    {

    }

    public static function install()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}touring (
                id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
                type_dechet VARCHAR(20) NOT NULL,
                ref_calendrier VARCHAR(20) NOT NULL,
                date_passage DATE NOT NULL,
                PRIMARY KEY (id)
            ){$charset_collate};"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}street_listing (
                id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
                ref_calendrier VARCHAR(20) NOT NULL,
                intitule_voie VARCHAR(90) NOT NULL,
                quartier VARCHAR(40) NOT NULL,
                PRIMARY KEY (id),
                FULLTEXT ind_intitule_voie (intitule_voie(40)) 
            ){$charset_collate};"
        );
    }

    public static function uninstall()
    {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}touring;");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}street_listing;");
    }
}