<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BackEnd extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	
	public function __construct()
	{
              /*  header("Access-Control-Allow-Origin: *");
                header("Access-Control-Allow-Headers: X-API-KEY, Origin,X-Requested-With, Content-Type, Accept, Access-Control-Requested-Method, Authorization");
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, PUT, DELETE");
                $method = $_SERVER['REQUEST_METHOD'];
                if($method == "OPTIONS"){
                die();
                }*/
		parent::__construct();
		$this->load->model('BackEndDB');
		$this->load->model('Users');

		header("Access-Control-Allow-Methods: GET, OPTIONS, POST, GET, PUT");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		$this->_siteKey = 'aKD746fgho86vo86bo8o8701923801923asdasdasd8ybo535fasasdasddsdfAsthd456rtyhdKLs8793wqgiluhedfgd123j2334hjk589';
        date_default_timezone_set('America/Monterrey');
                
	}
	
	
	public function test()
	{
		$query = $this->BackEndDB->getData();
		echo json_encode($query);
	}
	public function getData()
	{


		$query = $this->BackEndDB->getData();
		echo json_encode($query);
	}
	

	public function login()
	{

		$formdata = json_decode(file_get_contents('php://input'), true);
		$correo = $formdata['email'];
		$password = $formdata['password'];
        $query = $this->Users->findByField('correo', $correo);
		
        if ($query->num_rows() == 1) {
            //Si se encontró un registro con ese correo
            
            $query = $query->result();
            
            if($query[0]->u_is_deleted == 1){
               // $this->form_validation->set_message('checar_login', 'Este usuario ha sido eliminado del sistema, favor de contactar al administrador.');
                return false;
            }
            
            $id = $query[0]->id;
            $nombre = $query[0]->nombre;
            $apellidos = $query[0]->apellidos;
            $pass_archivo = $query[0]->password;
            $password = $password . $query[0]->user_salt;
            $password = $this->hashData($password);
            $pass_temporal = $query[0]->password_temporal;
            
            $intentos = $query[0]->intentos;
            $last_login_attempt = $query[0]->last_login_attempt;
            $dif_tiempo = time() - $last_login_attempt;
            
            //Si el usuario llega a 4 intentos fallidos
            if($intentos == 4 && $dif_tiempo <= 60){
               // $this->form_validation->set_message('checar_login', 'Tu cuenta ha sido bloqueada por 20 min por exceder el número de intentos permitidos.');
				return false;
            }

            if($intentos == 6){
               // $this->form_validation->set_message('checar_login', 'Tu cuenta ha sido bloqueada por exceder el número de intentos permitidos. Favor de contactar al administrador para que te envie tus datos.');
				return false;
            }
                                   
            if(($password == $pass_archivo) || $password == $pass_temporal){
                //El password proporcionado por el usuario es correcto    

                $session_data = array(
                    'session'       =>  'ok',
                    'id_usuario'    =>  $id,
                    'correo'        =>  $correo,
                    'nombre'        =>  $nombre,
                    'apellidos'     =>  $apellidos,
                    
                );
                $data = array(
                    'intentos'               =>  0,
                    'last_login_attempt'     =>  time()
                );
                $this->Users->updateByField('correo', $correo, $data);
             //   $this->session->set_userdata(array("admin"    =>  $session_data));
			 	$respuesta = array('error' => FALSE, 'mensaje' => "Bienvenido a tu cuenta", 'id_user' => $id);
				echo json_encode($respuesta);
                return true;
            } else {
                //Si el password es incorrecto se manda mensaje de error
                $data = array(
                    'intentos'               =>  $intentos + 1,
                    'last_login_attempt'     =>  time()
                );
                $this->Users->updateByField('correo', $correo, $data);
				$respuesta = array('error' => TRUE, 'mensaje' => "Usuario o contraseña incorrecto..");
				echo json_encode($respuesta);
				die();
                
            }
        } else {
			
            //Si el correo proporcionado no esta registrado en la base de datos se manda un error
           // $this->form_validation->set_message('checar_login', 'No existe este correo en la base de datos..');
		   $respuesta = array('error' => TRUE, 'mensaje' => "No existe este correo en la base de datos..");
		   echo json_encode($respuesta);
		   die();
        }
	}
	private function randomString($length = 50){
		$string = '';
		$characters = 'ABCDEFGHIJKLMONPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		for($i=0; $i < $length; $i++){
			$string .= $characters[rand(0,strlen($characters)-1)];
		}//fin del for que genera el randomString

		return $string;
	}//fin de la funcion de randomString
    
    private function hashData($data) {
        //En esta función se hace el hash512 para la comprobación de passwords
        return hash_hmac("sha512", $data, $this->_siteKey);
    }//final de la funcion para hashear el pass
    function createTicket(){
        
      
        $data['datos'] = "test";
        //preg_replace('/>\s+</', "><", $html);
        $title = "Ticket de compra";
        $html = preg_replace('/>\s+</', "><", $this->load->view('pdf/index', $data, true));
        $this->pdf->generate($html, $title);
    }
		
}
