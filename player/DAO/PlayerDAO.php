<?php

class DAO {
	private $db;

	private $SELECTUSERBYEMAILPASSWORD = "SELECT u.id, u.fullname, c.name as 'club', g.name as 'gender', u.equipment, r.name as 'role', u.height, u.age, p.value as 'plays', u.birthplace FROM users u JOIN clubs c ON u.clubFK = c.id JOIN genders g ON u.gender = g.id JOIN roles r ON r.id = u.role JOIN plays p ON u.plays = p.id WHERE u.email = ? && u.password = ?;";
	private $SELECTEMAILPASSWORD = "SELECT u.email, u.password, r.name as 'role' FROM users u JOIN roles r ON u.role = r.id WHERE email = ? AND password = ?";
	private $SELECTUSERMATCHES = "SELECT  m.id, `date`, hostScore, challengerScore, winnerID, host, u.fullname as 'challenger', challengerConfirmation, hostFirstSet, challengerFirstSet, hostSecondSet, challengerSecondSet, hostThirdSet, challengerThirdSet FROM matchevidency m JOIN users u ON m.challenger = u.id WHERE (m.host = ? OR m.challenger = ?) AND m.challengerConfirmation = 1;";
	private $SELECTUSERUNCOFIRMEDMATCHES = "SELECT  m.id, `date`, hostScore, challengerScore, winnerID, host, u.fullname as 'challenger', challengerConfirmation, hostFirstSet, challengerFirstSet, hostSecondSet, challengerSecondSet, hostThirdSet, challengerThirdSet FROM matchevidency m JOIN users u ON m.challenger = u.id WHERE m.challenger = ? AND m.challengerConfirmation = 0;";
	private $SELECTALLBUT = "SELECT id, fullname FROM users WHERE id <> ? AND role = 1;";
	private $INSERTMATCH = "INSERT INTO matchevidency ( `date`, hostScore, challengerScore, winnerID, host, challenger, challengerConfirmation, hostFirstSet, challengerFirstSet, hostSecondSet, challengerSecondSet, hostThirdSet, challengerThirdSet) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
	private $DELETEMATCH = "DELETE FROM matchevidency WHERE id = ?;";
	private $SELECTMATCH = "SELECT * FROM matchevidency WHERE id = ?;";
	private $SELECTPLAYS = "SELECT * FROM plays";
	private $SELECTCLUBS = "SELECT * FROM clubs";
	private $SELECTCLUB = "SELECT id, `name` FROM clubs WHERE name = ?;";
	private $SELECTPLAY = "SELECT id, `value` FROM plays WHERE value = ?;";
	private $UPDATEPLAYER = "UPDATE `users` SET `users`.`fullname` = ?, `users`.`clubFK` = ?, `users`.`height` = ?, `users`.`age` = ?, `users`.`plays` = ?, `users`.`birthplace` = ? WHERE `users`.`id` = ?;";
	private $SELECTUSERBYID = "SELECT u.id, u.fullname, c.name as 'club', g.name as 'gender', u.equipment, r.name as 'role', u.height, u.age, p.value as 'plays', u.birthplace FROM users u JOIN clubs c ON u.clubFK = c.id JOIN genders g ON u.gender = g.id JOIN roles r ON r.id = u.role JOIN plays p ON u.plays = p.id WHERE u.id = ?;";
	private $SELECTOPPONENTS = "SELECT fullname FROM users WHERE id <> ?;";
	private $SELECTFIELDS = "SELECT * FROM fields;";
	private $SELECTFIELDBYNAME = "SELECT id FROM fields WHERE name = ?;";
	private $SELECTMATCHTYPES = "SELECT * FROM matchtypes;";
	private $SELECTMATCHTYPEIDBYNAME = "SELECT id FROM matchtypes WHERE type = ?;";
	private $INSERTRESERVATION = "INSERT INTO `reservations` (`reservationDate`, `reservationTime`, `fieldId`, `user`, `matchType`, `opponent`) VALUES (?, ?, ?, ?, ?, ?);";
	private $SELECTUSERIDBYFULLNAME = "SELECT id FROM users WHERE fullname = ?;";
	private $SELECTRESERVATIONBYDATETIMEFIELD = "SELECT * FROM reservations WHERE reservationDate = ? AND reservationTime = ? AND fieldId = ?;";
	private $SELECTRESERVATIONS = "SELECT * FROM reservations WHERE evidented = 0 AND matchType = 2 AND user = ?;";
	private $SELECTOPPONENTFROMRESERVATIONS = "SELECT opponent, user FROM reservations WHERE reservationDate = ? AND reservationTime = ?;";
	private $UPDATERESERVATIONSEVIDENTED = "UPDATE reservations SET evidented = 1 WHERE reservationDate = ? AND reservationTime = ?;";
	private $CONFIRMMATCH = "UPDATE matchevidency SET challengerConfirmation = 1 WHERE id = ?;";
	private $INSERTPHOTO = "UPDATE `equipment` SET `url` = ? WHERE `equipmentType` = ? AND `userId` = ?";
	private $SELECTEQUIPMENT = "SELECT url FROM `equipment` WHERE userId = ? AND equipmentType = ?;";
	private $EDITMATCH = "UPDATE `matchevidency` SET `hostScore` = ?, `challengerScore` = ?, `winnerID` = ?, `challenger` = ?, `hostFirstSet` = ?, `challengerFirstSet` = ?, `hostSecondSet` = ?, `challengerSecondSet` = ?, `hostThirdSet` = ?, `challengerThirdSet` = ? WHERE `matchevidency`.`id` = ?;";

