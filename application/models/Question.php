<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Question extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllQuestions(){
        $result = $this->db->query('SELECT `question`.*, `category`.`categoryName`, `user`.`userName` 
        FROM `question` LEFT JOIN `category` ON `question`.`categoryId` = `category`.`categoryId` 
        LEFT JOIN `user` ON `question`.`userId` = `user`.`userId`');
        $row = $result->result_array();
        return $row;
    }
   
    public function getQuestionByCategory($catId){
        $result = $this->db->query(' SELECT `question`.*, `user`.`userName`, `category`.`categoryName`
        FROM `question` 
            LEFT JOIN `user` ON `question`.`userId` = `user`.`userId` 
            LEFT JOIN `category` ON `question`.`categoryId` = `category`.`categoryId`
        WHERE `category`.`categoryId` = '.$catId);
        $row = $result->result_array();
        return $row;
    }

    public function createQuestion($data){
        $this->db->insert('question', $data);
        return $this->db->insert_id();
    }

    public function updateQuestion($userID,$data){
        $this->db->where('userId', $userID);
		$this->db->update('question', $data);
    }
    public function likeQuestion($newLike){
        $query = $this->db->insert('userlikequestion', $newLike);
        if($query){
            $this->db->where('questionId', $newLike['questionId']);
            if((int)$newLike['likeValue'] > 0){
                $this->db->set('questionLikes', 'questionLikes+1', FALSE);
            }else{
                $this->db->set('questionLikes', 'questionLikes-1', FALSE);
            }
                $update = $this->db->update('question');
        }else{
            $this->db->where('userId', $newLike['userId']); 
            $this->db->where('questionId', $newLike['questionId']); 
            $result = $this->db->get('userlikequestion');
            $row = $result->result_array();
            if($row[0]['likeValue'] == $newLike['likeValue']){
                $update = false;
            }else{
                $this->db->where('userId', $newLike['userId']); 
                $this->db->where('questionId', $newLike['questionId']); 
                $this->db->update('userlikequestion', array('likeValue' => $newLike['likeValue']));
                $this->db->select('questionLikes');
                $this->db->where('questionId', $newLike['questionId']);
                if($newLike['likeValue'] >0){
                    $this->db->set('questionLikes', 'questionLikes+1', FALSE);
                }else{
                    $this->db->set('questionLikes', 'questionLikes-1', FALSE);
                }
                $update = $this->db->update('question');
            }
        }
        $this->db->select('questionLikes');
        $this->db->where('questionId', $newLike['questionId']);
        $finalValue = $this->db->get('question')->result();
        $message = [
            'status' => $update,
            'question' => $finalValue
        ];
        return $message;
       
    }
    
 
    public function searchQuestion($searchString){
        $result = $this->db->query('SELECT `question`.*, `user`.`userName`, `category`.`categoryName`
        FROM `question` 
            LEFT JOIN `user` ON `question`.`userId` = `user`.`userId` 
            LEFT JOIN `category` ON `question`.`categoryId` = `category`.`categoryId`
    WHERE `question`.`questionTitle` LIKE "%'.$searchString.'%" OR `question`.`questionContent` LIKE "%'.$searchString.'%"');
        $row = $result->result_array();
        return $row;
    }

    public function questionById($id){
        $this->db->where('userId', $id);
        $query = $this->db->get('question');
        $row = $query->result_array();
        return $row;
    }

    public function getQuestionbyRecent(){
        $query = $this->db->query('SELECT `questionId`,`questionTitle`,`questionLikes`
        FROM `question` 
        ORDER BY `questionId` DESC LIMIT 3;');
        $row = $query->result_array();
        return $row;
    }

    public function getQuestionbyLiked(){
        $query = $this->db->query('SELECT `questionId`,`questionTitle`,`questionLikes`
        FROM `question` 
        ORDER BY `questionLikes` DESC LIMIT 3;');
        $row = $query->result_array();
        return $row;
    }

    public function getQuestionbyUnanswered(){
            $query = $this->db->query('SELECT `question`.`questionLikes`, `question`.`questionId`, `answer`.`questionId`AS `values` , `questionTitle`
            FROM `question` 
                LEFT JOIN `answer` ON `answer`.`questionId` = `question`.`questionId`
            ORDER BY `answer`.`questionId` LIMIT 3;');
        $row = $query->result_array();
        return $row;
    }

    public function ifLiked($id,$questionId){
        $query = $this->db->query('SELECT `likeValue` FROM `userlikequestion` WHERE `userId` like '.$id .' && `questionId` like '. $questionId);
        $row = $query->row();
        // if($row["row_data"] == NULL)
        return $row;
    }
}
