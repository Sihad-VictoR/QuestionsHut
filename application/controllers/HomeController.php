<?php
 header("Access-Control-Allow-Origin: *");

class HomeController extends CI_Controller
{
	private $data = [];


	function __construct()
	{
		parent::__construct();
		// $this->load->helper(array('form', 'url'));
		// $this->load->library('form_validation');

		$this->load->library('session');
		$categoryData = $this->getCategory();
		$trendingData = $this->getTrending();
		$this->load->model('Answer');
		$this->data['header'] = $this->load->view('headerView', null, TRUE);
		$this->data['category'] = $this->load->view('categoryView', $categoryData, TRUE);
		$this->data['trending'] = $this->load->view('trendingView', $trendingData, TRUE);
	}
	public function index()
	{
		$this->load->helper('url');
		$questions['questions'] = $this->getAllQuestions();
		$this->data['main'] = $this->load->view('allQuestionsView', $questions, TRUE);
		$this->load->view('homeView', $this->data);
	}


	public function getCategory()
	{
		$this->load->model('Category');
		$data['category'] = $this->Category->getCategoryData();
		return $data;
	}
	
	public function getTrending(){
		$this->load->model('Question');
		$data['unanswered'] = $this->Question->getQuestionbyUnanswered();
		$data['mostliked'] = $this->Question->getQuestionbyLiked();
		$data['recent'] = $this->Question->getQuestionbyRecent();
		return $data;	
	}

	public function changeUserPage()
	{
		$this->load->model('User');
		$data['user'] = $this->User->getUserByEmail($this->session->email);
		$categoryData = $this->getCategory();
		$this->load->view('headerView');
		$this->load->view('categoryView', $categoryData);
		$this->load->view('trendingView');
		$this->load->view('questionsView');
	}
	public function askQuestion()
	{
		$categoryData = $this->getCategory();
		$this->data['main'] = $this->load->view('askQuestionView', $categoryData, TRUE);
		$this->load->view('homeView', $this->data);
	}

	public function getAllQuestions()
	{
		$api_url = $this->config->base_url().'index.php/Api/QuestionApi/questions';
		$client = curl_init($api_url);

		curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($client);

		curl_close($client);

		$result = json_decode($response, true);
		return $result;
	}

	function sortQuestionsCategory()
	{
		$categoryID = (string) $this->uri->segment(2);
		$api_url = $this->config->base_url().'index.php/Api/QuestionApi/questions/category/' . $categoryID . '';
		$client = curl_init($api_url);

		curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($client);

		curl_close($client);

		$result = json_decode($response, true);
		if(isset($result['status'])){
			$result = null;
		}
		$questions['questions'] = $result;
		// echo json_encode($result);
		$this->data['main'] = $this->load->view('allQuestionsView', $questions, TRUE);
		$this->load->view('homeView', $this->data);
	}

	function viewQuestion()
	{ 
		$userAvailable = $this->session->is_logged_in;
		if($userAvailable){
			$user_data = $this->session->userdata('userdata');
			$userId = $user_data['userId'];
		}
		$questionId = (string) $this->uri->segment(2);
		$api_url = $this->config->base_url().'index.php/Api/QuestionApi/questions/id/' . $questionId . '';
		$client = curl_init($api_url);

		curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($client);

		curl_close($client);

		$result = json_decode($response, true);
		if(isset($result['status'])){
			$result = null;
		}
		$question['question'] = $result;
		if(isset($userId)){
			$ifLiked = $this->Question->ifLiked($userId,$questionId);
			$question['liked'] = $ifLiked;
		}
		$this->data['main'] = $this->load->view('questionView', $question, TRUE);
		$this->load->view('homeView', $this->data);
	}

	function getAnswers(){
		$questionId = (string) $this->input->post("questionId");
		$api_url = $this->config->base_url().'index.php/Api/AnswerApi/answers/qId/' . $questionId . '';
		$client = curl_init($api_url);

		curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($client);

		curl_close($client);

		$result = json_decode($response, true);
		if(isset($result['status'])){
			$result = null;
		}
		echo json_encode($result);
	}

	function addAnswer(){
		$userAvailable = $this->session->is_logged_in;
		if($userAvailable){
			$user_data = $this->session->userdata('userdata');
        	$userId = $user_data['userId'];
			$userName = $user_data['username'];
			$questionId = (string) $this->input->post("questionId");
			$answerContent = (string) $this->input->post("answerContent");
			$postParameter = array(
				'userId'			=>	$userId,
				'questionId'		=>	$questionId,
				'answerContent'		=>	$answerContent
			);
			// $api_url = $this->config->base_url().'index.php/Api/AnswerApi/answers';
			// $client = curl_init($api_url);

			// curl_setopt($client, CURLOPT_POST, 1);
			// curl_setopt($client, CURLOPT_POSTFIELDS, $postParameter);	
			// curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
			// $response = curl_exec($client);

			// curl_close($client);
			$createAnsInDB = $this->Answer->createAnswer($postParameter);
            if ($createAnsInDB) {
                $response = [
                    'answerId' => $createAnsInDB,
                    'userId' => $userId,
                    'questionId' => $questionId,
                    'answerContent' => $answerContent,
                    'answerVoteCount' => "0",
					'userName' => $userName,
                    'message' => 'Answer Added'
                ];
            } else {
                $response = [
                    'answerId' =>$createAnsInDB,
                    'message' => 'Fail to Add Answer',
                    'status' => FALSE
                ];
            }
			

			// $result = $response;
			// if(isset($result['status'])){
			// 	$result = null;}
			// }else{
			// 	// TODO: getting userName from session
			// 	$result["userName"] ="SIhad";
			// }
			echo json_encode($response);
		}else echo json_encode(null);
	}

