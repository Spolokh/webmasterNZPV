<!DOCTYPE html>
<html lang="ru">
  	<head>
		<title><?=$data['title']?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- CSS -->
		<link rel="stylesheet" href="/css/minified.css.php"/>
		
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<![endif]-->

		<!-- Fav and touch icons -->
		<link rel="shortcut icon" href="/ico/favicon.png">
  	</head>
  	<body>

    <!--Part 1: Wrap all page content here-->
    <!--div id="wrap"-->
    <!--Fixed navbar-->
      	<div class="navbar">
        	<div class="navbar-inner">
          		<div class="container">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href="/">Webmaster.nzpv.ru</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li><a href="/">Главная</a></li>
							<li><a href="/users">Люди</a></li>
							<li><a href="/contacts">Контакты</a></li>
						</ul>
						
						<ul class="nav pull-right">
						<?php if ($login) : ?>
							<li><a href=""><?=$login ?></a></li>
							<li><a href="?exit">Выйти</a></li>	
						<?php else: ?>	
							<li><a href="#myModal" data-toggle="modal"><i class="fa fa-sign-in" aria-hidden="true"></i> Вход</a></li>
							<li><a href="/registration">Регистрация</a></li>	
						<?php endif; ?>
						</ul>
					</div><!--/.nav-collapse -->
          		</div>
        	</div>
      	</div>

      	<!-- Begin page content -->
      	<div id="content" class="container">
			<?php include VIEW_PATH .DIRECTORY_SEPARATOR. $content; ?>
		</div>
					 
		<div id="footer"></div>

		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-sm">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Авторизация</h4>
					</div>
					<div class="modal-body">
						<form method="POST" id="Authorize" class="modal-form">                                                                    
							<input name="username" type="text" class="span3" placeholder="Ваша логин" required />
							<input name="password" type="password" class="span3" placeholder="Ваш пароль" required />
							<input name="action" type="hidden" value="authorize" />
						</form>
						<a href="/registration">Регистрация</a>
					</div>
					<div class="modal-footer">
						<button type="submit" form="Authorize" class="btn btn-default"> Войти </button>
						<button type="button" class="btn btn-default" data-dismiss="modal"> Закрыть </button>
					</div>
				</div>
			</div>
		</div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
		<script src="/js/jquery.js"></script>
		<script src="/js/jquery.maskedinput.min.js"></script>
		<script src="/js/bootstrap-transition.js"></script>
		<script src="/js/bootstrap-alert.js"></script>
		<script src="/js/bootstrap-modal.js"></script>
		<script src="/js/bootstrap-dropdown.js"></script>
		<script src="/js/bootstrap-scrollspy.js"></script>
		<script src="/js/bootstrap-tab.js"></script>
		<script src="/js/bootstrap-button.js"></script>
		<script src="/js/functions.js"></script>
		<script src="/js/script.js"></script>
  	</body>
</html>
