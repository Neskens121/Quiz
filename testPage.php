<?php
session_start();
//echo json_encode('sasas');

if($_POST){
	//echo $_POST['myArray'];
	$currentQuestion = $_POST['currentQuestion'];
	$answerIndex = $_POST['answerIndex'];
	//echo $currentQuestion;
	require 'quizQuestions.php';
	foreach ($_SESSION['questionIndexArr'] as $key => $value) {
		$tempQuestionArr[] = $questions[$value];
	}

	echo $tempQuestionArr[$currentQuestion]['indexOfCorrectAnswer'] == $answerIndex ? 'true' : 'false';
	//echo $currentQuestion + '<br>';
	//echo $_POST['answerIndex'];

	//echo json_encode($tempQuestionArr[$currentQuestion]);
}






