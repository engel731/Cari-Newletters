<?php 

use Model\MailManager;

class Cari_Newletters
{
    private $_mailManager;
    
    public function __construct() 
    {
        global $wpdb;
        $this->_mailManager = new MailManager($wpdb);

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

    public function install() { $this->_mailManager->create(); }
    public function uninstall() { $this->_mailManager->delete(); }
}