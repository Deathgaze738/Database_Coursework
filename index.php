<h1> Create Account Form Structure </h1>
<form action="createAccount.php" method="POST">
	<label>Email Address: </label><input name="emailAddress" type="text"><br>
	<label>First Name: </label><input name="firstName" type="text"><br>
	<label>Last Name: </label><input name="lastName" type="text"><br>
	<label>Country: </label><input name="country" type="text"><br>
	<label>Age: </label><input name="age" type="number"><br>
	<label>Password: </label><input name="password" type="password" ><br>
	<label>Confirm Password: </label><input name="password_conf" type="password" ><br>
	<input type="submit"><br>
</form>




<h1> Login Form Structure </h1>
<form action="login.php" method="POST">
	<label>Email Address: </label><input name="emailAddress" type="text"><br>
	<label>Password: </label><input name="password" type="password" ><br>
	<input type="submit" value="Login"><br>
</form>