<!DOCTYPE html>
<html lang="cs" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Jan Javorek (honza@javorek.net)">

	<title>FakturoidCalc</title>

	<link rel="shortcut icon" type="image/x-icon" href="http://www.fakturoid.cz/favicon.ico">
</head>

<body>

	<h1>FakturoidCalc</h1>
	<p>
		Jsem malinký skript, který si povídá s <a href="http://www.fakturoid.cz">Fakturoidem</a> a pokouší se spočítat,
		kolik jako OSVČ asi zaplatíš na daních státu. Sestavil mě <a href="http://twitter.com/littlemaple">Honza Javorek</a>.
		Jsem úplně bez záruky &ndash; pamatuj, že ani robot není neomylný. Moje <a href="https://github.com/Littlemaple/FakturoidCalc">rentgenové snímky</a> jsou veřejné.
	</p>

<?php
require dirname(__FILE__) . '/app/bootstrap.php';
$param = (empty($_GET['file']))? NULL : $_GET['file'];

$tpl = new Template;
if (!$param) {
?>
	
	<h2>Dostupné konfigurace</h2>
	<ul>
	<?php foreach ($tpl->getFiles() as $file): ?>
		<li><a href="?file=<?php echo $file ?>"><?php echo $file ?></a></li>
	<?php endforeach ?>
	</ul>
	
<?php
} else {
	try {
		
		$app = new Calculator($param);
		$data = $app->run();
?>
	
	<h2>Výsledky mého počítání</h2>
	
	<h3>Daně</h3>
	<table>
		<tr>
			<th>Příjmy:</th>
			<td><?php echo $tpl->currency($data['income']) ?></td>
		</tr>
		<tr>
			<th>Výdaje:</th>
			<td><?php echo $tpl->currency($data['expenses']) ?> (<?php echo $data['cfg']['expenses_percent'] ?> % příjmů)</td>
		</tr>
		<tr>
			<th>Zisk:</th>
			<td><?php echo $tpl->currency($data['profit']) ?></td>
		</tr>
		<tr>
			<th>Daň:</th>
			<td><?php echo $tpl->currency($data['taxes']['tax']) ?> (<?php echo $data['cfg']['taxes']['percent'] ?> % z <?php echo $tpl->currency($data['taxes']['base']) ?>)</td>
		</tr>
		<tr>
			<th>Daň po slevách:</th>
			<td><?php echo $tpl->currency($data['taxes']['finalTax']) ?> (<?php echo $tpl->currency($data['taxes']['reducedTax']) ?>)</td>
		</tr>
	</table>

	<h3>VZP</h3>
	<table>
		<tr>
			<th>Pojištění za <?php echo $data['cfg']['year'] ?>:</th>
			<td><?php echo $tpl->currency($data['health_insurance']['insurance']) ?></td>
		</tr>
		<tr>
			<th>Zálohy na <?php echo $data['cfg']['year'] + 1 ?>:</th>
			<td><?php echo $tpl->currency($data['health_insurance']['deposit']) ?></td>
		</tr>
	</table>
	
	<h3>ČSSZ</h3>
	<table>
		<tr>
			<th>Pojištění za <?php echo $data['cfg']['year'] ?>:</th>
			<td><?php echo $tpl->currency($data['social_insurance']['insurance']) ?></td>
		</tr>
		<tr>
			<th>Zálohy na <?php echo $data['cfg']['year'] + 1 ?>:</th>
			<td><?php echo $tpl->currency($data['social_insurance']['deposit']) ?></td>
		</tr>
	</table>
	
<?php
	} catch (Exception $e) {
		echo '<p style="background: #8B0000; padding: 1em;"><strong style="color: #FFF;">[ERROR] ' . $e->getMessage() . '</strong></p>';
	}
}

?>

</body>
</html>
	