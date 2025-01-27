<?php
/**
 * Created by Nguyen Tien Dat.
 * Date: 8/3/13
 */
?>
<?php defined('BASEPATH') or exit('No direct script access allowed');
class Module_Languages extends Module {

    public $version = '1.0.1';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Languages'
            ),
            'description' => array(
                'en' => 'Module for Languages'
            ),
            'frontend' => TRUE,
            'backend' => TRUE,
            'sections' => array(
                'languages' => array(
                    'name' => 'languages:list',
                    'uri' => 'admin/languages',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'languages:create',
                            'uri' => 'admin/languages/create',
                            'class' => 'add'
                        )
                    )
                )
            )
        );
    }

    public function install()
    {
    	$languages_setting = array(
            'slug' => 'languages_setting',
            'title' => 'Languages Setting',
            'description' => 'A Yes or No option for the Contact module',
            '`default`' => '1',
            '`value`' => '1',
            'type' => 'select',
            '`options`' => '1=Yes|0=No',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'languages'
        );

        if($this->db->insert('settings', $languages_setting) AND
            is_dir($this->upload_path.'languages') OR @mkdir($this->upload_path.'languages',0777,TRUE))
        {
            return true;
        }
    }

    public function uninstall()
    {
    	$this->db->delete('settings', array('module' => 'languages'));
        return TRUE;
    }

 	public function admin_menu(&$menu)
    {
        $menu['General Settings']['Languages'] = 'admin/languages';
    }
    public function upgrade($old_version)
    {
        // Your Upgrade Logic
        return TRUE;
    }

    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }
}