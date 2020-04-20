<?php
class Model_final_quiz extends CI_Model{
	function __construct() {
            parent::__construct();
        }
        public function index(){
        	//$this->load->database('final_sub_quiz');
        }
        public function store_data($title,$class,$subject,$chapter){
		$this->load->database('final_sub_quiz');
	$data=array('title'=>$title,'class'=>$class,'subject'=>$subject,'chapter'=>$chapter);
	//$this->db->set('date', 'NOW()', FALSE);
$this->db->insert('quiz',$data);

	}

	public function display_data(){
		$this->load->database('final_sub_quiz');
		$query = $this->db->query("SELECT * FROM quiz ORDER BY id DESC LIMIT 1");
	
return $query;
	}

	public function show_t(){
		$this->load->database('final_sub_quiz');
		$query = $this->db->query("SELECT * FROM quiz ORDER BY id DESC LIMIT 1");
	
return $query;
	}
	public function get_id(){
		$this->load->database('final_sub_quiz');
		$query = $this->db->query("SELECT * FROM quiz ORDER BY id DESC LIMIT 1");
	
return $query;
	}
	public function insert_data_quiz($id,$question){
		$ques_id = $id;
		$this->load->database('final_sub_quiz');
		$data=array('question'=>$question,'ques_id'=>$ques_id);
		
		$this->db->insert('quiz_ques',$data);

		
	}
	public function show_ques(){
		$this->load->database('final_sub_quiz');
		$query = $this->db->query("SELECT * FROM quiz_ques ORDER BY p_id DESC LIMIT 1");
		//$query = $this->db->get('quiz_ques');
		return $query;
	}
	public function store_q($data_display){
		$this->load->database('final_sub_quiz');
		$query = $this->db->query("SELECT id,title,class,subject FROM quiz ORDER BY id DESC LIMIT 1");
		foreach($query->result() as $row){
			$q_id = $row->id;
			$title = $row->title;
			$class = $row->class;
			$subject = $row->subject;
		}
		/*foreach($data_display as $row){
		$query="insert into quiz values('','$q_id','$row','$title','$class','subject')";
	$this->db->query($query);		
		}*/
		//$this->db->where('q_id',$q_id);
		//$output= $this->db->query("SELECT * FROM final_quiz ORDER BY id DESC LIMIT 1");
	//	display($output);
		$output = $this->db->query("SELECT * FROM quiz");
		return $output;

	}

	function fetch_data($id){
		$this->load->database('final_sub_quiz');
		
		//$this->db->where("q_id",$id);

		//$query = $this->db->get("final_quiz");
		$this->db->select('id,title,class,subject,question');
			$this->db->from('quiz');
			$this->db->join('quiz_ques','quiz.id=quiz_ques.ques_id');
			//return $this->db->get('quiz');
			$this->db->where("id",$id);
			return $this->db->get();
		//return $query;
	}
	
	public function insert_fetch_q($q_id,$title,$question,$answer){
		$this->load->database('final_sub_quiz');
		$data = array('ques_id' => $q_id,'title' => $title,'question' =>$question,'answer' =>$answer);
		 $this->db->insert('fetch_q',$data);
	//$this->db->query($query);	
	echo "quiz submitted";	
	}
	public function view_score($q_id){
		$this->load->database('final_sub_quiz');
		$this->db->select('q_id,score');
		$this->db->from('score_update');
		$this->db->where('q_id',$q_id);
		  return  $this->db->get();
		 /*$this->db->select_sum('score');

		$this->db->from('fetch_q');
		$this->db->where('q_id',$q_id);
		 $result = $this->db->get()->result();*/
		 //$score = $result->score;
		//$query="insert into history values('','$q_id','$score')";
	//$this->db->query($query); 
      
		
	
	}

	public function teacher_questions(){
		$this->load->database('final_sub_quiz');
		$query = "select DISTINCT(ques_id) from fetch_q";
		$result =$this->db->query($query); 
		return $result;
		//$this->db->select('count(DISTINCT(q_id))'); 
		//$this->db->from('fetch_q');
		//return $this->db->get();  

	}
	public function save_score($q_id,$score){
		$this->load->database('final_sub_quiz');
		$data = array('q_id' => $q_id,'score' => $score);
		//$data=['score' =>$score,];
		$this->db->insert('score_update',$data);
		//$this->db->where('q_id',$q_id);
		//$this->db->update('fetch_q',$data);

	}
	public function display_quizname(){
			$this->load->database('final_sub_quiz');
			$this->db->select('ques_id,title,class,subject,date,question');
			$this->db->from('quiz');
			$this->db->join('quiz_ques','quiz.id=quiz_ques.ques_id');
			//return $this->db->get('quiz');
			return $this->db->get();
			

	}
	public function show_ans($q_id){
		$this->load->database('final_sub_quiz');
		$this->db->select('ques_id,question,answer');
		$this->db->from('fetch_q');
		$this->db->where('ques_id',$q_id);
		return $this->db->get();

	}

	public function display_subquiz_score(){
		$this->load->database('final_sub_quiz');
		$this->db->select('ques_id,date,score');
		$this->db->from('fetch_q');
		$this->db->join('score_update','fetch_q.ques_id = score_update.q_id');
		
	return 	$this->db->get();
	}
	public function show_summary($q_id){
		$this->load->database('final_sub_quiz');
		$this->db->select('ques_id,question,answer,score');
		$this->db->from('fetch_q');
		$this->db->join('score_update','fetch_q.ques_id = score_update.q_id');
		
	return 	$this->db->get();
	}
	public function get_data($id){
		$this->load->database('final_sub_quiz');
		$this->db->select('id,title,class,subject,question');
			$this->db->from('quiz');
			$this->db->join('quiz_ques','quiz.id=quiz_ques.ques_id');
			//return $this->db->get('quiz');
			$this->db->where("id",$id);
			return $this->db->get();

	}
	public function stu_contact($name,$number,$class){
		$data = array('Student_name' =>$name,'Contact' => $number,'Class' =>$class);
		$this->load->database('final_sub_quiz');
		$this->db->insert('student',$data);

	}
	public function fetch_student(){
		$this->load->database('final_sub_quiz');
		return $this->db->query("SELECT * FROM student");
		

	}
	public function check_user($id){
		$this->load->database('final_sub_quiz');
		return $this->db->select('*')->from('fetch_q')->where('ques_id', $id)->get()->result_array();
	}

	
}
	
?>