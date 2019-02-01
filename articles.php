<?php if ($results) { ?>

<!--Pagination-->
<ul><?php if ($count > $per_page) { ?>
<?php for($i = 1; $i < ($count/$per_page)+1; $i++) { ?>
<li><?php if($_GET['page'] == $i) { ?>
	<span class="active"><?php echo $i; ?></span>
	<?php } else { ?><a href="<? echo get_page($_SERVER['REQUEST_URI'], $i); ?>"><?php echo $i; ?></a><?php } ?>
</li><?php } ?>
<?php } ?></ul>
<h2><?php echo ($count) ? $count : count($results); ?> Ergebnisse</h2>
<button id="pdf">PDF herunterladen</button>
<?php $logos = []; $last = count($results)-1; ?>
<?php foreach ($results as $key => $values) { ?>
<?php array_push($logos, $values->Logo); $logos = array_unique($logos); ?>
<?php } ?>

<!--Logos-->
<div class="anchors">
	<ul>
	<?php $logos_sum_keys = []; ?>
	<?php foreach ($logos as $key => $logo) { ?>
		<?php array_push($logos_sum_keys, $key-1, $last); ?>
		<?php echo '<li><a href="#logo_' . $key . '">'.$logo.'</a></li>'; ?>
	<?php } ?>
	</ul>
</div>

<!--Results-->
<div id="articles" class="articles">
<?php $we = $fakt = $lag = $aq = $retq = $rekq = 0; ?>
<?php foreach ($results as $key => $values) { ?>
<?php if (in_array($key, array_keys($logos))) { $we = $fakt = $lag = $aq = $retq = $rekq = 0; ?>
<?php echo trim('<h3 id="logo_' . $key . '">Lieferant '.$values->Logo.'</h3>'); ?>
<?php } ?>
<div class="artikel_info">
<a class="article_link" artikelnummer="<?php echo $values->Art; ?>" sid="<?php echo $_GET['statistikSaison']; ?>" href="<?php echo '/verkaufs/article.php?Artikelnummer='. $values->Art; ?>&statistikSaison=<?php echo $_GET['statistikSaison']; ?>" onclick="return false;">
<table>
 <tr>
 	<td class="img_td" rowspan="4"><?php echo '<img class="img" src="'.get_image(trim($values->Picture)).'"> '; ?></td>
 	<td colspan="4"><?php echo trim('<span class="label">Art:</span> <span class="bold">'.$values->Art.'</span>'); ?></td>
 	<td colspan="5"><?php echo trim('<span class="label">Logo:</span> <span class="normal">'.$values->Logo.'</span>'); ?></td>  
 </tr>
 <tr>
 	<td colspan="4"><?php echo trim('<span class="label">Mod:</span> <span class="normal">'.$values->Mod.'</span>'); ?></td>
 	<td colspan="5"><?php echo trim('<span class="label">LiefArt:</span> <span class="normal">'.$values->LiefArt.'</span>'); ?></td>
 </tr>
 <tr>
 	<td colspan="4"><?php echo trim('<span class="label">LiefFb:</span> <span class="normal">'.$values->LiefFb.'</span>'); ?></td>
 	<td colspan="5"><?php echo trim('<span class="label">LiefMat:</span> <span class="normal">'.$values->LiefMat.'</span>'); ?></td>
 </tr>
 <tr>
 	<td><?php echo trim('<span class="label">WE:</span><br><span class="bold">'.$values->WE.'</span>'); ?></td>
 	<td><?php echo trim('<span class="label">Fakt:</span><br><span class="bold big green">'.$values->Fakt.'</span>'); ?></td>
 	<td><?php echo trim('<span class="label">Lag:</span><br><span class="bold big red">'.$values->Lag.'</span>'); ?></td>
 	<td><?php echo trim('<span class="label">AQ:</span><br><span class="bold big blue">'.$values->AQ.'</span>'); ?></td>
 	<td><?php echo trim('<span class="label">off:</span><br><span class="bold">'.$values->off.'</span>'); ?></td>
 	<td><?php echo trim('<span class="label">off2:</span><br><span class="bold">'.$values->off2.'</span>'); ?></td>
 	<td><?php echo trim('<span class="label">RetQ:</span><br><span class="bold">'.$values->RetQ.'</span>'); ?></td>
 	<td><?php echo trim('<span class="label">RekQ:</span><br><span class="bold">'.$values->RekQ.'</span>'); ?></td>
 	<td><?php echo trim('<span class="label">Akt:</span><br><span class="bold">'.$values->Akt.'</span>'); ?></td>
 </tr>
 <tr>
 	<td>&nbsp;</td>
 	<td colspan="3"><?php echo trim('<span class="label">Rohertrag:</span><br><span class="bold">' . number_format($values->Rohertrag, 2, ',', ' ') .' &euro;</span>'); ?></td>
 	<td colspan="3"><?php echo trim('<span class="label">Kalkulatorische Kosten:</span><br><span class="bold">' . number_format($values->Kalk_Kosten, 2, ',', ' ') . ' &euro;</span>'); ?></td>
 	<td colspan="3"><?php echo trim('<span class="label">Kalkulatorischer Ertrag:</span><br><span class="bold">' . number_format($values->Kalk_Ertrag, 2, ',', ' ') . ' &euro;</span>'); ?></td>
 </tr>
</table>
</a>
</div>
 <?php $we 		+= $values->WE; 
 	   $fakt 	+= $values->Fakt;
 	   $verkauft += $values->Verkauft;
 	   $retour  += $values->Retour;
 	   $lag 	+= $values->Lag;
 	   $aq 		= ($we > 0) ? ceil(100 - ($lag*100/$we)) : 0;
 	   $retq 	= ($verkauft > 0) ? ceil(($retour * 100)/($verkauft)) : 0;
 	   $rekq 	+= $values->RekQ;
 	   $matchcode = $values->Matchcode;
 	   ?>
 	<?php if (in_array($key, $logos_sum_keys)) { ?>
	<div class="total">
	<table>
		<tr><td>Weinsatz Summe</td><td>Fakt Summe</td><td>Lag Summe</td><td>AbvQ durchschnittlich</td><td>RetQ durchschnittlich</td><td>RekQ durchschnittlich</td></tr>
		<tr class="topline">
			<td><?php echo $we; ?></td>
			<td><span class="bold big green"><?php echo $fakt; ?></span></td>
			<td><span class="bold big red"><?php echo $lag; ?></span></td>
			<td><span class="bold big blue"><?php echo $aq; ?></span></td>
			<td><?php echo $retq; ?></td>
			<td><?php echo $rekq; ?></td>
		</tr>
	</table>
	</div>
<?php } } ?>
<h3>Gesamtsumme <?php if ($_GET['hersteller']) { echo $matchcode; } ?></h3>
<div class="total">
<table>
	<tr><td>Weinsatz Summe</td><td>Fakt Summe</td><td>Lag Summe</td><td>AbvQ durchschnittlich</td><td>RetQ durchschnittlich</td><td>RekQ durchschnittlich</td></tr>
	<tr class="topline">
		<?php foreach ($results_overall as $key => $value) { ?>
			<td><?php echo $value->WE; ?></td>
			<td><span class="bold big green"><?php echo $value->Fakt; ?></span></td>
			<td><span class="bold big red"><?php echo $value->Lag; ?></span></td>
			<td><span class="bold big blue"><?php echo $value->AQ; ?></span></td>
			<td><?php echo $value->RetQ; ?></td>
			<td><?php echo $value->RekQ; ?></td>
		<?php } ?>
	</tr>
</table>
</div>
</div>
<div id="article">
</div>
<div class="wait"></div>
<?php } else { ?>
<h2>Keine Ergebnisse gefunden</h2>
<?php } ?>