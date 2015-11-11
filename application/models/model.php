
<?php
	class Model extends CI_Model {

		function selectAll() {
			$query = $this->db->query("SELECT * FROM EXAMPLE");
			return $query->result_array();

		}

	}