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
			$query = $this->db->query("SELECT TIMETABLE_ID, sc.SUBJECTS_CLASS_ID, DAYOFWEEK_ID,
			r.ROOM_NAME, s.SUBJECT_NAME, tm.TIME_START, tm.TIME_FINISH, tm.TIME_ID
			FROM TIMETABLE t JOIN SUBJECTS_CLASS sc ON t.SUBJECTS_CLASS_ID = sc.SUBJECTS_CLASS_ID
			LEFT JOIN SUBJECT s ON s.SUBJECT_ID = sc.SUBJECT_ID
			LEFT JOIN ROOM r ON r.ROOM_ID = t.ROOM_ID
			JOIN TIME tm ON tm.TIME_ID = t.TIME_ID
			WHERE t.DAYOFWEEK_ID = '$day' AND sc.CLASS_ID = '$class_id'
			ORDER BY 4");
			return $query->result_array();
		}


		function getSubjectsClass($class_id) {
			$query = $this->db->query("SELECT sc.SUBJECTS_CLASS_ID, s.SUBJECT_NAME, sc.TEACHER_ID
			FROM SUBJECTS_CLASS sc LEFT JOIN SUBJECT s ON s.SUBJECT_ID = sc.SUBJECT_ID
			WHERE CLASS_ID = '$class_id' ORDER BY 2");
			return $query->result_array();
		}

		function getTeachers($class_id) {
			$query = $this->db->query("SELECT t.TEACHER_NAME, t.TEACHER_ID
			FROM TEACHER t JOIN SUBJECTS_CLASS sc ON sc.TEACHER_ID = t.TEACHER_ID
			WHERE sc.CLASS_ID = '$class_id' ORDER BY 1");
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

		function getHomeworksForSubject($subject_id, $start, $finish) {
			$query = $this->db->query("SELECT *
			FROM LESSON
			WHERE SUBJECTS_CLASS_ID = '$subject_id' AND LESSON_HOMEWORK != '' AND
			LESSON_DATE >= '$start' AND LESSON_DATE <= '$finish' ORDER BY LESSON_DATE DESC");
			return $query->result_array();
		}


		function getHomeworksForClass($class_id, $limit, $offset) {
			$query = $this->db->query("SELECT *
			FROM LESSON l JOIN SUBJECTS_CLASS sc ON l.SUBJECTS_CLASS_ID = sc.SUBJECTS_CLASS_ID
			JOIN SUBJECT s ON s.SUBJECT_ID = sc.SUBJECT_ID
			WHERE CLASS_ID = '$class_id' AND LESSON_HOMEWORK != ''
			ORDER BY LESSON_DATE DESC
			LIMIT $offset, $limit");
			return $query->result_array();
		}

		function getProgressMarks($subject, $pupil_id) {
			$query = $this->db->query("SELECT p.PROGRESS_ID, p.PROGRESS_MARK, p.PERIOD_ID, PERIOD_NAME
			FROM PROGRESS p JOIN PERIOD pe ON pe.PERIOD_ID = p.PERIOD_ID
			WHERE p.SUBJECTS_CLASS_ID = '$subject' AND p.PUPIL_ID = '$pupil_id' ORDER BY PERIOD_NAME");
			return $query->result_array();
		}


		function getBorders($class_id) {
			$query = $this->db->query("SELECT * FROM PERIOD
			WHERE YEAR_ID = (SELECT YEAR_ID FROM CLASS WHERE CLASS_ID = '$class_id')
			ORDER BY PERIOD_NAME");
			return $query->result_array();
		}

		function getMarksForSubject($id, $subject_id, $start, $finish) {
			$query = $this->db->query("SELECT ACHIEVEMENT_MARK, TYPE_NAME, LESSON_DATE, a.LESSON_ID, ACHIEVEMENT_ID
			FROM ACHIEVEMENT a LEFT JOIN TYPE t ON t.TYPE_ID = a.TYPE_ID
			JOIN LESSON l ON l.LESSON_ID = a.LESSON_ID
			WHERE PUPIL_ID = '$id' AND SUBJECTS_CLASS_ID = '$subject_id' AND
			LESSON_DATE >= '$start' AND LESSON_DATE <= '$finish' ORDER BY LESSON_DATE DESC");
			return $query->result_array();
		}

		function getPeriodById($period) {
			$query = $this->db->query("SELECT * FROM PERIOD WHERE PERIOD_ID = '$period'");
			return $query->row_array();
		}


		function getAverageMarkForPupil($pupil, $subject, $start, $end) {
			$query = $this->db->query("SELECT AVG(ACHIEVEMENT_MARK) AS MARK
			FROM ACHIEVEMENT a JOIN LESSON l ON l.LESSON_ID = a.LESSON_ID
			WHERE PUPIL_ID = '$pupil' AND SUBJECTS_CLASS_ID = '$subject' AND LESSON_DATE >= '$start'
			AND LESSON_DATE <= '$end'");
			return $query->row_array();

		}

		function getAverageMarkForClass($class_id, $subject, $start, $end) {
			$query = $this->db->query("SELECT max(MARK) AS MAX,min(MARK) AS MIN FROM(
			SELECT AVG(ACHIEVEMENT_MARK) AS MARK, PUPIL_ID
			FROM ACHIEVEMENT a JOIN LESSON l ON l.LESSON_ID = a.LESSON_ID JOIN SUBJECTS_CLASS sc
			ON sc.SUBJECTS_CLASS_ID = l.SUBJECTS_CLASS_ID
			WHERE l.SUBJECTS_CLASS_ID = '$subject' AND LESSON_DATE >= '$start'
			AND LESSON_DATE <= '$end' AND CLASS_ID = '$class_id'
			GROUP BY PUPIL_ID) t");
			return $query->row_array();
		}

		function getCountPupilInClassByMark($mark, $pupil, $class_id, $subject, $start, $finish) {
			$result = array();

			$query = $this->db->query("SELECT COUNT(MARK) AS COUNT FROM( SELECT AVG(ACHIEVEMENT_MARK) AS MARK, PUPIL_ID
			FROM ACHIEVEMENT a JOIN LESSON l ON l.LESSON_ID = a.LESSON_ID JOIN SUBJECTS_CLASS sc
			ON sc.SUBJECTS_CLASS_ID = l.SUBJECTS_CLASS_ID
			WHERE l.SUBJECTS_CLASS_ID = '$subject' AND LESSON_DATE >= '$start'
			AND LESSON_DATE <= '$finish' AND CLASS_ID = '$class_id' AND PUPIL_ID != '$pupil'
			GROUP BY PUPIL_ID
			HAVING AVG(ACHIEVEMENT_MARK) < $mark) t");
			$arr = $query->row_array();
			$result["min"] = $arr['COUNT'];

			$query = $this->db->query("SELECT COUNT(MARK) AS COUNT FROM( SELECT AVG(ACHIEVEMENT_MARK) AS MARK, PUPIL_ID
			FROM ACHIEVEMENT a JOIN LESSON l ON l.LESSON_ID = a.LESSON_ID JOIN SUBJECTS_CLASS sc
			ON sc.SUBJECTS_CLASS_ID = l.SUBJECTS_CLASS_ID
			WHERE l.SUBJECTS_CLASS_ID = '$subject' AND LESSON_DATE >= '$start'
			AND LESSON_DATE <= '$finish' AND CLASS_ID = '$class_id' AND PUPIL_ID != '$pupil'
			GROUP BY PUPIL_ID
			HAVING AVG(ACHIEVEMENT_MARK) = $mark) t");
			$arr = $query->row_array();
			$result["same"] = $arr['COUNT'];

			$query = $this->db->query("SELECT COUNT(MARK) AS COUNT FROM( SELECT AVG(ACHIEVEMENT_MARK) AS MARK, PUPIL_ID
			FROM ACHIEVEMENT a JOIN LESSON l ON l.LESSON_ID = a.LESSON_ID JOIN SUBJECTS_CLASS sc
			ON sc.SUBJECTS_CLASS_ID = l.SUBJECTS_CLASS_ID
			WHERE l.SUBJECTS_CLASS_ID = '$subject' AND LESSON_DATE >= '$start'
			AND LESSON_DATE <= '$finish' AND CLASS_ID = '$class_id' AND PUPIL_ID != '$pupil'
			GROUP BY PUPIL_ID
			HAVING AVG(ACHIEVEMENT_MARK) > $mark) t");
			$arr = $query->row_array();
			$result["max"] = $arr['COUNT'];

			return $result;
		}





		function addGCMDevice($pupil_id, $token) {
			$this->db->set('PUPIL_ID', $pupil_id);
			$this->db->set('GCM_USERS_REGID', $token);
			$this->db->insert('GCM_USERS');
		}

		function deleteGCMDevice($pupil_id, $token) {
			$this->db->delete('GCM_USERS', array('PUPIL_ID' => $pupil_id, 'GCM_USERS_REGID' => $token));
		}


		function readConversation($user, $id, $from, $to) {
			$this->db->set('MESSAGE_READ', 1);
			$this->db->where($from."_ID", $user);
			$this->db->where($to."_ID", $id);
			$this->db->update($from."S_MESSAGE");
		}


		function addMessage($id, $user, $text, $date, $from, $to) {
			$this->db->set('MESSAGE_TEXT', $text);
			$this->db->set('MESSAGE_DATE', $date);
			$this->db->insert('MESSAGE');
			$message_id = $this->db->insert_id();

			$this->db->set('MESSAGE_FOLDER', 1);
			$this->db->set('MESSAGE_ID', $message_id);
			$this->db->set($to."_ID", $user);
			$this->db->set($from."_ID", $id);
			$this->db->set('MESSAGE_READ', 0);
			$this->db->insert($to."S_MESSAGE");

			$this->db->set('MESSAGE_FOLDER', 2);
			$this->db->set('MESSAGE_ID', $message_id);
			$this->db->set($to."_ID", $user);
			$this->db->set($from."_ID", $id);
			$this->db->set('MESSAGE_READ', 0);
			$this->db->insert($from."S_MESSAGE");

			return $this->db->insert_id();
		}


		function getConversations($pupil_id) {
			$query = $this->db->query("SELECT TEACHER_ID
			FROM PUPILS_MESSAGE
			WHERE PUPIL_ID = '$pupil_id'
			GROUP BY TEACHER_ID");
			return $query->result_array();
		}


		function getLastMessageInConversation($pupil_id, $teacher_id) {
			$query = $this->db->query("SELECT TEACHER_ID, pm.MESSAGE_ID, MESSAGE_TEXT, MESSAGE_DATE, PUPILS_MESSAGE_ID
			FROM pupils_message pm JOIN MESSAGE m ON pm.MESSAGE_ID = m.MESSAGE_ID
			WHERE PUPIL_ID = '$pupil_id' AND TEACHER_ID = '$teacher_id'
			ORDER BY MESSAGE_DATE DESC
			LIMIT 1");
			return $query->row_array();
		}



		function getNewMessages($pupil_id, $teacher_id) {
			$query = $this->db->query("SELECT COUNT(*) AS COUNT
			FROM PUPILS_MESSAGE
			WHERE PUPIL_ID = '$pupil_id' AND TEACHER_ID = '$teacher_id' AND MESSAGE_READ = 0 AND MESSAGE_FOLDER = 1");
			return $query->row_array();
		}



}