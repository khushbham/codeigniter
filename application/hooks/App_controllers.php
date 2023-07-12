<?php

function laad_extra_app_controllers()
{
	spl_autoload_register(function ($class) {
		if (strpos($class, 'CI_') !== 0 && strpos($class, 'MY_') !== 0)
		{
			if (is_readable(APPPATH . 'core/' . $class . '.php'))
			{
				require_once(APPPATH . 'core/' . $class . '.php');
			}
		}
	});
}
