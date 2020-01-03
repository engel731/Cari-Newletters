<?php 

class Cari_Newletters
{
    public static function install()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mail_listing (
                id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, 
                lieu SMALLINT UNSIGNED NOT NULL,
                mail VARCHAR(60) NOT NULL,
                PRIMARY KEY (id)
            ){$charset_collate};"
        );
    }

    public static function uninstall()
    {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mail_listing;");
    }
}