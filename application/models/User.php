<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function createUser(string $username, string $password, string $email, string $activation_code, int $expiry = 1 * 24  * 60 * 60)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = $this->db->insert(
            'user',
            array(
                'userName' => $username,
                'userPassword' => $hashed_password,
                'userEmail' => $email,
                'userActivationCode' => $activation_code,
                'userActivationExpiry' => $expiry
            )
        );
        $insert_id = $this->db->insert_id();
        if($query){
            return  $insert_id;
        }else{
            $error = $this->db->error();
            throw new Exception('model_name->record: ' . $error['code'] . ' ' . $error['message']);
        }        
    }

    public function authenticateUser(string $email, string $password)
    {
        $user =  $this->getUserbyEmail($email);
        if ($user) {
            foreach ($user->result() as $res) {
                if (password_verify($password, $res->userPassword)) {
                    return $user->result_array();
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function findUnverifiedUser(string $activationCode, int $id)
    {
        $this->db->select('userId,userActivationCode');
        $this->db->from('user');
        $this->db->where('userId', $id);
        $this->db->where('userEmailVerified', 0);
        $user = $this->db->get();
        if ($user) {
            foreach ($user->result_array() as $res) {
                if ($activationCode == $res['userActivationCode']) {
                    return $res;
                }
            }
        }

        return null;
    }

    public function activateUser(int $id)
    {
        $data = array(
            'userEmailVerified' => 1,
            'userActivatedAt' => 'CURRENT_TIMESTAMP'
        );

        $this->db->where('userId', $id);
        return $this->db->update('user', $data);
    }

    public function deleteUserbyId(int $id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('user');
    }

    public function getUserbyName(string $username)
    {
        $this->db->where('userName', $username);
        $query = $this->db->get('user');
        $result = $query->result_array();
        return $result;
    }

    public function getUserbyEmail(string $email)
    {
        $this->db->where('userEmail', $email);
        $query = $this->db->get('user');
        return $query;
    }

    public function getUserbyId($id)
    {
        $this->db->select('*');
        $this->db->where('userId', $id);
        $query = $this->db->get('user');
        return $query->result_array();
    }

    public function getAllUsersOnCustom(){
        $result = $this->db->query('SELECT DISTINCT user.userName, 
        (SELECT COUNT(*) FROM answer
         WHERE answer.userId = user.userId) AS answers,
         (SELECT COUNT(*) FROM question
         WHERE question.userId = user.userId) AS questions
        FROM user');
        $row = $result->result_array();
        return $row;
    }

    public function editUser($name,$about,$id){
        $data = array(
            'userAbout' => $about,
            'userName' => $name
        );
        $this->db->where('userId',$id);
        $result = $this->db->update('user',$data);
        return $result;
    }

    public function changePassword($id,$password){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $data = array(
            'userPassword' => $hashed_password
        );
        $this->db->where('userId', $id);
        return $this->db->update('user', $data);
    }

    public function deleteUser($id){
        $this->db-> where('userId', $id);
        $sql = $this->db-> delete('user');
        return $sql;
    }

}

