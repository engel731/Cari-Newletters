<?php 

class Cari_Newletters
{
    public function __construct() 
    {
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function register_settings()
    {
        register_setting('cari_newsletter_settings', 'cari_newsletter_sender');
        register_setting('cari_newsletter_settings', 'cari_newsletter_object');
        register_setting('cari_newsletter_settings', 'cari_newsletter_content');
        
        add_settings_section('cari_newsletter_section', 'Paramètres d\'envoi', array($this, 'section_html'), 'cari_newsletter_settings');
        
        add_settings_field('cari_newsletter_sender',  'Expéditeur', array($this, 'sender_html'),  'cari_newsletter_settings', 'cari_newsletter_section');
        add_settings_field('cari_newsletter_object',  'Objet',      array($this, 'object_html'),  'cari_newsletter_settings', 'cari_newsletter_section');
        add_settings_field('cari_newsletter_content', 'Contenu',    array($this, 'content_html'), 'cari_newsletter_settings', 'cari_newsletter_section');
    }

    public function send_newsletter()
    {
        global $wpdb;

        /*$recipients = $wpdb->get_results("SELECT email FROM {$wpdb->prefix}zero_newsletter_email");
        $object = get_option('zero_newsletter_object', 'Newsletter');
        $content = get_option('zero_newsletter_content', 'Mon contenu');
        $sender = get_option('zero_newsletter_sender', 'no-reply@example.com');
        $header = array('From: '.$sender);

        foreach ($recipients as $_recipient) {
            $result = wp_mail($_recipient->email, $object, $content, $header);
        }*/
    }
    
    public function section_html()
    {
        echo 'Renseignez les paramètres d\'envoi de la newsletter ↓';
    }

    public function sender_html()
    {
        ?><input 
            style="width: 230px" 
            type="email" 
            name="cari_newsletter_sender" 
            value="<?php echo get_option('cari_newsletter_sender')?>"
        /><?php
    }

    public function object_html()
    {
        ?><input 
            style="width: 230px" 
            type="text" 
            name="cari_newsletter_object" 
            value="<?php echo get_option('cari_newsletter_object')?>"
        /><?php
    }

    public function content_html()
    {
        ?><textarea 
            style="height: 100px; width: 250px;" 
            name="cari_newsletter_content"><?php echo get_option('cari_newsletter_content')?>
        </textarea><?php
    }

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