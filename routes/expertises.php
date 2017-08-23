<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Expertises extends Controller_Mycontroller {

	public function action_index(){
		$this->template->title = "Экспертизы | РГКП 'Центр судебных экспертиз'";
		$this->template->menu = View::factory('admin/menu');

		$user_id = Auth::instance()->get_user()->id;
		$result = Model::factory('departments')->get_user_info_by_id($user_id);
		$department_id = $result['department_id'];
		$position_id = $result['position_id'];
		$arr = array();
		if($position_id==8){
			$users = Model::factory('expertises')->get_users_for_exp($department_id);
			foreach($users as $user):
				array_push($arr, $user['id']);
			endforeach;
			$users = implode(', ', $arr);
			unset($arr);
			$expertises = Model::factory('expertises')->get_all_expertises_for_zam($users);
		}
		elseif($position_id==25 OR $position_id==31){
			$users = Model::factory('expertises')->get_users_for_exp2($department_id);
			foreach($users as $user):
				array_push($arr, $user['id']);
			endforeach;
			$users = implode(', ', $arr);
			unset($arr);
			$expertises = Model::factory('expertises')->get_all_expertises_for_zav_and_ruk($users);
		}
		else{
			$expertises = Model::factory('expertises')->get_all_expertises($user_id);
		}
		

		$this->template->content = View::factory('expertises/index', array(
			'expertises' => $expertises,
			'position_id' => $position_id,
		));
	}

	public function action_register(){
		$user_id = Auth::instance()->get_user()->id;
		$this->template->title = "Регистрация документа | РГКП 'Центр судебных экспертиз'";
		$this->template->menu = View::factory('admin/menu');
		$deal_categories = Model::factory('expertises')->get_deal_categories();
		$expertise_specialties = Model::factory('expertises')->get_expertise_specialties();
		$statuses = Model::factory('expertises')->get_statuses();
		$statuses_extra = Model::factory('expertises')->get_statuses_extra();
		$agencies = Model::factory('expertises')->get_agencies();
		$regions = Model::factory('expertises')->get_regions();
		$users = Model::factory('expertises')->get_users();
		$repeat_regions = Model::factory('expertises')->get_repeat_regions();

		$view = View::factory('expertises/register', array(
			'deal_categories' => $deal_categories,
			'expertise_specialties' => $expertise_specialties,
			'statuses' => $statuses,
			'statuses_extra' => $statuses_extra,
			'agencies' => $agencies,
			'regions' => $regions,
			'users' => $users,
			'repeat_regions' => $repeat_regions,
		));
		
		if(isset($_POST['register_btn'])){
			$date_of_reg = date('Y-m-d', time());
			$reg = "";
			$plot = htmlspecialchars(strip_tags($_POST['plot']));
			$deal_category_id = htmlspecialchars(strip_tags($_POST['deal_category_id']));
			$deal_num = htmlspecialchars(strip_tags($_POST['deal_num']));
			$article_num = htmlspecialchars(strip_tags($_POST['article_num']));
			$specialty_ids = $_POST['specialty_ids'];
			$spec_ids = '';
			$specialty_ids_counter = count($specialty_ids);
			for($i=0; $i<$specialty_ids_counter; $i++){
				$spec_ids .= $specialty_ids[$i] . ", ";
			}
			$spec_ids = substr($spec_ids, 0, strlen($spec_ids)-2);
			$status_id = htmlspecialchars(strip_tags($_POST['status_id']));
			$status_extra_id = htmlspecialchars(strip_tags($_POST['status_extra_id']));
			$agency_id = htmlspecialchars(strip_tags($_POST['agency_id']));
			if($agency_id=='10'){
				$agency_name = htmlspecialchars(strip_tags($_POST['agency_name']));
			}
			else{
				$agency_name = "";
			}

			$last_register_num = Model::factory('expertises')->get_last_register_num_from_expertise_array();
			if(count($last_register_num)==0){
				$register_num = 1;
			}
			else{
				$register_num = (int)$last_register_num+1;
			}

			$region_id = htmlspecialchars(strip_tags($_POST['region_id']), ENT_QUOTES);
			$sub_agency_id = htmlspecialchars(strip_tags($_POST['sub_agency_id']), ENT_QUOTES);
			$extra_sub_agency = htmlspecialchars(strip_tags($_POST['extra_sub_agency']), ENT_QUOTES);
			$region_id = htmlspecialchars(strip_tags($_POST['region_id']), ENT_QUOTES);
			$sub_agency_id = htmlspecialchars(strip_tags($_POST['sub_agency_id']), ENT_QUOTES);
			$agency_executor_fio = htmlspecialchars(strip_tags($_POST['agency_executor_fio']), ENT_QUOTES);
			$agency_executor_position = htmlspecialchars(strip_tags($_POST['agency_executor_position']), ENT_QUOTES);
			$agency_executor_rank = htmlspecialchars(strip_tags($_POST['agency_executor_rank']), ENT_QUOTES);
			$repeat_region_id = (int)$_POST['repeat_region_id'];

			$access_ids = $user_id . ", 2";
			$result = Model::factory('expertises')->add_expertise_array($spec_ids, $register_num, $access_ids, $date_of_reg, $plot, $deal_category_id, $deal_num, $article_num, $status_id, $status_extra_id, $agency_id, $region_id, $sub_agency_id, $agency_executor_fio, $agency_executor_position, $agency_executor_rank, $repeat_region_id, $user_id);
			if($result){
				$arr_id = $result[0]['last_insert_id'];
				$this->request->redirect('/expertises/send?id=' . $arr_id);
			}
		}

		$this->template->content = $view;
	}

	public function action_send(){
		if(isset($_GET['id'])&&(int)$_GET['id']!=0){
			$user_id = Auth::instance()->get_user()->id;
			$result = Model::factory('departments')->get_user_info_by_id($user_id);
			$position_id = $result['position_id'];
			if($position_id != 9): $this->request->redirect('/expertises'); endif;
			$this->template->title = "Отправить на поручение проект экспертизы | РГКП 'Центр судебных экспертиз'";
			$this->template->menu = View::factory('admin/menu');
			$arr_id = $_GET['id'];
			$arr = Model::factory('expertises')->get_arr_by_arr_id($arr_id);
			$register_num = $arr[0]['expertise_register_num'];
			$spec_ids = $arr[0]['specialty_ids'];
			$ciphers_and_names = Model::factory('expertises')->get_ciphers_and_names_by_ids($spec_ids);
			$this->template->content = View::factory('expertises/send', array(
				'arr' => $arr[0],
				'spec_ids' => $spec_ids,
				'ciphers_and_names' => $ciphers_and_names,
			));
		}
		else{
			$this->request->redirect('/expertises');
		}
	}

	public function action_order(){
		if(isset($_GET['id'])&&(int)$_GET['id']!=0){
			$arr_id = $_GET['id'];
			$user_id = Auth::instance()->get_user()->id;
			$user_info = Model::factory('departments')->get_user_info_by_id($user_id);
			$position_id = $user_info['position_id'];
			$department_id = $user_info['department_id'];
			$arr = Model::factory('expertises')->get_arr_by_arr_id($arr_id);
			if(count($arr)==0){
				$this->request->redirect('/expertises');
			}
			if($position_id == 9){
				$this->request->redirect('/expertises');
			}
			$access_ids = explode(',', $arr[0]['access_ids']);
			if(in_array($user_id, $access_ids)){
				$this->template->title = "Поручение проекта экспертизы | РГКП 'Центр судебных экспертиз'";
				$this->template->menu = View::factory('admin/menu');

				$spec_ids = $arr[0]['specialty_ids'];
				if($position_id == 7)
					$zams = Model::factory('departments')->get_zams();
				else
					$zams = array();
				if($position_id == 8){
					$result = Model::factory('expertises')->get_spec_ids_from_task($user_id, $arr_id);
					$spec_ids = $result['spec_ids'];
					$zavs = Model::factory('expertises')->get_zavs_for_zam($department_id);
					$sent_2 = Model::factory('expertises')->get_counter_tasks($user_id, $arr_id);
				}
				else{
					$zavs = array();
					$sent_2 = 0;
				}
				if($position_id == 25 OR $position_id == 31){
					$result = Model::factory('expertises')->get_spec_ids_from_task($user_id, $arr_id);
					$spec_ids = $result['spec_ids'];
					$experts = Model::factory('expertises')->get_experts_for_zav($department_id, $user_id, $spec_ids);
					$sent_3 = Model::factory('expertises')->get_counter_tasks($user_id, $arr_id);
				}
				else{
					$experts = array();
					$sent_3 = 0;
				}
				$register_num = $arr[0]['expertise_register_num'];
				
				$ciphers_and_names = Model::factory('expertises')->get_ciphers_and_names_by_ids($spec_ids);
				$counter = count($ciphers_and_names);
				$tasks = Model::factory('expertises')->get_expertise_tasks($arr_id);

				/*foreach ($tasks as $task) :
				echo "<pre>";
				print_r($task);
				echo "</pre>";
				endforeach;

				die();*/

				$this->template->content = View::factory('expertises/order', array(
					'arr' => $arr[0],
					'sent' => $arr[0]['sent'],
					'sent_2' => $sent_2,
					'sent_3' => $sent_3,
					'ciphers_and_names' => $ciphers_and_names,
					'zams' => $zams,
					'zavs' => $zavs,
					'experts' => $experts,
					'position_id' => $position_id,
					'counter' => $counter,
					'spec_ids' => $spec_ids,
					'tasks' => $tasks,
				));
				
			}
			else{
				$this->request->redirect('/expertises');
			}
			
		}
		else{
			$this->request->redirect('/expertises');
		}
	}

	public function action_viewexp(){
		if(isset($_GET['id'])&&(int)$_GET['id']!=0){
			$arr_id = $_GET['id'];
			$user_id = Auth::instance()->get_user()->id;
			$user_info = Model::factory('departments')->get_user_info_by_id($user_id);
			$position_id = $user_info['position_id'];
			$department_id = $user_info['department_id'];
			$arr = Model::factory('expertises')->get_arr_by_arr_id($arr_id);
			if(count($arr)==0){
				$this->request->redirect('/expertises');
			}
			if($position_id == 9){
				$this->request->redirect('/expertises');
			}
			$access_ids = explode(',', $arr[0]['access_ids']);
			//if(in_array($user_id, $access_ids)){
				$this->template->title = "Обзор проекта экспертизы | РГКП 'Центр судебных экспертиз'";
				$this->template->menu = View::factory('admin/menu');

				$spec_ids = $arr[0]['specialty_ids'];
				if($position_id == 7)
					$zams = Model::factory('departments')->get_zams();
				else
					$zams = array();
				if($position_id == 8){
					$result = Model::factory('expertises')->get_spec_ids_from_task($user_id, $arr_id);
					$spec_ids = $result['spec_ids'];
					$zavs = Model::factory('expertises')->get_zavs_for_zam($department_id);
					$sent_2 = Model::factory('expertises')->get_counter_tasks($user_id, $arr_id);
				}
				else{
					$zavs = array();
					$sent_2 = 0;
				}
				if($position_id == 25 OR $position_id == 31){
					$result = Model::factory('expertises')->get_spec_ids_from_task($user_id, $arr_id);
					$spec_ids = $result['spec_ids'];
					$experts = Model::factory('expertises')->get_experts_for_zav($department_id, $user_id, $spec_ids);
					$sent_3 = Model::factory('expertises')->get_counter_tasks($user_id, $arr_id);
				}
				else{
					$experts = array();
					$sent_3 = 0;
				}
				$register_num = $arr[0]['expertise_register_num'];
				
				$ciphers_and_names = Model::factory('expertises')->get_ciphers_and_names_by_ids($spec_ids);
				$counter = count($ciphers_and_names);
				$tasks = Model::factory('expertises')->get_expertise_tasks($arr_id);
				$this->template->content = View::factory('expertises/viewexp', array(
					'arr' => $arr[0],
					'sent' => $arr[0]['sent'],
					'sent_2' => $sent_2,
					'sent_3' => $sent_3,
					'ciphers_and_names' => $ciphers_and_names,
					'zams' => $zams,
					'zavs' => $zavs,
					'experts' => $experts,
					'position_id' => $position_id,
					'counter' => $counter,
					'spec_ids' => $spec_ids,
					'tasks' => $tasks,
				));
				
			//}
			//else{
			//	$this->request->redirect('/expertises');
			//}
			
		}
		else{
			$this->request->redirect('/expertises');
		}
	}

	public function action_execute(){
		if(isset($_GET['id'])&&(int)$_GET['id']!=0){
			$this->template->title = "Выполнение проекта экспертизы | РГКП 'Центр судебных экспертиз'";
			$this->template->menu = View::factory('admin/menu');
			$user_id = Auth::instance()->get_user()->id;
			$user_position = Model::factory('expertises')->check_for_addons($user_id);
			$exp_id = (int)htmlspecialchars(strip_tags($_GET['id'], ENT_QUOTES));

			$is_filled = Model::factory('expertises')->is_exp_filled($exp_id);
			$if_exists = Model::factory('expertises')->if_exp_exists($exp_id);
			if($is_filled[0]['filled']==1 OR $if_exists[0]['counter']==0){
				$this->request->redirect('/expertises');
			}
			$expertise = Model::factory('expertises')->get_expertise_by_id($exp_id);
			$selected_reasons = array();
			if($expertise[0]['pause_reason_id_5']!=0){
				$selected_reasons[] = $expertise[0]['pause_reason_id_5'];
				$selected_reasons[] = $expertise[0]['pause_reason_id_4'];
				$selected_reasons[] = $expertise[0]['pause_reason_id_3'];
				$selected_reasons[] = $expertise[0]['pause_reason_id_2'];
				$selected_reasons[] = $expertise[0]['pause_reason_id'];
			}
			elseif($expertise[0]['pause_reason_id_4']!=0){
				$selected_reasons[] = $expertise[0]['pause_reason_id_4'];
				$selected_reasons[] = $expertise[0]['pause_reason_id_3'];
				$selected_reasons[] = $expertise[0]['pause_reason_id_2'];
				$selected_reasons[] = $expertise[0]['pause_reason_id'];
			}
			elseif($expertise[0]['pause_reason_id_3']!=0){
				$selected_reasons[] = $expertise[0]['pause_reason_id_3'];
				$selected_reasons[] = $expertise[0]['pause_reason_id_2'];
				$selected_reasons[] = $expertise[0]['pause_reason_id'];
			}
			elseif($expertise[0]['pause_reason_id_2']!=0){
				$selected_reasons[] = $expertise[0]['pause_reason_id_2'];
				$selected_reasons[] = $expertise[0]['pause_reason_id'];
			}
			elseif($expertise[0]['pause_reason_id']!=0){
				$selected_reasons[] = $expertise[0]['pause_reason_id'];
			}

			$experts = Model::factory('expertises')->get_experts_and_their_busyness($expertise[0]['specialty_id']);
			
			$status = false;
			$specialty_ids = Model::factory('expertises')->if_expert($user_id);
			//foreach($specialty_ids as $var):
			//	if($expertise[0]['specialty_id'] == $var['specialty_id'])
			//		$status = true;
			//endforeach;
			if($expertise[0]['expert_id']==$user_id){
				$status = true;
			}
			if(!$status){
				$this->request->redirect('/expertises');
			}
			$complexities = Model::factory('expertises')->get_complexities();
			$pause_reasons = Model::factory('expertises')->get_pause_reasons();
			$expertise_results = Model::factory('expertises')->get_expertise_results();
			$exp_document_types = Model::factory('expertises')->get_exp_document_type();
			$this->template->content = View::factory('expertises/execute', array(
				'expertise' => $expertise[0],
				'experts' => $experts,
				'user_position' => $user_position[0],
				'complexities' => $complexities,
				'pause_reasons' => $pause_reasons,
				'results' => $expertise_results,
				'exp_document_types' => $exp_document_types,
				'expert_id' => $expertise[0]['expert_id'],
				'selected_reasons' => $selected_reasons,
			));
			if(isset($_POST['save_before_btn'])){
				$complexity_id = (int)htmlspecialchars(strip_tags($_POST['complexity_id']), ENT_QUOTES);
				$questions = (int)htmlspecialchars(strip_tags($_POST['questions']), ENT_QUOTES);
				$objects = (int)htmlspecialchars(strip_tags($_POST['objects']), ENT_QUOTES);
				$deadline = date('Y-m-d', strtotime(htmlspecialchars(strip_tags($_POST['deadline']), ENT_QUOTES)));
				$pause_date = date('Y-m-d', strtotime(htmlspecialchars(strip_tags($_POST['pause_date']), ENT_QUOTES)));
				if(isset($_POST['pause_reason_id'])&&(int)$_POST['pause_reason_id']!=0){
					$pause_reason_id = $_POST['pause_reason_id'];
					$pause_reason_basis = $_POST['pause_reason_basis'];
					$renewal_date = date('Y-m-d', strtotime($_POST['renewal_date']));
				}
				else{
					$pause_reason_id = 0;
					$pause_reason_basis = 0;
					$renewal_date = "0000-00-00";
				}
				if(isset($_POST['pause_reason_id_2'])&&(int)$_POST['pause_reason_id_2']!=0){
					$pause_reason_id_2 = $_POST['pause_reason_id_2'];
					$pause_reason_basis_2 = $_POST['pause_reason_basis_2'];
					$renewal_date_2 = date('Y-m-d', strtotime($_POST['renewal_date_2']));
				}
				else{
					$pause_reason_id_2 = 0;
					$pause_reason_basis_2 = 0;
					$renewal_date_2 = "0000-00-00";
				}
				if(isset($_POST['pause_reason_id_3'])&&(int)$_POST['pause_reason_id_3']!=0){
					$pause_reason_id_3 = $_POST['pause_reason_id_3'];
					$pause_reason_basis_3 = $_POST['pause_reason_basis_3'];
					$renewal_date_3 = date('Y-m-d', strtotime($_POST['renewal_date_3']));
				}
				else{
					$pause_reason_id_3 = 0;
					$pause_reason_basis_3 = 0;
					$renewal_date_3 = "0000-00-00";
				}
				if(isset($_POST['pause_reason_id_4'])&&(int)$_POST['pause_reason_id_4']!=0){
					$pause_reason_id_4 = $_POST['pause_reason_id_4'];
					$pause_reason_basis_4 = $_POST['pause_reason_basis_4'];
					$renewal_date_4 = date('Y-m-d', strtotime($_POST['renewal_date_4']));
				}
				else{
					$pause_reason_id_4 = 0;
					$pause_reason_basis_4 = 0;
					$renewal_date_4 = "0000-00-00";
				}
				if(isset($_POST['pause_reason_id_5'])&&(int)$_POST['pause_reason_id_5']!=0){
					$pause_reason_id_5 = $_POST['pause_reason_id_5'];
					$pause_reason_basis_5 = $_POST['pause_reason_basis_5'];
					$renewal_date_5 = date('Y-m-d', strtotime($_POST['renewal_date_5']));
				}
				else{
					$pause_reason_id_5 = 0;
					$pause_reason_basis_5 = 0;
					$renewal_date_5 = "0000-00-00";
				}

				if(isset($_POST['renewal_outcome_num'])&&(int)$_POST['renewal_outcome_num']!=''){
					$renewal_outcome_num = $_POST['renewal_outcome_num'];
				}
				else{
					$renewal_outcome_num = 0;
				}
				if(isset($_POST['renewal_income_num'])&&(int)$_POST['renewal_income_num']!=''){
					$renewal_income_num = $_POST['renewal_income_num'];
				}
				else{
					$renewal_income_num = 0;
				}

				$result = Model::factory('expertises')->update_expertise_before($exp_id, $user_id, $complexity_id, $questions, $objects, $deadline, $pause_date, $pause_reason_id, $pause_reason_id_2, $pause_reason_id_3, $pause_reason_id_4, $pause_reason_id_5, $pause_reason_basis, $pause_reason_basis_2, $pause_reason_basis_3, $pause_reason_basis_4, $pause_reason_basis_5, $renewal_date, $renewal_date_2, $renewal_date_3, $renewal_date_4, $renewal_date_5, $renewal_outcome_num, $renewal_income_num);
				if($result){
					$this->request->redirect('/expertises/execute?id=' . $exp_id);
				}

			}
			if(isset($_POST['save_exp_btn'])){
				echo "<pre>";
				var_dump($_POST);
				echo "</pre>";
				$complexity_id = (int)htmlspecialchars(strip_tags($_POST['complexity_id']), ENT_QUOTES);
				$questions = (int)htmlspecialchars(strip_tags($_POST['questions']), ENT_QUOTES);
				$objects = (int)htmlspecialchars(strip_tags($_POST['objects']), ENT_QUOTES);
				$deadline = date('Y-m-d', strtotime(htmlspecialchars(strip_tags($_POST['deadline']), ENT_QUOTES)));
				$pause_date = date('Y-m-d', strtotime(htmlspecialchars(strip_tags($_POST['pause_date']), ENT_QUOTES)));
				$renewal_income_id = (int)htmlspecialchars(strip_tags($_POST['renewal_income_num']), ENT_QUOTES);
				$renewal_outcome_id = (int)htmlspecialchars(strip_tags($_POST['renewal_outcome_num']), ENT_QUOTES);
				$study_end_date = date('Y-m-d', strtotime(htmlspecialchars(strip_tags($_POST['study_end_date']), ENT_QUOTES)));
				$days_difference = (int)htmlspecialchars(strip_tags($_POST['days_difference']), ENT_QUOTES);
				$days_sum = (int)htmlspecialchars(strip_tags($_POST['days_sum']), ENT_QUOTES);
				$result_id = (int)$_POST['result_id'];
				$cat_conclusions = (int)htmlspecialchars(strip_tags($_POST['cat_conclusions']), ENT_QUOTES);
				$prob_conclusions = (int)htmlspecialchars(strip_tags($_POST['prob_conclusions']), ENT_QUOTES);
				$nvp = (int)htmlspecialchars(strip_tags($_POST['nvp']), ENT_QUOTES);
				$reason_for_return = htmlspecialchars(strip_tags($_POST['reason_for_return']), ENT_QUOTES);
				$reason_sndz = htmlspecialchars(strip_tags($_POST['reason_sndz']), ENT_QUOTES);
				$expertise_price = htmlspecialchars(strip_tags($_POST['expertise_price']), ENT_QUOTES);
				
				if(isset($_POST['annotation'])){
					$annotation = htmlspecialchars(strip_tags($_POST['annotation']), ENT_QUOTES);
				}
				else{
					$annotation = "";
				}

				if((int)htmlspecialchars(strip_tags($_POST['pause_reason_id']), ENT_QUOTES)!=0){
					$pause_reason_id = (int)htmlspecialchars(strip_tags($_POST['pause_reason_id']), ENT_QUOTES);
					$pause_reason_basis =  (int)htmlspecialchars(strip_tags($_POST['pause_reason_basis']), ENT_QUOTES);
					$renewal_date = date('Y-m-d', strtotime(htmlspecialchars(strip_tags($_POST['renewal_date']), ENT_QUOTES)));
					if(isset($_POST['pause_reason_id_2'])&&((int)htmlspecialchars(strip_tags($_POST['pause_reason_id_2']), ENT_QUOTES)!=0)){
						$pause_reason_id_2 = (int)htmlspecialchars(strip_tags($_POST['pause_reason_id_2']), ENT_QUOTES);
						$pause_reason_basis_2 =  (int)htmlspecialchars(strip_tags($_POST['pause_reason_basis_2']), ENT_QUOTES);
						$renewal_date_2 = date('Y-m-d', strtotime(htmlspecialchars(strip_tags($_POST['renewal_date_2']), ENT_QUOTES)));
						if(isset($_POST['pause_reason_id_3'])&&((int)htmlspecialchars(strip_tags($_POST['pause_reason_id_3']), ENT_QUOTES)!=0)){
							$pause_reason_id_3 = (int)htmlspecialchars(strip_tags($_POST['pause_reason_id_3']), ENT_QUOTES);
							$pause_reason_basis_3 =  (int)htmlspecialchars(strip_tags($_POST['pause_reason_basis_3']), ENT_QUOTES);
							$renewal_date_3 = date('Y-m-d', strtotime(htmlspecialchars(strip_tags($_POST['renewal_date_3']), ENT_QUOTES)));
							if(isset($_POST['pause_reason_id_4'])&&((int)htmlspecialchars(strip_tags($_POST['pause_reason_id_4']), ENT_QUOTES)!=0)){
								$pause_reason_id_4 = (int)htmlspecialchars(strip_tags($_POST['pause_reason_id_4']), ENT_QUOTES);
								$renewal_date_4 = date('Y-m-d', strtotime(htmlspecialchars(strip_tags($_POST['renewal_date_4']), ENT_QUOTES)));
								$pause_reason_basis_4 =  (int)htmlspecialchars(strip_tags($_POST['pause_reason_basis_4']), ENT_QUOTES);
								if(isset($_POST['pause_reason_id_5'])&&((int)htmlspecialchars(strip_tags($_POST['pause_reason_id_5']), ENT_QUOTES)!=0)){
									$pause_reason_id_5 = (int)htmlspecialchars(strip_tags($_POST['pause_reason_id_5']), ENT_QUOTES);
									$pause_reason_basis_5 =  (int)htmlspecialchars(strip_tags($_POST['pause_reason_basis_5']), ENT_QUOTES);
									$renewal_date_5 = date('Y-m-d', strtotime(htmlspecialchars(strip_tags($_POST['renewal_date_5']), ENT_QUOTES)));
								}
								else{
									$pause_reason_id_5 = 0;
									$pause_reason_basis_5 = 0;
									$renewal_date_5 = "0000-00-00";
								}
							}
							else{
								$pause_reason_id_4 = 0;
								$pause_reason_id_5 = 0;
								$pause_reason_basis_4 = 0;
								$pause_reason_basis_5 = 0;
								$renewal_date_4 = "0000-00-00";
								$renewal_date_5 = "0000-00-00";
							}
						}
						else{
							$pause_reason_id_3 = 0;
							$pause_reason_id_4 = 0;
							$pause_reason_id_5 = 0;
							$pause_reason_basis_3 = 0;
							$pause_reason_basis_4 = 0;
							$pause_reason_basis_5 = 0;
							$renewal_date_3 = "0000-00-00";
							$renewal_date_4 = "0000-00-00";
							$renewal_date_5 = "0000-00-00";
						}
					}
					else{
						$pause_reason_id_2 = 0;
						$pause_reason_id_3 = 0;
						$pause_reason_id_4 = 0;
						$pause_reason_id_5 = 0;
						$renewal_date_2 = "0000-00-00";
						$renewal_date_3 = "0000-00-00";
						$renewal_date_4 = "0000-00-00";
						$renewal_date_5 = "0000-00-00";
						$pause_reason_basis_2 = 0;
						$pause_reason_basis_3 = 0;
						$pause_reason_basis_4 = 0;
						$pause_reason_basis_5 = 0;
					}
				}
				else{
					$pause_reason_id = 0;
					$pause_reason_id_2 = 0;
					$pause_reason_id_3 = 0;
					$pause_reason_id_4 = 0;
					$pause_reason_id_5 = 0;
					$pause_reason_basis = 0;
					$pause_reason_basis_2 = 0;
					$pause_reason_basis_3 = 0;
					$pause_reason_basis_4 = 0;
					$pause_reason_basis_5 = 0;
					$renewal_date = "0000-00-00";
					$renewal_date_2 = "0000-00-00";
					$renewal_date_3 = "0000-00-00";
					$renewal_date_4 = "0000-00-00";
					$renewal_date_5 = "0000-00-00";
				}
				if(isset($_POST['document_type'])){
					$document_type = (int)htmlspecialchars(strip_tags($_POST['document_type']), ENT_QUOTES);
					$payment_note_num = htmlspecialchars(strip_tags($_POST['payment_note_num']), ENT_QUOTES);
					$payment_note_date = date('Y-m-d', (strtotime(htmlspecialchars($_POST['payment_note_date']), ENT_QUOTES)));
				}
				else{
					$document_type = 0;
					$payment_note_num = "";
					$payment_note_date = "0000-00-00";
				}
				$result = Model::factory('expertises')->update_expertise($exp_id, $user_id, $complexity_id, $questions, $objects, $deadline, $pause_date, $renewal_income_id, $renewal_outcome_id, $pause_reason_id, $pause_reason_id_2, $pause_reason_id_3, $pause_reason_id_4, $pause_reason_id_5, $pause_reason_basis, $pause_reason_basis_2, $pause_reason_basis_3, $pause_reason_basis_4, $pause_reason_basis_5, $renewal_date, $renewal_date_2, $renewal_date_3, $renewal_date_4, $renewal_date_5, $study_end_date, $days_difference, $days_sum, $result_id, $cat_conclusions, $prob_conclusions, $nvp, $reason_for_return, $reason_sndz, $document_type, $expertise_price, $payment_note_num, $payment_note_date, $annotation);
				echo "<pre>";
				if($result){
					$this->request->redirect('/expertises/view?id=' . $exp_id);
				}
				else{
					var_dump("ERROR");
				}
				echo "</pre>";
			}
		}
		else{
			$this->request->redirect('/expertises');
		}
	}

	public function action_view(){
		if(isset($_GET['id'])){
			$exp_id = (int)htmlspecialchars(strip_tags($_GET['id'], ENT_QUOTES));
			if($exp_id!=0){

				$this->template->title = "Обзор экспертизы | РГКП 'Центр судебных экспертиз'";
				$this->template->menu = View::factory('admin/menu');

				$user_id = Auth::instance()->get_user()->id;
				$expertise = Model::factory('expertises')->get_expertise_for_view_by_id($exp_id);

				$specialty_ids = Model::factory('expertises')->if_expert($user_id);
				$if_exists = Model::factory('expertises')->if_exp_exists($exp_id);
				$status = false;
				if($user_id != 1 && $user_id != 2){
					$status = true;
				}
				foreach($specialty_ids as $cipher):
					if($cipher==$expertise[0]['specialty_id']){
						$status = true;
						break;
					}
				endforeach;
				$this->template->content = View::factory('expertises/view', array(
					'expertise' => $expertise[0],
				));
			}
			else{
				$this->request->redirect('/expertises');
			}
			
		}
		
	}

	protected function _save_image($image, $type){
		$type = (int)$type;
		if(!Upload::valid($image)OR(!Upload::not_empty($image)OR(!Upload::type($image, array('jpg', 'jpeg', 'png', 'gif'))))){
			return FALSE;
		}

		if($type==0){
			$directory = DOCROOT.'img/';
		}
		if($type==1){
			$directory = DOCROOT.'img/slider/';
		}
		if($type==2){
			$directory = DOCROOT.'img/advantages/';
		}
		if($type==3){
			$directory = DOCROOT.'img/gallery/';
		}
		$format = ".jpg";
		if(Upload::type($image, array('png'))){
			$format = ".png";
		}
		if($file = Upload::save($image, NULL, $directory)){
			$filename = strtolower(Text::random('alnum', 20)) . $format;
			Image::factory($file)->save($directory.$filename);
			unlink($file);
			return $filename;
		}
		return FALSE;
	}

}