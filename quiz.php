<?php 
var_dump($_POST);



$quizResult = 0;
$maxNumberOfQuestions = 5;
$currentQuestionNumber = $_POST ? $_POST['questionNumber'] : NULL;
/*IF we got data through HTML form*/
if($_POST){
	session_start();
	var_dump($_SESSION);
	/*Check if the last page is reached and logout Btn pressed */
	if(isset($_POST['logout'])){
		$_SESSION = array();
		if(isset($_COOKIE[session_name()])){
			setcookie(session_name(), '', time() -86400, '/');
		}
		session_destroy();
		header('Location: http://localhost/quiz/quiz.php');
	} else{
		require 'quizQuestions.php';
		/*Check if the first page is reached and start Btn pressed */
		if(isset($_POST['startBtn'])){
			/*Generate 5 random index from questions array and save it to SESSION variable questionIndexArr*/
			$_SESSION['questionIndexArr'] = array_rand( $questions, $maxNumberOfQuestions );
			shuffle($_SESSION['questionIndexArr']);
			//var_dump($_SESSION);
			/*Pull off questions from array*/
			foreach ($_SESSION['questionIndexArr'] as $key => $value) {
				$tempQuestionArr[] = $questions[$value];
			}
			$_SESSION['userAnswerArr'] = array();
		} elseif(isset($_POST['questionNumber'])) {
			/*Again same thing could it be done in some smarter way*/
			$tempQuestionArr = [];
			foreach ($_SESSION['questionIndexArr'] as $key => $value) {
				$tempQuestionArr[] = $questions[$value];

			}
			//var_dump($tempQuestionArr);
			if(isset($_POST['question'])){
				$_SESSION['userAnswerArr'][$currentQuestionNumber-1] = array("questionIndex"=>"{$_SESSION['questionIndexArr'][$currentQuestionNumber-1]}", "indexOfAnswer"=>"{$_POST['question']}");
				if($_POST['question'] == ($tempQuestionArr[$currentQuestionNumber-1]['indexOfCorrectAnswer'])){
					//var_dump($_SESSION);
					$_SESSION['result'] = !isset($_SESSION['result']) ? 1 : $_SESSION['result']+1;
					$quizResult = $_SESSION['result'] * 1;
					//setcookie('result',$quizResult);
					//echo $quizResult . '<br>';
					echo calculateScore($tempQuestionArr, $_SESSION['userAnswerArr']) . '<br>';
				}
			} else {
				//header('Location: http://localhost/quiz/quiz.php');
			}
		}
	}
}

function calculateScore($questionsArr, $userInfoArr){
	$score = 0;
	for($i = 0; $i < count($userInfoArr); $i++){
		if($questionsArr[$i]['indexOfCorrectAnswer'] == $userInfoArr[$i]['indexOfAnswer']){$score++;}
	}
	return $score;
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Quiz</title>
	<style type="text/css">
	ul {
		list-style-type: none;
		padding: 0;
		margin: 0;
	}
</style>
</head>
<body>
	<?php if(!$_POST){ ?>
	<h1>Welcome to our Quiz</h1>
	<form action="" method="POST">
		<input type="hidden" id="questionNumber" name="questionNumber" value="0">
		<input type="submit" name="startBtn" id="startBtn" value="startBtn">
	</form>
	<?php } elseif (isset($_POST['startBtn']) || $currentQuestionNumber < $maxNumberOfQuestions) { ?> 
	<h3>Question Number: <?php echo ($currentQuestionNumber + 1)?></h3>
	<p>
		<?php echo  $tempQuestionArr[$currentQuestionNumber]['question']?>		
	</p>
	<form id="questionForm" action="" method="POST">
		<ul>
			<?php foreach ($tempQuestionArr[$currentQuestionNumber]['potentialAnswers'] as $key => $value) {
				echo "<li><input type='radio' name='question' value=$key required>" . $value . "</li>";
			} ?>		
		</ul>
		<input type="hidden" id="questionNumber" name="questionNumber" value=<?php echo $_POST['questionNumber']; ?>>
		<input type='submit' name='nextQuestionBtn' id='nextQuestionBtn' value='Next Question'/>
	</form>
	<button type="button" id="checkAnswerBtn">Check answer</button>
	<?php } else { ?>
	<h3>Quiz is over</h3>
	<h4>You scored <?php echo calculateScore($tempQuestionArr, $_SESSION['userAnswerArr']) ?> points</h4>
	<form action="" method="POST">
		<input name="logout" type="submit" id="logout" value="Start Quiz Again">
	</form>
	<?php } ?> 

	<script type="text/javascript">
		var startBtn = document.getElementById('startBtn');
		var nextQuestionBtn = document.getElementById('nextQuestionBtn');
		var questionNumber = document.getElementById('questionNumber');
		var checkAnswerBtn = document.getElementById('checkAnswerBtn');
		
		/*if(startBtn){startBtn.addEventListener('click', changeQuestionNumber(1), false);}*/
		if(nextQuestionBtn){nextQuestionBtn.addEventListener('click', changeQuestionNumber(1), false);}
		if(checkAnswerBtn){checkAnswerBtn.addEventListener('click', testFunction, false);}


		function changeQuestionNumber(val){
			return function(){
				//alert(val);
				if(document.querySelectorAll('input[type="radio"][name="question"]:checked').length > 0 ){
							questionNumber.value = questionNumber.value * 1 + val;
				}
				//document.cookie = "result=0";
				//alert(questionNumber);
			}
		}


		
		function testFunction(){
			if(document.querySelectorAll('input[type="radio"][name="question"]:checked').length > 0 ){
				var xhttp = new XMLHttpRequest();
				//console.log(document.querySelector('input[name="question"]:checked').value);

				//should check for NULL value in case that no radio button is selected
				var answerIndex = document.querySelector('input[name="question"]:checked').value;
				
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
				    //console.log(JSON.parse(this.responseText));
				    console.log((this.responseText));
				     //document.getElementById("demo").innerHTML = this.responseText;
				 }
				};
				xhttp.open("POST", "http://localhost/quiz/testPage.php", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("currentQuestion=" + questionNumber.value + "&answerIndex=" + answerIndex);
			} else {
				console.log('change this');
			}
		}


	</script>
</body>
</html>