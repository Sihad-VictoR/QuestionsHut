<?php
 header("Access-Control-Allow-Origin: *");

class AuthController extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('User');
        $this->load->library('session');
        $this->load->helper('cookie');
    }

    public function index()
    {
        $this->load->helper('url');
        $this->load->view('headerView');
    }

    public function loadLogin()
    {
        $this->load->view('loginView');
    }
    public function loadSignUp()
    {
        $this->load->view('signupView');
    }

    //registering a new user after authentication is successful
    public function registerUser()
    {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $activationCode = $this->generateActivationCode();
        try{
            $data = $this->User->createUser($name, $password, $email, $activationCode, 1);
            if($data){
                $mailResponse = $this->sendActivationEmail($email, $activationCode, $data);
                $response['mail'] = $mailResponse;
                $response['status'] = true;
                $response['user'] = $data;
                //Creating Session and Assigning values.
                session_regenerate_id();
                $newdata = array(
                    'userId'    => $data,
                    'username'  => $name,
                    'email'     => $email,
                    'is_logged_in' => true
                );
                $this->session->set_userdata('userdata', $newdata);
                $this->session->is_logged_in = true;
            }
        }catch  (Exception $e) {
            log_message('error', $e->getMessage());
            $response['status'] = false;
        } 
        $this->session->set_flashdata('success', 'Registration successful');
        
        
        echo json_encode($response);
    }
    //logging a  user after authentication is successful
    public function authenticateUser()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $remember = $this->input->post('checkbox');
        $data = $this->User->authenticateUser($email, $password);
        //creating a new session
        if ($data) {
            session_regenerate_id();
            $newdata = array(
                'userId'    => $data[0]['userId'],
                'username'  => $data[0]['userName'],
                'email'     => $data[0]['userEmail'],
                'is_logged_in' => true
            );
            $this->session->set_userdata('userdata', $newdata);
            $this->session->is_logged_in = true;
            $response['status'] = true;
            //creating a new cookie if user wants to remember him/her for 10 minutes
            if($remember){
                $this->input->set_cookie('userId', $data[0]['userId'], 60000);
            }
        } else {
            $response['status'] = false;
        }
        echo json_encode($response);
    }

    //logging out using session and cookie destruction
    public function logout()
    {
        $this->session->is_logged_in = false;
        $this->session->set_flashdata('success', 'Logout successful');
        $this->session->unset_userdata('userdata');
        $this->session->sess_destroy();
        if ($this->input->cookie('userId', TRUE)){
            delete_cookie('userId');
        }
        redirect(base_url().'index.php');
    }

    //generating an activationcode
    public function generateActivationCode()
    {
        return bin2hex(random_bytes(16));
    }

    //sending an activationcode through mail
    //used another server because of uow unsupport port.
    public function sendActivationEmail(string $email, string $activationCode, int $id)
    {
        $subject='Account Activation';
        $message =     "<html><head><title>Verification Code</title></head><body><h2>Thank you for Registering.</h2><p>Please click the link below to activate your account.</p><h4><a href='" . base_url() . "index.php/AuthController/activate/" . $id . "/" . $activationCode . "'>Activate My Account</a></h4></body></html>";
        $postParameter = array(
				'to'			=>	$email,
                'subject'	    =>	$subject,
				'message'		=>	$message
			);
		$api_url = 'https://qhut.000webhostapp.com/email.php';
		$client = curl_init($api_url);

		curl_setopt($client, CURLOPT_POSTFIELDS, $postParameter);	
		curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($client);

		curl_close($client);

		return json_decode($response, true);
    }

    //activating email from email
    public function activate()
    {
        $userAvailable = $this->session->is_logged_in;
        if($userAvailable){
            $this->logout();
        }
        $id =  $this->uri->segment(3);
        $activationCode = $this->uri->segment(4);
        //fetch user details
        $user = $this->User->findUnverifiedUser($activationCode, $id);
        //if code matches
        if ($user) {
            //update user active status
            $query = $this->User->activateUser($user['userId']);

            if ($query) {
                $this->session->set_flashdata('message', 'User activated successfully');
            } else {
                $this->session->set_flashdata('message', 'Something went wrong in activating account');
            }
        } else {
            $this->session->set_flashdata('message', 'Cannot activate account. Code didnt match');
            redirect('signup');
        }

        redirect('login');
    }
    //check if user is already logged in using session or cookies
    public function isUserLoggedIn()
    {
        $userId = $this->input->cookie('userId', TRUE);
        if(isset($userId)){
            $user = $this->User->getUserById($userId);
            session_regenerate_id();
            $newdata = array(
                'userId'    => $user[0]['userId'],
                'username'  => $user[0]['userName'],
                'email'     => $user[0]['userEmail'],
                'is_logged_in' => true
            );
            $this->session->set_userdata('userdata', $newdata);
            $this->session->is_logged_in = true;
        }
        $status = null;
        $username = null;
        if (isset($this->session->is_logged_in) && $this->session->is_logged_in) {
            $user_data = $this->session->userdata('userdata');
            $username = $user_data['username'];
            $status = true;
        } else {
            $status = false;
        }
        $return_arr = array('status' => $status, 'name' => $username);

        echo json_encode($return_arr);
    }
    //editing user data
    public function editUser(){
        $user_data = $this->session->userdata('userdata');
        $userId = $user_data['userId'];
        $userName = (string) $this->input->post("userName");
		$userAbout = (string) $this->input->post("userAbout");
        //seesion
        $result = $this->User->editUser($userName,$userAbout,$userId);
		echo json_encode($result);
    }

    //changing to password view
    public function changePassword(){
        $id =  $this->uri->segment(2);
        $user = $this->User->getUserbyId($id);

        $data['user'] = $user;
        $this->load->view('changePasswordView',$data);
    }

    //resetting user password finally
    public function resetUserPassword(){
        $userId = (int) $this->input->post("userId");
		$password = (string) $this->input->post("password");

        $result = $this->User->changePassword($userId,$password);
        if($result){
            if (isset($this->session->is_logged_in)){
                $this->logout();
            }
            $data['status'] = true;
        }else{
            $data['status'] = false;
        }
        echo json_encode($data);
    }

    //checking mail exist and sending mail to reset password
    public function checkMail(){
        $userEmail = (string) $this->input->post("userEmail");
        try{
            $user = $this->User->getUserbyEmail($userEmail);
            $userId = $user->result_array();
            if(isset($userId[0])){
                $subject='Password Reset';
                $data['status'] = true;
                $message =     "<html><head><title>Password Reset</title></head><body><p>Please click the link below to reset your password.</p><h4><a href='" . base_url() . "index.php/changePassword/" . $userId[0]['userId'] . "'>Reset Password</a></h4></body></html>";
                $postParameter = array(
                        'to'			=>	$userEmail,
                        'subject'	    =>	$subject,
                        'message'		=>	$message
                    );
                $api_url = 'https://qhut.000webhostapp.com/email.php';
                $client = curl_init($api_url);

                curl_setopt($client, CURLOPT_POSTFIELDS, $postParameter);	
                curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($client);

                curl_close($client);
                $data['response'] = $response;
            }else{
                $data['status'] = false;
            }
        }catch(Exception $e){
            $data['status'] = false;
        }
        echo json_encode($data);
    }

    //deleting an user from the system
    public function deleteUser(){
        $user_data = $this->session->userdata('userdata');
        $userId = $user_data['userId'];
        $del = $this->User->deleteUser($userId);

        $this->logout();
    }
}