	function voteAnswer(){
		$userAvailable = $this->session->is_logged_in;
		if($userAvailable){
			$answerId = (int) $this->input->post("answerId");
			$voteValue = (int) $this->input->post("voteValue");
			// TODO: getting userId from session
			$user_data = $this->session->userdata('userdata');
        	$userId = $user_data['userId'];
			$postParameter = array(
				'userId'			=>	$userId,
				'answerId'		=>	$answerId,
				'voteValue'		=>	$voteValue
			);
			// $api_url = $this->config->base_url().'index.php/Api/AnswerApi/answers';
			// $client = curl_init($api_url);

			// curl_setopt($client, CURLOPT_POST, 1);
			// curl_setopt($client, CURLOPT_POSTFIELDS, $postParameter);	
			// curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
			// $response = curl_exec($client);

			// curl_close($client);
			$response = $this->Answer->voteAnswer($postParameter);

			// $result = json_decode($response, true);
			echo json_encode($response);
		}else echo json_encode(null);
	}

	function likeQuestion(){
		$userAvailable = $this->session->is_logged_in;
		if($userAvailable){
			$questionId = (int) $this->input->post("questionId");
			$likeValue = (int) $this->input->post("likeValue");
			// TODO: getting userId from session
			$user_data = $this->session->userdata('userdata');
        	$userId = $user_data['userId'];
			$postParameter = array(
				'userId'			=>	$userId,
				'questionId'		=>	$questionId,
				'likeValue'		=>	$likeValue
			);
			// $api_url = $this->config->base_url().'index.php/Api/QuestionApi/questions';
			// $client = curl_init($api_url);

			
			// curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        	// curl_setopt($client, CURLOPT_CUSTOMREQUEST, "PUT");
        	// curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($postParameter));

			// $response = curl_exec($client);
			// curl_close($client);
		
			$questionLiked = $this->Question->likeQuestion($postParameter);

			// $result = json_decode($questionLiked, true);
			echo json_encode($questionLiked);
		}else echo json_encode(null);
	}

	function searchQuestion(){
		$searchString = (string) $this->uri->segment(2);
		$api_url = $this->config->base_url().'index.php/Api/QuestionApi/questions/search/' . $searchString . '';
		$client = curl_init($api_url);

		curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($client);

		curl_close($client);

		$result = json_decode($response, true);
		if(isset($result['status'])){
			$result = null;
		}
		$questions['questions'] = $result;
		// echo json_encode($result);
		$this->data['main'] = $this->load->view('allQuestionsView', $questions, TRUE);
		$this->load->view('homeView', $this->data);
	}

	function addQuestion(){
		$userAvailable = $this->session->is_logged_in;
		if($userAvailable){
			$user_data = $this->session->userdata('userdata');
        	$userId = $user_data['userId'];
			$questionTitle = (string) $this->input->post("questionTitle");
			$questionContent = (string) $this->input->post("questionContent");
			$categoryId = (int) $this->input->post("categoryId");

			$postParameter = array(
				'userId'			=>	$userId,
				'questionTitle'		=>	$questionTitle,
				'questionContent'	=>	$questionContent,
				'categoryId'		=>	$categoryId
			);
			// $api_url = $this->config->base_url().'index.php/Api/QuestionApi/questions';
			// $client = curl_init($api_url);
			// curl_setopt($client, CURLOPT_POST, 1);
			// curl_setopt($client, CURLOPT_POSTFIELDS, $postParameter);	
			// curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
			// $response = curl_exec($client);

			// curl_close($client);
			$createQueInDB = $this->Question->createQuestion($postParameter);
			if ($createQueInDB) {
				$response = [
					'id' => $createQueInDB,
					'categoryId' => $categoryId,
					'questionTitle' => $questionTitle,
					'questionContent' => $questionContent,
					'status' => true,
					'message' => 'Question Added'
				];
			}else {
				$response = [
					'userId' => $userId,
					'categoryId' => $categoryId,
					'questionTitle' => $questionTitle,
					'questionContent' => $questionContent,
					'status' => false,
					'message' => 'Fail to Add Question'
				];
			}
		}else{
			$response = null;
		}
		echo json_encode($response);
	}

	function loadUsers(){
		$this->load->model('User');
		$users['user'] = $this->User->getAllUsersOnCustom();
		$this->data['main'] = $this->load->view('usersListView', $users, TRUE);
		$this->load->view('homeView', $this->data);
	}

	public function loadUserView(){
        $this->load->model('User');
		$this->load->model('Answer');
		$this->load->model('Question');
		$user_data = $this->session->userdata('userdata');
        $userId = $user_data['userId'];
		$users['questions'] = $this->Question->questionById($userId);
		$users['answers'] = $this->Answer->answerByIdCustom($userId);
		$users['user'] = $this->User->getUserbyId($userId);
		$this->data['main'] = $this->load->view('userView', $users, TRUE);
		$this->load->view('homeView', $this->data);
    }

	public function loadForgotPasswordView(){
		$this->load->view('forgotPasswordView');
	}


}
