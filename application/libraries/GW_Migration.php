<?php defined('BASEPATH') OR exit('No direct script access allowed');


class GW_Migration extends CI_Migration
{
    public function __construct($config = array())
	{
		# Only run this constructor on main library load
		if(get_parent_class($this) !== FALSE && get_class($this) != 'GW_Migration')
		{
			return;
		}

        foreach ($config as $key => $val)
        {
            $this->{'_' . $key} = $val;
        }

        log_message('debug', 'Migrations class initialized');

        // Are they trying to use migrations while it is disabled?
        if ($this->_migration_enabled !== TRUE)
        {
            show_error('Migrations has been loaded but is disabled or set up incorrectly.');
        }

        // If not set, set it
        $this->_migration_path == '' AND $this->_migration_path = APPPATH . 'migrations/';

        // Add trailing slash if not set
        $this->_migration_path = rtrim($this->_migration_path, '/').'/';

        // Load migration language
        $this->load->helper('language');
        $this->lang->load('migration');

        // They'll probably be using dbforge
        $this->load->dbforge();

        // If the migrations table is missing, make it
        if ( ! $this->db->table_exists('migrations'))
        {
            $this->dbforge->add_field(array(
                'version' => array('type' => 'INT', 'constraint' => 3),
            ));

            $this->dbforge->create_table('migrations', TRUE);

            $this->db->insert('migrations', array('version' => 0));
        }
	}
    
    /**
     * Wrapper function for the protected _get_version.
     * Get's the database current version
     *
     * @access	public
     * @return	integer	Current DB Migration version
     */
    public function get_db_version()
    {
        return parent::_get_version();
    }
    
    /**
     * Retrieves current file system version
     *
     * @access	public
     * @return	integer	Current file system Migration version
     */
    public function get_fs_version()
    {
        return $this->_migration_version;
    }
}