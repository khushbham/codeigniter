<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utilities
{
	public static function array_orderby($data, $sort_key, $sort_order)
	{
		if(!empty($data))
		{
			$sortArray = array();
	
			foreach($data as $thing)
			{
				foreach($thing as $key=>$value)
				{
					if(!isset($sortArray[$key]))
					{
						$sortArray[$key] = array();
					}
					$sortArray[$key][] = $value;
				}
			}
	
			array_multisort($sortArray[$sort_key], $sort_order, $data);
		}
		return $data;
	}
}