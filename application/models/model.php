
<?php
	class Model extends CI_Model {

		function selectAll() {
			$query = $this->db->query("SELECT * FROM EXAMPLE");
			return $query->result_array();

		}


		function getPupil($login, $hash) {
			$query = $this->db->query("SELECT p.PUPIL_LOGIN, p.PUPIL_HASH, p.ROLE_ID, p.PUPIL_STATUS, p.PUPIL_ID, pc.CLASS_ID
			FROM PUPIL p JOIN PUPILS_CLASS pc ON p.PUPIL_ID = pc.PUPIL_ID JOIN CLASS c ON c.CLASS_ID = pc.CLASS_ID
			WHERE PUPIL_LOGIN collate utf8_bin = '$login' AND PUPIL_HASH = '$hash' AND CLASS_STATUS = 1");
			return $query->row_array();
		}


	}