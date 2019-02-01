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
 <nav></nav>
 <div class="main">
 <h1>Artikel Statistik</h1>
 <article>
	<nav><aside><?php include 'filters.php'; ?></aside></nav>
	<?php if($_GET) { include 'articles.php'; } ?>
 </article>
</div>
<footer></footer>
<script type="text/javascript" src="js/script.js"></script>
</body>
</html>