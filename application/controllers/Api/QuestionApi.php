<?php
require APPPATH . '/libraries/REST_Controller.php';

defined('BASEPATH') or exit('No direct script access allowed');
class QuestionApi extends \Restserver\Libraries\REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Question');
		$this->load->library('form_validation');
	}


	public function questions_get()
	{
		// Gettiing all questions
		$questions = $this->Question->getAllQuestions();
		$id = $this->get('id');
		$category = $this->get('category');
		$searchString = $this->get('search');

		$question = NULL;
		if ($id === NULL && $category === NULL && $searchString === NULL) {
			if ($questions) {
				$this->response($questions, \Restserver\Libraries\REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => FALSE,
					'message' => 'No questions were found'
				], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
			}
		} elseif ($id === NULL && $searchString === NULL) {
			$category = (int) $category;
			if ($category <= 0) {
				$this->response(NULL, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
			}
			$questions = $this->Question->getQuestionByCategory((string)$category);
			//Question ?Found
			if (!empty($questions)) {
				$this->set_response($questions, \Restserver\Libraries\REST_Controller::HTTP_OK);
			} else {
				$this->set_response([
					'status' => FALSE,
					'message' => 'Question in chosen category could not be found'
				], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
			}
		} elseif ($id === NULL && $category === NULL) {
			$searchString = (string) $searchString;

			$questions = $this->Question->searchQuestion($searchString);
			//Question ?Found
			if (!empty($questions)) {
				$this->set_response($questions, \Restserver\Libraries\REST_Controller::HTTP_OK);
			} else {
				$this->set_response([
					'status' => FALSE,
					'message' => 'No Results Found'
				], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
			}
		} else { // Getting a Single Question.
			$id = (int) $id;
			if ($id <= 0) {
				$this->response(NULL, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
			}
			//Searching all questions
			if (!empty($questions)) {

				foreach ($questions as $key => $value) {
					if ($value['questionId'] === (string)$id) {

						$question = $value;
					}
				}
			}
			//Question ?Found
			if (!empty($question)) {
				$this->set_response($question, \Restserver\Libraries\REST_Controller::HTTP_OK);
			} else {
				$this->set_response([
					'status' => FALSE,
					'message' => 'Question could not be found'
				], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
			}
		}
	}


	public function questions_post()
	{
		$userId = $this->post('userId');
		$categoryId = $this->post('categoryId');
		$questionTitle = $this->post('questionTitle');
		$questionContent = $this->post('questionContent');

		$newQuestion = array(
			'userId'			=>	$userId,
			'categoryId'		=>	$categoryId,
			'questionTitle'		=>	$questionTitle,
			'questionContent'	=>	$questionContent
		);
		$createQueInDB = $this->Question->createQuestion($newQuestion);
		if ($createQueInDB) {
			$message = [
				'id' => $createQueInDB,
				'categoryId' => $categoryId,
				'questionTitle' => $questionTitle,
				'questionContent' => $questionContent,
				'status' => true,
				'message' => 'Question Added'
			];
			$this->set_response($message, \Restserver\Libraries\REST_Controller::HTTP_CREATED);
		} else {
			$message = [
				'userId' => $userId,
				'categoryId' => $categoryId,
				'questionTitle' => $questionTitle,
				'questionContent' => $questionContent,
				'status' => false,
				'message' => 'Fail to Add Question'
			];
			$this->set_response($message, \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function questions_put()
    {
		$userId = $this->put('userId');
		$questionId = $this->put('questionId');
		$likeValue = $this->put('likeValue');

		// if($questionId == null || $likeValue == null ){
		// 	$message = [
		// 		'message' => 'Null Values Provided'
		// 	];
		// 	$this->set_response($message, \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
		// }else{
			$newLike = array(
				'userId'		=>	$userId,
				'questionId'		=>	$questionId,
				'likeValue'		=>	$likeValue
			);
			$questionLiked = $this->Question->likeQuestion($newLike);
			$this->set_response($questionLiked, \Restserver\Libraries\REST_Controller::HTTP_OK);
		// }
	}
}
