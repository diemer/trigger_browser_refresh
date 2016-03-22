<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine Trigger Browser Refresh Extension
 *
 * Creates/updates a small html file (in this dir) when an entry is submitted.
 * For use with your favorite browser refresh tool like Codekit, Browsersync, Livereload, etc
 *
 * @package		Trigger Browser Refresh
 * @author		Dan Diemer <dan.diemer@gmail.com>
 * @link		http://dans.messywork.space
 * @addon link	http://devot-ee.com/add-ons/trigger-browser-refresh/
 * @copyright 	Copyright (c) 2016 Dan Diemer
 */

class Trigger_browser_refresh_ext {

    var $name       = 'Trigger Browser Refresh';
    var $version        = '1.0';
    var $description    = 'Triggers a brower refresh on entry submission';
    var $settings_exist = 'n';
    var $docs_url       = '';

    var $settings       = array();

    /**
     * Constructor
     *
     * @param   mixed   Settings array or empty string if none exist.
     */
    function __construct($settings = '')
    {
        $this->settings = $settings;
    }

    function activate_extension()
    {
      $this->settings = array();

      $hooks = array(
        'entry_submission_absolute_end' => 'touch_file'
      );
      foreach ($hooks as $hook => $method) {
        $data = array(
            'class'     => __CLASS__,
            'method'    => $method,
            'hook'      => $hook,
            'settings'  => serialize($this->settings),
            'priority'  => 10,
            'version'   => $this->version,
            'enabled'   => 'y'
        );

        ee()->db->insert('extensions', $data);
      }
    }

    function update_extension($current = '')
    {
      if ($current == '' OR $current == $this->version)
      {
          return FALSE;
      }

      if ($current < '1.0')
      {
          // Update to version 1.0
      }

      ee()->db->where('class', __CLASS__);
      ee()->db->update(
                  'extensions',
                  array('version' => $this->version)
      );
    }

    function disable_extension()
    {
      ee()->db->where('class', __CLASS__);
      ee()->db->delete('extensions');
    }

    function touch_file()
    {
      $refresh_file = PATH_THIRD.'trigger_browser_refresh/browser_refresh.html';
      // Update the browser refresh file timestamp
      if(is_writable($refresh_file))
      {
        $myfile = fopen($refresh_file, "w");
        $txt = "Browser Refresh!";
        fwrite($myfile, $txt);
        fclose($myfile);
      }
    }
}
