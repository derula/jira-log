<h1>Jira Zeitlogger</h1>
<div class="errorBox"></div>

<table>
	<tr>
		<th colspan="2">
			Konfiguration
		</th>
	</tr>
	<tr>
		<td>Host</td>
		<td>{$host}</td>
	</tr>
	<tr>
		<td>RVS-Weiche</td>
		<td></td>
	</tr>
</table>

<table>
	<tr>
		<th colspan="2">
			Autorisierung
		</th>
	</tr>
	<tr>
		<td>Benutzer</td>
		<td><input type="text" name="user" value="" /></td>
	</tr>
	<tr>
		<td>Passwort</td>
		<td>
			<input type="password" name="pass" value="" />
			<input type="button" name="testConnection" value="Test connection" id="testConnection" />
		</td>
	</tr>
</table>

<table>
	<tr>
		<th colspan="2">
			Activity-Sheet
		</th>
	</tr>
	<tr>
		<td>
			<textarea name="sheet"></textarea>
		</td>
	</tr>
</table>