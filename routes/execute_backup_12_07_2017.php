<div class="col-lg-10" id="container">
	<div id="info" style="text-align: center;">
		<? if($expert_id!=0): ?>
			<? if($user_position['position_id']==8 OR $user_position['position_id']==25): ?>
				<button class="btn btn-default" data-toggle="modal" data-target="#send_modal">Отправить на производство</button>
			<? endif; ?>
			<button class="btn btn-default" id="execute_btn">К производству</button>
		<? endif; ?>
	</div>
	<div id="main-content">
		<div class="col-lg-12" style="border-bottom: 1px solid #ccc; padding-top:20px; padding-bottom:20px;">
			<div class="col-lg-12" style="border-bottom: 1px solid #ccc;">
				<div class="col-lg-6"><h3>Регистрационный номер: № <?=$expertise['register_num'];?></h3></div>
				<div class="col-lg-6"><h3>Дата: <?=date('d.m.Y', strtotime($expertise['date_of_reg']));?></h3></div>
			</div>
			<div class="col-lg-6" style="padding-top:5px;">
				<div class="col-lg-5">
					<strong>Делопроизводитель:</strong>
				</div>
				<div class="col-lg-7">
					<a href="/structure/profile?id=<?=$expertise['author_id'];?>" target="_blank">
						<?=$expertise['lastname'];?> <?=substr($expertise['firstname'], 0, 2);?>.<?=substr($expertise['middlename'], 0, 2); ?>.
					</a>
				</div>
				<div class="col-lg-5">
					<strong>Фабула:</strong>
				</div>
				<div class="col-lg-7">
					<?=$expertise['plot'];?>
				</div>
				<div class="col-lg-5">
					<strong>Категория дела:</strong>
				</div>
				<div class="col-lg-7">
					<?=$expertise['deal_category_name']; ?>
				</div>
				<div class="col-lg-5">
					<strong>№ дела:</strong>
				</div>
				<div class="col-lg-7">
					<?=$expertise['deal_num'];?>
				</div>
				<div class="col-lg-5">
					<strong>№ статьи:</strong>
				</div>
				<div class="col-lg-7">
					<?=$expertise['article_num'];?>
				</div>
				<div class="col-lg-5">
					<strong>Статус:</strong>
				</div>
				<div class="col-lg-7">
					<?=$expertise['status_name']; ?>
				</div>
				<div class="col-lg-5">
					<strong>Дополнительный статус:</strong>
				</div>
				<div class="col-lg-7">
					<?=$expertise['status_extra_name']; ?>
				</div>
				<? if($expertise['status_id']==3): ?>
					<div class="col-lg-5">
						<strong>Первичная экспертиза провдилась в:</strong>
					</div>
					<div class="col-lg-7">
						<?=$expertise['repeat_region_name']; ?>
					</div>
				<? endif; ?>
			</div>
			<div class="col-lg-6" style="padding-top:5px;">
				<div class="col-lg-5">
					<strong>Шифр экспертизы:</strong>
				</div>
				<div class="col-lg-7">
					<?=$expertise['cipher']; ?> - <?=$expertise['spec_name']; ?>
				</div>
				<div class="col-lg-5">
					<strong>Орган назначивший экспертизу:</strong>
				</div>
				<div class="col-lg-7">
					<?=$expertise['agency_name']; ?>
				</div>

				<div class="form-group" id="agency_case">
					<div class="input-group">
						<div class="input-group-addon">Название органа</div>
						<input type="text" class="form-control" name="extra_sub_agency" id="extra_sub_agency" value="<?=$expertise['extra_sub_agency'];?>">
					</div>
				</div>

				<div class="col-lg-5">
					<strong>Регион назначивший экспертизу:</strong>
				</div>
				<div class="col-lg-7">
					<?=$expertise['region_name']; ?>
				</div>

				<div class="col-lg-5">
					<strong>Наименование органа:</strong>
				</div>
				<div class="col-lg-7">
					<?=$expertise['sub_agency_name']; ?>
				</div>
				<div class="col-lg-12" style="padding:0 5px; margin-top:16px;">
					<h4 style="background:#fff; margin-left:auto; margin-right:auto; text-align: center;">Данные лица назначившего экспертизу</h4>
					<div class="col-lg-4">
						<strong>ФИО:</strong>
					</div>
					<div class="col-lg-8">
						<?=$expertise['agency_executor_fio'];?>
					</div>
					<div class="col-lg-4">
						<strong>Должность:</strong>
					</div>
					<div class="col-lg-8">
						<?=$expertise['agency_executor_position'];?>
					</div>
					<? if(!empty($expertise['agency_executor_rank'])): ?>
						<div class="col-lg-4">
							<strong>Звание:</strong>
						</div>
						<div class="col-lg-8">
							<?=$expertise['agency_executor_rank'];?>
						</div>
					<? endif; ?>
				</div>
			</div>
		</div>
	</div>
	
	<div id="main-content2" class="col-lg-12">
		<form action="" method="POST" id="execute_form" enctype="multipart/form-data">
			<div class="col-lg-6">
				<div class="form-group">
					<label>Категория сложности:</label>
					<? if($expertise['complexity_id']!=0): ?>
						<? $complexity_name = ""; ?>
						<select style="display:none" name="complexity_id" class="form-control" form="execute_form">
							<? foreach($complexities as $var): ?>
								<? if($var['id']==$expertise['complexity_id']): ?>
									<option selected="selected" value="<?=$var['id'];?>"><?=$var['name'];?></option>
									<? $complexity_name = $var['name']; ?>
								<? endif; ?>
							<? endforeach; ?>
						</select>
						<input type='text' class="form-control" value="<?=$complexity_name; ?>" readonly>
					<? else: ?>
						<select name="complexity_id" class="form-control" form="execute_form">
							<option value="0">Выберите</option>
							<? foreach($complexities as $var): ?>
								<option value="<?=$var['id'];?>"><?=$var['name'];?></option>
							<? endforeach; ?>
						</select>
					<? endif; ?>
				</div>
				<div class="form-group">
					<label>Кол-во поставленных вопросов:</label>
					<input type="text" class="form-control" name="questions" <? if($expertise['questions']!=0): ?> value="<?=$expertise['questions'];?>" readonly="true" <? endif; ?> >
				</div>
				<div class="form-group">
					<label>Кол-во объектов:</label>
					<input type="text" class="form-control" name="objects" <? if($expertise['objects']!=0): ?> value="<?=$expertise['objects'];?>" readonly="true" <? endif; ?>>
				</div>
				<div class="form-group">
					<label>Срок производства:</label>
					<input readonly="true" placeholder="Выберите дату" type="text" class="form-control" id="deadline" name="deadline" <? if($expertise['deadline']!='0000-00-00'): ?> value="<?=date('d.m.Y', strtotime($expertise['deadline']));?>" readonly="true" <? endif; ?>>
				</div>
				<div class="form-group">
					<label>Дата приостановления:</label>
					<input readonly="true" placeholder="Выберите дату" type="text" class="form-control" id="pause_date" name="pause_date" <? if($expertise['pause_date']!='0000-00-00'): ?> value="<?=date('d.m.Y', strtotime($expertise['pause_date']));?>" readonly="true" <? endif; ?>>
				</div>
				<? if($expertise['pause_reason_id']!=0): ?>
					<div class="form-group">
						<label>Причина приостановления:</label>
						<input class="form-control" type="text" style="display:none;" readonly value="<?=$expertise['pause_reason_id'];?>" name="pause_reason_id">
						<input class="form-control" type="text" readonly value="<? if($expertise['pause_reason_id']==1): ?><?='Ходатайство';?><? elseif($expertise['pause_reason_id']==2): ?><?='Командировка';?><? elseif($expertise['pause_reason_id']==3): ?><?='Больничный лист';?> <? elseif($expertise['pause_reason_id']==4): ?><?='Вызов в суд';?> <? elseif($expertise['pause_reason_id']==5): ?><?='Участие в комплексной экспертизе';?><? endif; ?>">
					</div>
					<? if($expertise['pause_reason_id']!=1): ?>
					<div class="form-group">
						<label>Основание:</label><br>
						<a <? if($expertise['pause_reason_id']==2 OR $expertise['pause_reason_id']==3 OR $expertise['pause_reason_id']==5): ?> href="/documents/viewdoc?id=<?=$expertise['pause_reason_basis'];?>" <?else: ?> href="/correspondence/income?id=<?=$expertise['pause_reason_basis'];?>" <? endif; ?> class="btn btn-success btn-sm" target="_blank">ОСНОВАНИЕ</a>
					</div>
					<? endif; ?>
					<input type="hidden" name="pause_reason_basis" value="<?=$expertise['pause_reason_basis'];?>">
					<div class="form-group">
						<label>Дата возобновления:</label>
						<input type="text" readonly class="form-control" value="<?=date('d.m.Y', strtotime($expertise['renewal_date']));?>">
						<input type="hidden" name="renewal_date" value="<?=$expertise['renewal_date'];?>">
					</div>
				<? else:?>
					<div class="form-group">
						<label>Причина приостоновления:</label>
						<select name="pause_reason_id" id="pause_reason_id" class="form-control pause_reasons" form="execute_form">
							<option value="0">Выберите</option>
							<? foreach($pause_reasons as $var): ?>
								<option value="<?=$var['id'];?>"><?=$var['name'];?></option>
							<? endforeach; ?>
						</select>	
					</div>
					<div class="form-group pause_reason_basis_wrapper">
						<label>Основание:</label>
						<input type="text" class="form-control pause_reason_basises">
						<input type="hidden" name="pause_reason_basis">
						<div class="pause_reason_hints"></div>
					</div>
					<div class="form-group" id="renewal_date_wrapper">
						<label>Дата возобновления:</label>
						<input readonly="true" placeholder="Выберите дату" type="text" class="form-control" id="renewal_date" name="renewal_date">
					</div>
					<div id="qqq" class="form-group" style="display:none;">
						<button type="submit" name="save_before_btn" class="btn btn-info">Сохранить</button>
					</div>
					<div class="form-group add_more_wrapper" style="text-align: right;">
						<button type="button" class="btn btn-primary add_more" id="add_more_2">Добавить еще</button>
					</div>
				<? endif; ?>

				<? if($expertise['pause_reason_id_2']!=0): ?>
					<div class="form-group">
						<label>Причина приостановления:</label>
						<input class="form-control" type="text" style="display:none;" readonly value="<?=$expertise['pause_reason_id_2'];?>" name="pause_reason_id_2">
						<input class="form-control" type="text" readonly value="<? if($expertise['pause_reason_id_2']==1): ?><?='Ходатайство';?><? elseif($expertise['pause_reason_id_2']==2): ?><?='Командировка';?><? elseif($expertise['pause_reason_id_2']==3): ?><?='Больничный лист';?> <? elseif($expertise['pause_reason_id_2']==4): ?><?='Вызов в суд';?> <? elseif($expertise['pause_reason_id_2']==5): ?><?='Участие в комплексной экспертизе';?><? endif; ?>">
					</div>
					<? if($expertise['pause_reason_id_2']!=1): ?>
					<div class="form-group">
						<label>Основание:</label><br>
						<a <? if($expertise['pause_reason_id_2']==2 OR $expertise['pause_reason_id_2']==3 OR $expertise['pause_reason_id_2']==5): ?> href="/documents/viewdoc?id=<?=$expertise['pause_reason_basis_2'];?>" <?else: ?> href="/correspondence/income?id=<?=$expertise['pause_reason_basis_2'];?>" <? endif; ?> class="btn btn-success btn-sm" target="_blank">ОСНОВАНИЕ</a>
					</div>
					<? endif; ?>
					<input type="hidden" name="pause_reason_basis_2" value="<?=$expertise['pause_reason_basis_2'];?>">
					<div class="form-group">
						<label>Дата возобновления:</label>
						<input type="text" readonly class="form-control" value="<?=date('d.m.Y', strtotime($expertise['renewal_date_2']));?>">
						<input type="hidden" name="renewal_date_2" value="<?=$expertise['renewal_date_2'];?>">
					</div>
					<!--<div class="form-group add_more_wrapper" style="text-align: right;">
						<button type="button" class="btn btn-primary add_more" id="add_more_3">Добавить еще</button>
					</div>-->
				<? elseif($expertise['pause_reason_id']!=0):?>
					<div class="form-group">
						<label>Причина приостоновления:</label>
						<select name="pause_reason_id_2" id="pause_reason_id_2" class="form-control pause_reasons" form="execute_form">
							<option value="0">Выберите</option>
							<? foreach($pause_reasons as $var): ?>
								<option value="<?=$var['id'];?>"><?=$var['name'];?></option>
							<? endforeach; ?>
						</select>	
					</div>
					<div class="form-group pause_reason_basis_wrapper_2">
						<label>Основание:</label>
						<input type="text" class="form-control pause_reason_basises">
						<input type="hidden" name="pause_reason_basis_2">
						<div class="pause_reason_hints"></div>
					</div>
					<div class="form-group" id="renewal_date_wrapper_2">
						<label>Дата возобновления:</label>
						<input readonly="true" placeholder="Выберите дату" type="text" class="form-control" id="renewal_date_2" name="renewal_date_2">
					</div>
					<div class="form-group" id="save_2" style="display:none;">
						<button type="submit" name="save_before_btn" class="btn btn-info">Сохранить</button>
					</div>
					<div class="form-group add_more_wrapper" style="text-align: right;">
						<button type="button" class="btn btn-primary add_more" id="add_more_3">Добавить еще</button>
					</div>
				<? endif; ?>

				<? if($expertise['pause_reason_id_3']!=0): ?>
					<div class="form-group">
						<label>Причина приостановления:</label>
						<input class="form-control" type="text" style="display:none;" readonly value="<?=$expertise['pause_reason_id_3'];?>" name="pause_reason_id_3">
						<input class="form-control" type="text" readonly value="<? if($expertise['pause_reason_id_3']==1): ?><?='Ходатайство';?><? elseif($expertise['pause_reason_id_3']==2): ?><?='Командировка';?><? elseif($expertise['pause_reason_id_3']==3): ?><?='Больничный лист';?> <? elseif($expertise['pause_reason_id_3']==4): ?><?='Вызов в суд';?> <? elseif($expertise['pause_reason_id_3']==5): ?><?='Участие в комплексной экспертизе';?><? endif; ?>">
					</div>
					<? if($expertise['pause_reason_id_3']!=1): ?>
					<div class="form-group">
						<label>Основание:</label><br>
						<a <? if($expertise['pause_reason_id_3']==2 OR $expertise['pause_reason_id_3']==3 OR $expertise['pause_reason_id_3']==5): ?> href="/documents/viewdoc?id=<?=$expertise['pause_reason_basis_3'];?>" <?else: ?> href="/correspondence/income?id=<?=$expertise['pause_reason_basis_3'];?>" <? endif; ?> class="btn btn-success btn-sm" target="_blank">ОСНОВАНИЕ</a>
					</div>
					<? endif; ?>
					<input type="hidden" name="pause_reason_basis_3" value="<?=$expertise['pause_reason_basis_3'];?>">
					<div class="form-group">
						<label>Дата возобновления:</label>
						<input type="text" readonly class="form-control" value="<?=date('d.m.Y', strtotime($expertise['renewal_date_3']));?>">
						<input type="hidden" name="renewal_date_3" value="<?=$expertise['renewal_date_3'];?>">
					</div>
					<!--<div class="form-group add_more_wrapper" style="text-align: right;">
						<button type="button" class="btn btn-primary add_more" id="add_more_3">Добавить еще</button>
					</div>-->
				<? elseif($expertise['pause_reason_id_2']!=0):?>
					<div class="form-group">
						<label>Причина приостоновления:</label>
						<select name="pause_reason_id_3" id="pause_reason_id_3" class="form-control pause_reasons" form="execute_form">
							<option value="0">Выберите</option>
							<? foreach($pause_reasons as $var): ?>
								<option value="<?=$var['id'];?>"><?=$var['name'];?></option>
							<? endforeach; ?>
						</select>	
					</div>
					<div class="form-group pause_reason_basis_wrapper_3">
						<label>Основание:</label>
						<input type="text" class="form-control pause_reason_basises">
						<input type="hidden" name="pause_reason_basis_3">
						<div class="pause_reason_hints"></div>
					</div>
					<div class="form-group" id="renewal_date_wrapper_3">
						<label>Дата возобновления:</label>
						<input readonly="true" placeholder="Выберите дату" type="text" class="form-control" id="renewal_date_3" name="renewal_date_3">
					</div>
					<div class="form-group" id="save_3" style="display:none;">
						<button type="submit" name="save_before_btn" class="btn btn-info">Сохранить</button>
					</div>
					<div class="form-group add_more_wrapper" style="text-align: right;">
						<button type="button" class="btn btn-primary add_more" id="add_more_4">Добавить еще</button>
					</div>
				<? endif; ?>

				<? if($expertise['pause_reason_id_4']!=0): ?>
					<div class="form-group">
						<label>Причина приостановления:</label>
						<input class="form-control" type="text" style="display:none;" readonly value="<?=$expertise['pause_reason_id_4'];?>" name="pause_reason_id_4">
						<input class="form-control" type="text" readonly value="<? if($expertise['pause_reason_id_4']==1): ?><?='Ходатайство';?><? elseif($expertise['pause_reason_id_4']==2): ?><?='Командировка';?><? elseif($expertise['pause_reason_id_4']==3): ?><?='Больничный лист';?> <? elseif($expertise['pause_reason_id_4']==4): ?><?='Вызов в суд';?> <? elseif($expertise['pause_reason_id_4']==5): ?><?='Участие в комплексной экспертизе';?><? endif; ?>">
					</div>
					<? if($expertise['pause_reason_id_4']!=1): ?>
					<div class="form-group">
						<label>Основание:</label><br>
						<a <? if($expertise['pause_reason_id_4']==2 OR $expertise['pause_reason_id_4']==3 OR $expertise['pause_reason_id_4']==5): ?> href="/documents/viewdoc?id=<?=$expertise['pause_reason_basis_4'];?>" <?else: ?> href="/correspondence/income?id=<?=$expertise['pause_reason_basis_4'];?>" <? endif; ?> class="btn btn-success btn-sm" target="_blank">ОСНОВАНИЕ</a>
					</div>
					<? endif; ?>
					<input type="hidden" name="pause_reason_basis_4" value="<?=$expertise['pause_reason_basis_4'];?>">
					<div class="form-group">
						<label>Дата возобновления:</label>
						<input type="text" readonly class="form-control" value="<?=date('d.m.Y', strtotime($expertise['renewal_date_4']));?>">
						<input type="hidden" name="renewal_date_4" value="<?=$expertise['renewal_date_4'];?>">
					</div>
					<!--<div class="form-group add_more_wrapper" style="text-align: right;">
						<button type="button" class="btn btn-primary add_more" id="add_more_4">Добавить еще</button>
					</div>-->
				<? elseif($expertise['pause_reason_id_3']!=0):?>
					<div class="form-group">
						<label>Причина приостоновления:</label>
						<select name="pause_reason_id_4" id="pause_reason_id_4" class="form-control pause_reasons" form="execute_form">
							<option value="0">Выберите</option>
							<? foreach($pause_reasons as $var): ?>
								<option value="<?=$var['id'];?>"><?=$var['name'];?></option>
							<? endforeach; ?>
						</select>	
					</div>
					<div class="form-group pause_reason_basis_wrapper_4">
						<label>Основание:</label>
						<input type="text" class="form-control pause_reason_basises">
						<input type="hidden" name="pause_reason_basis_4">
						<div class="pause_reason_hints"></div>
					</div>
					<div class="form-group" id="renewal_date_wrapper_4">
						<label>Дата возобновления:</label>
						<input readonly="true" placeholder="Выберите дату" type="text" class="form-control" id="renewal_date_4" name="renewal_date_4">
					</div>
					<div class="form-group" id="save_4" style="display:none;">
						<button type="submit" name="save_before_btn" class="btn btn-info">Сохранить</button>
					</div>
					<div class="form-group add_more_wrapper" style="text-align: right;">
						<button type="button" class="btn btn-primary add_more" id="add_more_5">Добавить еще</button>
					</div>
				<? endif; ?>

				<? if($expertise['pause_reason_id_5']!=0): ?>
					<div class="form-group">
						<label>Причина приостановления:</label>
						<input class="form-control" type="text" style="display:none;" readonly value="<?=$expertise['pause_reason_id_5'];?>" name="pause_reason_id_5">
						<input class="form-control" type="text" readonly value="<? if($expertise['pause_reason_id_5']==1): ?><?='Ходатайство';?><? elseif($expertise['pause_reason_id_5']==2): ?><?='Командировка';?><? elseif($expertise['pause_reason_id_5']==3): ?><?='Больничный лист';?> <? elseif($expertise['pause_reason_id_5']==4): ?><?='Вызов в суд';?> <? elseif($expertise['pause_reason_id_5']==5): ?><?='Участие в комплексной экспертизе';?><? endif; ?>">
					</div>
					<? if($expertise['pause_reason_id_5']!=1): ?>
					<div class="form-group">
						<label>Основание:</label><br>
						<a <? if($expertise['pause_reason_id_5']==2 OR $expertise['pause_reason_id_5']==3 OR $expertise['pause_reason_id_5']==5): ?> href="/documents/viewdoc?id=<?=$expertise['pause_reason_basis_5'];?>" <?else: ?> href="/correspondence/income?id=<?=$expertise['pause_reason_basis_5'];?>" <? endif; ?> class="btn btn-success btn-sm" target="_blank">ОСНОВАНИЕ</a>
					</div>
					<? endif; ?>
					<input type="hidden" name="pause_reason_basis_5" value="<?=$expertise['pause_reason_basis_5'];?>">
					<div class="form-group">
						<label>Дата возобновления:</label>
						<input type="text" readonly class="form-control" value="<?=date('d.m.Y', strtotime($expertise['renewal_date_5']));?>">
						<input type="hidden" name="renewal_date_5" value="<?=$expertise['renewal_date_5'];?>">
					</div>
					<!--<div class="form-group add_more_wrapper" style="text-align: right;">
						<button type="button" class="btn btn-primary add_more" id="add_more_5">Добавить еще</button>
					</div>-->
				<? elseif($expertise['pause_reason_id_4']!=0):?>
					<div class="form-group">
						<label>Причина приостоновления:</label>
						<select name="pause_reason_id_5" id="pause_reason_id_5" class="form-control pause_reasons" form="execute_form">
							<option value="0">Выберите</option>
							<? foreach($pause_reasons as $var): ?>
								<option value="<?=$var['id'];?>"><?=$var['name'];?></option>
							<? endforeach; ?>
						</select>	
					</div>
					<div class="form-group pause_reason_basis_wrapper_5">
						<label>Основание:</label>
						<input type="text" class="form-control pause_reason_basises">
						<input type="hidden" name="pause_reason_basis_5">
						<div class="pause_reason_hints"></div>
					</div>
					<div class="form-group" id="renewal_date_wrapper_5">
						<label>Дата возобновления:</label>
						<input readonly="true" placeholder="Выберите дату" type="text" class="form-control" id="renewal_date_5" name="renewal_date_5">
					</div>
					<div class="form-group" id="save_5" style="display:none;">
						<button type="submit" name="save_before_btn" class="btn btn-info">Сохранить</button>
					</div>
				<? endif; ?>


				
				<div id="outcome_wrapper" style="border:1px solid #ccc; margin:5px 0; padding:15px; border-radius: 4px; margin-top:15px; ">
					<h4 style="background:#fff; margin-top:-10px; margin-left:auto; margin-right:auto; text-align: center;">Ходатайство института о продлении</h4>
					<div class="form-group">
						<label>№ исходящего документа:</label>
						<input autocomplete="off" type="text" class="form-control" style="width:385px !important;" id="renewal_outcome_num">
						<input type="text" class="form-control" style="display: none;" name="renewal_outcome_num">
						<div id="renewal_outcome_nums" class="form-group" style="position:absolute; margin-top:5px; background:#fff; width:385px !important; border:1px solid #ccc; width:100%;"></div>
					</div>
				</div>
				<div id="income_wrapper" style="border:1px solid #ccc; margin:5px 0; padding:15px; border-radius: 4px; margin-top:15px;">
					<h4 style="background:#fff; margin-top:-10px; margin-left:auto; margin-right:auto; text-align: center;">Согласие органа на продление срока</h4>
					<div class="form-group">
						<label>№ входящего документа:</label>
						<input autocomplete="off" type="text" class="form-control" style="width:385px !important;" id="renewal_income_num">
						<input type="text" class="form-control" style="display: none;" name="renewal_income_num">
						<div id="renewal_income_nums" class="form-group" style="position:absolute; margin-top:5px; background:#fff; width:385px !important; border:1px solid #ccc; width:100%;"></div>
					</div>
				</div>
				<div id="outcome_wrapper2" style="border:1px solid #ccc; margin:5px 0; padding:15px; border-radius: 4px; margin-top:15px; display:none ">
					<h4 style="background:#fff; margin-top:-10px; margin-left:auto; margin-right:auto; text-align: center;">Ходатайство института о продлении</h4>
					<div class="form-group">
						<label>Исходящий документ:</label><br>
						<a target="_blank" id="outcome_link" class="btn btn-success">Просмотр</a>
					</div>
				</div>
				<div id="income_wrapper2" style="border:1px solid #ccc; margin:5px 0; padding:15px; border-radius: 4px; margin-top:15px; display:none">
					<h4 style="background:#fff; margin-top:-10px; margin-left:auto; margin-right:auto; text-align: center;">Согласие органа на продление срока</h4>
					<div class="form-group">
						<label>Входящий документ:</label><br>
						<a target="_blank" id="income_link" class="btn btn-success">Просмотр</a>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" style="display: none;" class="btn btn-info" name="save_before_btn">Сохранить</button>
				</div>
				<div class="form-group">
					<label>Дата завершения исследования:</label>
					<input readonly="true" placeholder="Выберите дату" type="text" class="form-control" id="study_end_date" name="study_end_date">
				</div>
				<div class="form-group">
					<label>Фактическое количество дней нахождения материалов в территориальном подразделении:</label>
					<input type="text" class="form-control" name="days_difference">
				</div>
				<div class="form-group">
					<label>Количество дней в производстве:</label>
					<input type="text" class="form-control" name="days_sum">
				</div>
			</div>
			<div class="col-lg-6">
				
				<div class="form-group">
					<label>Результат исследования:</label>
					<select name="result_id" class="form-control" form="execute_form">
						<option>Выберите</option>
						<? foreach($results as $var): ?>
							<option value="<?=$var['id'];?>"><?=$var['name'];?></option>
						<? endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label>Категорические выводы:</label>
					<input type="text" class="form-control" name="cat_conclusions">
				</div>
				<div class="form-group">
					<label>Вероятные выводы:</label>
					<input type="text" class="form-control" name="prob_conclusions">
				</div>
				<div class="form-group">
					<label>Не представилось возможным:</label>
					<input type="text" class="form-control" name="nvp">
				</div>
				<div class="form-group">
					<label>Причины возврата без исполнения:</label>
					<textarea class="form-control" name="reason_for_return" style="resize: none;"></textarea>
				</div>
				<div class="form-group">
					<label>Причины СНДЗ:</label>
					<textarea class="form-control" name="reason_sndz" style="resize: none;"></textarea>
				</div>
				<div class="form-group">
					<label>Стоимость исследования:</label>
					<input type="text" class="form-control" name="expertise_price">
				</div>
				<? if($expertise['deal_category_id']!=1): ?>
					<div style="border:1px solid #ccc; margin:5px 0; padding:15px; border-radius: 4px; margin-top:15px; text-align: center;">
						<h4 style="background:#fff; margin-top:-10px; margin-left:auto; margin-right:auto; text-align: center;">Отметка об оплате</h4>
						<div class="form-group">
							<label>Вид документа:</label>
							<select name="document_type" class="form-control" style="width:450px !important; margin:0px auto;" form="execute_form">
							<? foreach($exp_document_types as $var): ?>
								<option value="<?=$var['id'];?>"><?=$var['name'];?></option>
							<? endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label>Номер документа:</label>
							<input type="text" class="form-control" style="width:450px !important; margin:0px auto;" name="payment_note_num">
						</div>
						<div class="form-group">
							<label>Дата документа:</label>
							<input readonly="true" placeholder="Выберите дату" type="text" id="payment_note_date" class="form-control" style="width:450px !important; margin:0px auto;" name="payment_note_date">
						</div>
					</div>
				<? endif; ?>
				<? if($expertise['status_id']=='3'): ?>
					<div class="form-group">
						<label>Примечание:</label>
						<textarea name="annotation" class="form-control" style="resize:none; height:70px;"></textarea>
					</div>
				<? endif; ?>
				<div class="group-form">
					<button type="submit" name="save_exp_btn" class="btn btn-primary">Сохранить</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="send_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" style="width:100%; margin:0px;" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Выберите получателя</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
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
					<? $i=1; ?>	
				<?foreach($experts as $var): ?>
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
				</div>
				<div class="form-group" id="search_results">
				</div>
				<div style="text-align: center;"><p style="display:none; margin:5px; color:red;">Выберите получателя</p></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" id="close_btn" data-dismiss="modal">Закрыть</button>
				<button type="button" class="btn btn-primary" id="send_btn">Отправить</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="solicitation-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Формирование ходатайства</h4>
			</div>
			<div class="modal-body">
				
			</div>
			<div class="modal-footer" style="border:0px;">
				<button type="button" class="btn btn-default" id="close_solic_btn" data-dismiss="modal">Закрыть</button>
				<button type="button" class="btn btn-success" id="send_solic_btn">Сформировать</button>
			</div>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
	$(document).ready(function(){
		var x = 0;
		var exp_id = getURLParameter('id');
		var pause_reasons_arr = [];
		var formSelected = false;
		reasons_selected_brd = [];
		<? foreach($selected_reasons as $var): ?>
		pause_reasons_arr.push(<?=$var;?>);
		<? endforeach; ?>
		var len = parseInt(pause_reasons_arr.length);
		if(len!=0){
			$.each(pause_reasons_arr, function(i,v){
				$('.pause_reasons>option[value='+v+']').remove();
			});
		}
		reason_counter = parseInt(pause_reasons_arr.length)+1;
		var hod_index = $.inArray(1, pause_reasons_arr);
		if(hod_index>-1){
			$.ajax({
				url : "/actions2/getincomeoutcome",
				data : {exp_id: <?=$_GET['id'];?>},
				type : "POST",
				dataType : "json",
				success : function(response){
					var inc = response['income'];
					var out = response['outcome'];
					$('#income_wrapper2').show();
					$('#outcome_wrapper2').show();
					$('#outcome_link').attr('href', '/correspondence/outcome?view='+out);
					$('#income_link').attr('href', '/correspondence/income?id='+inc);
					$('input[type=text][name=renewal_outcome_num]').val(out);
					$('input[type=text][name=renewal_income_num]').val(inc);
				}
			});
			
		}
		console.log(pause_reasons_arr);
		console.log(reason_counter);
		if($('#deadline').val()==''){
			$('#deadline').datepicker($.extend({
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
		}
		
		if($('#pause_date').val()==''){
	       	$('#pause_date').datepicker($.extend({
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
	    }
	    if($('#renewal_date').val()==''){
	       	$('#renewal_date').datepicker($.extend({
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
	    }
	    if($('#renewal_date_2').val()==''){
	       	$('#renewal_date_2').datepicker($.extend({
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
	    }
	    if($('#renewal_date_3').val()==''){
	       	$('#renewal_date_3').datepicker($.extend({
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
	    }
	    if($('#renewal_date_4').val()==''){
	       	$('#renewal_date_4').datepicker($.extend({
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
	    }
	    if($('#renewal_date_5').val()==''){
	       	$('#renewal_date_5').datepicker($.extend({
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
	    }

       	$('#payment_note_date, #study_end_date').datepicker($.extend({
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
       	$('.pause_reasons').on('change', function(){
       		if($(this).val()!='0'){
       			var reason_id = $(this).attr('name');
       			reason_id = reason_id.replace('pause_reason_id_', '');
       			$('#save_'+reason_id).slideDown();
       			$('.pause_reason_basis_wrapper_'+reason_id).show();
       			$('#renewal_date_wrapper_'+reason_id).show();
       		}
       	});

       	
       	


		$('input[name=questions]').numeric();
		$('input[name=objects]').numeric();

		$('#execute_btn').click(function(){
			var executor_id = <?=Auth::instance()->get_user()->id; ?>;
			var expertise_id = <?=(int)$_GET['id'];?>;
			$.ajax({
				url : "/actions/setexperttoexp",
				type : "POST",
				data : {exp_id: expertise_id, expert_id : executor_id},
				success : function(data){
					$('#main-content2').slideDown();
				}
			});
		});

		$('#pause_reason_id').on('change', function(){
			var pause_reason_id = $(this).val();
			if(pause_reason_id!='0'){
				$('#renewal_date_wrapper').slideDown();
				$('.add_more').show();
				if(pause_reason_id!='0'&&pause_reason_id=='1'&&formSelected==false){
					$('#pause_reason_id').after("<button style='margin-top:10px;' type='button' class='btn btn-info' id='form'>Сформировать</button>&nbsp;<button style='margin-top:10px;' id='choose_btn' type='button' class='btn btn-info'>Выбрать</button>");
					$('#outcome_wrapper').slideDown();
					$('#income_wrapper').slideDown();
					$('.pause_reason_basis_wrapper').slideUp();

					$('#form').click( function(){
						$.ajax({
							url : "/actions/getform",
							data : { go : "go", exp_id: exp_id},
							type : "POST",
							success : function(data){
								$('#solicitation-modal .modal-body').html(data);
								$('#solicitation-modal').modal('show');
								if(CKEDITOR.instances['brd_text']){
									delete CKEDITOR.instances['brd_text'];
									CKEDITOR.replace('brd_text');
								}
								else{
									CKEDITOR.replace('brd_text');
								}
								formSelected = true;
							}
						});
					});
					$('#choose_btn').click(function(){
						$('#renewal_outcome_num').focus();
						$('html,body').animate({
							scrollTop : $('#renewal_outcome_num').offset().top
						}, 'slow');
					});
				}
				else{
					$('#outcome_wrapper').slideUp();
					$('#income_wrapper').slideUp();
					$('#form').remove();
					$('#choose_btn').remove();
					$('.pause_reason_basis_wrapper').slideDown();
					$('#qqq').show();
				}
			}
			else{
				$('.pause_reason_basis_wrapper').slideUp();
				$('#outcome_wrapper').slideUp();
				$('#income_wrapper').slideUp();
				$('#form').remove();
				$('#choose_btn').remove();
			}
		});

		$('div').click(function(){
			$('#renewal_outcome_nums').slideUp();
			$('#renewal_outcome_nums').html('');
			$('#renewal_income_nums').slideUp();
			$('#renewal_income_nums').html('');
			$('.pause_reason_hints').slideUp();
			$('.pause_reason_hints').html('');
		});



		$('#renewal_income_num').keyup(throttle(function(){
			var req = $(this).val();
			if(req.trim()!=''){
				$.ajax({
					url : "/actions/getallincomesforexp",
					type : "POST",
					data : {req: req},
					success : function(data){
						$('#renewal_income_nums').html(data);
						$('#renewal_income_nums').slideDown();

					}
				})
				
			}
		}));

		$('#renewal_outcome_num').keyup(throttle(function(){
			var req = $(this).val();
			if(req.trim()!=''){
				$.ajax({
					url : "/actions/getalloutcomesforexp",
					type : "POST",
					data : {req: req},
					success : function(data){
						$('#renewal_outcome_nums').html(data);
						$('#renewal_outcome_nums').slideDown();
					}
				})
				
			}
		}));

		
		$('#send_btn').click(function(){
			$('input[type=checkbox]:checked').each(function(){
				var receiver_id = $(this).val();
				alert(receiver_id)
				$.ajax({
				url : "/actions/sendexptoreceivers2",
				type : "POST",
				data : {exp_id : exp_id, author_id: <?=Auth::instance()->get_user()->id;?>, receiver_id: receiver_id},
					success : function(data){
						$('#info').html("<p style='color:green;'>Экспертиза успешно отправлена на производство</p>");
					}
				});
			});
		});

		$('.add_more').click(function(e){
			if($('#pause_reason_id').val()!='0'){
				$('.pause_reasons').each(function(){
					$(this).css('border', '1px solid #ccc');
				});
				e.preventDefault();
				var id = $(this).attr('id');
				var id = id.replace('add_more_', '');
				reason_counter = parseInt(id);

				
				if(id=='2'){
					pause_reasons_arr.push($('#pause_reason_id').val());
				}
				else{
					pause_reasons_arr.push($('#pause_reason_id_'+(parseInt(id)-1)).val());
				}
				$('.add_more').hide();
				console.log(pause_reasons_arr);
				$.ajax({
					url : "/actions/addmoreexpertisereason/",
					type : "POST",
					data : {add: 'add', id: id, pause_reasons_arr: pause_reasons_arr},
					success : function(data){
						$('.add_more_wrapper').before(data);
						$('#add_more_'+id).attr('id', "add_more_"+(parseInt(id)+1));
						if(parseInt(id)==5){
							$('.add_more').remove();
						}
					}
				});
			}
			else{
				if(id=='2'){
					$('#pause_reason_id').css('border', '1px solid red');
				}
				else{

				}
			}
		});
		$('.pause_reason_basises').keyup(throttle(function(){
			var req = $(this).val();
			if(parseInt(reason_counter)==0){
				var reason_id = $('#pause_reason_id').val();
			}
			else{
				var reason_id = $('#pause_reason_id_'+reason_counter).val();

			}
			
			if(req.trim()!=''){
				if(reason_id=='4'){
					$.ajax({
						url : "/actions/getincomesforexp",
						data : {req: req},
						type : "POST",
						success : function(data){
							$('.pause_reason_hints').html(data);
							$('.pause_reason_hints').slideDown();
						}
					});
				}
				else{
					$.ajax({
						url : "/actions/getdocsbyreq",
						type : "POST",
						data : {req: req, reasons_selected_brd: JSON.stringify(reasons_selected_brd)},
						success : function(data){
							$('.pause_reason_hints').html(data);
							$('.pause_reason_hints').slideDown();
						}
					});
				}
			}
		}));
		$('.pause_reason_hints').css('width', $('.pause_reason_basises').width()+26+'px');

		$('#send_solic_btn').click(function(){
			var subject = $('input[name=subject]').val();
			var folder_id = $('select[name=folder_id]').val();
			var nom_code = $('input[name=nom_code]').val();
			var brd_text = $('#brd_text').html();
			$('#solicitation-modal .modal-footer').html('');
			$.ajax({
				url : "/actions/createform",
				type : "POST",
				data : {subject: subject, folder_id: folder_id, nom_code: nom_code, brd_text: brd_text, author_id : <?=Auth::instance()->get_user()->id; ?>},
				success : function(data){
					var brd_id = parseInt(data);
					$.ajax({
						url : "/actions/viewdocforexp",
						type : "POST",
						data : {brd_id: brd_id, author_id: <?=Auth::instance()->get_user()->id; ?>},
						success : function(data){
							$('#solicitation-modal .modal-title').html("Обзор документа | РГКП 'Центр Судебных Экспертиз'");
							$('#solicitation-modal .modal-body').html(data);
						}
					})
				}
			});
		});
		$('#pause_reason_id_'+reason_counter).on('change', function(){
				var pause_reason_id = $(this).val();
				if(pause_reason_id!='0'){
					$('#renewal_date_wrapper_'+reason_counter).slideDown();
					if(reason_counter<5){
						$('.add_more').show();
					}
					if(pause_reason_id!='0'&&pause_reason_id=='1'&&formSelected==false){
						$('#outcome_wrapper').slideDown();
						$('#income_wrapper').slideDown();
						$('.pause_reason_basis_wrapper_'+reason_counter).hide();
						$('#save_'+reason_counter).hide();
						$('#pause_reason_id_'+reason_counter).after("<button style='margin-top:10px;' type='button' class='btn btn-info' id='form'>Сформировать</button>&nbsp;<button style='margin-top:10px;' id='choose_btn' type='button' class='btn btn-info'>Выбрать</button>");
						$('#form').click(function(){
							$.ajax({
								url : "/actions/getform",
								data : { go : "go", exp_id: exp_id},
								type : "POST",
								success : function(data){
									$('#solicitation-modal .modal-body').html(data);
									$('#solicitation-modal').modal('show');
									if(CKEDITOR.instances['brd_text']){
										delete CKEDITOR.instances['brd_text'];
										CKEDITOR.replace('brd_text');
									}
									else{
										CKEDITOR.replace('brd_text');
									}
									formSelected = true;
								}
							});
						});
						$('#choose_btn').click(function(){
							$('#renewal_outcome_num').focus();
							$('html,body').animate({
								scrollTop : $('#renewal_outcome_num').offset().top
							}, 'slow');
						});
					}
					else{
						$('#pause_reason_id_' + reason_counter + ' + #form').remove();
						$('#pause_reason_id_' + reason_counter + ' + #choose_btn').remove();
						$('.pause_reason_basis_wrapper_'+reason_counter).slideDown();
					}
				}
				else{
					$('.pause_reason_basis_wrapper_'+reason_counter).show();
					$('#form').remove();
					$('#choose_btn').remove();
				}
			});

		$(document).ajaxComplete(function(){
			
			$('#pause_reason_id_'+reason_counter).on('change', function(){
				var pause_reason_id = $(this).val();
				if(pause_reason_id!='0'){
					$('#renewal_date_wrapper_'+reason_counter).slideDown();
					if(reason_counter<5){
						$('.add_more').show();
					}
					if(pause_reason_id!='0'&&pause_reason_id=='1'&&formSelected==false){
						$('#outcome_wrapper').slideDown();
						$('#income_wrapper').slideDown();
						$('#pause_reason_id_'+reason_counter).after("<button style='margin-top:10px;' type='button' class='btn btn-info' id='form'>Сформировать</button>&nbsp;<button style='margin-top:10px;' id='choose_btn' type='button' class='btn btn-info'>Выбрать</button>");
						$('#form').click(function(){
							$.ajax({
								url : "/actions/getform",
								data : { go : "go", exp_id: exp_id},
								type : "POST",
								success : function(data){
									$('#solicitation-modal .modal-body').html(data);
									$('#solicitation-modal').modal('show');
									if(CKEDITOR.instances['brd_text']){
										delete CKEDITOR.instances['brd_text'];
										CKEDITOR.replace('brd_text');
									}
									else{
										CKEDITOR.replace('brd_text');
									}
									formSelected = true;
								}
							});
						});
						$('#choose_btn').click(function(){
							$('#renewal_outcome_num').focus();
							$('html,body').animate({
								scrollTop : $('#renewal_outcome_num').offset().top
							}, 'slow');
						});
					}
					else{
						$('#pause_reason_id_' + reason_counter + ' + #form').remove();
						$('#pause_reason_id_' + reason_counter + ' + #choose_btn').remove();
						$('.pause_reason_basis_wrapper_'+reason_counter).slideDown();
					}
				}
				else{
					$('.pause_reason_basis_wrapper_'+reason_counter).show();
					$('#form').remove();
					$('#choose_btn').remove();
				}
			});
			$('#pause_reason_basis_'+reason_counter).keyup(throttle(function(){
				var req = $(this).val();
				var reason_id = $('#pause_reason_id_'+reason_counter).val();
				if(req.trim()!=''){
					if(reason_id=='4'){
						$.ajax({
							url : "/actions/getincomesforexp",
								data : {req: req},
								type : "POST",
								success : function(data){
									$('#pause_reason_hint_'+reason_counter).html(data);
									$('#pause_reason_hint_'+reason_counter).slideDown();
								}
						});
					}
					else{
						$.ajax({
							url : "/actions/getdocsbyreq",
							type : "POST",
							data : {req: req, reasons_selected_brd: JSON.stringify(reasons_selected_brd)},
							success : function(data){
								$('#pause_reason_hint_'+reason_counter).html(data);
								$('#pause_reason_hint_'+reason_counter).slideDown();
							}
						});
					}
				}
			}));

			$('.docs_for_select > li').click(function(){
				var doc_register_num = $(this).html();
				var doc_id = $(this).attr('id');
				doc_id = doc_id.replace('doc_id_', '');
				reasons_selected_brd.push(doc_id);
				$(this).parent().parent().prev().prev('input[type=text]').val(doc_register_num);
				$(this).parent().parent().prev('input[type=hidden]').val(doc_id);
				$(this).parent().parent().slideUp().html('');
			});

			$('.incomes_for_select > li').click(function(){
				var income_register_num = $(this).html();
				var income_id = $(this).attr('id');
				income_id = income_id.replace('income_id_', '');
				$(this).parent().parent().prev().prev('input[type=text]').val(income_register_num);
				$(this).parent().parent().prev('input[type=hidden]').val(income_id);
				$(this).parent().parent().slideUp().html('');

			});


			$('.pause_reason_hints').css('width', $('.pause_reason_basises').width()+26+'px');
			$('.income_for_select > li').click(function(){
				var income_register_num = $(this).html();
				var income_id = $(this).attr('class');
				income_id = income_id.replace('income_id_', '');
				$('#renewal_income_num').val(income_register_num);
				$('input[name=renewal_income_num]').val(income_id);
				$('#renewal_income_nums').slideUp();
				$('#renewal_income_nums').html('');
				$('button[type=submit][name=save_before_btn]').show();
			});

			$('.outcome_for_select > li').click(function(){
				var outcome_register_num = $(this).html();
				var outcome_id = $(this).attr('class');
				outcome_id = outcome_id.replace('outcome_id_', '');
				$('#renewal_outcome_num').val(outcome_register_num);
				$('input[name=renewal_outcome_num]').val(outcome_id);
				if(parseInt(reason_counter)==0){
					$('input[name=pause_reason_basis]').val(outcome_id);
				}
				else{
					$('input[name=pause_reason_basis_'+reason_counter+']').val(outcome_id);
				}
				
				$('#renewal_outcome_nums').slideUp();
				$('#renewal_outcome_nums').html('');
				$('button[type=submit][name=save_before_btn]').show();
			});

			

			$('#solicitation-modal .modal-dialog').css('height', $(window).height()+100+"px");
			$('#renewal_date_2, #renewal_date_3, #renewal_date_4, #renewal_date_5').datepicker($.extend({
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
			$('#brd_nom').on('change', function(){
				var x = parseInt($('#brd_nom').val());
				if(x!=0){
					$('#brd_nom option[value=0]').attr('disabled', 'disabled');
					$.ajax({
						url : "/actions/getbrdnomcode",
						data : {brd_nom_id: x},
						type : "POST",
						success : function(data){
							$('#nom_code').val(data);
						}
					});
				}
			});

			

		});
		function getURLParameter(name) {
			return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
		}

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
<style>
	<? if($expert_id==0): ?>
	#main-content2{ /*display:none;*/ }
	<? endif; ?>
	#save_before_btn{ margin-top:10px; }
	.add_more, #income_wrapper, #outcome_wrapper{ display:none; }
	.modal-content { border-radius: 0px; height: 100%; }
	.modal-dialog { width:100%; margin:0; height: 100%; }
	.in{ padding-right:0px !important; }
	#renewal_income_nums, #renewal_outcome_nums { display: none; }
	#main-content, #main-content2{
		margin:40px auto;
	}
	.col-lg-5, .col-lg-4{
		margin: 5px 0;
		border-bottom: 1px solid #ccc;
		padding-bottom:5px;
		height:45px;
	}
	.col-lg-7, .col-lg-8{
		margin: 5px 0;
		border-bottom: 1px solid #ccc;
		padding-bottom:5px;
		height:45px;
	}
	#agency_case{
		display:none;
	}
	.income_for_select, .outcome_for_select{
		list-style: none;
		padding-left:0;
		margin:0px;
	}
	.income_for_select > li, .outcome_for_select > li{
		border-bottom:1px solid #ccc;
		padding:5px;
		cursor: pointer;
	}
	.income_for_select > li:hover, .outcome_for_select > li:hover{
		background: #ccc;
	}
	.income_for_select > li:nth-last-child(1), .outcome_for_select > li:nth-last-child(1){
		border:0px;
	}
	.pause_reason_basis_wrapper, .pause_reason_basis_wrapper_1,.pause_reason_basis_wrapper_2,.pause_reason_basis_wrapper_3,.pause_reason_basis_wrapper_4, .pause_reason_basis_wrapper_5{
		display:none;
	}
	#renewal_date_wrapper,#renewal_date_wrapper_2, #renewal_date_wrapper_3, #renewal_date_wrapper_4, #renewal_date_wrapper_5{
		display:none;
	} 
	.pause_reason_hints{
		margin-top:5px;
		width:inherit;
		border:1px solid #ccc;
		background:#fff;
		position:absolute;
		display: none;
	}
	.docs_for_select, .incomes_for_select{
		list-style: none;
		padding-left:0;
		margin-bottom:0;
	}
	.docs_for_select > li, .incomes_for_select > li{
		border-bottom:1px solid #ccc;
		padding:5px;
		cursor: pointer;
	}
	.docs_for_select > li:nth-last-child(1), .incomes_for_select > li:nth-last-child(1),{
		border:0px;
	}
	.docs_for_select > li:hover, .incomes_for_select > li:hover{
		background:#ccc;
	}
</style>