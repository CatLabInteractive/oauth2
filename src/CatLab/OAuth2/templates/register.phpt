<?php
	$this->layout ('index.phpt');
?>

<h2>OAuth2 application registration</h2>

<p>Please provide a valid redirect URI. This application ID must be equal to the redirect URI that you use in your authorization call.</p>

<form method="post">

	<ol>
		<li>
			<label for="redirecturi">Redirect URI:</label>
			<input type="text" id="redirecturi" name="redirecturi" />
		</li>

		<li>
			<label for="loginlayout">Login design:</label>
			<select id="loginlayout" name="loginlayout">
				<option value="default">default</option>
				<option value="mobile">mobile</option>
				<option value="platform">platform</option>
			</select>
		</li>

		<li>
			<button type="submit">Register OAuth2 app</button>
		</li>
	</ol>

</form>