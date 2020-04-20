<?php
class addsubquiz extends CI_Controller{
	 function __construct() {
        parent::__construct();
        $this->load->model('volu/Model_final_quiz'); //this will show "Print" word on browser. 
    }
	public function index(){
		if (!$this->session->userdata('teacherlogin')['id']) {
			redirect(base_url());
		}
		$data['page_title'] = 'Intelify | Teacher';
		$this->load->model('volu/Model_quiz');
	 //$this->Model_quiz->temp_quiz();

		$this->load->view('volu/accountheader', $data);
		$this->load->view('volu/Add_sub_quiz',$data);
		$this->load->view('volu/accountfooter');
	}
	public function quizset(){
		if(isset($_POST['submit'])){
			$title = $_POST['title'];
			foreach ($_POST['class'] as $select)
{
$class = $select; // Displaying Selected Value
} 
foreach ($_POST['sub'] as $select)
{
$subject = $select; // Displaying Selected Value
} 
foreach ($_POST['chapter'] as $select)
{
$chapter = $select; // Displaying Selected Value
} 
$this->load->model('volu/Model_final_quiz');
$this->Model_final_quiz->store_data($title,$class,$subject,$chapter);
$query['query']=$this->Model_final_quiz->display_data();
if (!$this->session->userdata('teacherlogin')['id']) {
			redirect(base_url());
		}
		$data['page_title'] = 'Intelify | Teacher';

		$this->load->view('volu/accountheader', $data);
$this->load->view('volu/open_modal',$query);
$this->load->view('volu/accountfooter');


		}
	}
	
	public function display_questions(){
		if(isset($_POST['submit'])){
			$this->load->model('volu/Model_final_quiz');
			$question = $_POST['question'];
			/*$data_q = array(
				'question' => $question,
				'id' => $id
		
			);*/
		//	$show['show']=$this->Model_final_quiz->show_t();
		//$this->load->view('volu/Add_subj_quiz',$show);
			$result = $this->Model_final_quiz->get_id();
			foreach($result->result() as $row){
				$id = $row->id;
			}
			/*$data_q = array(
				'question' => $question,
				'ques_id' => $id
		
			);*/
			$this->Model_final_quiz->insert_data_quiz($id,$question);
			$data['ques'] = $this->Model_final_quiz->show_t();
			$data['data']=$this->Model_final_quiz->show_ques();
			if (!$this->session->userdata('teacherlogin')['id']) {
			redirect(base_url());
		}
		$data['page_title'] = 'Intelify | Teacher';

		$this->load->view('volu/accountheader', $data);
		$this->load->view('volu/quiz_show',$data);
		$this->load->view('volu/accountfooter');
		}
	}


public function view_q(){
		if(isset($_POST['submit'])){

    if(!empty($_POST['ques'])) {

        foreach($_POST['ques'] as $value){
           $data_display['data_display'] = $value;
           $this->load->model('volu/Model_final_quiz');
           $output['output'] =$this->Model_final_quiz->store_q($data_display);
            redirect(base_url() . 'Teacher_subquiz/dashboard');

           //$this->load->view('volu/show_ques_title',$output);
           $this->load->view('volu/accountfooter');

        }


    }

}
	}


	

	public function store_a(){
		if(isset($_POST['submit'])){
			$q_id = $this->input->post("hidden_id");
			$title = $_POST['hidden_title'];
			$question = $this->input->post("hidden_question");
			$answer = $_POST['answer'];
			$this->load->model('volu/Model_final_quiz');
			$this->Model_final_quiz->insert_fetch_q($q_id,$title
				,$question,$answer);
			$query['query'] =$this->Model_final_quiz->view_score($q_id);

			$this->load->view('volu/Score_dis',$query);

		}
	}
}

?>