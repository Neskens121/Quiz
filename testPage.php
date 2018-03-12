<?php
session_start();
//echo json_encode('sasas');

if($_POST){
	//echo $_POST['myArray'];
	$currentQuestion = $_POST['currentQuestion'];
	//echo $currentQuestion;
	require 'quizQuestions.php';
	foreach ($_SESSION['questionIndexArr'] as $key => $value) {
		$tempQuestionArr[] = $questions[$value];
	}
	echo json_encode($tempQuestionArr[$currentQuestion]);
}






