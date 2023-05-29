<?php

header('Content-Type: text/html; charset=UTF-8');
$username = htmlspecialchars($_POST["username"]);
$email = htmlspecialchars($_POST["email"]);
$year = intval(htmlspecialchars($_POST["year"]));
$gender = htmlspecialchars($_POST["gender"]);
$countlimbs = intval(htmlspecialchars($_POST["countlimbs"]));
$superPowers = $_POST["super-powers"];
$biography = htmlspecialchars($_POST["biography"]);
if (!empty($_POST)) {
	$bioreg = "/^\s*\w+[\w\s\.,-]*$/";
	$reg = "/^\w+[\w\s-]*$/";
	$mailreg = "/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/";
	$list_sup = array('inv','walk','fly');
	if (empty(!preg_match($reg,$username)) {
		$errors[] = "Укажите Ваше имя! Это поле не должно быть пустым";
	}
	if (!preg_match($mailreg,$email)) {
		$errors[] = "Введите Ваш e-mail! Это поле не должно быть пустым";
	}
	if (empty($_POST["year"])) {
		$errors[] = "Выберите Ваш год рождения! Это поле не должно быть пустым";
	}
	if ($gender !== 'male' && $gender !== 'female')) {
		$errors[] = "Выберите пол! Это поле не должно быть пустым";
	}
	if (!isset($_POST["countlimbs"])) {
		$errors[] = "Выберите кол-во конечностей! Это поле не должно быть пустым";
	}
	foreach($superpowers as $checking){
	if(array_search($checking,$list_sup)=== false){
			print_r('Неверный формат суперсил');
			exit();
	}
}
	if (empty(!preg_match($bioreg,$biography)) {
		$errors[] = "Расскажите что-нибудь о себе! Это поле не должно быть пустым";
	}
} else {
	$errors[] = "Неверные данные для формы!";
}

if (isset($errors)) {
	foreach ($errors as $value) {
		echo "$value<br>";
	}
	exit();
}
	    
if (!isset($_POST["agree"])) {
	$agree = 0;
} else {
	$agree = 1;
}

$serverName = 'localhost';
$user = "u52806";
$pass = "7974759";
$dbName = $user;

$db = new PDO("mysql:host=$serverName;dbname=$dbName", $user, $pass, array(PDO::ATTR_PERSISTENT => true));

$lastId = null;
try {
	$stmt = $db->prepare("INSERT INTO user_profile (name, email, date, gender, limbs, biography, agreement) VALUES (:name, :email, :date, :gender, :limbs, :biography, :agreement)");
	$stmt->execute(array('name' => $username, 'email' => $email, 'date' => $year, 'gender' => $gender, 'limbs' => $countlimbs, 'biography' => $biography, 'agreement' => $agree));
	$lastId = $db->lastInsertId();
} catch (PDOException $e) {
	print('Error : ' . $e->getMessage());
	exit();
}

try {
	if ($lastId === null) {
		exit();
	}
	foreach ($superPowers as $value) {
		$stmt = $db->prepare("INSERT INTO user_superpower (id, power) VALUES (:id, :power)");
		$stmt->execute(array('id' => $lastId, 'power' => $value));
	}
} catch (PDOException $e) {
	print('Error : ' . $e->getMessage());
	exit();
}
$db = null;
echo "Данные отправлены! Спасибо)";
