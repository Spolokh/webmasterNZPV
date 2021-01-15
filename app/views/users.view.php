<style>
</style>
<h3><?=$title ?></h3>

<ul id="result"></ul>
 
<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item active" role="presentation">
		<a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Весть список</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link" id="form-tab" data-toggle="tab" href="#form" role="tab" aria-controls="profile" aria-selected="false">Добавить</a>
	</li>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
<?php if (empty($query)) { ?>
		<div class="alert alert-danger alert-dismissible">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    		Записей не найдено !
		</div>
<?php
} 
else 
{
?>
	<form method="POST" id="editPhone">
		<table class="table table-striped table-bordered list">
			<thead>
				<tr>
					<th width="20">#</th>
					<th>Имя</th>
					<th>E-mail</th>
					<th>Телефон</th>
					<th>Фото</th>
					<th width="20"><label class="option">
										<input type="checkbox" name="" value="" />
										<span class="checkbox"></span>
									</label>
					</th>
				</tr>
			</thead>
			<tbody id="tbody">
		<?php foreach ($query->findMany() AS $k => $row) { ?>
				<tr>
					<td><?=$row->id ?></td>
					<td>
						<a href="/users/user?user=<?=$row->id ?>"><?=$row->name ?></a>
					</td>
					<td><?=$row->mail ?></td>
					<td><a href="tel:<?=str_replace(['+', '(', ')', '-', ' '], '', $row->phone) ?>"><?=$row->phone ?></a></td>
					<td>
						<figure>
							<img class="img-circle" loading="lazy" data-src="/img/<?=($row->icon ? $row->icon : 'default.png') ?>" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="<?=$row->name ?>"/>
						</figure>
					</td>
					<td><label class="option">
							<input type="checkbox" name="item[]" class="checkdelTask" value="<?=$row->id ?>" <?=(!$login ? "disabled" : '')?>>
							<span class="checkbox"></span>
						</label>
					</td>
				</tr>
		<?php } ?>
		</tbody>
			<tr>
				<td colspan="2" style="text-align: left;">
					&nbsp;Всего: <?=$query->count(); ?>
				</td>
				<td colspan="4" style="text-align: right;">
				<?php if ($login) { ?>
					<input name="action" type="hidden" value="delete" />
					<button type="submit" class="btn btn-default"> Удалить </button>
				<?php } ?>
				</td>
			</tr>
		</table>
	</form>
<?php } ?>
	</div>

  	<div class="tab-pane fade" id="form" role="tabpanel" aria-labelledby="form-tab">   
		<form method="POST" id="addPhone">																
			<input name="name[]" type="text" class="form-control span5" placeholder="Имя" required /> &nbsp;
			<input name="name[]" type="text" class="form-control span5" placeholder="Фамилия" required />
			<input name="mail" type="email" class="form-control span5" placeholder="E-mail" required /> &nbsp;
			<input name="phone" type="tel" class="form-control span5" placeholder="Телефон" required />
			<input name="icon" type="file" class="form-control span5" />

			<input name="action" type="hidden" value="addbook" /> <br /> <br />
			<button type="submit" class="btn btn-primary">Добавить</button>
			<button type="reset" class="btn btn-primary">Очистить</button>
		</form>
	</div>
	  
</div>