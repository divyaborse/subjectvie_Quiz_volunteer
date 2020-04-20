<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Teacher extends CI_Controller {
	public function index() {
		if ($this->session->userdata('teacherlogin')['id']) {

			redirect(base_url() . 'volu/Teacher/dashboard');
		}
		redirect(base_url());
	}
	public function login() {

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$teacher_login = array(
				'email' => htmlspecialchars((strip_tags(trim($this->input->post('t_email'))))),
				'password' => md5(htmlspecialchars(strip_tags(trim($this->input->post('t_password'))))),
				'type' => htmlspecialchars(strip_tags(trim($this->input->post('logintype')))),
			);
			$this->load->model('Model_teacher', 'mt');
			$result_login = $this->mt->login_teacher($teacher_login);

			if ($result_login == true) {
				$resultlogin = $this->mt->getteacherData(htmlspecialchars(strip_tags(trim($this->input->post('t_email')))));
				print_r($resultlogin);
				$session_data = array(
					'id' => $resultlogin[0]['id'],
					'name' => $resultlogin[0]['name'],
					'email' => $resultlogin[0]['email'],
					'contact' => $resultlogin[0]['contact'],
					'gender' => $resultlogin[0]['gender'],
					'type' => $resultlogin[0]['type'],
					'school' => $resultlogin[0]['school'],
				);
				// Add user data in session
				$this->session->set_userdata('teacherlogin', $session_data);
				if ($this->session->userdata('studentlogin')['id']) {
					$this->session->unset_userdata('studentlogin');
				}
				//activity track
				$this->load->model('Model_clientIP');
				date_default_timezone_set('Asia/Kolkata');
				$activity = array(
					'user_id' => $this->session->userdata('teacherlogin')['id'],
					'user_name' => $this->session->userdata('teacherlogin')['name'] . '(' . $this->session->userdata('teacherlogin')['school'] . ')',
					'system_info' => php_uname(),
					'activity_name' => 'school_' . $resultlogin['type'] . '_teacher_login',
					'access_date_time' => date('Y-m-d H:i:s', time()),
				);

				$this->Model_clientIP->user_Activity($activity);
				//actiivty track end
				redirect(base_url() . 'volu/Teacher/dashboard');
			} else {
				$cdata = array(
					'flag' => 40,
				);

				$this->session->set_flashdata('o_register', $cdata);
				redirect(base_url());
			}
		}
	}
	public function dashboard() {
		if (!$this->session->userdata('teacherlogin')['id']) {

			redirect(base_url());
		}
		$data['page_title'] = 'Intelify | Teacher';
		$this->load->model('Model_quiz', 'mq');
		$data['quiz'] = $this->mq->show_quiz($this->session->userdata('teacherlogin')['id']);
		$data['share'] = $this->mq->share($this->session->userdata('teacherlogin')['id']);

		$this->load->view('volu/accountheader', $data);
		$this->load->view('volu/teacher_dashboard', $data);
		$this->load->view('volu/accountfooter', $data);
	}
	public function add_contact_form() {
		if (!$this->session->userdata('teacherlogin')['id']) {

			redirect(base_url());
		}
		$this->load->model('Model_quiz', 'mq');
		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			$data1 = array(
				'teacher_id' => $this->session->userdata('teacherlogin')['id'],
				'student_contact_name' => $this->input->post('stdname'),
				'student_contact_number' => $this->input->post('stdcontact'),
				'student_class' => $this->input->post('class'),
			);
			$this->mq->add_contact($data1);

			$this->session->set_flashdata('loginnotify', 'Contact added successfully');
			redirect('volu/Teacher/dashboard');
		}
	}

	public function ManageContact() {
		if (!$this->session->userdata('teacherlogin')['id']) {

			redirect(base_url());
		}
		$this->load->model('Model_quiz', 'mq');
		$data['page_title'] = 'Intelify | Teacher';
		$data['share'] = $this->mq->share($this->session->userdata('teacherlogin')['id']);
		$this->load->view('volu/accountheader', $data);
		$this->load->view('volu/manage_contact', $data);
		$this->load->view('volu/accountfooter', $data);
	}

	public function delete_contact() {
		if (!$this->session->userdata('teacherlogin')['id']) {

			redirect(base_url());
		}
		$data = array(
			'contact_id' => $this->input->post('id'),
			'teacher_id' => $this->input->post('owner'),
		);
		print_r($data);
		$this->load->model('Model_quiz', 'mq');
		echo $this->mq->Delete_contact($data);
		$this->session->set_flashdata('loginnotify', 'Contact deleted successfully!!');

	}
	public function share_url() {
		if (!$this->session->userdata('teacherlogin')['id']) {

			redirect(base_url());
		}
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$url = $this->input->post('url');
			//	$stdchoosen['choose'] = $this->input->post('stdchoosen');

			$stdchoosen = '91' . implode('", "91', $this->input->post('stdchoosen'));

			// echo "<pre>";
			// print_r($stdchoosen);
			// print_r($url);
			// redirect('volu/Teacher/dashboard',$url);
			if (filter_var($stdchoosen, FILTER_SANITIZE_NUMBER_INT)) {

				$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://api.msg91.com/api/v2/sendsms",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => "{ \"sender\": \"INTLFY\", \"route\": \"4\", \"country\": \"91\", \"sms\": [ { \"message\": \"Your teacher has invited you to join and learn at www.intellify.in , " . $url . "\", \"to\": [ \"" . $stdchoosen . "\" ] } ] }",
					CURLOPT_SSL_VERIFYHOST => 0,
					CURLOPT_SSL_VERIFYPEER => 0,
					CURLOPT_HTTPHEADER => array(
						"authkey: 307315AL7iQXSUHCz5dea5f24",
						"content-type: application/json",
					),
				));
				if (curl_exec($curl)) {

					$this->session->set_flashdata('loginnotify', 'URL shared successfully');
					curl_close($curl);
					redirect(base_url() . 'volu/Teacher/dashboard');
				} else {
					$this->session->set_flashdata('loginnotify', 'Somthing went wrong');
				}

			} else {
				$this->session->set_flashdata('loginnotify', 'Somthing went wrong');

				// Process your response here

			}

		}

	}
	public function remove_session() {
		if (isset($this->session->userdata('quiz_data')['q_id'])) {
			$this->session->unset_userdata('quiz_data');
		}
		redirect(base_url() . '/volu/Teacher/dashboard');
	}
	public function quiz_users() {
		if (!$this->session->userdata('teacherlogin')['id']) {

			redirect(base_url());
		} else {
			$data['page_title'] = 'Intelify | Teacher';
			$this->load->model('Model_quiz', 'mq');
			$data['quiz_users'] = $this->mq->fetch_quiz_users($this->uri->segment(3));
			$this->load->view('volu/accountheader', $data);
			if ($data['quiz_users']) {
				$this->load->view('volu/quiz_user_list');
			} else {
				$this->load->view('volu/quiz_user_list', $data);
			}
			$this->load->view('volu/accountfooter');
		}
	}
	public function logout() {
		if ($this->session->userdata('teacherlogin')) {
			//activity track
			$this->load->model('Model_clientIP');
			date_default_timezone_set('Asia/Kolkata');
			$activity = array(
				'user_id' => $this->session->userdata('teacherlogin')['id'],
				'user_name' => $this->session->userdata('teacherlogin')['name'] . '(' . $this->session->userdata('teacherlogin')['school'] . ')',
				'system_info' => php_uname(),
				'activity_name' => 'school_' . $this->session->userdata('teacherlogin')['type'] . '_teacher_login',
				'access_date_time' => date('Y-m-d H:i:s', time()),
			);

			$this->Model_clientIP->user_Activity($activity);
			//actiivty track end
			$this->session->unset_userdata('teacherlogin');
			redirect(base_url() . 'Teacher');
		} else {
			redirect(base_url());
		}
	}
	public function delete_quiz() {
		if (!$this->session->userdata('teacherlogin')['id']) {

			redirect(base_url());
		} else {
			$this->load->model('Model_quiz', 'mq');
			if ($this->mq->delete_quiz_db($this->uri->segment(3), $this->session->userdata('teacherlogin')['id'])) {
				redirect(base_url('volu/Teacher/dashboard'));
			}
			redirect(base_url('volu/Teacher/dashboard'));
		}
	}
}
