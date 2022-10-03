<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Repairs extends MY_Model 
{
    public function getData()
    {
        $query = $this->db->get('repairs');
        return $query->result();
    }                        
                        
}


/* End of file Test_model.php and path \application\models\Test_model.php */
