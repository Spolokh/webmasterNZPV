<?php 
//use Pagination;
?>


<h3><?=$title ?></h3>

<ul id="result"></ul>
 
<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item active" role="presentation">
		<a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Весть список</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link" id="form-tab" data-toggle="tab" href="#form" role="tab" aria-controls="form" aria-selected="false">Добавить</a>
	</li>
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
					<th width="20">
						<label class="option">
							<input type="checkbox" value="" />
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
					<td><a href="tel:<?=preg_replace('/_|-|\s|\(|\)|/', '', $row->phone) ?>"><?=$row->phone ?></a></td>
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
		<tfoot>
			<tr>
				<td colspan="2" class="text-left">
					&nbsp;Всего: <?=$query->count() ?>
				</td>
				<td colspan="4" class="text-right" style="text-align: right">
				<?php if ($login) { ?>
					<input name="action" type="hidden" value="delete" />
					<button type="submit" class="btn btn-default"> Удалить </button>
				<?php } ?>
				</td>
			</tr>
		</tfoot>
		</table>
	</form>

	<!--div class="pagination pagination-centered">
		<ul>
<?php 
		 
		//$total = ceil($query->count() / 7);

		//for ($i = 1; $i <= $total; $i++) { 
		//	echo '<li><a href="users?skip='.$i.'">'.$i.'</a></li>';
		//};
		 //echo (new Pagination($query->count()))->get(); 
?>
		</ul>
	</div-->

<?php } ?>
	</div>


	<style>
.objectFit
{
	width: 100%;
    height: 100%;
    object-fit: cover;
	-o-object-fit: cover;
}

.labelFile > input[type="file"] {
	width: .1px; 
	height: .1px; 
	opacity: 0; 
	overflow:hidden;
	position:absolute;
	z-index:-1
}

#xhrStatus {
    padding: 4px;
    line-height: 1.5;
	display: inline-block;
}

.labelFile > .icon:before
	{content: "\f093"}
.labelFile > .icon.value:before
	{
		content:"\f00c";
		color: green
	}
</style>

  	<div class="tab-pane fade in" id="form" role="tabpanel" aria-labelledby="form-tab"> 

		<form method="POST" id="addPhone" style="float: left">																
			<input name="name" type="text" class="form-control span5" placeholder="Имя" required /><br />
			<input name="family" type="text" class="form-control span5" placeholder="Фамилия" required /><br />
			<input name="mail" type="email" class="form-control span5" placeholder="E-mail" required /><br />
			<input name="phone" type="tel" class="form-control span5" placeholder="Телефон" required /><br />
			<!--input name="icon" id="xhrField" type="file" accept="image/jpeg, image/png, image/gif" class="form-control span5" /-->
			
			<label for="xhrField" class="btn btn-tertiary labelFile">
				<input name="icon" id="xhrField" type="file" accept="image/jpeg, image/png, image/gif" class="" />
      			<i class="icon fa"></i> 
      			<span class="js-fileName">Загрузить файл</span>
   			</label> 
			<span id="xhrStatus"></span>
			<!--fa-check-->
			<br />

			<input name="sessid" type="hidden" value="<?=session_id() ?>" />  
			<input name="action" type="hidden" value="addbook" /><br />
			<button type="submit" class="btn btn-primary">Добавить</button>
			<button type="reset" class="btn btn-primary">Очистить</button>
		</form>

		<div class="avatar" style="width:185px; height:185px; float: right">
  			<img id="srcImage" class="objectFit img-circle" src="img/default.png" alt="">
		</div>
	</div>	  
</div>
