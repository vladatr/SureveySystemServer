 $(document).ready(function() {
	 $(document).on("change", "#surveysResults", function() {
		 $("#okvirResult").html("");
		var anketa=$(this).find("option:selected").val();
		$.post("services/results/get_results.php",
			{
				"anketa": anketa
			},
			function(data, status){
				$("#okvirResult").html(data);
			});
	 });
	 
	  $(document).on("change", "#surveysEnter", function() {
		var kod=$(this).find("option:selected").attr('kod');
		
		$.post("services/get_survey/get_anketa.php",
			{
				"kod": kod,
				"for_web": 1
			},
			function(data, status){
				$("#okvirEnter").html(data);
			});
	 });
	 
	 //***********Popunjavanje ankete
	 $(document).on("click", ".odgovor", function() {
		 $(this).toggleClass("blueSelected"); 
	 });
	 
 });