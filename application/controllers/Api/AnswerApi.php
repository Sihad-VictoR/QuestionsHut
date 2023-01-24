<?php
require APPPATH . '/libraries/REST_Controller.php';

defined('BASEPATH') or exit('No direct script access allowed');
class AnswerApi extends \Restserver\Libraries\REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Answer');
		$this->load->library('form_validation');
	}


	public function answers_get()
	{
		//getting all inputs
		$qId = $this->get('qId');
        $answers = NULL;
        $userId = $this->get('userId');
        
		//checking inputs and setting response based on the request
		if ($qId === NULL && $userId === NULL) {
				$this->response([
					'status' => FALSE,
					'message' => 'Specify a question / User Id Or User Id and AnswerId'
				], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
			
		} elseif ($qId === NULL) {
			$userId = (int) $userId;
			if ($userId <= 0) {
				$this->response(NULL, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
			}
			$answers = $this->Answer->getAnswerByUser((string)$userId);
			//Question ?Found
			if (!empty($answers)) {
				$this->set_response($answers, \Restserver\Libraries\REST_Controller::HTTP_OK);
			} else {
				$this->set_response([
					'status' => FALSE,
					'message' => 'Answers in chosen User could not be found'
				], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
			}
		} else { 
			$qId = (int) $qId;
			if ($qId <= 0) {
				$this->response(NULL, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
			}
			$answers = $this->Answer->getAnswerByQuestion((string)$qId);

			if (!empty($answers)) {
				$this->set_response($answers, \Restserver\Libraries\REST_Controller::HTTP_OK);
			} else {
				$this->set_response([
					'status' => FALSE,
					'message' => 'Answers in chosen Question could not be found'
				], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
			}
		}
	}

	public function answers_post()
	{
		$userId = $this->post('userId');
		$questionId = $this->post('questionId');
		$answerContent = $this->post('answerContent');
        $answerId = $this->post('answerId');
        $voteValue = $this->post('voteValue');
		$userName = $this->post('userName');
		//checking for answer post or vote post
        if($answerId == null){
            $newAnswer = array(
                'userId'			=>	$userId,
                'questionId'		=>	$questionId,
                'answerContent'		=>	$answerContent
            );
            $createAnsInDB = $this->Answer->createAnswer($newAnswer);
            if ($createAnsInDB) {
                $message = [
                    'answerId' => $createAnsInDB,
                    'userId' => $userId,
                    'questionId' => $questionId,
                    'answerContent' => $answerContent,
                    'answerVoteCount' => "0",
					'userName' => $userName,
                    'message' => 'Answer Added'
                ];
                $this->set_response($message, \Restserver\Libraries\REST_Controller::HTTP_CREATED);
            } else {
                $message = [
                    'answerId' =>'$createAnsInDB',
                    'message' => 'Fail to Add Answer',
                    'status' => FALSE
                ];
                $this->set_response($message, \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }else{
            $newVote = array(
                    'userId'		=>	$userId,
                    'answerId'		=>	$answerId,
                    'voteValue'		=>	$voteValue
                );
            $voteAnswer = $this->Answer->voteAnswer($newVote);
            
            $this->set_response($voteAnswer, \Restserver\Libraries\REST_Controller::HTTP_OK);
        }
	}
}
