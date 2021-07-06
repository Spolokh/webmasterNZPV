
registerListener('load', setLazy);
registerListener('load', lazyLoad);
registerListener('scroll', lazyLoad);

jQuery(function($) 
{
	$('#registration').submit(function(e)
	{
		var form = new FormData(this);
		var ajax = getXmlHttp();
		var post = e.target.method;
		var path = this.getAttribute('action');

		var click = $(this).find('input[type="submit"]');
			click.attr('disabled', true);

		ajax.onload = function()
		{
			if ( this.status == 200 )
			{
				$('#result').html(this.responseText);
				$('input[type="reset"]').click();
			}

			if ( this.status == 500 )
			{
				$('#result').html(this.responseText);
			}
			click.attr('disabled', null);
		};

		ajax.onerror = function()
		{
		};

		ajax.open (post, path); 
		ajax.setRequestHeader ('Cache-Control', 'no-cache');
		ajax.setRequestHeader ('X-Requested-With', 'XMLHttpRequest');
		ajax.send(form); e.preventDefault();
	});

	$('#addPhone').submit(function(e)
	{
		var json;
		var form = new FormData(this);
		var ajax = getXmlHttp();
		var post = e.target.method;
		var click = $(this).find('button[type="submit"]');
			click.attr('disabled', true);

		ajax.onreadystatechange = function()
		{
			if ( this.readyState == 4 )
			{
				if ( this.status == 200 )
				{
					json = JSON.parse(this.responseText);	//var json = this.response;
					jsonParse(json);
					$('button[type="reset"]').click();
				}

				if ( this.status == 500 )
				{
					alert(this.responseText);
				}
				click.attr('disabled', null);
			}
		};

		ajax.open(post, '/ajax/addphone'); 
		ajax.setRequestHeader('Cache-Control', 'no-cache');
		ajax.setRequestHeader ('X-Requested-With', 'XMLHttpRequest');
		ajax.send(form); e.preventDefault();
	});

	$('#editPhone').submit (function(e)
	{
		var ajax = getXmlHttp();
		var form = new FormData(this);
		var task = $('input.checkdelTask:checked');

		if ( !task || typeof task === "undefined" ) {
			alert('Not checked');
		}

		var click = $(this).find('button[type="submit"]');
			click.text('Wait...').attr('disabled', true);

		if ( !confirm('Вы действительно хотите удалить выбранное?') )
		{
			return;
		}

		ajax.onreadystatechange = function()
		{
			if ( this.readyState == 4 )
			{
				if ( this.status == 200 )
				{
					task.parent().parent().parent().hide();
				}
				if ( this.status == 500 )
				{
					alert(this.responseText);
				}

				click.text('Удалить').attr('disabled', null);
			}
		};

		ajax.open('POST', '/ajax/delbook'); 
		ajax.setRequestHeader('Cache-Control', 'no-cache');
		ajax.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		ajax.send(form); e.preventDefault();
	});

	$('#contactForm').submit (function(e)
	{
		var ajax = getXmlHttp();
		var form = new FormData(this);
		var post = this.getAttribute('method');
		var file = this.getAttribute('action');
		var click = $(this).find('button[type="submit"]')
			.attr('disabled', true);

		ajax.onload = function()
		{
			if ( this.status == 200 )
			{
				$('#result').html(this.responseText);
				$('button[type="reset"]').click();
			}
			if ( this.status == 500 )
			{
				alert(this.responseText);
			}

			click.attr('disabled', null);
		};

		ajax.open(post, file); 
		ajax.setRequestHeader('Cache-Control', 'no-cache');
		ajax.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		ajax.send(form); e.preventDefault();
	});

	$('#myTab a').on('click', function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	$('#xhrField').on('change', function()
	{
		uploadImage(this.files[0], this, 7);
	});

	$('input[type="tel"]').mask('+7 (999) 999-99-99');
});
