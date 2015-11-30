<?php
	class Model extends CI_Model {

		function selectAll() {
			$query = $this->db->query("SELECT * FROM EXAMPLE");
			return $query->result_array();

		}


		function getPupil($login, $hash) {
			$query = $this->db->query("SELECT p.PUPIL_LOGIN, p.PUPIL_NAME, p.PUPIL_HASH, p.ROLE_ID, p.PUPIL_STATUS, p.PUPIL_ID, pc.CLASS_ID,
			c.CLASS_LETTER, c.CLASS_NUMBER
			FROM PUPIL p JOIN PUPILS_CLASS pc ON p.PUPIL_ID = pc.PUPIL_ID JOIN CLASS c ON c.CLASS_ID = pc.CLASS_ID
			WHERE PUPIL_LOGIN collate utf8_bin = '$login' AND PUPIL_HASH = '$hash' AND CLASS_STATUS = 1");
			return $query->row_array();

		}


		function getTimes()
		{
			$query = $this->db->query("SELECT * FROM TIME ORDER BY TIME_START");
			return $query->result_array();
		}


		function getTimetable($class_id, $day) {
			$query = $this->db->query("SELECT TIMETABLE_ID, sc.SUBJECTS_CLASS_ID, r.ROOM_NAME, s.SUBJECT_NAME, tm.TIME_START, tm.TIME_FINISH, tm.TIME_ID
			FROM TIMETABLE t JOIN SUBJECTS_CLASS sc ON t.SUBJECTS_CLASS_ID = sc.SUBJECTS_CLASS_ID
			LEFT JOIN SUBJECT s ON s.SUBJECT_ID = sc.SUBJECT_ID
			LEFT JOIN ROOM r ON r.ROOM_ID = t.ROOM_ID
			JOIN TIME tm ON tm.TIME_ID = t.TIME_ID
			WHERE t.DAYOFWEEK_ID = '$day' AND sc.CLASS_ID = '$class_id'
			ORDER BY 4");
			return $query->result_array();
		}


		function getSubjectsClass($class_id) {
			$query = $this->db->query("SELECT sc.SUBJECTS_CLASS_ID, s.SUBJECT_NAME
			FROM SUBJECTS_CLASS sc LEFT JOIN SUBJECT s ON s.SUBJECT_ID = sc.SUBJECT_ID
			WHERE CLASS_ID = '$class_id' ORDER BY 2");
			return $query->result_array();
		}


		function getPass($lesson, $pupil) {
			$query = $this->db->query("SELECT ATTENDANCE_PASS
			FROM ATTENDANCE a
			WHERE LESSON_ID = '$lesson' AND PUPIL_ID = '$pupil'");
			return $query->row_array();
		}


		function getNote($lesson, $pupil) {
			$query = $this->db->query("SELECT NOTE_TEXT
			FROM NOTE
			WHERE LESSON_ID = '$lesson' AND PUPIL_ID = '$pupil'");
			return $query->row_array();
		}

		function getMarks($lesson, $pupil) {
			$query = $this->db->query("SELECT ACHIEVEMENT_MARK, a.ACHIEVEMENT_ID, a.TYPE_ID, t.TYPE_NAME, LESSON_ID
			FROM ACHIEVEMENT a LEFT JOIN TYPE t ON a.TYPE_ID = t.TYPE_ID
			WHERE LESSON_ID = '$lesson' AND PUPIL_ID = '$pupil'");
			return $query->result_array();
		}

		function getLessonByDate($subject, $day, $time) {
			$query = $this->db->query("SELECT *
			FROM LESSON WHERE LESSON_DATE = '$day' AND SUBJECTS_CLASS_ID = '$subject' AND TIME_ID = '$time'");
			return $query->row_array();
		}
	}