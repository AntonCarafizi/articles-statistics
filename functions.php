<?php 

function db_connect($server, $user, $passwd){
	$link = mssql_connect($server, $user, $passwd);
	if (!$link) {
		echo 'Keine Verbindung ...<br>';
		exit;
	}
	mssql_select_db('[wawi]', $link);
	return $link;
}

function select_filter($link, $sql){
	//echo $sql;
	$query = mssql_query($sql, $link);
	$results = [];
	if (!mssql_num_rows($query)) {
   // echo 'Keine Ergebnisse gefunden';
} else {
	while ($row = mssql_fetch_row($query)) {
		$results[$row[0]] = $row[1];
		}
	}
	return $results;
}


function select_results($link, $sql){
	$query = mssql_query($sql, $link);
	$results = [];
	if (!mssql_num_rows($query)) {
    //echo 'Keine Ergebnisse gefunden';
} else {
	while ($row = mssql_fetch_object($query)) {
			$results[] = $row;
		}
	}
	return $results;
}

function get_page($url, $page){
	$str = substr($url, 0, strrpos($url, '=') + 1);
	return $str . $page;
}

function get_image($pic){
	$url = 'http://wawineu.spohr.local/artikelverwaltung/imgArtikel100/' . $pic . '.jpg';
	$handle = curl_init($url);
	curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
	/* Get the HTML or whatever is linked in $url. */
	$response = curl_exec($handle);
	/* Check for 404 (file not found). */
	$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	if($httpCode == 404) {
	    $url = '/img/kein_bild.png';
	}
	curl_close($handle);
	/* Handle $response here. */
	return $url;
}

// DB connect
$link = db_connect('192.168.53.245\wawineu', 'wawi_r', 'wawi_r');

// FILTERS
$filter_sqlscript 		= select_filter($link, "SELECT id, Name  FROM avs.artikelsuche ORDER BY sortierung;");
$filter_hersteller 		= select_filter($link, "SELECT FirmenID, Matchcode FROM dbo.Firmen WHERE AKZ = 1 AND Lieferantennummer > 0 AND Matchcode != '' ORDER BY Matchcode;");
$filter_sid 			= select_filter($link, "SELECT SID, CASE WHEN aktuell = 1 THEN SaisonName + ' ist aktuell ' ELSE SaisonName END AS SaisonName FROM dbo.Saison WHERE SID > 9 ORDER BY aktuell DESC; ");
$filter_kategorieEbene1 = select_filter($link, "SELECT DISTINCT KatID_Ebene1, KategorieEbene1 FROM statistik.Kategorie_artikelzuordnungen ORDER BY KatID_Ebene1; ");
$filter_kategorieEbene2 = select_filter($link, "SELECT DISTINCT KatID_Ebene2, KategorieEbene2 FROM statistik.Kategorie_artikelzuordnungen ORDER BY KatID_Ebene2; ");
$filter_kategorieEbene3 = select_filter($link, "SELECT DISTINCT KatID_Ebene3, KategorieEbene3 FROM statistik.Kategorie_artikelzuordnungen ORDER BY KatID_Ebene3; ");



// RESULTS
$sid = ($_GET['statistikSaison']) ? $_GET['statistikSaison'] : 'SID';

switch ($_GET['sort']) 
{
	case '1' : $order_by = " ORDER BY ss.Matchcode, ss.Logo, ss.Artikelnummer ASC "; break;
	case '2' : $order_by = " ORDER BY ss.Matchcode, ss.Logo, ss.LiefArt ASC "; break;
	case '3' : $order_by = " ORDER BY ss.Matchcode, ss.Logo, ss.sumFakt DESC "; break;
	case '4' : $order_by = " ORDER BY ss.Matchcode, ss.Logo, ss.Bestand_GES DESC "; break;
	case '5' : $order_by = " ORDER BY ss.Matchcode, ss.Logo, ss.AbverkaufQ DESC "; break;
}

