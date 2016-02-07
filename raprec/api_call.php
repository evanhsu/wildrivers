<html>
<head>
	<script type="text/javascript" src="scripts/sha1.js"></script>
	<script type="text/javascript">
	function submitform() {
		var controller = document.getElementById("controller_field").value;
		var action = document.getElementById("action_field").value;
		var id = document.getElementById("id_field").value;
		var year = document.getElementById("year_field").value;
		var timestamp = Math.round(new Date().getTime()/1000.0);
		var api_user = document.getElementById("api_user_field").value;
		var password = document.getElementById("password_field").value;
		
		var requestString = "c="+controller+"&a="+action+"&id="+id+"&year="+year+'&timestamp='+timestamp+"&api_user="+api_user;
		
		var hashedUsernamePassword = SHA1(api_user+password);
		var key = SHA1(requestString+hashedUsernamePassword);
		
		document.getElementById("timestamp_field").value = timestamp;
		document.getElementById("key_field").value = key;
		
		document.getElementById("myform").action = "api.php?"+requestString+"&key="+key;
		document.getElementById("myform").submit();
	}
	</script>
</head>
<body>

	<form action="" method="post" id="myform">
    Username: <input type="text" name="api_user" size="30" id="api_user_field" value="evanhsu@gmail.com" /><br />
    Password: <input type="password" name="password" id="password_field" /><br />
    Controller: <input type="text" name="c" id="controller_field" value="people" /><br />
    Action: <input type="text" name="a" id="action_field" value="rappels" /><br />
    ID: <input type="text" name="id" id="id_field" value="45" /><br />
    Year: <input type="text" name="year" id="year_field" value="2011" /><br />
    <input type="hidden" name="timestamp" id="timestamp_field" value="" />
    <input type="hidden" name="key" id="key_field" value=""  />
    <input type="button" value="Submit" onclick="submitform();" >
    </form>

</body>
</html>
