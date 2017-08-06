<?php
require("lock.php");

?>
<html>
<head>

<title>New Survey</title>
<script type="text/javascript" src="js/jquery-3.2.1.js"> </script>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script>
 $(document).ready(function() {
	var max_questions = 20;
	var max_answers = 20;
	var add_question = $(".add_question");
	var s_frame = $(".survey_frame");
	var q_frame = $(".question_frame");
	var add_answer = $(".add_answer");
	var num_answers = new Array();
	num_answers[1]=1;
	var num_questions = 1;
	
	s_frame.on("click", ".add_answer", function(e) {
	//adding answer to a question
		 e.preventDefault();
		 var num_q = $(this).attr("q");
		//alert($(this).parent('div').attr("id")); 
        if(num_answers[num_q] < max_answers){ 
            num_answers[num_q]++;
            $("#q" + num_q + " .remove_question").before('<div><input type="text" class="answer" /><input type="radio" name="openin' + num_q + '"" /><a href="#" class="remove_answer remove">Remove</a></div>');
        }
	});
	
	$(add_question).on("click", function(e) {
		e.preventDefault();
		if(num_questions < max_questions) {
			num_questions++;
			s_frame.append(makeQuestion(num_questions));	
			num_answers[num_questions]=1;
		}
	});
	
	 $(s_frame).on("click",".remove_answer", function(e){ 
        e.preventDefault(); 
		var num_q = $(this).parent('div').siblings(".add_answer").attr("q");
		$(this).parent('div').remove(); 
		num_answers[num_q]--;
    })
	
	$(s_frame).on("click",".remove_question", function(e){ 
		if(num_questions == 1) {
			alert("There must be at least one question!");
			return;
		}
        e.preventDefault(); 
		$(this).parent('div').remove(); 
		num_questions--;
    })
	
	
	function makeQuestion(num_q) {
		var new_q_frame = 	'<div class="question_frame" id="q' + num_q + '">';
			new_q_frame += 		'<div><label>Question <input type="text" id="question" ></div>';
			new_q_frame += 		'<button class="add_answer btn" q=' + num_q + '>Add an Answer</button> <span class="tip">Tip <select id=tip><option value=1>Choose only one</option><option value=2>Choose multiple</option></select></span>';
			new_q_frame += 		'<div><input type="text" class="answer" ><input type="radio" name="openin' + num_q + '" /> choose open input </div>';
			new_q_frame +=	'<a href="#" class="remove_question remove">Remove the whole Question</a></div>';
			
		return new_q_frame;
	}	
	
	var save_clicked=0;
	$(".save_survey").on('click', function() {
		if(save_clicked == 1) return;
		save_clicked=1;
		var title = $("#survey").val();
		var json = '{ "survey": { "title": "' + title + '", "questions": [';
		brq=0;
		$(".question_frame").each(function(i) {
			if(brq>0) { json += ", "; }
			brq++;
			var tip = $(this).find("#tip option:selected").val();
			var question = $(this).find("#question").val() ;
			//alert(i + ". " + question + " tip " + tip);
			json += ' { "question": "' + question + '", "tip":"' + tip + '", "answers": ['; 
			var bra=0;
			$(".answer", this).each(function(i) {
				if(bra>0) { json += ", "; }
				bra++;
				var answer = $(this).val();
				var open_input = $(this).siblings('input[type=radio]').is(":checked");
				//alert(answer + " " + open_input);
				json += '{ "answer":"' + answer + '", "open_input":"' + open_input + '" }';
			});
			json += '] } ';
		});
		json += '] } }';
		$("#info").html(json);
		
		$.ajax
		({
			type: "POST",
			url: 'services/web_insert_survey/save.php',
			data: {"survey": json},
			success: function (data) { 
				$("#info").html(data);
				save_clicked=0;
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				alert("Status: " + textStatus); alert("Error: " + errorThrown); 
				save_clicked=0;
			}
		})
	});
 
 });


</script>
</head>
<body>
<?php require_once("header.php"); ?>

<div class="survey_frame">
	<div><label>Survey title <input type="text" id="survey"></div>
	<div><button class="add_question btn">Add a Question</button> <button class="save_survey btn">Save Survey</button></div>
	
	<div class="question_frame" id="q1">
		<div><label>Question <input type="text" id="question" ></div>
		<button class="add_answer btn" q="1">Add an Answer</button> <span class="ctip">Tip <select id="tip"><option value=1>Choose only one</option><option value=2>Choose multiple</option></select></span>
		<div><input type="text" class="answer" ><input type="radio" name="openin1" /><lalbel>choose open input</label></div>
		<a href="#" class="remove_question remove">Remove the whole Question</a>
	</div>
</div>
<div id=info></div>
</body>
</html>