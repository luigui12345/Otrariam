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
 * @since		Version 0.0.1 alpha 1
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Game extends CI_Controller {

	public function __construct() 
	{
        parent::__construct();
        
		//if not exist the session, go to home
		if(!$this->session->userdata('username'))
		    exit();
		
		//carico il template
		$this->layout->template('game');
		
		//carico la lingua
		$this->lang->load('ingame');
		
		//carico il model
		$this->load->model('Village_Model');
		
		$production[lang('wood')]  = $this->Village_Model->production('woodcutter');
		$production[lang('clay')]  = $this->Village_Model->production('clay_pit');
		$production[lang('iron')]  = $this->Village_Model->production('iron_mine');
		$production[lang('crop')]  = $this->Village_Model->production('farm');
				
		$map = "<a href='map/".$this->Village_Model->x."/".$this->Village_Model->y."'><img src='".base_url('design/skin/menu/map.png')."'></a>";

		$this->Village_Model->check_resources('si');
		$this->layout->bind(array('production' => $production, 'map_link' => $map));
    }
	
    public function village()
    {     	
		$buildings = $this->Village_Model->show_buildings();
		$this->layout->bind(array('buildings' => $buildings))->show('view/village');
    }
	
	public function slot($slot) {
	    if($slot != 5 && 1 <= $slot && $slot <= 12)
			$html = $this->Village_Model->getBuildingConstructible();
		else 
		    echo "ciao"; //We have a cheat
			
		$this->layout->bind(array('html' => $html))->show('view/build');
	}
	
	//TODO: check that we can upgrade the building
	public function build($building, $slot) {
	    $this->Village_Model->ordenar_ampliar($building, $slot);
	    $this->Village_Model->check_resources('no');
	}
	
	public function town_hall() {
	    
		//TODO: remove all code from switch and use the function() as town_hall()
		
		$sql = "select building, level, production from buildings where id_town = ".$this->Village_Model->id_town." and building = 'town_hall' limit 1";
	    $res = $this->db->query($sql);
		$reg = $res->row_array(); 
		if ($reg['level'] == 0)
		{
			//redirect to homepage
			exit;
		}
		$next_level = $reg['level'] + 1;
		echo $reg['production'];
		$text = sprintf(lang('town_hall_description'), $reg['production']);
	    $html = '<div class="building_name"><strong>'.lang('town_hall').'</strong> - Nivel '.$reg["level"].'</div>

				<div class="building_description">
				'. $text .'
				</div>

				<img src="'.base_url('design/skin/buildings/town_hall.png').'" class="img_recurso" title="'.lang('town_hall').'">

				<div class="building_costs">
				<p>Subir a nivel '.$next_level.'</p>
				<div class="upgrade">'.$this->Village_Model->construction_costs($reg['building'], $reg['level']).'</div>
				</div>';
				$this->layout->bind(array('html' => $html))->show('view/building');
	}
	
	public function farm() {
	
	    $sql = "select building, level, production from buildings where id_town = ".$this->Village_Model->id_town." and building = 'farm' limit 1";
	    $res = $this->db->query($sql);
		$reg = $res->row_array(); 
		if ($reg['level'] == 0)
		{
			//redirect to homepage
			exit;
		}
		$next_level = $reg['level'] + 1;
		$html = '<div class="building_name"><strong>Granja</strong> - Nivel '.$reg["level"].'</div>

				<div class="building_description">
				La Granja produce Cereal con el que abastecer a tus poblaciones y ejércitos y seguir creciendo.
				</div>

				<img src="'.base_url('design/skin/buildings/farm.png').'" class="img_recurso" title="Granja">

				<div class="building_costs">
				<p>Subir a nivel '.$next_level.'</p>
				<div class="subir_nivel">'.$this->Village_Model->construction_costs($reg["building"],$reg["level"]).'</div>
				</div>';
				$this->layout->bind(array('html' => $html))->show('view/building');
	}
	
	public function logout() {
	    $this->session->sess_destroy();
		redirect('../', 'refresh');
	}
}

/* End of file game.php */
/* Location: ./system/application/controllers/game.php */