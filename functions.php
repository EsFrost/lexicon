<?php

	//low level sanitization of data
	function sanitize_input_lowLevel($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

?>