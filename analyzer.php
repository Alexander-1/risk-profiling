<?php

	$data = array(
		'CatMbship' => array(
			array(1, 0.5, 0, 0.5, 1),
			array(1, 0.5, 0, 0.5, 1),
			array(0, 0, 0, 0.5, 1),
			array(1, 0.5, 0, 0.5, 1),
			array(0, 0, 0, 0.5, 1)
		),
		'IPMbship' => array(0, 0.33333333, 0.16666666667, 0, 0.5),
		'RedFlag' => array(false, true, false, true, false),
		'Questions' => array(array(), array(1, 5), array(), array(3, 9), array())
	);
	die(json_encode($data));