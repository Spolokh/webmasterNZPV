
<h3>Авторизация</h3>

<?php
if ($login) {

	echo '<a href="">'.$login.'</a> | <a href="?exit">Выйти</a>';
	return;
}
?>

<!--form method="POST" id="authorisation">
  	<div class="form-group">
		<label for="exampleInputEmail1">Login</label>
		<input type="text" name="username" class="form-control"  placeholder="Username" required />
  	</div>
  	<div class="form-group">
		<label for="exampleInputPassword1">Password</label>
		<input type="password" name="password" class="form-control" placeholder="Password" required />
  	</div>
  	<button type="submit" class="btn btn-primary">Submit</button>
</form-->
