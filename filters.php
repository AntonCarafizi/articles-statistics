<form action="" method="get" id="filter">
<fieldset>
 <legend>Filter:</legend>
 <ul>
 	<li><label for="search">Suchen:</li></label>
 	<input type="search" name="search" id="search" placeholder="Artikelnummer, LiefArt, Matchcode" value="<?php if ($_GET['search']) echo $_GET['search']; ?>">
	<li><label for="hersteller">Hersteller:</label>
	<select name="hersteller[]" size="10" id="hersteller" multiple="multiple">
	<?php foreach ($filter_hersteller as $key => $value) { ?>
	<option value="<?php echo $key; ?>" <?php if ($_GET['hersteller']) { foreach ($_GET['hersteller'] as $single) { if($single == $key) { ?> selected <?php } } } ?>><?php echo $value; ?></option>
	<?php } ?>
	</select></li>
	<li><label for="artikel_sid">Artikel SID:</label>
	<select name="artikel_sid[]" size="10" id="artikel_sid" multiple="multiple">
	<?php foreach ($filter_sid as $key => $value) { ?>
	<option value="<?php echo $key; ?>" <?php if ($_GET['artikel_sid']) { foreach ($_GET['artikel_sid'] as $single) { if($single == $key) { ?> selected <?php } } } ?>><?php echo $value; ?></option>
	<?php } ?>
	</select></li>
	<li><label for="kategorieebene1">Kategorieebene1:</label>
	<select name="kategorieebene1[]" size="4" id="kategorieebene1" multiple="multiple">
	<?php foreach ($filter_kategorieEbene1 as $key => $value) { ?>
	<option value="<?php echo $key; ?>" <?php if ($_GET['kategorieebene1']) { foreach ($_GET['kategorieebene1'] as $single) { if($single == $key) { ?> selected <?php } } } ?>><?php echo $value; ?></option>
	<?php } ?>
	</select></li>
	<li><label for="kategorieebene2">Kategorieebene2:</label>
	<select name="kategorieebene2[]" size="10" id="kategorieebene2" multiple="multiple">
	<?php foreach ($filter_kategorieEbene2 as $key => $value) { ?>
	<option value="<?php echo $key; ?>" <?php if ($_GET['kategorieebene2']) { foreach ($_GET['kategorieebene2'] as $single) { if($single == $key) { ?> selected <?php } } } ?>><?php echo $value; ?></option>
	<?php } ?>
	</select></li>
	<li><label for="kategorieebene3">Kategorieebene3:</label>
	<select name="kategorieebene3[]" size="10" id="kategorieebene3" multiple="multiple">
	<?php foreach ($filter_kategorieEbene3 as $key => $value) { ?>
	<option value="<?php echo $key; ?>" <?php if ($_GET['kategorieebene3']) { foreach ($_GET['kategorieebene3'] as $single) { if($single == $key) { ?> selected <?php } } } ?>><?php echo $value; ?></option>
	<?php } ?>
	</select></li>
	<li><label for="statistikSaison">StatistikSaison:</label>
	<select name="statistikSaison" id="statistikSaison">
	<option value="">Bitte wählen sie</option>
	<option value="SID" <?php if($_GET['statistikSaison'] == 'SID' || !$_GET['statistikSaison']) { ?> selected <?php } ?>>SID</option>
	<option value="SID-1" <?php if($_GET['statistikSaison'] == 'SID-1') { ?> selected <?php } ?>>SID-1</option>
	</select></li>
	<li><label for="sql_script">SQL Script:</label>
	<select name="sql_script" id="sql_script">
	<option value="">Bitte wählen sie</option>
	<?php foreach ($filter_sqlscript as $key => $value) { ?>
	<option value="<?php echo $key; ?>" <?php if($_GET['sql_script'] == $key) { ?> selected <?php } ?>><?php echo $value; ?></option>
	<?php } ?>
	</select>
	</li>
	<li><label for="bestand">Bestand</label>
	<select name="compare" id="compare">
		<option value="" selected></option>
		<option value=">" <?php if($_GET['compare'] == '>') { ?> selected <?php } ?>> > </option>
		<option value="<" <?php if($_GET['compare'] == '<') { ?> selected <?php } ?>> < </option>
		<option value=">=" <?php if($_GET['compare'] == '>=') { ?> selected <?php } ?>> >= </option>
		<option value="<=" <?php if($_GET['compare'] == '<=') { ?> selected <?php } ?>> <= </option>
		<option value="=" <?php if($_GET['compare'] == '=') { ?> selected <?php } ?>> = </option>
	</select>
	<select name="bestand" id="bestand">
		<option value="" selected></option>
		<option value="0" <?php if($_GET['bestand'] == '0') { ?> selected <?php } ?>>0</option>
		<option value="10" <?php if($_GET['bestand'] == '10') { ?> selected <?php } ?>>10</option>
		<option value="20" <?php if($_GET['bestand'] == '20') { ?> selected <?php } ?>>20</option>
		<option value="30" <?php if($_GET['bestand'] == '30') { ?> selected <?php } ?>>30</option>
		<option value="40" <?php if($_GET['bestand'] == '40') { ?> selected <?php } ?>>40</option>
		<option value="50" <?php if($_GET['bestand'] == '50') { ?> selected <?php } ?>>50</option>
	</select>
	</li>
	<li><label for="aktueller_katalog">Aktueller Katalog: </label>
		<input type="checkbox" name="aktueller_katalog" id="aktueller_katalog" value="1" <?php if($_GET['aktueller_katalog'] == 1) { ?> checked="checked" <?php } ?>></li>
		<li><label for="letzter_katalog">Letzter Katalog: </label>
		<input type="checkbox" name="letzter_katalog" id="letzter_katalog" value="1" <?php if($_GET['letzter_katalog'] == 1) { ?> checked="checked" <?php }?>></li>
		<li>
			<label for="bestand">Sortierung</label>
			<select name="sort" id="sort">
				<option value="1" <?php if($_GET['sort'] == '1') { ?> selected <?php } ?>>Art</option>
				<option value="2" <?php if($_GET['sort'] == '2') { ?> selected <?php } ?>>LiefArt</option>
				<option value="3" <?php if($_GET['sort'] == '3') { ?> selected <?php } ?>>Fakt</option>
				<option value="4" <?php if($_GET['sort'] == '4') { ?> selected <?php } ?>>Lag</option>
				<option value="5" <?php if($_GET['sort'] == '5') { ?> selected <?php } ?>>AQ</option>
			</select>
		</li>
	</ul>
	<input type="submit" name="submit"  id="submit" value="Suchen"> <a class="filter_remove" href="/artikelstat/">Filter löschen</a>
	<input type="hidden" name="page" value="1">
</fieldset>
</form>