$select = " SELECT DISTINCT
	ROW_NUMBER() OVER(" . $order_by . ") AS Row
	,ss.Artikelnummer AS Art
	,ss.Artikelnummer AS Picture 
	,ss.Logo AS Logo
	,ss.Matchcode AS Matchcode
	,a.Extras3 AS Mod
	,ss.LiefArt AS LiefArt
	,ss.LiefFb AS LiefFb
	,ss.LiefMat AS LiefMat
	,ss.WEinsatz AS WE
	,ss.posFakt AS Verkauft
	,ss.negFakt*(-1) AS Retour
	,ss.sumFakt AS Fakt
	,ss.Bestand_GES AS Lag
	,ss.AbverkaufQ AS AQ
	,ss.offLief AS [off]
	,ss.offLief2 AS [off2]
	,ss.RetourQ AS RetQ
	,ss.ReklaQ AS RekQ
	,ss.Aktion AS Akt
	,ss.[€Rohertrag] AS Rohertrag
    ,ss.[€Kalk_Kosten] AS Kalk_Kosten
    ,ss.[€Kalk_Ertrag] AS Kalk_Ertrag
	FROM statistik.[statistik_VK_{$sid}]
	AS ss 
	JOIN dbo.Artikel_Stamm AS a ON (a.Artikelnummer = ss.Artikelnummer) 
	JOIN dbo.Logo AS l ON (a.id_logo = l.id_logo) ";

$select_overall = " SELECT
	SUM(ss.WEinsatz) AS WE
	,SUM(ss.sumFakt) AS Fakt
	,SUM(ss.Bestand_GES) AS Lag
	,(100 - (SUM(ss.Bestand_GES)*100/SUM(ss.WEinsatz))) AS AQ
	,AVG(ss.RetourQ) AS RetQ
	,AVG(ss.ReklaQ) AS RekQ
	FROM statistik.[statistik_VK_{$sid}]
	AS ss 
	JOIN dbo.Artikel_Stamm AS a ON (a.Artikelnummer = ss.Artikelnummer) 
	JOIN dbo.Logo AS l ON (a.id_logo = l.id_logo) ";

$select_qty = " SELECT COUNT(*) AS count FROM statistik.[statistik_VK_{$sid}] AS ss JOIN dbo.Artikel_Stamm AS a ON (a.Artikelnummer = ss.Artikelnummer) ";
$select_by_id = " SELECT
	   vk.Matchcode AS Matchcode
      ,vk.ArtSID AS ArtSID
      ,vk.ArtikelNummer AS ArtikelNummer
      ,vk.Shopbeschreibung AS Shopbeschreibung
      ,vk.sumFakt AS Fakt
      ,vk.Bestand_GES AS Lager
      ,vk.AbverkaufQ AS AbvQ
      ,vk.LiefArt AS LiefArt
      ,vk.LiefFb AS LiefFb
      ,vk.LiefMat AS LiefMat
      ,a.Extras3 AS Mod
      ,vk.RST AS RST
      ,vk.lieferbar AS Lieferbar
      ,vk.rekla AS Rekla
      ,vk.offLief AS OffLief ";
if ($sid == 'SID') { 
	$select_by_id .= ",vk.offLief2 AS OffLief2
					  ,vk.[€Rohertrag] AS Rohertrag
      				  ,vk.[€Kalk_Kosten] AS Kalk_Kosten
     	 			  ,vk.[€Kalk_Ertrag] AS Kalk_Ertrag ";
}
$select_by_id .= ",vk.RetourQ AS RetourQ
      ,vk.ReklaQ AS ReklaQ
      ,vk.[€EKPaarBruttoMin] AS EKPaarBruttoMin
      ,vk.[€EKPaarBruttoMax] AS EKPaarBruttoMax
      ,vk.LiefMat AS LiefMat
  FROM statistik.[statistik_VK_{$sid}] AS vk
  JOIN dbo.Artikel_Stamm AS a ON (a.Artikelnummer = vk.Artikelnummer)
  WHERE vk.ArtikelNummer = {$_GET['Artikelnummer']} ";

$select_by_id_sid = " SELECT
  vkgr.Groesse AS Groesse
  ,vkgr.Bestand_HL AS Anzahl
  ,vkgr.Bestand_LAD AS Laden   
  ,vkgr.RST AS Rueckstand 
  ,vkgr.offLief AS Offene_Lief
  ,vkgr.offLief2 AS Offene_Lief2
  ,vkgr.sumFakt AS Fakt_aktuelle_Saison
  FROM statistik.[statistik_VK_GR_SID] AS vkgr
  JOIN dbo.Größenleisten AS gl ON (gl.Größe = vkgr.Groesse)
  WHERE vkgr.ArtikelNummer = {$_GET['Artikelnummer']} AND gl.id_leisten = '7' ORDER BY gl.sortierung ";

$select_by_id_sid_1 = " SELECT
  vkgr.Groesse AS Groesse
  ,vkgr.Bestand_HL AS Anzahl
  ,vkgr.Bestand_LAD AS Laden   
  ,vkgr.RST AS Rueckstand 
  ,vkgr.offLief AS Offene_Lief
  ,vkgr.offLief2 AS Offene_Lief2
  ,vkgr.sumFakt AS Fakt_aktuelle_Saison
  FROM statistik.[statistik_VK_GR_SID-1] AS vkgr
  JOIN dbo.Größenleisten AS gl ON (gl.Größe = vkgr.Groesse)
  WHERE vkgr.ArtikelNummer = {$_GET['Artikelnummer']} AND gl.id_leisten = '7' ORDER BY gl.sortierung ";

$select_by_id_sid_2 = " SELECT
  vkgr.Groesse AS Groesse
  ,vkgr.Bestand_HL AS Anzahl
  ,vkgr.Bestand_LAD AS Laden   
  ,vkgr.RST AS Rueckstand 
  ,vkgr.offLief AS Offene_Lief
  ,vkgr.offLief2 AS Offene_Lief2
  ,vkgr.sumFakt AS Fakt_aktuelle_Saison
  FROM statistik.[statistik_VK_GR_SID-2] AS vkgr
  JOIN dbo.Größenleisten AS gl ON (gl.Größe = vkgr.Groesse)
  WHERE vkgr.ArtikelNummer = {$_GET['Artikelnummer']} AND gl.id_leisten = '7' ORDER BY gl.sortierung ";

$join = " ";

//$order_by = '';

switch ($_GET['sort']) 
{
	case '1' : $order_by = " ORDER BY Matchcode, Logo, Art ASC "; break;
	case '2' : $order_by = " ORDER BY Matchcode, Logo, LiefArt ASC "; break;
	case '3' : $order_by = " ORDER BY Matchcode, Logo, Fakt DESC "; break;
	case '4' : $order_by = " ORDER BY Matchcode, Logo, Lag DESC "; break;
	case '5' : $order_by = " ORDER BY Matchcode, Logo, AQ DESC "; break;
}



$where = " WHERE 1=1 ";
$per_page = 20;
$parent_where = ($_GET['page']) ? " WHERE Row > {$per_page}*({$_GET['page']}-1) AND Row <= {$per_page}*{$_GET['page']} " : "";


if($_GET['hersteller'] || $_GET['artikel_sid']){
	$where .= " AND a.Freigabe = 1 ";
}

if($_GET['hersteller']) {
	$join .= " JOIN dbo.Firmen AS f ON (f.FirmenID = a.id_lieferant AND f.AKZ = 1 AND f.FirmenID IN (" . implode(",",$_GET['hersteller']) . ")) ";
}

if($_GET['artikel_sid']) {
	$where .= " AND a.Saison IN (" . implode(",",$_GET['artikel_sid']) . ") ";
}

if($_GET['bestand'] || $_GET['compare']) {
	$join .= " JOIN dbo.msp_Lagerbestand_gesamt AS msplg ON (msplg.ArtikelNummer = a.Artikelnummer) ";
	$where .= " AND msplg.Lagergesamt {$_GET['compare']} " .(int)$_GET['bestand'] . " ";
}

if($_GET['aktueller_katalog']) { 
	$where .= " AND a.katalog1 = 1 ";
}

if($_GET['letzter_katalog']) { 
	$where .= " AND a.katalog0 = 1 ";
}

if($_GET['kategorieebene1'] || $_GET['kategorieebene2'] || $_GET['kategorieebene3']) { 
	$join .= " JOIN statistik.Kategorie_artikelzuordnungen AS ka ON (ka.Artikelnummer = a.Artikelnummer) ";
}

if($_GET['kategorieebene1']) {
	$where .= " AND ka.KatID_Ebene1 IN (" . implode(",",$_GET['kategorieebene1']) . ") ";
}

if($_GET['kategorieebene2']) { 
	$where .= " AND ka.KatID_Ebene2 IN (" . implode(",",$_GET['kategorieebene2']) . ") ";
}
if($_GET['kategorieebene3']) {
	$where .= " AND ka.KatID_Ebene3 IN (" . implode(",",$_GET['kategorieebene3']) . ") ";
}

if ($_GET['search']) {
	$where .= " AND ss.Artikelnummer IN ('{$_GET["search"]}') OR ss.Matchcode LIKE '%{$_GET["search"]}%' OR ss.LiefArt LIKE '%{$_GET["search"]}%' ";
}

/*if($_GET['statistikSaison']) {
	$join .= " JOIN [statistik].[statistik_VK_" . $_GET['statistikSaison'] . "] AS ss ON (ss.Artikelnummer = a.Artikelnummer) ";
} */

if($_GET['sql_script']) {
	$selectFromArtikelsuche = select_results($link, " SELECT sqlText FROM avs.Artikelsuche WHERE id = " .$_GET['sql_script']." ");
	$sqltext = array_shift($selectFromArtikelsuche)->sqlText;
	/* switch ($_GET['sql_script']) 
	{
		case '26' : $join .=  " JOIN [statistik].[statistik_VK_GR_{$sid}] AS ssGR ON (ssGR.AID = a.ArtikelID AND ssGR.VKProzent > 30) JOIN dbo.Saison AS s ON (s.SID = a.Saison AND s.aktuell = 1) "; break;
		case '28' : $join .=  " JOIN [statistik].[statistik_VK_GR_{$sid}] AS ssGR ON (ssGR.AID = a.ArtikelID AND ssGR.VKProzent > 40) JOIN dbo.Saison AS s ON (s.SID = a.Saison AND s.aktuell = 1) "; break;
		case '29' : $join .=  " JOIN [statistik].[statistik_VK_GR_{$sid}] AS ssGR ON (ssGR.AID = a.ArtikelID AND ssGR.VKProzent > 50) JOIN dbo.Saison AS s ON (s.SID = a.Saison AND s.aktuell = 1) "; break;
		case '30' : $join .=  " JOIN [statistik].[statistik_VK_GR_{$sid}] AS ssGR ON (ssGR.AID = a.ArtikelID AND ssGR.VKProzent > 60) JOIN dbo.Saison AS s ON (s.SID = a.Saison AND s.aktuell = 1) "; break;
		case '31' : $join .=  " JOIN [statistik].[statistik_VK_GR_{$sid}] AS ssGR ON (ssGR.AID = a.ArtikelID AND ssGR.VKProzent > 70) JOIN dbo.Saison AS s ON (s.SID = a.Saison AND s.aktuell = 1) "; break;
		case '35' : $join .=  " JOIN [statistik].[statistik_VK_GR_{$sid}] AS ssGR ON (ssGR.AID = a.ArtikelID AND ssGR.VKProzent > 50) JOIN dbo.Saison AS s ON (s.SID = a.Saison AND s.aktuell = 1) JOIN dbo.Preislisten AS pl ON (pl.AID = a.ArtikelID AND pl.aktiv = 1 AND pl.AktionsID IN (0,250)) "; break;
		default: $join .= $sqltext1[1];
	
	}*/

	$where .= ' AND a.Artikelnummer IN ( ' . $sqltext . ' ) ' ;
}

//$select .= $join . $where . $order_by;
$parent_select .= " SELECT DISTINCT
	main.Picture
	,main.Art
	,main.Logo
	,main.Matchcode
	,main.Mod
	,main.LiefArt
	,main.LiefFb
	,main.LiefMat
	,main.WE
	,main.Fakt
	,main.Lag
	,main.AQ
	,main.[off]
	,main.[off2]
	,main.RetQ
	,main.RekQ
	,main.Akt
	FROM ({$select} {$join} {$where}) AS main {$parent_where} {$order_by} ";


$count_select = $select_qty . $join . $where . ';';
$select_overall = $select_overall . $join . $where . ';';
//echo $count_select;
if ($_GET['sql_script'] == '8' or $_GET['hersteller']) { $select = $select . $join . $where . $order_by; }

$final_select = ($_GET['hersteller']) ? $select : $parent_select;

//if(!$_GET['Artikelnummer']) { echo $final_select; }

if (!$_GET['Artikelnummer'] && $_GET['statistikSaison']) { 
	$results = select_results($link, $final_select); 
	$results_overall = select_results($link, $select_overall); 
}

if($_GET['Artikelnummer'])  { 
	$result = select_results($link, $select_by_id);
	$result_sid = select_results($link, $select_by_id_sid);
	$result_sid_1 = select_results($link, $select_by_id_sid_1);
	$result_sid_2 = select_results($link, $select_by_id_sid_2); 
}
//echo $select_overall;
if (!$_GET['hersteller']) { $count_select = select_results($link, $count_select); $count = array_shift($count_select)->count; }

?>