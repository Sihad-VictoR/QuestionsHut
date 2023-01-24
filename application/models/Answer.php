<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Answer extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    public function getAnswerByQuestion($qId){
        $query = $this->db->query('SELECT `answer`.*, `user`.`userName`
        FROM `answer` 
            LEFT JOIN `user` ON `answer`.`userId` = `user`.`userId`
        WHERE `answer`.`questionId` = '. $qId);
        $row = $query->result_array();
        return $row;
    }

    public function getAnswerByUser($uId){
        $this->db->where('userId', $uId); 
        $result = $this->db->get('answer');
        $row = $result->result_array();
        return $row;
    }

    public function createAnswer($data){
        $this->db->insert('answer', $data);
        return $this->db->insert_id();
    }

    public function voteAnswer($newVote){
        $query = $this->db->insert('uservoteanswer', $newVote);
        if($query){
            $this->db->where('answerId', $newVote['answerId']);
            if((int)$newVote['voteValue'] > 0){
                $this->db->set('answerVoteCount', 'answerVoteCount+1', FALSE);
            }else{
                $this->db->set('answerVoteCount', 'answerVoteCount-1', FALSE);
            }
                $update = $this->db->update('answer');
        }else{
            $this->db->where('userId', $newVote['userId']); 
            $this->db->where('answerId', $newVote['answerId']); 
            $result = $this->db->get('uservoteanswer');
            $row = $result->result_array();
            if($row[0]['voteValue'] == $newVote['voteValue']){
                $update = false;
            }else{
                $this->db->where('userId', $newVote['userId']); 
                $this->db->where('answerId', $newVote['answerId']); 
                $this->db->update('uservoteanswer', array('voteValue' => $newVote['voteValue']));
                $this->db->where('answerId', $newVote['answerId']);
                if((int)$newVote['voteValue'] > 0){
                    $this->db->set('answerVoteCount', 'answerVoteCount+2', FALSE);
                }else{
                    $this->db->set('answerVoteCount', 'answerVoteCount-2', FALSE);
                }
                $update = $this->db->update('answer');
            }
        }
        $this->db->select('answerVoteCount');
        $this->db->where('answerId', $newVote['answerId']);
        $finalValue = $this->db->get('answer')->result();
        $message = [
            'status' => $update,
            'answer' => $finalValue
        ];
        return $message;
    }

    public function answerByIdCustom($id){
        $query = $this->db->query('SELECT `answer`.`answerContent`, `question`.`questionTitle`
        FROM `answer` 
            LEFT JOIN `question` ON `answer`.`questionId` = `question`.`questionId`
        WHERE answer.userId = '.$id.';');
        $row = $query->result_array();
        return $row;
    }
}
