<?php
class Player_Model extends CI_Model
{
    
    function __construct()
    {
        parent::__construct();
    }

    function Error($error = '')
    {
        echo $error;
    }
	
	function Login($username, $password)
    {
        $query = $this->db->get_where('users', array('username' => $username, 'password' => $password));
        if ($query->num_rows() > 0) {
            $user = $query->row();
            $query = $this->db->get_where('map', array('id_user' => $user->id));
			$citta = $query->row();

			$this->session->set_userdata(array('id' => $user->id, 'username' => $user->username, 'id_town' => $citta->id, 'logged_in' => TRUE, 'email' => $user->email));
			redirect('game/village');
        } else
            $this->Error('Nessun utente trovato');
    }

    function Registration($username, $password, $email, $ip)
    {
		$this->db->where('username =', $username);
		$this->db->or_where('email =', $email);
		$this->db->or_where('ip =', $ip);  
		$query = $this->db->get('users');
		if($query->num_rows() > 0) $this->Error('Qualche campo è già preso.');
				
		$data = array(
            'username'      => $username,
            'password'      => $password,
            'email'         => $email,
		    'last_login'    => time(),
            'ip'            => $ip
        );
        
		$this->db->insert('users', $data); 
        $id = $this->db->insert_id();
		$x = rand(1,10);
		$y = rand(1,10);
		
		$this->db->select('id');
		$this->db->from('map');
		$this->db->where('x', $x); 
		$this->db->where('y', $y); 
		$this->db->where('id_user', '0'); 
		$res = $this->db->get();
		
		do {
            $x   = rand(1,10);
			$y   = rand(1,10);
            $this->db->select('id');
		    $this->db->from('map');
		    $this->db->where('x', $x); 
		    $this->db->where('y', $y); 
		    $this->db->where('id_user', '0'); 
		    $res = $this->db->get();
        } while (count($res) < 1);
		$id_town = $res->row()->id;
		
		$data =	array(
		            'state' => 'Village of '.$username,
				    'type' => 'Village',
					'population' => '6',
					'wood'   => '500',
					'clay'  => '500',
					'iron'  => '500',
					'crop'   => '500',
					'capital' => '1',
					'last_update' => time(),
					'id_user' => $id,
					); 
		$this->db->where('x', $x);
		$this->db->where('y', $y);
        $this->db->update('map', $data); 		
				
		$data = array(
		        'building' => 'town_hall',
			    'level' => '1',
			    'resource' => 'none',
			    'production' => '0',
			    'population' => '2',
			    'slot' => '5',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data); 
		
		
		$data = array(
		        'building' => 'farm',
			    'level' => '0',
			    'resource' => 'crop',
			    'production' => '3',
			    'population' => '1',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data); 
		
		$data = array(
		        'building' => 'woodcutter',
			    'level' => '0',
			    'resource' => 'wood',
			    'production' => '3',
			    'population' => '1',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data); 
		
	    $data = array(
		        'building' => 'clay_pit',
			    'level' => '0',
			    'resource' => 'clay',
			    'production' => '3',
			    'population' => '1',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data); 
		
		$data = array(
		        'building' => 'iron_mine',
			    'level' => '0',
			    'resource' => 'iron',
			    'production' => '3',
			    'population' => '1',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data);
		
		$data = array(
		        'building' => 'warehouse',
			    'level' => '0',
			    'resource' => 'capacity',
			    'production' => '800',
			    'population' => '0',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data);
		
		$data = array(
		        'building' => 'market',
			    'level' => '0',
			    'resource' => 'trade',
			    'production' => '0',
			    'population' => '0',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data);
        
		$data = array(
		        'building' => 'barracks',
			    'level' => '0',
			    'resource' => 'troops',
			    'production' => '0',
			    'population' => '0',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data);
        
		$data = array(
		        'building' => 'stable',
			    'level' => '0',
			    'resource' => 'troops',
			    'production' => '0',
			    'population' => '0',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data);
        
		$data = array(
		        'building' => 'workshop',
			    'level' => '0',
			    'resource' => 'troops',
			    'production' => '0',
			    'population' => '0',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data);
        
		$data = array(
		        'building' => 'embassy',
			    'level' => '0',
			    'resource' => 'member',
			    'production' => '0',
			    'population' => '0',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data);
        
		$data = array(
		        'building' => 'cranny',
			    'level' => '0',
			    'resource' => 'hidden',
			    'production' => '0',
			    'population' => '0',
			    'slot' => '0',
			    'id_town' => $id_town
		);
		$this->db->insert('buildings', $data);
        
		$data = array(
		        'troop1'  => '0',
                'troop2'  => '0', 
			    'troop3'  => '0',
                'troop4'  => '0', 
			    'troop5'  => '0',
                'troop6'  => '0', 
			    'troop7'  => '0',
                'troop8'  => '0',
			    'troop9'  => '0',
                'troop10' => '0',
                'id_town' => $id_town
		);
		$this->db->insert('troops', $data);

	    $newdata = array(
                   'id'        => $id,
				   'username'  => $username,
                   'email'     => $email,
				   'id_town'   => $id_town,
                   'logged_in' => TRUE
        );
        $this->session->set_userdata($newdata);
		redirect('game/village', 'refresh');
    }
	
}

/* End of file player_model.php */
/* Location: ./system/application/models/player_model.php */