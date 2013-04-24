<?php
/**
 * Otrariam
 *
 * An open source browsergame development with codeigniter
 *
 * @package		Otrariam core
 * @author		Flash-Back, XxidroxX
 * @copyright	Copyright (c) 2012 - 2013, Otrarian (board url)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link		url
 * @since		Version 0.0.1 alpha 1 "Dragon"
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
    {
        parent::__construct();

		if ($this->session->userdata('language'))
            $this->lang->load('home', $this->session->userdata('language'));
        else
            $this->lang->load('home');
    }
	
	/**
	* Index page of this application, we see the home page
	 */
	public function index()
	{
		$this->layout->show('main/main_index');
	}
	
	public function login()
	{
	    $username = $this->input->post('username');
		$password = sha1($this->input->post('password'));

		if($username && $password) {
		    $this->load->model('Player_Model');
			$this->Player_Model->Login($username, $password);
		} else {
		    echo "ciao";
			exit();
		}
	}
	
	public function register()
	{
	    $username = $this->input->post('username');
		$password = sha1($this->input->post('password'));
		$email    = $this->input->post('email');
		$ip       = $this->input->ip_address();
		
		if($username && $password && $email) {
		    $this->load->model('Player_Model');
			$this->Player_Model->Registration($username, $password, $email, $ip);
		} else {
		    echo "ciao";
			exit();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */