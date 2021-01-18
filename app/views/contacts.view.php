
	<h3><?=$title ?></h3>

	<form id="contactForm" action="/ajax/contact" method="POST">
		<div class="form-group">
			<input type="text" class="span5" name="name" placeholder="Ваше имя" required />
		</div>
		
		<div class="form-group">
			<input type="email" class="span5" name="mail" placeholder="Ваша почта" required />
		</div>
		
		<div class="form-group">
			<input type="text" class="span5" name="subject" placeholder="Тема письма" required />
		</div>

		<div class="form-group">
			<textarea class="span5" name="message" placeholder="Сообщение" required></textarea>
		</div>

		<div class="form-group">
			<label class="option">
				<input type="checkbox" name="check" class="checkdelTask" value="1"/>
				<span class="checkbox"></span> &nbsp; Прислать копию ?
			</label>
		</div>

		<br/>
		<input type="hidden" name="action" value="contact" />
		<input type="hidden" name="sessid" value="<?=session_id()?>" />
		<button type="submit" class="btn btn-primary">Отправить</button>
		<button type="reset"  class="btn btn-primary">Очистить</button>
		<ul id="result"></ul>
	</form>