	public function __construct($config)
	{
		$this->db = DB::createInstance($config);
	}

	public function getUserByEmailPassword($email, $password)
	{
		$statement = $this->db->prepare($this->SELECTUSERBYEMAILPASSWORD);
		$statement->bindValue(1, $email, PDO::PARAM_STR);
		$statement->bindValue(2, $password, PDO::PARAM_STR);

		$statement->execute();
		
		$result = $statement->fetchAll();
		return $result;
	}
	
	public function insertReservation($date, $time, $field, $user, $matchType, $opponent)
	{
		$fieldId = $this->selectFieldByName($field);
		$opponentId = $this->selectUserIdByFullname($opponent);
		$matchTypeId = $this->selectMatchTypeIdByType($matchType);
		
		$statement = $this->db->prepare($this->INSERTRESERVATION);
		$statement->bindValue(1, $date, PDO::PARAM_STR);
		$statement->bindValue(2, $time, PDO::PARAM_INT);
		$statement->bindValue(3, $fieldId['id'], PDO::PARAM_INT);
		$statement->bindValue(4, $user, PDO::PARAM_INT);
		$statement->bindValue(5, $matchTypeId['id'], PDO::PARAM_INT);
		$statement->bindValue(6, $opponentId['id'], PDO::PARAM_INT);

		$statement->execute();
	}

	public function getUserById($id)
	{
		$statement = $this->db->prepare($this->SELECTUSERBYID);
		$statement->bindValue(1, $id, PDO::PARAM_INT);

		$statement->execute();

		$result = $statement->fetch();
		return $result;
	}

	public function getPlayByValue($value)
	{
		$statement = $this->db->prepare($this->SELECTPLAY);
		$statement->bindValue(1, $value, PDO::PARAM_STR);

		$statement->execute();

		$result = $statement->fetch();
		return $result;
	}

	public function getClubByName($name)
	{
		$statement = $this->db->prepare($this->SELECTCLUB);
		$statement->bindValue(1, $name, PDO::PARAM_STR);

		$statement->execute();

		$result = $statement->fetch();
		return $result;
	}

	public function updatePlayer($fullName, $height, $age, $plays, $birthplace, $club, $id)
	{
		$club = $this->getClubByName($club);
		$hand = $this->getPlayByValue($plays);
		
		$statement = $this->db->prepare($this->UPDATEPLAYER);
		$statement->bindValue(1, $fullName, PDO::PARAM_STR);
		$statement->bindValue(2, $club['id'], PDO::PARAM_INT);
		$statement->bindValue(3, $height, PDO::PARAM_INT);
		$statement->bindValue(4, $age, PDO::PARAM_INT);
		$statement->bindValue(5, $hand['id'], PDO::PARAM_INT);
		$statement->bindValue(6, $birthplace, PDO::PARAM_STR);
		$statement->bindValue(7, $id, PDO::PARAM_INT);

		$result = $statement->execute();
		return $result;
	}

