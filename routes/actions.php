<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Actions extends Controller{
	public function action_send(){
		if(isset($_POST['phone'])&&isset($_POST['text'])&&isset($_POST['name'])&&isset($_POST['email'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$from = "postmaster@new_url";
			$subject = "Новая заявка c сайта";
			$name = strip_tags($_POST['name']);
			$text = strip_tags($_POST['text']);
			$phone = strip_tags($_POST['phone']);
			$email = strip_tags($_POST['email']);
			$message = "Имя: $name<br>E-mail: $phone<br>Телефон: $phone<br>Cообщение: $text";
			$emails = array('bazarov.daniyar@gmail.com', 'bazarov.daniyar@inbox.ru');
			foreach($emails as $to){
				Email::send($to, $from, $subject, $message, $html = true);
				sleep(3);
			}
		}
	}

	public function action_sendtoreceiver(){
		if(isset($_POST['income_id'])){
			$income_id = $_POST['income_id'];
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$from = "postmaster@$new_url";
			$subject = "Новый входящий документ";
			$result = Model::factory('income')->get_income_by_id($income_id);
			$lastname = $result[0]['lastname']; 
			$name = $result[0]['firstname'];
			$middlename = $result[0]['middlename'];
			$to = $result[0]['email'];
			$author_id = $result[0]['author_id'];
			$result = Model::factory('departments')->get_user_by_id($author_id);
			$author_lastname = $result[0]['lastname'];
			$author_firstname = $result[0]['firstname'];
			$author_middlename = $result[0]['middlename'];
			$site_url = URL::site(NULL, "https");
			$message = 
				"Здравствуйте, $lastname $name $middlename<br>
				Для Вас имеется новый входящий документ.<br>
				Для того, чтобы просмотреть перейдите по <a href='" . $site_url. "correspondence/registerincome?confirm=$income_id'>ссылке</a>.<br>
				C Уважением, сотрудник канцелярии $author_lastname $author_firstname $author_middlename<br>
				$site_url
				";
			if(Email::send($to, $from, $subject, $message, $html = true)){
				echo "SENT";
			}
			Model::factory('income')->income_is_sent($income_id);

		}
	}

	public function action_sendtoexecutor(){
		if(isset($_POST['income_id'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$income_id = $_POST['income_id'];
			$executor_id = $_POST['executor_id'];
			$task_date = strtotime($_POST['task_date']);
			$task_date = date('d.m.Y', $task_date);
			$task_message = $_POST['task_message'];
			$from = "postmaster@$new_url";
			$subject = "Новый входящий документ";
			$result = Model::factory('departments')->get_fio_by_user_id($executor_id); 
			$name = $result[0]['firstname'];
			$middlename = $result[0]['middlename'];
			$to = $result[0]['email'];
			#$to = "bazarov.daniyar@inbox.ru";
			/*echo "Исполнитель: " . $name . " " . $middlename . '<br>';
			echo "E-mail: " . $to . "<br>";
			echo 'Дата выполнения:' . $task_date . "<br>";
			echo 'Сообщение: ' . $task_message .'<BR>';
			echo "Задание: " . $income_id;
			*/
			$site_url = URL::site(NULL, "https");
			$message = 
				"Здравствуйте, $name $middlename<br>
				Для Вас имеется новая карточка заданиря.<br>
				Срок выполнения: $task_date <BR>
				Сообщение:<br>
				<p style='border:1px solid #ccc; padding:10px;'>
					$task_message
				</p>
				Для того, чтобы просмотреть перейдите по <a href='" . $site_url . "correspondence/income?id=$income_id'>ссылке</a>.<br>
				C Уважением, Базаров Данияр Муратович<br>$site_url
				";
			if(Email::send($to, $from, $subject, $message, $html = true)){
				echo "SENT";
			}
			
		}
	}

	public function action_addmoreexpertisereason(){
		if(isset($_POST['add'])){
			$id = $_POST['id'];
			$pause_reasons_arr = $_POST['pause_reasons_arr'];
			$str = implode(', ', $pause_reasons_arr);
			$pause_reasons = Model::factory('expertises')->get_pause_reasons2($str);
			?>
				<div class="form-group">
					<label>Причина приостоновления:</label>
					<select name="pause_reason_id_<?=$id;?>" id="pause_reason_id_<?=$id;?>"  class="form-control" form="execute_form">
						<option value="0">Выберите</option>
						<? foreach($pause_reasons as $var): ?>
							<option value="<?=$var['id'];?>"><?=$var['name'];?></option>
						<? endforeach; ?>
					</select>
				</div>
				<div class="form-group pause_reason_basis_wrapper_<?=$id;?>">
						<label>Основание:</label>
						<input type="text" class="form-control pause_reason_basises" id="pause_reason_basis_<?=$id;?>">
						<input type="hidden" name="pause_reason_basis_<?=$id;?>">
						<div class="pause_reason_hints" id="pause_reason_hint_<?=$id;?>"></div>
					</div>
				<div class="form-group" id="renewal_date_wrapper_<?=$id;?>">
					<label>Дата возобновления:</label>
					<input type="text" class="form-control" id="renewal_date_<?=$id;?>" name="renewal_date_<?=$id;?>" placeholder="Выберите дату" readonly>
				</div>
				<div class="form-group">
					<button type="submit" name="save_before_btn" class="btn btn-info">Сохранить</button>
				</div>
			<?
		}
	}

	public function action_getexpertisebyid(){
		if(isset($_POST['expertise_id'])){
			$id = $_POST['expertise_id'];
			$result = Model::factory('expertises')->get_arr_by_arr_id($id);
			$arr = explode(',', $result[0]['specialty_ids']);
			$x = json_encode($arr);
			echo $x;
		}
	}

	public function action_sendtorecieverexp(){
		if(isset($_POST['user_id'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$user_id = $_POST['user_id'];
			$result = Model::factory('departments')->get_user_by_id($user_id);
			$lastname = $result[0]['lastname'];
			$firstname = $result[0]['firstname'];
			$middlename = $result[0]['middlename'];
			$to = $result[0]['email'];
			$expertise_id = $_POST['expertise_id'];
			$from = "postmaster@$new_url";
			$subject = "Проект экспертизы на производство";
			$author = Model::factory('departments')->get_user_by_id($_POST['author_id']);
			$author_lastname = $author[0]['lastname'];
			$author_firstname = $author[0]['firstname'];
			$author_middlename = $author[0]['middlename'];
			$site_url = URL::site(NULL, "https");
			$message = 
				"Здравствуйте, $firstname $middlename<br>
				Для того, чтобы просмотреть перейдите по <a href='" . $site_url . "expertises/order?id=$expertise_id'>ссылке</a>.<br>
				C Уважением, $author_lastname $author_firstname $author_middlename<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
			if(Email::send($to, $from, $subject, $message, $html = true)){
				echo "SENT";
			}
		}
	}

	public function action_sendtoexecutorexp(){
		if(isset($_POST['user_id'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$user_id = $_POST['user_id'];
			$result = Model::factory('departments')->get_user_by_id($user_id);
			$lastname = $result[0]['lastname'];
			$firstname = $result[0]['firstname'];
			$middlename = $result[0]['middlename'];
			$to = $result[0]['email'];
			$expertise_id = $_POST['expertise_id'];
			$from = "postmaster@$new_url";
			$subject = "Проект экспертизы на производство";
			$author = Model::factory('departments')->get_user_by_id($_POST['author_id']);
			$author_lastname = $author[0]['lastname'];
			$author_firstname = $author[0]['firstname'];
			$author_middlename = $author[0]['middlename'];
			$site_url = URL::site(NULL, "https");
			$message = 
				"Здравствуйте, $firstname $middlename<br>
				Для того, чтобы просмотреть перейдите по <a href='" . $site_url . "expertises/execute?id=$expertise_id'>ссылке</a>.<br>
				C Уважением, $author_lastname $author_firstname $author_middlename<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
			if(Email::send($to, $from, $subject, $message, $html = true)){
				echo "SENT";
			}
		}
	}

	public function action_incomesort(){
		if(isset($_POST['go'])){
			$register_num_filter = $_POST['register_num_filter'];
			$date_of_reg_filter = $_POST['date_of_reg_filter'];
			$from_filter = $_POST['from_filter'];
			$fio_executor_filter = $_POST['fio_executor_filter'];
			$result = Model::factory('income')->get_income_by_filter($register_num_filter, $date_of_reg_filter, $from_filter, $fio_executor_filter);
			$counter = count($result);
			if($counter>0){
			?>
			<tbody>
			<tr>
				<th>Регистрационный номер</th>
				<th>Дата регистрации</th>
				<th>Корреспондент</th>
				<th>ФИО исполнителя</th>
				<th style="text-align:center;">Просмотр</th>
			</tr>
			<? foreach($result as $income): ?>
			<tr>
				<td><?=$income['register_num'];?></td>
				<td><?=date('d.m.Y', strtotime($income['date_of_reg']));?></td>
				<td><?=$income['name'];?></td>
				<td><?=$income['fio_executor'];?></td>
				<td style="text-align:center;"><a href="/correspondence/income?id=<?=$income['id'];?>"><span class="glyphicon glyphicon-eye-open"></span></a></td>
			<tr>
			<? endforeach; ?>
		</tbody>
		<?}
			else{?>
				<tbody>
					<tr>
						<th>Регистрационный номер</th>
						<th>Дата регистрации</th>
						<th>Корреспондент</th>
						<th>ФИО исполнителя</th>
						<th>Краткое содержание</th>
						<th style="text-align:center;">Просмотр</th>
					</tr>
					<tr>
						<td colspan="6" style="text-align:center;">Ничего не найдено</td>
					<tr>
				</tbody>
			<?
			}
		}
	}

	public function action_outcomesort(){
		if(isset($_POST['go'])){
			$register_num_filter = $_POST['register_num_filter'];
			$date_of_reg_filter = $_POST['date_of_reg_filter'];
			$from_filter = $_POST['from_filter'];
			$fio_executor_filter = $_POST['fio_executor_filter'];
			$result = Model::factory('outcome')->get_outcome_by_filter($register_num_filter, $date_of_reg_filter, $from_filter, $fio_executor_filter);
			$counter = count($result);
			if($counter>0){
			?>
			<tbody>
			<tr>
				<th style="text-align:center;">Регистрационный номер</th>
				<th style="text-align:center;">Дата регистрации</th>
				<th style="text-align:center;">Корреспондент</th>
				<th style="text-align:center;">Исполнитель</th>
				<th style="text-align:center;">Просмотр</th>
			</tr>
			<? foreach($result as $outcome): ?>
			<tr>
				<td style="text-align:center;"><?=$outcome['register_num'];?></td>
				<td style="text-align:center;"><?=date('d.m.Y', strtotime($outcome['date_of_reg']));?></td>
				<td style="text-align:center;"><?=$outcome['name'];?></td>
				<td style="text-align:center;"><?=$outcome['lastname'] . " " . substr($outcome['firstname'], 0, 2) . "." . substr($outcome['middlename'], 0, 2) . ".";?></td>
				<td style="text-align:center;"><a href="/correspondence/outcome?view=<?=$outcome['id'];?>"><span class="glyphicon glyphicon-eye-open"></span></a></td>
			<tr>
			<? endforeach; ?>
		</tbody>
		<?}
			else{?>
				<tbody>
					<tr>
						<th style="text-align:center;">Регистрационный номер</th>
						<th style="text-align:center;">Дата регистрации</th>
						<th style="text-align:center;">Корреспондент</th>
						<th style="text-align:center;">Исполнитель</th>
						<th style="text-align:center;">Просмотр</th>
					</tr>
					<tr>
						<td colspan="5" style="text-align:center;">Ничего не найдено</td>
					<tr>
				</tbody>
			<?
			}
		}
	}

	public function action_brdsort(){
		if(isset($_POST['go'])){
			$register_num_filter = htmlspecialchars(strip_tags($_POST['register_num_filter']), ENT_QUOTES);
			$date_of_reg_filter = htmlspecialchars(strip_tags($_POST['date_of_reg_filter']), ENT_QUOTES);
			$author_filter = htmlspecialchars(strip_tags($_POST['author_filter']), ENT_QUOTES);
			$subject_filter = htmlspecialchars(strip_tags($_POST['subject_filter']), ENT_QUOTES);
			$result = Model::factory('brd')->brd_filter($register_num_filter, $date_of_reg_filter, $author_filter, $subject_filter);
			$counter = count($result);
			if($counter>0){
				?>
				<table class="table">
					<tr>
						<th style="text-align: center;">№</th>
						<th style="text-align: center;">Регистрационный номер</th>
						<th style="text-align: center;">Дата регистрации</th>
						<th style="text-align: center;">Автор</th>
						<th style="text-align: center;">Тема документа</th>
						<th style="text-align: center;">Папка</th>
						<th style="text-align: center;">Просмотр</th>
					</tr>
				<?
				$x = 1;
				foreach($result as $var): ?>
					<tr>
						<td style="text-align: center;"><?=$x; ?></td>
						<td style="text-align: center;"><?=$var['register_num'];?></td>
						<td style="text-align: center;"><?=date('d.m.Y', strtotime($var['date_of_reg']));?></td>
						<td style="text-align: center;"><?=$var['lastname'];?> <?=substr($var['firstname'], 0, 2);?>.<?=substr($var['middlename'], 0, 2);?>.</td>
						<td style="text-align: center;"><?=$var['subject'];?></td>
						<td style="text-align: center;"><?=$var['folder_name'];?></td>
						<td style="text-align: center;"><a target="_blank" href="/brd/viewdoc?id=<?=$var['id'];?>"><span class="glyphicon glyphicon-eye-open"></span></a></td>
					</tr>
					<? $x++; ?>
				<? endforeach; ?>
					</table>
				<?
			}
			else{?>
				<table class="table">
					<tr>
						<th style="text-align: center;">№</th>
						<th style="text-align: center;">Регистрационный номер</th>
						<th style="text-align: center;">Дата регистрации</th>
						<th style="text-align: center;">Автор</th>
						<th style="text-align: center;">Тема документа</th>
						<th style="text-align: center;">Папка</th>
						<th style="text-align: center;">Просмотр</th>
					</tr>
					<tr>
						<td colspan="7" style="text-align: center;">Ничего не найдено</td>
					</tr>
				</table>
			<?
			}
		}
	}

	public function action_ordsort(){
		if(isset($_POST['go'])){
			$register_num_filter = htmlspecialchars(strip_tags($_POST['register_num_filter']), ENT_QUOTES);
			$date_of_reg_filter = htmlspecialchars(strip_tags($_POST['date_of_reg_filter']), ENT_QUOTES);
			$author_filter = htmlspecialchars(strip_tags($_POST['author_filter']), ENT_QUOTES);
			$subject_filter = htmlspecialchars(strip_tags($_POST['subject_filter']), ENT_QUOTES);
			$result = Model::factory('ord')->ord_filter($register_num_filter, $date_of_reg_filter, $author_filter, $subject_filter);
			$counter = count($result);
			if($counter>0){
				?>
				<table class="table">
					<tr>
						<th style="text-align: center;">№</th>
						<th style="text-align: center;">Регистрационный номер</th>
						<th style="text-align: center;">Дата регистрации</th>
						<th style="text-align: center;">Автор</th>
						<th style="text-align: center;">Тема документа</th>
						<th style="text-align: center;">Папка</th>
						<th style="text-align: center;">Просмотр</th>
					</tr>
				<?
				$x = 1;
				foreach($result as $var): ?>
					<tr>
						<td style="text-align: center;"><?=$x; ?></td>
						<td style="text-align: center;"><?=$var['register_num'];?></td>
						<td style="text-align: center;"><?=date('d.m.Y', strtotime($var['date_of_reg']));?></td>
						<td style="text-align: center;"><?=$var['lastname'];?> <?=substr($var['firstname'], 0, 2);?>.<?=substr($var['middlename'], 0, 2);?>.</td>
						<td style="text-align: center;"><?=$var['subject'];?></td>
						<td style="text-align: center;"><?=$var['folder_name'];?></td>
						<td style="text-align: center;"><a target="_blank" href="/ord/viewdoc?id=<?=$var['id'];?>"><span class="glyphicon glyphicon-eye-open"></span></a></td>
					</tr>
					<? $x++; ?>
				<? endforeach; ?>
					</table>
				<?
			}
			else{?>
				<table class="table">
					<tr>
						<th style="text-align: center;">№</th>
						<th style="text-align: center;">Регистрационный номер</th>
						<th style="text-align: center;">Дата регистрации</th>
						<th style="text-align: center;">Автор</th>
						<th style="text-align: center;">Тема документа</th>
						<th style="text-align: center;">Папка</th>
						<th style="text-align: center;">Просмотр</th>
					</tr>
					<tr>
						<td colspan="7" style="text-align: center;">Ничего не найдено</td>
					</tr>
				</table>
			<?
			}
		}
	}

	public function action_getsubagencies(){
		if(isset($_POST)){
			$sub_agencies = Model::factory('expertises')->get_sub_agencies_by_region_id($_POST['region_id']);
			?><option disabled selected>Выберите</option><?
			foreach($sub_agencies as $var): ?>
				<option value="<?=$var['id'];?>"><?=$var['name'];?></option>
			<?endforeach;
		}
	}

	public function action_getbrdtabrecievers(){
		if(isset($_POST['go'])){
			$user_id = $_POST['user_id'];
			$result = Model::factory('brd')->get_brd_recievers($user_id);
			?>
				<div class="form-group">
					<label>Получатель:</label>
					<select id="reciever_id" name="receiver_ids[]" class="form-control" multiple="multiple">
						<? foreach($result as $var): ?>
							<option value=<?=$var['id'];?>><?=$var['lastname'];?> <?=substr($var['firstname'], 0, 2); ?>.<?=substr($var['middlename'], 0,2); ?>. (<?=$var['department_name']; ?> - <?=$var['position_name'];?>)</option>
						<? endforeach; ?>
					</select>
				</div>
			<?
		}
	}

	public function action_getordtabrecievers(){
		if(isset($_POST['go'])){
			$user_id = $_POST['user_id'];
			$result = Model::factory('ord')->get_ord_recievers($user_id);
			?>
				<div class="form-group">
					<label>Получатель:</label>
					<select id="reciever_id" class="form-control">
						<? foreach($result as $var): ?>
							<option value=<?=$var['id'];?>><?=$var['lastname'];?> <?=substr($var['firstname'], 0, 2); ?>.<?=substr($var['middlename'], 0,2); ?>. (<?=$var['department_name']; ?> - <?=$var['position_name'];?>)</option>
						<? endforeach; ?>
					</select>
				</div>
			<?
		}
	}

	public function action_brdsendtab(){
		if(isset($_POST['reciever_id'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$brd_id = (int)htmlspecialchars(strip_tags($_POST['brd_id']), ENT_QUOTES);
			$reciever_id = $_POST['reciever_id'];
			$result = Model::factory('departments')->get_user_by_id($reciever_id);
			$reciever_email = $result[0]['email'];
			$reciever = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
			$result = Model::factory('departments')->get_user_by_id($_POST['sender_id']);
			$sender = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
			$subject = "Новая закладка БРД";
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";
				$message = 
				"Здравствуйте, $reciever<br>
				Для того, чтобы просмотреть перейдите по <a href='" . $site_url . "brd/viewdoc?id=$brd_id'>ссылке</a>.<br>
				C Уважением, $sender<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
			if(Email::send($reciever_email, $from, $subject, $message, $html = true)){
				echo "SENT";
			}
		}
	}

	public function action_ordsendtab(){
		if(isset($_POST['reciever_id'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$ord_id = (int)htmlspecialchars(strip_tags($_POST['ord_id']), ENT_QUOTES);
			$reciever_id = $_POST['reciever_id'];
			$result = Model::factory('departments')->get_user_by_id($reciever_id);
			$reciever_email = $result[0]['email'];
			$reciever = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
			$result = Model::factory('departments')->get_user_by_id($_POST['sender_id']);
			$sender = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
			$subject = "Новая закладка БРД";
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";
				$message = 
				"Здравствуйте, $reciever<br>
				Для того, чтобы просмотреть перейдите по <a href='" . $site_url . "ord/viewdoc?id=$ord_id'>ссылке</a>.<br>
				C Уважением, $sender<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
			if(Email::send($reciever_email, $from, $subject, $message, $html = true)){
				echo "SENT";
			}
		}
	}

	public function action_getbrdnomcode(){
		if(isset($_POST['brd_nom_id'])){
			$brd_nom_id = (int)$_POST['brd_nom_id'];
			$result = Model::factory('brd')->get_brd_nom_code_by_id($brd_nom_id);
			echo $result[0]['code'];
		}
	}

	public function action_getordnomcode(){
		if(isset($_POST['ord_nom_id'])){
			$ord_nom_id = (int)$_POST['ord_nom_id'];
			$result = Model::factory('ord')->get_ord_nom_code_by_id($ord_nom_id);
			echo $result[0]['code'];
		}
	}

	public function action_getusers(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			$user_id = $_POST['user_id'];
			$ids = $_POST['ids'];
			$ids = implode(', ', json_decode($ids));
			if($ids==''){
				$result = Model::factory('brd')->get_users_for_matching2($req, $user_id);
			}
			else{
				$result = Model::factory('brd')->get_users_for_matching($req, $ids, $user_id);
			}
			?>
			<ul class="unstyled users_for_select">
			<?
			foreach($result as $var):
				echo "<li class='asd' id='user_id_" . $var['id'] . "'>" . $var['lastname'] . " ".  substr($var['firstname'], 0, 2) . "." . substr($var['middlename'], 0, 2) . ". " . " (" . $var['position_name'] . ")</li>";
			endforeach;
			?>
			</ul>
			<?
		}
	}

	public function action_getusers_zams(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			$user_id = $_POST['user_id'];
			$department_id = $_POST['department_id'];
			$ids = $_POST['ids'];
			$ids = implode(', ', json_decode($ids));
			if($ids==''){
				$result = Model::factory('brd')->get_users_for_dir_matching2($req);
			}
			else{
				$result = Model::factory('brd')->get_users_for_dir_matching($req, $ids);
			}
			if(count($result)>0){
				?>
				<ul class="unstyled users_for_select">
				<?
				foreach($result as $var):
					echo "<li class='asd' id='user_id_" . $var['id'] . "'>" . $var['lastname'] . " ".  substr($var['firstname'], 0, 2) . "." . substr($var['middlename'], 0, 2) . ". " . " (" . $var['position_name'] . ")</li>";
				endforeach;
				?>
				</ul>
			<?
			}
			else{
				echo "<p> По запросу <b>\"".  $req  ."\"</b> ничего не найдено</p>";
			}
		}
	}

	public function action_getusers_zavs(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			$user_id = $_POST['user_id'];
			$department_id = $_POST['department_id'];
			$ids = $_POST['ids'];
			$ids = implode(', ', json_decode($ids));
			if($ids==''){
				$result = Model::factory('brd')->get_users_for_matching4($req, $user_id, $department_id);
			}
			else{
				$result = Model::factory('brd')->get_users_for_matching3($req, $ids, $user_id, $department_id);
			}
			if(count($result)>0){
				?>
				<ul class="unstyled users_for_select">
				<?
				foreach($result as $var):
					echo "<li class='asd' id='user_id_" . $var['id'] . "'>" . $var['lastname'] . " ".  substr($var['firstname'], 0, 2) . "." . substr($var['middlename'], 0, 2) . ". " . " (" . $var['position_name'] . ")</li>";
				endforeach;
				?>
				</ul>
			<?
			}
			else{
				echo "<p> По запросу <b>\"".  $req  ."\"</b> ничего не найдено</p>";
			}
		}
	}

	public function action_getusers_forzavs(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			$user_id = $_POST['user_id'];
			$department_id = $_POST['department_id'];
			$ids = $_POST['ids'];
			$ids = implode(', ', json_decode($ids));
			if($ids==''){
				$result = Model::factory('brd')->get_users_for_matching6($req, $user_id, $department_id);
			}
			else{
				$result = Model::factory('brd')->get_users_for_matching5($req, $ids, $user_id, $department_id);
			}
			if(count($result)>0){
				?>
				<ul class="unstyled users_for_select">
				<?
				foreach($result as $var):
					echo "<li class='asd' id='user_id_" . $var['id'] . "'>" . $var['lastname'] . " ".  substr($var['firstname'], 0, 2) . "." . substr($var['middlename'], 0, 2) . ". " . " (" . $var['position_name'] . ")</li>";
				endforeach;
				?>
				</ul>
			<?
			}
			else{
				echo "<p> По запросу <b>\"".  $req  ."\"</b> ничего не найдено</p>";
			}
		}
	}

	public function action_getusers2(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			
			$ids = $_POST['ids'];
			$ids = implode(', ', json_decode($ids));
			if($ids==''){
				$result = Model::factory('ord')->get_users_for_matching2($req);
			}
			else{
				$result = Model::factory('ord')->get_users_for_matching($req, $ids);
			}
			?>
			<ul class="unstyled users_for_select">
			<?
			foreach($result as $var):
				echo "<li class='asd' id='user_id_" . $var['id'] . "'>" . $var['lastname'] . " ".  substr($var['firstname'], 0, 2) . "." . substr($var['middlename'], 0, 2) . ". " . " (" . $var['position_name'] . ")</li>";
			endforeach;
			?>
			</ul>
			<?
		}
	}

	public function action_getuserinfo(){
		if(isset($_POST['user_id'])){
			$user_id = (int)$_POST['user_id'];

			$result = Model::factory('brd')->get_user_info($user_id);
			$arr = array();
			$arr['user_id'] = $result[0]['id'];
			$arr['lastname'] = $result[0]['lastname'];
			$arr['firstname'] = $result[0]['firstname'];
			$arr['middlename'] = $result[0]['middlename'];
			$arr['position_name'] = $result[0]['position_name'];
			$arr['department_name'] = $result[0]['department_name'];
			$arr['email'] = $result[0]['email'];
			echo json_encode($arr);
		}
	}

	public function action_getuserinfo2(){
		if(isset($_POST['user_id'])){
			$user_id = (int)$_POST['user_id'];

			$result = Model::factory('ord')->get_user_info($user_id);
			$arr = array();
			$arr['user_id'] = $result[0]['id'];
			$arr['lastname'] = $result[0]['lastname'];
			$arr['firstname'] = $result[0]['firstname'];
			$arr['middlename'] = $result[0]['middlename'];
			$arr['position_name'] = $result[0]['position_name'];
			$arr['department_name'] = $result[0]['department_name'];
			$arr['email'] = $result[0]['email'];
			echo json_encode($arr);
		}
	}

	public function action_setrecievers(){
		if(isset($_POST['brd_id'])){
			$brd_id = (int)$_POST['brd_id'];
			$receivers = json_decode($_POST['recievers']);
			$author_id = (int)$_POST['author_id'];
			$length = count($receivers);
			$date_created = date('Y-m-d', time());
			$statuses = array();
			$last_insert_id = 0;
			$dates_matching = array();
			for($x=0; $x<$length; $x++){
				$statuses[] = 0;
				$dates_matching[] = '00-00-00'; 
			}
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";
			$copy_id = Model::factory('brd')->get_last_id_by_id($brd_id);
			if(count($copy_id)>0){
				$result = Model::factory('brd')->add_to_matching($copy_id[0]['id'], implode(', ', $receivers), implode(', ', $statuses), $length, $date_created, implode(', ', $dates_matching), $author_id);
			}
			else{
				$result = Model::factory('brd')->add_to_matching($brd_id, implode(', ', $receivers), implode(', ', $statuses), $length, $date_created, implode(', ', $dates_matching), $author_id);
			}
			
			$res = Model::factory('brd')->set_matching_brd($brd_id);
			if($result){
				$last_insert_id = $result[0]['last_insert_id'];
				$receiver_id = $receivers[0];
				$result = Model::factory('departments')->get_user_by_id($receiver_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$sender = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$subject = "Новый документ на согласование";
				$message = 
				"Здравствуйте, $receiver<br>
				Для того, чтобы просмотреть перейдите по <a href='" . $site_url . "brdmatching/view?id=$last_insert_id'>ссылке</a>.<br>
				C Уважением, $sender<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					
				}
				echo $last_insert_id;
			}
		}
		if(isset($_POST['ord_id'])){
			$ord_id = (int)$_POST['ord_id'];
			$receivers = json_decode($_POST['recievers']);
			$author_id = (int)$_POST['author_id'];
			$length = count($receivers);
			$date_created = date('Y-m-d', time());
			$statuses = array();
			$dates_matching = array();
			for($x=0; $x<$length; $x++){
				$statuses[] = 0;
				$dates_matching[] = '00-00-00'; 
			}
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";
			$result = Model::factory('ord')->add_to_matching($ord_id, implode(', ', $receivers), implode(', ', $statuses), $length, $date_created, implode(', ', $dates_matching), $author_id);
			if($result){
				$last_insert_id = $result[0]['last_insert_id'];
				$receiver_id = $receivers[0];
				$result = Model::factory('departments')->get_user_by_id($receiver_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$sender = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$subject = "Новый документ на согласование";
				$message = 
				"Здравствуйте, $receiver<br>
				Для того, чтобы просмотреть перейдите по <a href='" . $site_url . "ordmatching/view?id=$last_insert_id'>ссылке</a>.<br>
				C Уважением, $sender<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					echo "SENT";
				}
			}
		}
	}



	public function action_brdagree(){
		if(isset($_POST['result'])){

			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$brd_matching_id = $_POST['brd_matching_id'];
			$result = Model::factory('brdmatching')->get_matching_for_update($brd_matching_id);
			$stage = (int)$result[0]['stage'];
			$brd_id = (int)$result[0]['brd_id'];
			$statuses = explode(', ', $result[0]['statuses']);
			$dates_matching = explode(', ', $result[0]['dates_matching']);
			$receivers = explode(', ', $result[0]['receivers']);
			$length = (int)$result[0]['length'];
			$statuses[$stage] = 1;
			$dates_matching[$stage] = date('Y-m-d', time());
			$author_id = $result[0]['author_id'];
			$statuses = implode(', ', $statuses);
			$dates_matching = implode(', ', $dates_matching);
			$stage++;
			
			if($stage==$length){
				$result = Model::factory('brdmatching')->update_brdmatching2($brd_matching_id, $statuses, $dates_matching, $stage, 1);
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$subject = "Ваш документ прошел согласование";
				$message = 
					"Здравствуйте, $receiver<br>
					Ваш документ успешно прошел согласование.<br>
					Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "brdmatching/view?id=$brd_matching_id'>ссылке</a>.<br>
					<b>РГКП «Центр Судебных Экспертиз»</b>
					";
				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					echo "SENT";
				}
			}
			else{
				$result = Model::factory('brdmatching')->update_brdmatching($brd_matching_id, $statuses, $dates_matching, $stage);
				$next_receiver_id = $receivers[$stage];
				$result = Model::factory('departments')->get_user_by_id($next_receiver_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$sender = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";	
				if($result){
					$subject = "Новый документ на согласование";
					$message = 
					"Здравствуйте, $receiver<br>
					Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "brdmatching/view?id=$brd_matching_id'>ссылке</a>.<br>
					C Уважением, $sender<br>
					<b>РГКП «Центр Судебных Экспертиз»</b>
					";
					if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
						echo "SENT";
					}
				}
			}
		}
	}

	public function action_expagree(){
		if(isset($_POST['result'])){

			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$exp_matching_id = $_POST['exp_matching_id'];
			$result = Model::factory('expertisesmatching')->get_matching_for_update($exp_matching_id);
			$stage = (int)$result[0]['stage'];
			$exp_id = (int)$result[0]['exp_id'];
			$statuses = explode(', ', $result[0]['statuses']);
			$dates_matching = explode(', ', $result[0]['dates_matching']);
			$receivers = explode(', ', $result[0]['receivers']);
			$length = (int)$result[0]['length'];
			$statuses[$stage] = 1;
			$dates_matching[$stage] = date('Y-m-d', time());
			$author_id = $result[0]['author_id'];
			$statuses = implode(', ', $statuses);
			$dates_matching = implode(', ', $dates_matching);
			$stage++;
			
			if($stage==$length){
				$result = Model::factory('expertisesmatching')->update_expmatching2($exp_matching_id, $statuses, $dates_matching, $stage, 1);
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$subject = "Ваш проект экспертизы прошел согласование";
				$message = 
					"Здравствуйте, $receiver<br>
					Ваш проект экспертизы успешно прошел согласование.<br>
					Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "expmatching/view?id=$exp_matching_id'>ссылке</a>.<br>
					<b>РГКП «Центр Судебных Экспертиз»</b>
					";
				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					echo "SENT";
				}
			}
			else{
				$result = Model::factory('expertisesmatching')->update_expmatching($exp_matching_id, $statuses, $dates_matching, $stage);
				$next_receiver_id = $receivers[$stage];
				$result = Model::factory('departments')->get_user_by_id($next_receiver_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$sender = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";	
				if($result){
					$subject = "Новый проект экспертизы на согласование";
					$message = 
					"Здравствуйте, $receiver<br>
					Для того, чтобы просмотреть проект экспертизы, перейдите по <a href='" . $site_url . "expmatching/view?id=$exp_matching_id'>ссылке</a>.<br>
					C Уважением, $sender<br>
					<b>РГКП «Центр Судебных Экспертиз»</b>
					";
					if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
						echo "SENT";
					}
				}
			}
		}
	}

	public function action_brdagree2(){
		if(isset($_POST['result'])){

			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";
			$note = htmlspecialchars(strip_tags($_POST['note']));

			$brd_matching_id = $_POST['brd_matching_id'];
			$result = Model::factory('brdmatching')->get_matching_for_update($brd_matching_id);
			$stage = (int)$result[0]['stage'];
			$brd_id = (int)$result[0]['brd_id'];
			$statuses = explode(', ', $result[0]['statuses']);
			$dates_matching = explode(', ', $result[0]['dates_matching']);
			$receivers = explode(', ', $result[0]['receivers']);
			$length = (int)$result[0]['length'];
			$statuses[$stage] = 2;
			$dates_matching[$stage] = date('Y-m-d', time());
			$author_id = $result[0]['author_id'];
			$statuses = implode(', ', $statuses);
			$dates_matching = implode(', ', $dates_matching);
			$supervisor_id = $receivers[$stage];
			$result = Model::factory('departments')->get_user_by_id($supervisor_id);
			$supervisor = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";

			$stage++;
			
			if($stage==$length){
				$result = Model::factory('brdmatching')->update_brdmatching2($brd_matching_id, $statuses, $dates_matching, $stage, 1);
				$result = Model::factory('brdmatching')->add_brd_notes($note, $supervisor_id, $brd_id, $brd_matching_id);
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$subject = "Ваш документ прошел согласование";
				$message = 
					"Здравствуйте, $receiver<br>
					Ваш документ успешно прошел согласование.<br>
					Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "brdmatching/view?id=$brd_matching_id'>ссылке</a>.<br>
					<b>РГКП «Центр Судебных Экспертиз»</b>
					";
				
				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					echo "SENT";
				}
			}
			else{
				$result = Model::factory('brdmatching')->update_brdmatching($brd_matching_id, $statuses, $dates_matching, $stage);
				$result = Model::factory('brdmatching')->add_brd_notes($note, $supervisor_id, $brd_id, $brd_matching_id);
				$next_receiver_id = $receivers[$stage];
				$result = Model::factory('departments')->get_user_by_id($next_receiver_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$sender = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";	
				$subject = "Новый документ на согласование";
				$message = 
					"Здравствуйте, $receiver<br>
					Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "brdmatching/view?id=$brd_matching_id'>ссылке</a>.<br>
					C Уважением, $sender<br>
					<b>РГКП «Центр Судебных Экспертиз»</b>
					";
				$result = Email::send($receiver_email, $from, $subject, $message, $html = true);
				if($result){
					echo "SENT";
				}				
			}
		}
	}

	public function action_expagree2(){
		if(isset($_POST['result'])){

			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";
			$note = htmlspecialchars(strip_tags($_POST['note']));

			$exp_matching_id = $_POST['exp_matching_id'];
			$result = Model::factory('expertisesmatching')->get_matching_for_update($exp_matching_id);
			$stage = (int)$result[0]['stage'];
			$exp_id = (int)$result[0]['exp_id'];
			$statuses = explode(', ', $result[0]['statuses']);
			$dates_matching = explode(', ', $result[0]['dates_matching']);
			$receivers = explode(', ', $result[0]['receivers']);
			$length = (int)$result[0]['length'];
			$statuses[$stage] = 2;
			$dates_matching[$stage] = date('Y-m-d', time());
			$author_id = $result[0]['author_id'];
			$statuses = implode(', ', $statuses);
			$dates_matching = implode(', ', $dates_matching);
			$supervisor_id = $receivers[$stage];
			$result = Model::factory('departments')->get_user_by_id($supervisor_id);
			$supervisor = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";

			$stage++;
			
			if($stage==$length){
				$result = Model::factory('expertisesmatching')->update_expmatching2($exp_matching_id, $statuses, $dates_matching, $stage, 1);
				$result = Model::factory('expertisesmatching')->add_exp_notes($note, $supervisor_id, $exp_id, $exp_matching_id);
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$subject = "Ваш проект экспертизы прошел согласование";
				$message = 
					"Здравствуйте, $receiver<br>
					Ваш документ успешно прошел согласование.<br>
					Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "expmatching/view?id=$exp_matching_id'>ссылке</a>.<br>
					<b>РГКП «Центр Судебных Экспертиз»</b>
					";
				
				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					echo "SENT";
				}
			}
			else{
				$result = Model::factory('expertisesmatching')->update_expmatching($exp_matching_id, $statuses, $dates_matching, $stage);
				$result = Model::factory('expertisesmatching')->add_exp_notes($note, $supervisor_id, $exp_id, $exp_matching_id);
				$next_receiver_id = $receivers[$stage];
				$result = Model::factory('departments')->get_user_by_id($next_receiver_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$sender = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";	
				$subject = "Новый проект экспертизы на согласование";
				$message = 
					"Здравствуйте, $receiver<br>
					Для того, чтобы просмотреть проект экспертизы, перейдите по <a href='" . $site_url . "expmatching/view?id=$exp_matching_id'>ссылке</a>.<br>
					C Уважением, $sender<br>
					<b>РГКП «Центр Судебных Экспертиз»</b>
					";
				$result = Email::send($receiver_email, $from, $subject, $message, $html = true);
				if($result){
					echo "SENT";
				}				
			}
		}
	}

	public function action_brddisagree(){
		if(isset($_POST['result'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$note = htmlspecialchars(strip_tags($_POST['note']));
			$brd_matching_id = $_POST['brd_matching_id'];
			$result = Model::factory('brdmatching')->get_matching_for_update($brd_matching_id);
			$stage = (int)$result[0]['stage'];
			$brd_id = (int)$result[0]['brd_id'];
			$author_id = (int)$result[0]['author_id'];
			$statuses = explode(', ', $result[0]['statuses']);
			$dates_matching = explode(', ', $result[0]['dates_matching']);
			$receivers = explode(', ', $result[0]['receivers']);
			$length = (int)$result[0]['length'];
			$statuses[$stage] = 3;
			$dates_matching[$stage] = date('Y-m-d', time());
			$statuses = implode(', ', $statuses);
			$dates_matching = implode(', ', $dates_matching);
			$supervisor_id = $receivers[$stage];
			$result = Model::factory('departments')->get_user_by_id($supervisor_id);
			$supervisor = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
			$result = Model::factory('brdmatching')->update_brdmatching2($brd_matching_id, $statuses, $dates_matching, $stage, 2);
			$res = Model::factory('brd')->set_matching_brd2($brd_id);
			if($result){
				$result = Model::factory('brdmatching')->add_brd_notes($note, $supervisor_id, $brd_id, $brd_matching_id);
			}
			if($result){
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$subject = "Ваш документ не прошел согласование";
				$message = 
				"Здравствуйте, $receiver<br>
				Ваш документ не прошел согласование.<br>
				Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "documents/viewdoc?id=$brd_id'>ссылке</a>.<br>
				$supervisor<br>
				<b>РГКП «Центр Судебных Экспертиз»
				";
				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					echo "SENT";
				}
			}


		}
	}

	public function action_expdisagree(){
		if(isset($_POST['result'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$note = htmlspecialchars(strip_tags($_POST['note']));
			$exp_matching_id = $_POST['exp_matching_id'];
			$result = Model::factory('expertisesmatching')->get_matching_for_update($exp_matching_id);
			$stage = (int)$result[0]['stage'];
			$exp_id = (int)$result[0]['exp_id'];
			$author_id = (int)$result[0]['author_id'];
			$statuses = explode(', ', $result[0]['statuses']);
			$dates_matching = explode(', ', $result[0]['dates_matching']);
			$receivers = explode(', ', $result[0]['receivers']);
			$length = (int)$result[0]['length'];
			$statuses[$stage] = 3;
			$dates_matching[$stage] = date('Y-m-d', time());
			$statuses = implode(', ', $statuses);
			$dates_matching = implode(', ', $dates_matching);
			$supervisor_id = $receivers[$stage];
			$result = Model::factory('departments')->get_user_by_id($supervisor_id);
			$supervisor = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
			$result = Model::factory('expertisesmatching')->update_expmatching2($exp_matching_id, $statuses, $dates_matching, $stage, 2);
			$res = Model::factory('expertisesmatching')->set_matching_exp2($exp_id, $author_id);
			if($result){
				$result = Model::factory('expertisesmatching')->add_exp_notes($note, $supervisor_id, $exp_id, $exp_matching_id);
			}
			if($result){
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$subject = "Ваш проект экспертизы не прошел согласование";
				$message = 
				"Здравствуйте, $receiver<br>
				Ваш проект экспертизы не прошел согласование.<br>
				Для того, чтобы просмотреть проект экспертизы, перейдите по <a href='" . $site_url . "expertises/view?id=$exp_id'>ссылке</a>.<br>
				$supervisor<br>
				<b>РГКП «Центр Судебных Экспертиз»
				";
				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					echo "SENT";
				}
			}


		}
	}

	public function action_createoutcome(){
		if(isset($_POST['brd_id'])){
			$brd_id = $_POST['brd_id'];
			$brd = Model::factory('brd')->get_last_brd($brd_id);
			$executor_id = $brd[0]['author_id'];
			$short = $brd[0]['text'];
			$result = Model::factory('outcome')->set_outcome_from_brd($executor_id, $short, $brd_id);
			$outcome_id = $result[0]['last_insert_id'];
			echo $outcome_id;
		}
	}

	public function action_searchincomeforoutcome(){
		if(isset($_POST['request'])){
			$request = $_POST['request'];
			$result = Model::factory('outcome')->search_income($request);
			$arr = array();
			$x = 0;
			foreach($result as $var):
				$corr_id = $var['id'];
				$corr_name = $var['name'];
				$register_num = $var['register_num'];
				$arr[$x] = array(
					'corr_id' => $corr_id,
					'corr_name' => $corr_name,
					'register_num' => $register_num,
				);
			$x++;
			endforeach;
			echo json_encode($arr,JSON_UNESCAPED_UNICODE);
		}
	}

	public function action_sendbrdforoutcome(){
		if(isset($_POST['outcome_id'])){
			$outcome_id = $_POST['outcome_id'];
			$lang = htmlspecialchars(strip_tags($_POST['lang']), ENT_QUOTES);
			$correspondent = htmlspecialchars(strip_tags($_POST['correspondent']), ENT_QUOTES);
			$income_deal_num = htmlspecialchars(strip_tags($_POST['income_deal_num']), ENT_QUOTES);
			$pages = htmlspecialchars(strip_tags($_POST['pages']), ENT_QUOTES);
			$how_sent = htmlspecialchars(strip_tags($_POST['how_sent']), ENT_QUOTES);
			$outcome = Model::factory('outcome')->get_outcome_by_id($outcome_id);
			$result = Model::factory('outcome')->set_outcome_from_brd2($lang, $correspondent, $pages, $how_sent, $outcome_id, $income_deal_num);
			if($result){
				echo "UPDATED";
				$url = URL::site(NULL, 'https');
				$url_len = strlen($url);
				$new_url = substr($url, 12, ($url_len-12)-1);
				$site_url = URL::site(NULL, "https");
				$from = "postmaster@$new_url";

				$res = Model::factory('departments')->get_user_by_id($outcome[0]['executor_id']);
				$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

				$res = Model::factory('departments')->get_user_by_id(20);
				$receiver_email = $res[0]['email'];
				$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

				$subject = "Новый исходящий документ на регистрацию";
				$message = 
					"Здравствуйте, $receiver<br>
					Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "correspondence/registeroutcome?id=$outcome_id'>ссылке</a>.<br>
					$sender<br>
					<b>РГКП «Центр Судебных Экспертиз»
					";

				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					$this->request->redirect('/brd');
				}
			}
		}
	}

	public function action_sendordforoutcome(){
		if(isset($_POST['outcome_id'])){
			$outcome_id = $_POST['outcome_id'];
			$lang = htmlspecialchars(strip_tags($_POST['lang']), ENT_QUOTES);
			$correspondent = htmlspecialchars(strip_tags($_POST['correspondent']), ENT_QUOTES);
			$income_deal_num = htmlspecialchars(strip_tags($_POST['income_deal_num']), ENT_QUOTES);
			$pages = htmlspecialchars(strip_tags($_POST['pages']), ENT_QUOTES);
			$how_sent = htmlspecialchars(strip_tags($_POST['how_sent']), ENT_QUOTES);
			$outcome = Model::factory('outcome')->get_outcome_by_id($outcome_id);
			$result = Model::factory('outcome')->set_outcome_from_ord2($lang, $correspondent, $pages, $how_sent, $outcome_id, $income_deal_num);
			if($result){
				echo "UPDATED";
				$url = URL::site(NULL, 'https');
				$url_len = strlen($url);
				$new_url = substr($url, 12, ($url_len-12)-1);
				$site_url = URL::site(NULL, "https");
				$from = "postmaster@$new_url";

				$res = Model::factory('departments')->get_user_by_id($outcome[0]['executor_id']);
				$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

				$res = Model::factory('departments')->get_user_by_id(20);
				$receiver_email = $res[0]['email'];
				$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

				$subject = "Новый исходящий документ на регистрацию";
				$message = 
					"Здравствуйте, $receiver<br>
					Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "correspondence/registeroutcome?id=$outcome_id'>ссылке</a>.<br>
					$sender<br>
					<b>РГКП «Центр Судебных Экспертиз»
					";

				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					$this->request->redirect('/ord');
				}
			}
		}
	}

	public function action_outcomecancel(){
		if(isset($_POST['reason'])){
			$reason = strip_tags($_POST['reason']);
			$outcome_id = $_POST['outcome_id'];
		}
	}

	public function action_searchoutcomeforincome(){
		if(isset($_POST['request'])){
			$request = htmlspecialchars(strip_tags($_POST['request']), ENT_QUOTES);
			$result = Model::factory('income')->search_outcome($request);
			$counter = count($result);
			if($counter>0){
				?>
					<ul class="unstyled search_list">
				<?
				foreach($result as $var):
					echo "<li>". $var['register_num'] . "</li>";
				endforeach; ?>
					</ul>
				<?
			}
			else{
				echo "<h5 style='text-align:center;'>Ничего не найдено</h5>";
			}
		}
	}

	public function action_seturltooutcome(){
		if(isset($_POST['outcome_reply'])){
			$outcome_reply = htmlspecialchars(strip_tags($_POST['outcome_reply']), ENT_QUOTES);
			$result = Model::factory('income')->get_outcome_id_by_register_num($outcome_reply);
			echo $result[0]['id'];
		}
	}

	public function action_brdmatchingsort(){
		if(isset($_POST['go'])){
			$register_num_filter = htmlspecialchars(strip_tags($_POST['register_num_filter']), ENT_QUOTES);
			$date_created_filter = htmlspecialchars(strip_tags($_POST['date_created_filter']), ENT_QUOTES);
			$author_filter = htmlspecialchars(strip_tags($_POST['author_filter']), ENT_QUOTES);
			$subject_filter = htmlspecialchars(strip_tags($_POST['subject_filter']), ENT_QUOTES);
			$status_filter = htmlspecialchars(strip_tags($_POST['status_filter']), ENT_QUOTES);
			if($status_filter=='2'){
				$status_filter = '';
			}
			$result = Model::factory('brdmatching')->get_brdmatching_by_filter($register_num_filter, $date_created_filter, $author_filter, $subject_filter, $status_filter);
			?>
				<table class="table">
					<tr>
						<th style="text-align: center;">№</th>
						<th style="text-align: center;">Регистрационный номер</th>
						<th style="text-align: center;">Дата создания</th>
						<th style="text-align: center;">Автор</th>
						<th style="text-align: center;">Тема документа</th>
						<th style="text-align: center;">Статус</th>
						<th style="text-align: center;">Просмотр</th>
					</tr>
					<? $x = 1; ?>
					<? foreach($result as $var): ?>
					<tr>
						<td style="text-align: center;"><?=$x; ?></td>
						<td style="text-align: center;"><?=$var['register_num'];?></td>
						<td style="text-align: center;"><?=date('d.m.Y', strtotime($var['date_created']));?></td>
						<td style="text-align: center;"><?=$var['lastname'];?></td>
						<td style="text-align: center;"><?=$var['subject'];?></td>
						<td style="text-align: center;"><?
							if($var['final_result']=='0'):?>
								<?="На согласовании"; ?>
							<? else: ?>
								<?="Согласован"; ?>
							<? endif; ?>		
						</td>
						<td style="text-align: center;"><a href="/brdmatching/view?id=<?=$var['id'];?>"><span class="glyphicon glyphicon-eye-open"></span></a></td>
					</tr>
					<? $x++; ?>
					<? endforeach; ?>
					
				</table>
			<?
		}
	}


	public function action_asd(){
		if(isset($_POST['brd_matching_id'])){
			$matching_id = (int)$_POST['brd_matching_id'];
			$result = Model::factory('brdmatching')->get_matching_by_id($matching_id);
			$receivers = $result[0]['receivers'];
			$statuses = $result[0]['statuses'];
			$length = (int)$result[0]['length'];
			$x = 0;
			$receivers = explode(',', $receivers);
			$statuses = explode(',', $statuses);
			foreach($statuses as $status):
				if($status==2):
					$result = Model::factory('brdmatching')->get_note($matching_id, $receivers[$x]);
					$lastname = $result[0]['lastname'];
					$firstname = substr($result[0]['firstname'], 0, 2);
					$middlename = substr($result[0]['middlename'], 0, 2);
					$note = $result[0]['note'];
					?>
						<li>
							<strong>
								<?=$lastname; ?> <?=$firstname;?>.<?=$middlename;?>. - согласен с замечаниями
							</strong>
							<div class="note_message">
								<strong>Замечания: </strong><br><?=$note; ?>
							</div>
						</li>
					<?
				elseif($status==1):
					$result = Model::factory('departments')->get_user_by_id($receivers[$x]);
					$lastname = $result[0]['lastname'];
					$firstname = substr($result[0]['firstname'], 0, 2);
					$middlename = substr($result[0]['middlename'], 0, 2);
					?>
						<li>
							<strong>
								<?=$lastname; ?> <?=$firstname;?>.<?=$middlename;?>. - согласен
							</strong>
						</li>
					<?
				elseif($status==0):
					$result = Model::factory('departments')->get_user_by_id($receivers[$x]);
					$lastname = $result[0]['lastname'];
					$firstname = substr($result[0]['firstname'], 0, 2);
					$middlename = substr($result[0]['middlename'], 0, 2);
					?>
						<li>
							<strong>
								<?=$lastname; ?> <?=$firstname;?>.<?=$middlename;?>. - ожидает
							</strong>
						</li>
					<?	
				elseif($status==3):
					$result = Model::factory('brdmatching')->get_note($matching_id, $receivers[$x]);
					$lastname = $result[0]['lastname'];
					$firstname = substr($result[0]['firstname'], 0, 2);
					$middlename = substr($result[0]['middlename'], 0, 2);
					$note = $result[0]['note'];
					?>
						<li>
							<strong>
								<?=$lastname; ?> <?=$firstname;?>.<?=$middlename;?>. - не согласен
							</strong>
							<div class="note_message">
								<strong>Замечания: </strong><br><?=$note; ?>
							</div>
						</li>
					<?					
				endif;
				$x++;
			endforeach;
		}
	}

	public function action_asd2(){
		if(isset($_POST['exp_matching_id'])){
			$matching_id = (int)$_POST['exp_matching_id'];
			$result = Model::factory('expertisesmatching')->get_matching_by_id($matching_id);
			$receivers = $result[0]['receivers'];
			$statuses = $result[0]['statuses'];
			$length = (int)$result[0]['length'];
			$x = 0;
			$receivers = explode(',', $receivers);
			$statuses = explode(',', $statuses);
			foreach($statuses as $status):
				if($status==2):
					$result = Model::factory('expertisesmatching')->get_note($matching_id, $receivers[$x]);
					$lastname = $result[0]['lastname'];
					$firstname = substr($result[0]['firstname'], 0, 2);
					$middlename = substr($result[0]['middlename'], 0, 2);
					$note = $result[0]['note'];
					?>
						<li>
							<strong>
								<?=$lastname; ?> <?=$firstname;?>.<?=$middlename;?>. - согласен с замечаниями
							</strong>
							<div class="note_message">
								<strong>Замечания: </strong><br><?=$note; ?>
							</div>
						</li>
					<?
				elseif($status==1):
					$result = Model::factory('departments')->get_user_by_id($receivers[$x]);
					$lastname = $result[0]['lastname'];
					$firstname = substr($result[0]['firstname'], 0, 2);
					$middlename = substr($result[0]['middlename'], 0, 2);
					?>
						<li>
							<strong>
								<?=$lastname; ?> <?=$firstname;?>.<?=$middlename;?>. - согласен
							</strong>
						</li>
					<?
				elseif($status==0):
					$result = Model::factory('departments')->get_user_by_id($receivers[$x]);
					$lastname = $result[0]['lastname'];
					$firstname = substr($result[0]['firstname'], 0, 2);
					$middlename = substr($result[0]['middlename'], 0, 2);
					?>
						<li>
							<strong>
								<?=$lastname; ?> <?=$firstname;?>.<?=$middlename;?>. - ожидает
							</strong>
						</li>
					<?								
				endif;
				$x++;
			endforeach;
		}
	}

	public function action_getlastcontrol(){
		if(isset($_POST['go'])){
			$register_num = Model::factory('control')->get_last_control_register_num();
			echo (int)$register_num;
		}
	}

	public function action_addcontrol(){
		if(isset($_POST['go'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$basis = (int)$_POST['basis'];
			if($basis!=''){
				Model::factory('income')->income_is_confirmed($basis);
			}
			$author_id = $_POST['author_id'];
			$msg_time_ids = json_decode($_POST['msg_time_ids']);
			
			$res = Model::factory('departments')->get_user_by_id($author_id);
			$author = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
			$register_num = Model::factory('control')->get_last_control_register_num();
			
			foreach($msg_time_ids as $var):
				$register_num = (int)$register_num + 1;
				$result = Model::factory('control')->add_control(
					$register_num,
					date('Y-m-d', strtotime($var->date_deadline)),
					$author_id,
					htmlspecialchars(strip_tags($var->text), ENT_QUOTES),
					$basis,
					$var->receiver_id,
					$var->time
				);
				$last_insert_id = $result[0]['last_insert_id'];	
				$res = Model::factory('departments')->get_user_by_id($var->receiver_id);
				$executor = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
				$executor_email = $res[0]['email'];
				$subject = "Новая карточка задания на исполнение";
				$message = 
					"Здравствуйте, $executor<br>
					Для того, чтобы ознакомиться с заданием, перейдите по <a href='" . $site_url . "control/view?id=$last_insert_id'>ссылке</a>.<br>
					$author<br>
					<b>РГКП «Центр Судебных Экспертиз»
					";

				Email::send($executor_email, $from, $subject, $message, $html = true);
			endforeach;
			
		}
	}

	public function action_addcontrol2(){
		if(isset($_POST['go'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";


			$basis = (int)$_POST['basis'];
			$author_id = $_POST['author_id'];
			$res = Model::factory('departments')->get_user_by_id($author_id);
			$author = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
			/*$register_num = Model::factory('control')->get_register_num_by_id($_POST['copy_id']);
			$register_num = $register_num[0]['register_num'];*/
			$i = 1;
			$copy_id = (int)$_POST['copy_id'];
			$register_num = $_POST['register_num'];
            $step = (int)$_POST['step'] + 1;
			$msg_time_ids = json_decode($_POST['msg_time_ids']);
			
			
			foreach($msg_time_ids as $var):
				$reg = $register_num . "_" . $i;
				$result = Model::factory('control')->add_control2(
				        $reg,
				        date('Y-m-d', strtotime($var->date_deadline)),
                        $author_id,
                        $var->text,
                        $basis,
                        $var->receiver_id,
                        $copy_id,
                        $step,
                        $var->time
                );
				$last_insert_id = $result[0]['last_insert_id'];	
				$res = Model::factory('departments')->get_user_by_id($var->receiver_id);
				$executor = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
				$executor_email = $res[0]['email'];
				$subject = "Новая карточка задания на исполнение";
				$message = 
					"Здравствуйте, $executor<br>
					Для того, чтобы ознакомиться с заданием, перейдите по <a href='" . $site_url . "control/view?id=$last_insert_id'>ссылке</a>.<br>
					$author<br>
					<b>РГКП «Центр Судебных Экспертиз»
					";
				Email::send($executor_email, $from, $subject, $message, $html = true);
				echo $reg . '<br>';
				$i++;
			endforeach;
		}
	}

	public function action_searchcontrolfordoc(){
		if(isset($_POST['req'])){
			$user_id = (int)Auth::instance()->get_user()->id;
			$req = (int)htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			$result = Model::factory('control')->get_control_like($req, $user_id);
			$counter = count($result);
			if($counter>0){
				?><ul class="unstyled controls" style="list-style: none;"><?
					foreach($result as $var):
						?><li id="control_id_<?=$var['id'];?>"><?=$var['register_num'];?></li><?
					endforeach;
				?></ul>
				<?
			}
			else{
				echo "Ничего не найдено";
			}
		}
	}

	public function action_setcontrolfrombrdmatching(){
		if(isset($_POST['control_id'])&&isset($_POST['brd_matching_id'])){
			$control_id = $_POST['control_id'];
			$brd_matching_id = $_POST['brd_matching_id'];
			$result = Model::factory('control')->set_document_id_for_control($control_id, $brd_matching_id);
			if($result){
				echo "DONE";
			}
		}
	}

	public function action_getusersfortab(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			$user_id = $_POST['user_id'];
			$tab_ids = $_POST['tab_ids'];
			$tab_ids = implode(', ', json_decode($tab_ids));
			$result = Model::factory('departments')->get_users_for_tab($req, $user_id, $tab_ids);
			$counter = count($result);
			if($counter>0){
			?>
				<ul class="unstyled users_for_tab" style="list-style: none;">
			<?
				foreach($result as $var):
				?>
					<li id="user_id_<?=$var['id'];?>"><?=$var['lastname'];?> <?=substr($var['firstname'], 0, 2); ?>.<?=substr($var['middlename'], 0, 2); ?>.</li>
				<?
				endforeach;
			}
		}
	}

	public function action_sendtabtoreceivers(){
		if(isset($_POST['user_id'])&&isset($_POST['tab_ids'])&&isset($_POST['current_url'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$tab_ids = $_POST['tab_ids'];
			$bm_id = $_POST['brd_matching_id'];
			$current_url = $_POST['current_url'];
			$user_id = (int)$_POST['user_id'];

			$access_ids = implode(',', $tab_ids);
			$result = Model::factory('brdmatching')->get_access_ids($bm_id);
			$old_access_ids = $result[0]['access_ids'];
			if(!empty($old_access_ids)){
				$access_ids = $old_access_ids . ", " . $access_ids;
			}
			$result = Model::factory('brdmatching')->set_access_to_bm($bm_id, $access_ids);

			
			

			$res = Model::factory('departments')->get_user_by_id($user_id);
			$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
			
			foreach($tab_ids as $id):
				$res = Model::factory('departments')->get_user_by_id($id);
				$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
				$receiver_email = $res[0]['email'];
				$subject = "Новая закладка";
				$message = 
					"Здравствуйте, $receiver<br>
					Для того, чтобы просмотреть, перейдите по <a href='" . $current_url . "'>ссылке</a>.<br>
					$sender<br>
					<b>РГКП «Центр Судебных Экспертиз»
					";
				Email::send($receiver_email, $from, $subject, $message, $html = true);
			endforeach;
		}
	}

	public function action_createoutcomefrombm(){
		if(isset($_POST['brd_matching_id'])){
			$brd_matching_id = (int)htmlspecialchars(strip_tags($_POST['brd_matching_id']), ENT_QUOTES);
			$executor_id = (int)$_POST['executor_id'];
			$result = Model::factory('brdmatching')->get_matching_by_id($brd_matching_id);
			$author_id = $result[0]['author_id'];
			$document_type_id = $result[0]['document_type_id'];
			$register_num = $result[0]['register_num'];
			$num_code = substr($register_num, 0, strpos($register_num, '/'));
			$short = htmlspecialchars($result[0]['text'], ENT_QUOTES);
			$result = Model::factory('control')->get_basis_from_control_by_bm($brd_matching_id, $executor_id);
			$counter = count($result);
			if($counter>0){
				$income_id = $result[0]['basis'];
				$result = Model::factory('control')->get_income_reg_and_corr($income_id);
				$register_num = $result[0]['register_num'];
				$correspondent_id = $result[0]['correspondent_id'];
				$result = Model::factory('outcometemp')->create_outcome_with_income($author_id, $brd_matching_id, $document_type_id, $short, $num_code, $register_num, $correspondent_id);
				$outcome_id = $result[0]['last_insert_id'];
			}
			else{
				$result = Model::factory('outcometemp')->create_outcome($author_id, $brd_matching_id, $document_type_id, $short, $num_code);
				$outcome_id = $result[0]['last_insert_id'];
			}
			echo $outcome_id;
		}
	}

	public function action_searchcorrepondence(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			$result = Model::factory('correspondence')->get_correspondence_like($req);
			$counter = count($result);
			if($counter>0){
				foreach($result as $var):
				?>
					<ul class="unstyled search_list_corr">
						<li id="corr_id_<?=$var['id'];?>"><?=$var['name'];?></li>				
					</ul>
				<?
				endforeach;
			}
			else{?>
				<button style="margin:20px 0 0 20px;" class="btn btn-success" id="add_corr_btn_from_modal">Добавить</button>
			<?}
		}
	}

	public function action_registeroutcome(){
		if(isset($_POST)){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";


			$outcome_id = (int)$_POST['outcome_id'];
			$lang = (int)$_POST['lang'];
			$correspondent_id = (int)$_POST['correspondent_id'];
			$income_deal_num = $_POST['income_deal_num'];
			$pages = (int)$_POST['pages'];
			$bm_id = $_POST['bm_id'];
			$result = Model::factory('outcometemp')->update_outcometemp($outcome_id, $lang, $correspondent_id, $income_deal_num, $pages);

			$res = Model::factory('departments')->get_user_by_id((int)$_POST['author_id']);
			$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
			$subject = "Новый исходящий документ на регистрацию";

			if($result){
				$users = Model::factory('outcome')->get_users_for_outcome();
				foreach($users as $user):
					$access_ids = "" . $user['user_id'];
					$qwe = Model::factory('brdmatching')->get_access_ids($bm_id);
					$old_access_ids = $qwe[0]['access_ids'];
					if(!empty($old_access_ids)){
						$access_ids = $old_access_ids . ", " . $access_ids;
					}
					$qwe = Model::factory('brdmatching')->set_access_to_bm($bm_id, $access_ids);
					$res = Model::factory('departments')->get_user_by_id($user['user_id']);
					$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
					$receiver_email = $res[0]['email'];
					echo $receiver_email;
					$message = 
						"Здравствуйте, $receiver<br>
						Для того, чтобы просмотреть, перейдите по <a href='" . $site_url . "correspondence/confirmoutcome?id=$outcome_id'>ссылке</a>.<br>
						$sender<br>
						<b>РГКП «Центр Судебных Экспертиз»
						";
					Email::send($receiver_email, $from, $subject, $message, $html = true);
				endforeach;
			}

		}
	}

	public function action_registeroutcome2(){
		if(isset($_POST)){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";


			$outcome_id = (int)$_POST['outcome_id'];
			$lang = (int)$_POST['lang'];
			$pages = (int)$_POST['pages'];
			$bm_id = $_POST['bm_id'];
			$result = Model::factory('outcometemp')->update_outcometemp2($outcome_id, $lang, $pages);

			$res = Model::factory('departments')->get_user_by_id((int)$_POST['author_id']);
			$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
			$subject = "Новый исходящий документ на регистрацию";

			if($result){
				$users = Model::factory('outcome')->get_users_for_outcome();
				foreach($users as $user):
					$access_ids = "" . $user['user_id'];
					$qwe = Model::factory('brdmatching')->get_access_ids($bm_id);
					$old_access_ids = $qwe[0]['access_ids'];
					if(!empty($old_access_ids)){
						$access_ids = $old_access_ids . ", " . $access_ids;
					}
					$qwe = Model::factory('brdmatching')->set_access_to_bm($bm_id, $access_ids);
					$res = Model::factory('departments')->get_user_by_id($user['user_id']);
					$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
					$receiver_email = $res[0]['email'];
					echo $receiver_email;
					$message = 
						"Здравствуйте, $receiver<br>
						Для того, чтобы просмотреть, перейдите по <a href='" . $site_url . "correspondence/confirmoutcome?id=$outcome_id'>ссылке</a>.<br>
						$sender<br>
						<b>РГКП «Центр Судебных Экспертиз»
						";
					Email::send($receiver_email, $from, $subject, $message, $html = true);
				endforeach;
			}

		}
	}

	public function action_addnewcorr(){
		if(isset($_POST)){
			$new_corr_name = htmlspecialchars(strip_tags($_POST['new_corr_name']), ENT_QUOTES);
			$result = Model::factory('correspondence')->add_correspondence($new_corr_name);
			if($result){
				echo $result[0]['last_insert_id'];
			}
		}
	}

	public function action_finishoutcome(){
		if(isset($_POST)){

			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$user_id = (int)$_POST['user_id'];
			$outcome_id = (int)$_POST['outcome_id'];
			$how_sent = (int)$_POST['how_sent'];
			$result = Model::factory('outcome')->finish_outcome($user_id, $outcome_id, $how_sent);
			if($result){
				$res = Model::factory('departments')->get_user_by_id($result[0]['author_id']);
				$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
				$receiver_email = $res[0]['email'];
				$res = Model::factory('departments')->get_user_by_id($user_id);
				$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
				$subject = "Исходящий документ успешно прошел регистрацию";
				$message = 
					"Здравствуйте, $receiver<br>
					Для того, чтобы просмотреть, перейдите по <a href='" .$site_url. "correspondence/outcome?view=$outcome_id'>ссылке</a>.<br>
					$sender<br>
					<b>РГКП «Центр Судебных Экспертиз»
					";
				Email::send($receiver_email, $from, $subject, $message, $html = true);
			}
		}
	}

	public function action_canceloutcome(){
		if(isset($_POST)){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$reason = $_POST['reason'];
			$outcome_id = (int)$_POST['outcome_id'];
			$executor_id = (int)$_POST['user_id'];
			$result = Model::factory('outcome')->add_outcome_refusal($outcome_id, $executor_id, $reason);
			if($result){
				$result = Model::factory('outcome')->canceloutcome($outcome_id, $executor_id);
				if($result){
					$res = Model::factory('departments')->get_user_by_id($result[0]['author_id']);
					$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
					$receiver_email = $res[0]['email'];
					$res = Model::factory('departments')->get_user_by_id($executor_id);
					$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
					$subject = "Исходящий документ не прошел регистрацию";
					$message = 
						"Здравствуйте, $receiver<br>
						Для того, чтобы просмотреть, перейдите по <a href='" .$site_url. "correspondence/registeroutcome?id=$outcome_id'>ссылке</a>.<br>
						$sender<br>
						<b>РГКП «Центр Судебных Экспертиз»
						";
					Email::send($receiver_email, $from, $subject, $message, $html = true); 
				}
			}
		}
	}

	public function action_test(){
		?>
		<html>
			<head>
				<meta charset="utf-8">
		        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		        <meta name="viewport" content="width=device-width, initial-scale=1">
		        <link href="/css/bootstrap.css" rel="stylesheet">
		        <link rel="stylesheet" href="/css/style.css">
		        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
			</head>
			<body>

				<div class="col-lg-12">
					<form method="POST" action="">
						<input type="text" name="time" readonly="true" class="form-control" id="time">
						<input type="submit" value="GO">
					</form>
				</div>
				<? if(isset($_POST['time'])){
					$time = $_POST['time'];
					echo $time . "<br>";
					echo date('Y-m-d', strtotime($time));
				}
				?>

				<script src="/js/inputmask.js"></script>
		        <script src="/js/numeric.min.js"></script>
		        <script src="/js/script.js"></script>
		        <script src="/js/jquery.js"></script>
		        <script src="/js/bootstrap.js"></script>
		        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		        <script>
					$(document).ready(function(){
						
						$('#time').datepicker($.extend({
				            inline: true,
				            changeYear: true,
				            changeMonth: true,
				            minDate: new Date(),
				            maxDate: '+1m',
				            firstDay: 1,
				            beforeShowDay: $.datepicker.noWeekends
				        },
				        $.datepicker.regional['ru'] = {
				        	monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
				            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
				            monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
				            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
				            dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
				            dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
				            dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
				            weekHeader: 'Нед',
				            dateFormat: 'dd.mm.yy',
				       		}
				       	));
					});
				</script>
			</body>
		</html>



		<?
	}

	public function action_testdate(){
		if(isset($_POST['date'])){
			$date = $_POST['date'];
			echo date('Y-m-d', strtotime($date));
		}
	}

	public function action_searchexpspec(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			if(isset($_POST['spec_ids'])){
				$spec_ids = $_POST['spec_ids'];
				$spec_ids = json_decode($spec_ids);
				$spec_ids = implode(',', $spec_ids);
			}
			else{
				$spec_ids = "";
			}
			$result = Model::factory('expertises')->get_expertise_cipher_by_like($req, $spec_ids);
			if(count($result)>0){
			?>
				<ul class="unstyled exp_specs">
			<?
				foreach($result as $var):?>
					<li id="spec_id_<?=$var['id'];?>" name="<?=$var['name'];?>"> <?=$var['cipher'] . " - " . $var['name'];?></li>

				<?endforeach;
			?>
				</ul>
			<?
			}
			else{
				echo "<p style='margin:20px;'>Ничего не найдено</p>";
			}
		}
	}

	public function action_test2(){
		$result = Model::factory('expertises')->get_spec_ids_by_reg_num('11-11/14');
		foreach($result as $var):
			echo $var['id'] . ' - ' . $var['name'] . "<hr>"; 
		endforeach;
	}

	public function action_sendexptodir(){
		if(isset($_POST['sender_id'])&&isset($_POST['exp_id'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$sender_id = (int)$_POST['sender_id'];
			$exp_id = (int)$_POST['exp_id'];
			$is_sent = Model::factory('expertises')->exp_is_sent($exp_id);
			if($is_sent){
				ECHO "IS_SENT = 1<BR>";
			}

			$res = Model::factory('departments')->get_user_by_id($sender_id);
			$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

			$res = Model::factory('departments')->get_user_by_id(2);
			$receiver_email = $res[0]['email'];
			$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

			$subject = "Новый проект экспертизы";
			$message = 
				"Здравствуйте, $receiver<br>
				Зарегистрирован новый проект экспертизы.<br>
				Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "expertises/order?id=$exp_id'>ссылке</a>.<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
			if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
				echo "SENT";
			}
			else{
				echo "NOT SENT";
			}
		}
	}

	public function action_sendexptozam(){
		if(isset($_POST['sender_id'])&&isset($_POST['exp_id'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$sender_id = (int)$_POST['sender_id'];
			$exp_id = (int)$_POST['exp_id'];
			$receiver_id = (int)$_POST['receiver_id'];


			$res = Model::factory('departments')->get_user_by_id($sender_id);
			$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

			$res = Model::factory('departments')->get_user_by_id($receiver_id);
			$receiver_email = $res[0]['email'];
			$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

			$subject = "Новая экспертиза";
			$message = 
				"Здравствуйте, $receiver<br>
				Новая экспертиза.<br>
				Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "expertises/send?id=$exp_id'>ссылке</a>.<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
			if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
				echo "SENT";
			}
		}
	}

	public function action_getexperts(){
		if(isset($_POST['cipher_id'])){;
			$cipher_id = (int)$_POST['cipher_id'];
			$result = Model::factory('expertises')->get_experts_and_their_busyness($cipher_id);
			if(count($result)>0){
				$i = 1;
				?>
				<table class="table">
					<tr>
						<th style="text-align:center;">№</th>
						<th style="text-align:center;">ФИО</th>
						<th style="text-align:center;">Отдел</th>
						<th style="text-align:center;">Должность</th>
						<th style="text-align:center;">Общее кол-во экспертиз</th>
						<th style="text-align:center;">Легкой степени сложности</th>
						<th style="text-align:center;">Средней степени сложности</th>
						<th style="text-align:center;">Сложной степени сложности</th>
						<th style="text-align:center;">Особо сложной степени сложности</th>
						<th style="text-align:center;">Выбрать</th>
					</tr>	
				<?foreach($result as $var): ?>
					<tr>
						<td style="text-align:center;"><?=$i; ?></td>
						<td style="text-align:center;"><?=$var['lastname'];?> <?=substr($var['firstname'], 0, 2);?>.<?=substr($var['middlename'], 0, 2);?>.</td>
						<td style="text-align:center;"><?=$var['department_name'];?></td>
						<td style="text-align:center;"><?=$var['position_name'];?></td>
						<td style="text-align:center;"><?=$var['total']; ?></td>
						<td style="text-align:center;"><?=$var['easy'];?></td>
						<td style="text-align:center;"><?=$var['medium'];?></td>
						<td style="text-align:center;"><?=$var['hard'];?></td>
						<td style="text-align:center;"><?=$var['very_hard'];?></td>
						<td style="text-align:center;">
							<div class="checkbox">
								<label>
									<input type="checkbox" value="<?=$var['id'];?>">
								</label>
							</div>
						</td>
					</tr>
					<? $i++; ?>
					<?endforeach;?>
				</table>
				<?
			}
			
		}
	}
	public function action_sendexptoreceivers2(){
		if(isset($_POST['exp_id'])&&isset($_POST['receiver_id'])&&isset($_POST['author_id'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$exp_id = $_POST['exp_id'];
			$receiver_id = $_POST['receiver_id'];
			$author_id = $_POST['author_id'];

			$res = Model::factory('departments')->get_user_by_id($author_id);
			$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

			$res = Model::factory('departments')->get_user_by_id($receiver_id);
			$receiver_email = $res[0]['email'];
			$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

			$subject = "Новая экспертиза";
			$message = 
				"Здравствуйте, $receiver<br>
				Новая экспертиза для Вас<br>
				Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "expertises/execute?id=$exp_id'>ссылке</a>.<br>
				$sender<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
			if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
				echo "SENT";
			}
		}
	}

	public function action_sendexptoreceivers(){
		if(isset($_POST['exp_arr_id'])){
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";

			$author_id = (int)$_POST['author_id'];

			$res = Model::factory('departments')->get_user_by_id($author_id);
			$sender = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";

			

			$arr = $_POST['arr'];
			$exp_arr_id = $_POST['exp_arr_id'];
			$result = Model::factory('expertises')->finish_expertises_array($exp_arr_id);


			foreach($arr as $var):
				$cipher_id = $var['cipher'][0];
				$executor_id = $var['executor'][0];
				$result = Model::factory('expertises')->get_arr_by_arr_id($exp_arr_id);
				$register_num = $result[0]['expertise_register_num'];
				$res = Model::factory('departments')->get_user_by_id($executor_id);
				$receiver_email = $res[0]['email'];
				$receiver = $res[0]['lastname'] . " " . substr($res[0]['firstname'], 0, 2) . "." . substr($res[0]['middlename'], 0, 2) . ".";
				$result = Model::factory('expertises')->get_expertise_by_register_num_and_cipher_id($register_num, $cipher_id);
				$id = $result[0]['id'];
				$subject = "Новая экспертиза";
				$message = 
					"Здравствуйте, $receiver<br>
					Новая экспертиза для Вас<br>
					Для того, чтобы просмотреть документ, перейдите по <a href='" . $site_url . "expertises/execute?id=$id'>ссылке</a>.<br>
					$sender<br>
					<b>РГКП «Центр Судебных Экспертиз»</b>
					";
				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					echo "SENT";
				}
			endforeach;
			

		}
	}

	public function action_getallincomesforexp(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			$result = Model::factory('income')->get_all_incomes_for_exp($req);
			if(count($result)>0){?>
				<ul class="unstyled income_for_select">
				<? foreach($result as $var): ?>
					<li class="income_id_<?=$var['id'];?>"><?=$var['register_num']; ?></li>
				<? endforeach; ?>
				</ul>
			<?}
			else{
				echo "<div style='width:100%; padding-top:10px; height:40px; text-align:center;'><p><b>Ничего не найдено</b></p></div>";
			}
		}
	}

	public function action_getalloutcomesforexp(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			$result = Model::factory('outcome')->get_all_outcomes_for_exp($req);
			if(count($result)>0){?>
				<ul class="unstyled outcome_for_select">
				<? foreach($result as $var): ?>
					<li class="outcome_id_<?=$var['id'];?>"><?=$var['register_num']; ?></li>
				<? endforeach; ?>
				</ul>
			<?}
			else{
				echo "<div style='width:100%; padding-top:10px; height:40px; text-align:center;'><p><b>Ничего не найдено</b></p></div>";
			}
		}
	}

	public function action_getform(){
		if(isset($_POST['go'])&&$_POST['go']=='go'){
			$exp_id = $_POST['exp_id'];
			$expertise = Model::factory('expertises')->get_expertise_by_id($exp_id);
			$expertise = $expertise[0];
			$author = $expertise['lastname'] . " " . substr($expertise['firstname'], 0, 2) . "." .substr($expertise['middlename'], 0, 2) . ".";
			$expert = $expertise['expert_lastname'] . " " . substr($expertise['expert_firstname'], 0, 2) . "." .substr($expertise['expert_middlename'], 0, 2) . ".";
			$fabula = $expertise['plot'];
			$deal_category_name = $expertise['deal_category_name'];
			$deal_num = $expertise['deal_num'];
			$article_num = $expertise['article_num'];
			$status_name = $expertise['status_name'];
			$status_extra_name = $expertise['status_extra_name'];
			$agency_name = $expertise['agency_name'];
			$region_name = $expertise['region_name'];
			$sub_agency_name = $expertise['sub_agency_name'];
			$extra_sub_agency = $expertise['extra_sub_agency'];
			$agency_executor_fio = $expertise['agency_executor_fio'];
			$agency_executor_position = $expertise['agency_executor_position'];
			$agency_executor_rank = $expertise['agency_executor_rank'];
			$document_types = Model::factory('brd')->get_document_types();
			$folders = Model::factory('brd')->get_folders();
			$subfolders = Model::factory('brd')->get_subfolders();
			$brd_noms = Model::factory('brd')->get_brd_nom();
			?>
			<div id="form-wrapper">
				<div class="col-lg-12">
					<div class="form-group">
						<label>Тема документа:</label>
						<input type="text" required class="form-control" name="subject" placeholder="Введите тему документа">
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<label>Папка:</label>
						<select form="brd-create-form" name="folder_id" class="form-control">
							<? foreach($folders as $folder): ?>
								<option value="<?=$folder['id'];?>"><?=$folder['name'];?></option>
								<? foreach($subfolders as $subfolder): ?>
									<? if($subfolder['parent_id']==$folder['id']): ?>
										<option value="<?=$subfolder['id'];?>">--<?=$subfolder['name'];?></option>
									<? endif; ?>
								<? endforeach; ?>
							<? endforeach; ?>
						</select>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<label>Тип документа:</label>
						<select form="brd-create-form" name="type_id" class="form-control" readonly="true">
							<option value="2">Ходатайство</option>
						</select>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<label>Номенклатурное дело:</label>
						<select form="brd-create-form" class="form-control" id="brd_nom">
							<option value="0">Выберите</option>
							<? foreach($brd_noms as $brd_nom): ?>
								<option value="<?=$brd_nom['id'];?>"><?=$brd_nom['descr'];?></option>
							<? endforeach; ?>
						</select>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<label>Номенклатурный код:</label>
						<input type="text" readonly="readonly" class="form-control" name="nom_code" id="nom_code" value="" >
					</div>
				</div>
				<div class="col-lg-12">
					<div class="form-group">
						<label>Текст документа:</label>
						<textarea name="text" id="brd_text">
							<div style="width: 100%; text-align: right;">
								<p><b><?=$agency_name; ?></b></p>
								<p><b><?=$agency_executor_position;?></b></p>
								<p><b><?=$agency_executor_rank;?></b></p>
								<p><b><?=$agency_executor_fio;?></b></p>
							</div>
							<div style="width: 100%; text-align: justify;">
								В производстве экспертов Института судебных экспертиз по городу Астана филиала РГКП ЦСЭ МЮ РК <?=$expert; ?> находится назначенная Вами 
							</div>
						</textarea>
					</div>
				</div>
			</div>
			<?
		}
	}

	public function action_createform(){
		if(isset($_POST['subject'])&&isset($_POST['folder_id'])&&isset($_POST['nom_code'])&&isset($_POST['brd_text'])){
			$subject = $_POST['subject'];
			$folder_id = $_POST['folder_id'];
			$nom_code = $_POST['nom_code'];
			$text = $_POST['brd_text'];
			$author_id = $_POST['author_id'];
			$type_id = 2;
			$result = Model::factory('brd')->add_doc($subject, $folder_id, $author_id, $text, $type_id);
			$brd_id = $result[0]['last_insert_id'];
			$register_num = Model::factory('brd')->get_last_qwe();
			$x = strpos($register_num[0]['register_num'], '/')+1;
			$y = strpos($register_num[0]['register_num'], '_');
			if(!$y){
				$z = (int)substr($register_num[0]['register_num'], $x)+1;
			}
			else{
				$z = (int)substr($register_num[0]['register_num'], $x, $y-$x)+1;
			}
			$register_num = $nom_code . "/". $z;
			$result = Model::factory('brd')->set_register_num_by_id($brd_id, $register_num);
			echo $brd_id;

		}
	}

	public function action_viewdocforexp(){
		if(isset($_POST['brd_id'])){
			$author_id = $_POST['author_id'];
			$brd_id = (int)$_POST['brd_id'];
			$brds = Model::factory('brd')->get_brd_with_copies2($brd_id);
			$ids = array();
			if(count($brds)>0){
				foreach($brds as $var):
					$ids[] = $var['id'];
				endforeach;
				$ids = implode(',', $ids);
			}
			?>
			<div class="col-lg-12" id="container-matching">
				<div id="info">
						<? if($brds[0]['matching']==0): ?>
							<button class="btn btn-default" id="matching_modal_btn" data-toggle="modal" data-target="#matching_modal">Согласовать</button>&nbsp;
						<? endif; ?>
					<div class="notifications" style="border:1px solid #ccc; margin:20px; text-align: center; height:50px;">
						<p id="is_sent">Документ на согласование успешно отправлен</p>
					</div>
				</div>
				<div id="main-content">

				<? foreach($brds as $brd): ?>
					<div class="col-lg-12" style="border-bottom: 1px solid #ccc;">
						<div class="col-lg-12" style="text-align: center;"><h3><?=$brd['document_type_name'];?>: № <?=$brd['register_num'];?></h3></div>
						<div class="col-lg-6" style="font-size:16px;">Автор: <?=$brd['lastname'];?> <?=substr($brd['firstname'], 0, 2);?>.<?=substr($brd['middlename'], 0, 2);?>.</div>
						<div class="col-lg-6" style="font-size:16px; text-align: right;">Дата создания: <?=date('d.m.Y', strtotime($brd['date_of_reg']));?></div>
						<div class="col-lg-6" style="font-size:16px;">Подразделение: <?=$brd['name'];?></div>
						<div class="col-lg-6" style="font-size:16px; text-align: right;">Дата изменения: <?=date('d.m.Y', strtotime($brd['date_of_change']));?></div>
						
					</div>
					<div class="col-lg-12" style="border-bottom: 1px solid #ccc;">
						<div class="col-lg-6">
							<div class="col-lg-5" style="height:26px;"><strong>Тема документа:</strong></div>
							<div class="col-lg-7" style="height:26px;">
								<?=$brd['subject'];?>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="col-lg-5" style="height:26px;"><strong>Папка:</strong></div>
							<div class="col-lg-7" style="height:26px;">
								<?=$brd['folder_name'];?>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="col-lg-12"><strong>Текст документа:</strong></div>
							<div class="col-lg-12" style="word-break: break-all;">
								<?=htmlspecialchars_decode($brd['text']);?>
							</div>
						</div>
					</div>
				<? endforeach; ?>
				</div>
			</div>
			<style>
				#is_sent{ color:green; display: none; margin-top:10px; }
				#recievers{
					display:none;
				}
				.users_for_select{
					list-style-type: decimal;
				}
				.users_for_select > li {
					cursor:pointer;
				}
				.users_for_select > li:hover{
					background-color: #ccc;
				}
				#main-content{
					margin:20px auto;
				}
				.col-lg-5{
					margin: 5px 0;
					border-bottom: 1px solid #ccc;
					padding-bottom:5px;
				}
				.col-lg-7{
					margin: 5px 0;
					border-bottom: 1px solid #ccc;
					padding-bottom:5px;
				}
			</style>
			<script>
				$(document).ready(function(){
					var ids = [];
					var brd_id = <?=$brd_id; ?>;
					var brd_matching_id = 0;
					$('#matching_reciever').keyup(throttle(function(){
						var req =  $(this).val();
						if(req!=''){
							$.ajax({
								url : "/actions/getusers",
								data : {req: req, ids: JSON.stringify(ids), user_id: <?=Auth::instance()->get_user()->id;?>},
								type : "POST",
								success : function(data){
									$('.hints').show();
									$('.hints').html(data);

								}
							});
						}
						else{
							$('.hints').html('');
						}
					}));
					$('#fake_reciever_btn').on('click', function(){
						var user_ids_for_matching = $('#recievers').val();
						$('#matching_modal').modal('hide');
						$('#container-matching #info').html('<p id="is_sent">Документ на согласование успешно отправлен</p>');
						$.ajax({
							url : '/actions/setrecievers/',
							data : {brd_id: brd_id, recievers: JSON.stringify(user_ids_for_matching), author_id: <?=Auth::instance()->get_user()->id; ?>},
							type : "POST",
							success : function(data){
								brd_matching_id = data;
								$('#matching_modal_btn').hide();
								$('#real_reciever_btn').trigger('click');
								$('#renewal_date').parent().after("<div class='form-group'><button type='submit' class='btn btn-info' name='save_before_btn'>Сохранить</button></div>");
								$('#form').remove();
															
							}
						});
					});
					$(document).ajaxComplete(function(){
						$('.users_for_select > li').click(function(){
							var user_id = $(this).attr('id');
							user_id = user_id.replace('user_id_', '');
							$.ajax({
								url : "/actions/getuserinfo",
								data : {user_id: user_id, ids: ids},
								type : "POST",
								dataType : "json",
								success : function(response){
									var reciever_id = response['user_id'];
									var lastname = response['lastname'];
									var firstname = response['firstname'];
									var middlename = response['middlename'];
									var email = response['email'];
									var position_name = response['position_name'];
									var department_name = response['department_name'];
									$('#recievers').show();
									$('#recievers').append("<option selected='selected' value='"+reciever_id+"'>"+ lastname + " " + firstname.charAt(0) + "." + middlename.charAt(0) + ". " + " ("+ department_name +") " + position_name + "</option>");
									$('#matching_reciever').val('');
									$('.hints').html('');
									ids.push(user_id);
								}
							});
						});
						$('#create_outcome').click(function(){
							$.ajax({
								url : "/actions/createoutcomefrombm",
								data : {brd_matching_id: brd_matching_id, executor_id: <?=Auth::instance()->get_user()->id;?>},
								type : "POST",
								success : function(data){
									var outcome_id = data;
								}
							});
						});
						
					});
					function throttle(f, delay){
					    var timer = null;
					    return function(){
					        var context = this, args = arguments;
					        clearTimeout(timer);
					        timer = window.setTimeout(function(){
					            f.apply(context, args);
					        },
					        delay || 200);
					    };
					}
				});
			</script>
		<div class="modal fade" id="matching_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="width:700px; margin:100px auto;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Согласование</h4>
					</div>
					<div class="modal-body" id="matching_recievers_wrapper">
						<div class="form-group" id="chosen_recievers_wrapper">
							<select multiple="multiple" id="recievers" class="form-control">
								
							</select>
						</div> 
						<div class="form-group">
							<input type="text" class="form-control" id="matching_reciever" placeholder="Введите фамилию для выбора получателей">
						</div>
						<div class="hints col-lg-12" style="display:none;">
							
						</div>
					</div>
					<div class="modal-footer" style="border-top:0px;">
						<button id="fake_reciever_btn" class="btn btn-default" type="button">Отправить</button>
						<button id="real_reciever_btn" style="display:none;" data-dismiss="modal"></button>
					</div>
				</div>
			</div>
		</div>

		<?}
	}

	public function action_checkdocmatching(){
		if(isset($_POST['brd_matching_id'])){
			$bm_id = (int)$_POST['brd_matching_id'];
			$result = Model::factory('brd')->check_matching($bm_id);
			$final_result = (int)$result[0]['final_result'];
			echo $final_result;
		}
	}

	public function action_getdocsbyreq(){
		if(isset($_POST['req'])){
			$req = htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			if(isset($_POST['reasons_selected_brd'])){
				$reasons = implode(',', json_decode($_POST['reasons_selected_brd']));
			}
			else{
				$reasons = "";
			}
			$result = Model::factory('expertises')->search_docs_by_req($req, $reasons);
			if(count($result)>0){?>
				<ul class="unstyled docs_for_select">
				<? foreach($result as $var): ?>
					<li id="doc_id_<?=$var['id'];?>"><?=$var['register_num'];?></li>
				<? endforeach; ?>
				</ul>
			<?}
			else{?>
			<div style="width:100%; text-align: center;">
				<p><b>Ничего не найдено</b></p>
			</div>
			<?}
		}
	}

	public function action_getincomesforexp(){
		if(isset($_POST['req'])){
			$req =  htmlspecialchars(strip_tags($_POST['req']), ENT_QUOTES);
			$result = Model::factory('expertises')->search_income_by_req($req);
			if(count($result)>0){?>
				<ul class="unstyled incomes_for_select">
				<? foreach($result as $var): ?>
					<li id="income_id_<?=$var['id'];?>"><?=$var['register_num'];?></li>
				<? endforeach; ?>
				</ul>
			<?}
			else{?>
			<div style="width:100%; text-align: center;">
				<p><b>Ничего не найдено</b></p>
			</div>
			<?}
		}
	}

	public function action_setexperttoexp(){
		if(isset($_POST['expert_id'])&&isset($_POST['exp_id'])){
			$expert_id = (int)$_POST['expert_id'];
			$exp_id = (int)$_POST['exp_id'];
			$result = Model::factory('expertises')->get_expert_to_expertise($expert_id, $exp_id);
			$specialty_id = $result;
			$result = Model::factory('expertises')->get_expert_busyness($expert_id, $exp_id, $specialty_id);
			if($result){
				echo "DONE";
			}
		}
	}

	public function action_setrecieversexp(){
		if(isset($_POST['exp_id'])){
			$exp_id = (int)$_POST['exp_id'];
			$receivers = json_decode($_POST['recievers']);
			$author_id = (int)$_POST['author_id'];
			$length = count($receivers);
			$date_created = date('Y-m-d', time());
			$statuses = array();
			$last_insert_id = 0;
			$dates_matching = array();
			for($x=0; $x<$length; $x++){
				$statuses[] = 0;
				$dates_matching[] = '00-00-00'; 
			}
			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";
			$result = Model::factory('expertisesmatching')->add_to_matching($exp_id, implode(', ', $receivers), implode(', ', $statuses), $length, $date_created, implode(', ', $dates_matching), $author_id);
			$res = Model::factory('expertisesmatching')->set_matching_exp($exp_id, $author_id);
			if($result){
				$last_insert_id = $result[0]['last_insert_id'];
				$receiver_id = $receivers[0];
				$result = Model::factory('departments')->get_user_by_id($receiver_id);
				$receiver_email = $result[0]['email'];
				$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$result = Model::factory('departments')->get_user_by_id($author_id);
				$sender = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
				$subject = "Новый проект экспертизы на согласование";
				$message = 
				"Здравствуйте, $receiver<br>
				Для того, чтобы просмотреть перейдите по <a href='" . $site_url . "expmatching/view?id=$last_insert_id'>ссылке</a>.<br>
				C Уважением, $sender<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
				if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
					
				}
				echo $last_insert_id;
			}
		}
	}

	public function action_finishregisteroutcome(){
		if(isset($_POST['executor_id'])){	
			$outcometemp_id = $_POST['outcome_id'];
			$type = $_POST['type'];
			$reserve_id = $_POST['reserve_id'];
			$reserve_value = $_POST['reserve_value'];
			$outcometemp = Model::factory('outcometemp')->get_outcometemp_by_id($outcometemp_id);

			$executor_id = $_POST['executor_id'];
			$how_sent = $_POST['how_sent'];			
			$lang = $outcometemp['lang'];
			$author_id = $outcometemp['author_id'];
			$bm_id = $outcometemp['bm_id'];
			$document_type_id = $outcometemp['document_type_id'];
			$short = $outcometemp['short'];
			$num_code = $outcometemp['num_code'];
			$income_deal_num = $outcometemp['income_deal_num'];
			$correspondent_id = $outcometemp['correspondent_id'];
			$pages = $outcometemp['pages'];

			$result = Model::factory('outcometemp')->set_sent($outcometemp_id);

			$the_id = 0;
			if($type==1){
				$register_num = Model::factory('outcome')->get_last2();
				$i = stripos($register_num, '/');
				if($i){
					$last = (int)substr($register_num, $i+1) + 1;
				}
				else{
					$last = (int)$register_num + 1;
				}
				$result = Model::factory('outcome')->finish_outcomefromtemp($author_id, $executor_id, $how_sent, $lang, $bm_id, $document_type_id, $short, $num_code, $income_deal_num, $correspondent_id, $pages, $last);
				echo $result[0]['last_insert_id'];
				$the_id = $result[0]['last_insert_id'];
			}
			else{
				$result = Model::factory('outcome')->finish_outcomefromtemp2($author_id, $executor_id, $how_sent, $lang, $bm_id, $document_type_id, $short, $num_code, $income_deal_num, $correspondent_id, $pages, $reserve_id, $reserve_value);
				echo $result;
				$the_id = $result;
			}

			$url = URL::site(NULL, 'https');
			$url_len = strlen($url);
			$new_url = substr($url, 12, ($url_len-12)-1);
			$site_url = URL::site(NULL, "https");
			$from = "postmaster@$new_url";
			$result = Model::factory('departments')->get_user_by_id($author_id);
			$receiver = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
			$receiver_email = $result[0]['email'];
			$result = Model::factory('departments')->get_user_by_id($executor_id);
			$sender = $result[0]['lastname'] . " " . substr($result[0]['firstname'], 0, 2) . "." . substr($result[0]['middlename'], 0, 2) . ".";
			$subject = "Исходящий документ успешно зарегистрирован";
			$message = 
				"Здравствуйте, $receiver<br>
				Для того, чтобы просмотреть перейдите по <a href='" . $site_url . "correspondence/outcome?view=$the_id'>ссылке</a>.<br>
				C Уважением, $sender<br>
				<b>РГКП «Центр Судебных Экспертиз»</b>
				";
			if(Email::send($receiver_email, $from, $subject, $message, $html = true)){
				
			}

		}
	}

	public function action_download(){
		if(isset($_GET['file'])){
			$file = $_GET['file'];
			if (file_exists($file)) {
			    $basename = basename($file);
			    $length   = sprintf("%u", filesize($file));

			    header('Content-Description: File Transfer');
			    header('Content-Type: application/octet-stream');
			    header('Content-Disposition: attachment; filename="' . $basename . '"');
			    header('Content-Transfer-Encoding: binary');
			    header('Connection: Keep-Alive');
			    header('Expires: 0');
			    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			    header('Pragma: public');
			    header('Content-Length: ' . $length);

			    set_time_limit(0);
			    readfile($file);
			}
		}
	}

	public function action_url(){
		$arr = explode('.', $_SERVER['SERVER_NAME']);
		$url = URL::site(NULL, 'https');
		$url_len = strlen($url);
		$new_url = substr($url, 12, ($url_len-12)-1);
		echo $url;
	}
}