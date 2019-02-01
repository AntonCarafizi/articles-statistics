<?php include 'functions.php'; ?>
<!DOCTYPE html lang="de">
<html>
<head>
<meta charset="utf-8">
<title>Verkaufs Statistik</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
</head>
<body>
	<header><a href="/" class="logo"></a></header>
	<div class="main">
		<h1>Verkaufs Statistik</h1>
		<h2><?php echo $result->Shopbeschreibung; ?></h2>
		<article>
		<?php  //echo '<div><p><pre>'->$select_by_id->'</pre></p></div> ?>
		<?php if ($result) {  ?>
    			<div id="content" class="container"><img style="max-width: 200px; max-height: 200px;" src="img/imgArtikel/<?php echo $result[0]->ArtikelNummer; ?>.jpg">
			    <table class="primary"><tr><td>Matchcode:</td><td><span><?php echo $result[0]->Matchcode; ?></span></td></tr>
			    <tr><td>ArtSID:</td><td><span><?php echo $result[0]->ArtSID; ?></span></td></tr>
			    <tr><td>ArtikelNummer:</td><td><span><?php echo $result[0]->ArtikelNummer; ?></span></td></tr>
			    <tr><td>Fakt:</td><td><span><?php echo $result[0]->Fakt; ?></span></td></tr>
			    <tr><td>Lager:</td><td><span><?php echo $result[0]->Lager; ?></span></td></tr>
			    <tr><td>AbvQ:</td><td><span><?php echo $result[0]->AbvQ; ?></span></td></tr></table>
	        	<table class="secondary"><tr><td width="150">LiefArt:</td><td><span><?php echo $result[0]->LiefArt; ?></span></td></tr>
	        	<tr><td width="150">LiefFb:</td><td><span><?php echo $result[0]->LiefFb; ?></span></td></tr>
	        	<tr><td width="150">LiefMat:</td><td><span><?php echo $result[0]->LiefMat; ?></span></td></tr>
	        	<tr><td width="150">RST:</td><td><span><?php echo $result[0]->RST; ?></span></td></tr>
	        	<tr><td width="150">Lieferbar:</td><td><span><?php echo $result[0]->Lieferbar; ?></span></td></tr>
	        	<tr><td width="150">OffLief:</td><td><span><?php echo $result[0]->OffLief; ?></span></td></tr>
	        	<?php if ($_GET['statistikSaison'] == 'SID') { ?>
	        		<tr><td width="150">OffLief2:</td><td><span><?php echo $result[0]->OffLief2; ?></span></td></tr>
	        	<?php } ?>
	        	<tr><td width="150">RetourQ:</td><td><span><?php echo $result[0]->RetourQ; ?></span></td></tr>
	        	<tr><td width="150">ReklaQ:</td><td><span><?php echo $result[0]->ReklaQ; ?></span></td></tr>
	        	<tr><td width="150">€EKPaarBruttoMin:</td><td><span><?php echo $result[0]->EKPaarBruttoMin; ?></span></td></tr>
	        	<tr><td width="150">€EKPaarBruttoMax:</td><td><span><?php echo $result[0]->EKPaarBruttoMax; ?></span></td></tr>
	        	<?php if ($_GET['statistikSaison'] == 'SID') { ?>
		        	<tr><td width="150">€Rohertrag:</td><td><span><?php echo $result[0]->Rohertrag; ?></span></td></tr>
		        	<tr><td width="150">€Kalk Kosten:</td><td><span><?php echo $result[0]->Kalk_Kosten; ?></span></td></tr>
		        	<tr><td width="150">€Kalk Ertrag:</td><td><span><?php echo $result[0]->Kalk_Ertrag; ?></span></td></tr>
	        	<?php } ?>
	        	<tr><td width="150">LiefMat:</td><td><span><?php echo $result[0]->LiefMat; ?></span></td></tr></table>
	        	<table class="secondary"><tr><td width="150">Größen:</td>
        		<?php foreach ($result as $key => $value) { ?>
		        	<td><span><?php echo $value->Groesse; ?></span></td>
		        <?php } ?>
		        </tr><tr><td width="150">Anzahl:</td>
        		<?php foreach ($result as $key => $value) { ?>
		        	<td><span><?php echo $value->Anzahl; ?></span></td>
		        <?php } ?>
		        </tr><tr><td width="150">Laden:</td>
        		<?php foreach ($result as $key => $value) { ?>
		        	<td><span><?php echo $value->Laden; ?></span></td>
		        <?php } ?>
		        </tr><tr><td width="150">Rückstand:</td>
        		<?php foreach ($result as $key => $value) { ?>
		        	<td><span><?php echo $value->Rueckstand; ?></span></td>
		        <?php } ?>
		        </tr><tr><td width="150">Offene Lief:</td>
        		<?php foreach ($result as $key => $value) { ?>
		        	<td><span><?php echo $value->Offene_Lief; ?></span></td>
		        <?php } ?>
		        <?php if ($_GET['statistikSaison'] == 'SID') { ?>
			        </tr><tr><td width="150">Offene Lief2:</td>
	        		<?php foreach ($result as $key => $value) { ?>
			        	<td><span><?php echo $value->Offene_Lief2; ?></span></td>
			        <?php } ?>
		    	<?php } ?>
		        </tr><tr><td width="150">Fakt letzte Saison:</td>
        		<?php foreach ($result as $key => $value) { ?>
		        	<td><span><?php echo $value->Fakt_letzte_Saison; ?></span></td>
		        <?php } ?>
		        </tr><tr><td width="150">Fakt aktuelle Saison:</td>
        		<?php foreach ($result as $key => $value) { ?>
		        	<td><span><?php echo $value->Fakt_aktuelle_Saison; ?></span></td>
		        <?php } ?>
		        </tr>
        		</table>
		<?php } ?>
		</article>
	</div>
	<footer></footer>
</body>
</html>