	public function getEmailPassword($email, $password)
	{
		$statement = $this->db->prepare($this->SELECTEMAILPASSWORD);
		$statement->bindValue(1, $email, PDO::PARAM_STR);
		$statement->bindValue(2, $password, PDO::PARAM_STR);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function getPlays()
	{
		$statement = $this->db->prepare($this->SELECTPLAYS);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function getClubs()
	{
		$statement = $this->db->prepare($this->SELECTCLUBS);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function getPlayersExceptCurrent($id)
	{
		$statement = $this->db->prepare($this->SELECTALLBUT);
		$statement->bindValue(1, $id, PDO::PARAM_INT);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function getUserMatches($id)
	{
		$statement = $this->db->prepare($this->SELECTUSERMATCHES);
		$statement->bindValue(1, $id, PDO::PARAM_INT);
		$statement->bindValue(2, $id, PDO::PARAM_INT);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function insertMatch($date, $hostScore, $challengerScore, $winnerID, $host, $challenger, 
	$challengerConfirmation, $hostFirstSet, $challengerFirstSet, $hostSecondSet, $challengerSecondSet, 
	$hostThirdSet, $challengerThirdSet)
	{
		$statement = $this->db->prepare($this->INSERTMATCH);
		$statement->bindValue(1, $date, PDO::PARAM_STR);
		$statement->bindValue(2, $hostScore, PDO::PARAM_INT);
		$statement->bindValue(3, $challengerScore, PDO::PARAM_INT);
		$statement->bindValue(4, $winnerID, PDO::PARAM_INT);
		$statement->bindValue(5, $host, PDO::PARAM_INT);
		$statement->bindValue(6, $challenger, PDO::PARAM_INT);
		$statement->bindValue(7, $challengerConfirmation, PDO::PARAM_INT);
		$statement->bindValue(8, $hostFirstSet, PDO::PARAM_INT);
		$statement->bindValue(9, $challengerFirstSet, PDO::PARAM_INT);
		$statement->bindValue(10, $hostSecondSet, PDO::PARAM_INT);
		$statement->bindValue(11, $challengerSecondSet, PDO::PARAM_INT);
		$statement->bindValue(12, $hostThirdSet, PDO::PARAM_INT);
		$statement->bindValue(13, $challengerThirdSet, PDO::PARAM_INT);

		$statement->execute();
	}
	
	public function editMatch($hostScore, $challengerScore, $winnerID, $challenger, 
	$hostFirstSet, $challengerFirstSet, $hostSecondSet, $challengerSecondSet, 
	$hostThirdSet, $challengerThirdSet, $id)
	{
		$statement = $this->db->prepare($this->EDITMATCH);
		$statement->bindValue(1, $hostScore, PDO::PARAM_INT);
		$statement->bindValue(2, $challengerScore, PDO::PARAM_INT);
		$statement->bindValue(3, $winnerID, PDO::PARAM_INT);
		$statement->bindValue(4, $challenger, PDO::PARAM_INT);
		$statement->bindValue(5, $hostFirstSet, PDO::PARAM_INT);
		$statement->bindValue(6, $challengerFirstSet, PDO::PARAM_INT);
		$statement->bindValue(7, $hostSecondSet, PDO::PARAM_INT);
		$statement->bindValue(8, $challengerSecondSet, PDO::PARAM_INT);
		$statement->bindValue(9, $hostThirdSet, PDO::PARAM_INT);
		$statement->bindValue(10, $challengerThirdSet, PDO::PARAM_INT);
		$statement->bindValue(11, $id, PDO::PARAM_INT);

		$statement->execute();
	}

	public function deleteMatch($id)
	{
		$statement = $this->db->prepare($this->DELETEMATCH);
		$statement->bindValue(1, $id, PDO::PARAM_INT);

		$statement->execute();
	}

	public function selectOpponents($id)
	{
		$statement = $this->db->prepare($this->SELECTOPPONENTS);
		$statement->bindValue(1, $id, PDO::PARAM_INT);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function selectFields()
	{
		$statement = $this->db->prepare($this->SELECTFIELDS);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function selectMatchTypes()
	{
		$statement = $this->db->prepare($this->SELECTMATCHTYPES);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function selectFieldByName($name)
	{
		$statement = $this->db->prepare($this->SELECTFIELDBYNAME);
		$statement->bindValue(1, $name, PDO::PARAM_STR);

		$statement->execute();

		$result = $statement->fetch();
		return $result;
	}

	public function selectUserIdByFullname($fullname)
	{
		$statement = $this->db->prepare($this->SELECTUSERIDBYFULLNAME);
		$statement->bindValue(1, $fullname, PDO::PARAM_STR);

		$statement->execute();

		$result = $statement->fetch();
		return $result;
	}

	public function selectMatchTypeIdByType($type)
	{
		$statement = $this->db->prepare($this->SELECTMATCHTYPEIDBYNAME);
		$statement->bindValue(1, $type, PDO::PARAM_STR);

		$statement->execute();

		$result = $statement->fetch();
		return $result;
	}

	public function selectReservationByDateTimeField($date, $time, $field)
	{
		$fieldId = $this->selectFieldByName($field);

		$statement = $this->db->prepare($this->SELECTRESERVATIONBYDATETIMEFIELD);
		$statement->bindValue(1, $date, PDO::PARAM_STR);
		$statement->bindValue(2, $time, PDO::PARAM_INT);
		$statement->bindValue(3, $fieldId['id'], PDO::PARAM_INT);

		$statement->execute();

		$result = $statement->fetchAll();
		return count($result) > 0;
	}

	public function selectReservations($id)
	{
		$statement = $this->db->prepare($this->SELECTRESERVATIONS);
		$statement->bindValue(1, $id, PDO::PARAM_INT);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function selectOpponentIdFromReservations($date, $time)
	{
		$statement = $this->db->prepare($this->SELECTOPPONENTFROMRESERVATIONS);
		$statement->bindValue(1, $date, PDO::PARAM_STR);
		$statement->bindValue(2, $time, PDO::PARAM_INT);
		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function updateReservationsEvidented($date, $time)
	{
		$statement = $this->db->prepare($this->UPDATERESERVATIONSEVIDENTED);
		$statement->bindValue(1, $date, PDO::PARAM_STR);
		$statement->bindValue(2, $time, PDO::PARAM_INT);
		$statement->execute();
	}

	public function getUserUncofirmedMatches($id)
	{
		$statement = $this->db->prepare($this->SELECTUSERUNCOFIRMEDMATCHES);
		$statement->bindValue(1, $id, PDO::PARAM_INT);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	}

	public function confirmMatch($id)
	{
		$statement = $this->db->prepare($this->CONFIRMMATCH);
		$statement->bindValue(1, $id, PDO::PARAM_INT);

		$statement->execute();
	}

	public function insertPhoto($id, $equipmentType, $url)
	{
		$statement = $this->db->prepare($this->INSERTPHOTO);
		$statement->bindValue(1, $url, PDO::PARAM_STR);
		$statement->bindValue(2, $equipmentType, PDO::PARAM_INT);
		$statement->bindValue(3, $id, PDO::PARAM_INT);

		$statement->execute();
	}

	public function selectEquipment($id, $equipmentType)
	{
		$statement = $this->db->prepare($this->SELECTEQUIPMENT);
		$statement->bindValue(1, $id, PDO::PARAM_INT);
		$statement->bindValue(2, $equipmentType, PDO::PARAM_INT);

		$statement->execute();
		
		$result = $statement->fetch();
		return $result;
	}

	public function selectMatch($id)
	{
		$statement = $this->db->prepare($this->SELECTMATCH);
		$statement->bindValue(1, $id, PDO::PARAM_INT);

		$statement->execute();
		
		$result = $statement->fetch();
		return $result;
	}
}
?>
