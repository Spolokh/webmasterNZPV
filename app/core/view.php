<?php

class View
{
	function render($content, $template, $data = null)
	{
		if(is_array($data))
		{
			extract($data); // преобразуем элементы массива в переменные
		}
		
		include_once VIEW_PATH .'/'. $template;
	}
}
