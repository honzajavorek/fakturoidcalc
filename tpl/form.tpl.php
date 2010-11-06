<form method="post">

	<fieldset>
		<legend>Tohle fakt potřebuju vědět</legend>
		
		<?php foreach ($errors as $message): ?>
		<div class="error">
			<p><strong><?php echo $message ?></strong></p>
		</div>
		<?php endforeach ?>
		
		<table>
			<tr>
				<th><label for="frm-username">Uživatelské jméno:</label></th>
				<td><input type="text" name="username" id="frm-username"></td>
			</tr>
			<tr>
				<th><label for="frm-apiKey">API klíč:</label></th>
				<td><input type="text" name="apiKey" id="frm-apiKey"></td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>Je libo nějaká nastavení?</legend>
		
		<table>
			<tr>
				<th><label for="frm-year">Rok:</label></th>
				<td><select name="year" id="frm-year">
					<?php foreach ($years as $year => $selected): ?>
						<option<?php if ($selected): ?> selected<?php endif ?> value="<?php echo $year ?>"><?php echo $year ?></option>
					<?php endforeach ?>
				</select></td>
			</tr>
			<tr>
				<th><label for="frm-expenses">Výdaje:</label></th>
				<td><select name="year" id="frm-year">
					<?php foreach ($expenses as $val => $selected): ?>
						<option<?php if ($selected): ?> selected<?php endif ?> value="<?php echo $val ?>"><?php echo $val ?> %</option>
					<?php endforeach ?>
				</select> <a href="http://www.jakpodnikat.cz/pausalni-vydaje-procentem.php">?</a></td>
			</tr>
			<tr>
				<th><label for="frm-student">Student:</label></th>
				<td><input type="checkbox" name="student" id="frm-student" value="1" checked> <a href="http://www.jakpodnikat.cz/sleva-na-dani-studenta.php">?</a></td>
			</tr>
		</table>
	</fieldset>
	
	<input type="submit" name="sent" id="frm-sent" value="OK">

</form>