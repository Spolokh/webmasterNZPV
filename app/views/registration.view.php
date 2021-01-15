<style>
form p.red{
  color: red;
}

form p.green{
  color: green;
}
</style>
			
<h3><?=$title ?></h3>
 
<br clear="both"/>

<form id="registration" method="POST">
  <div class="form-group">
    <label for="exampleInputUsername">Логин:</label>
    <input type="text" class="form-control span4" name="username" maxlength="16" pattern="[a-zA-Z0-9\-]{3,16}" placeholder="Латинские буквы, цифры, до 16 знаков" required />
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Пароль :</label>
    <input type="password" class="form-control span4" name="password" maxlength="16" pattern="[a-zA-Z0-9]{6,16}" placeholder="Разные регистры + цифры, не менее 6 знаков" required />
  </div>

  <div class="form-group">
    <label for="exampleInputPassword2">Пароль (ещё раз) :</label>
    <input type="password" class="form-control span4" name="password2" placeholder="" required />
  </div>

  <div class="form-group">
    <label for="exampleInputEmail">Ваша почта :</label>
    <input type="email" class="form-control span4" name="mail" placeholder="" required />
  </div>

  <br/>
  <input type="hidden" name="action" value="registration" />
  <input type="submit" class="btn btn-primary" value="Регистрация" />
  <input type="reset"  class="btn btn-primary" value=" Очистить " />

  <ul id="result"></ul>
</form>
 


