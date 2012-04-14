<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">

	<title><?=$title?> &ndash; Magic Rainbow Adventure!</title>
    <meta name="description" content="">
    <meta name="author" content="Joseph Wynn / Magic Rainbow Adventure!">

	<link href='http://fonts.googleapis.com/css?family=Coustard|Open+Sans:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" media="all" href="<?=URL::base();?>/basset/magicrainbowadventure.css">

	<script src="<?=URL::base();?>/assets/js/modernizr-2.5.3.min.js"></script>
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-25125418-2']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
</head>
<?php flush(); ?>

<body>

    <header>
		<div class="container_12" id="heading">
			<h1><?=HTML::link('/', 'Magic Rainbow Adventure!')?></h1>

			<nav id="main-navigation"><?=$navigation?></nav>
			<nav id="account-navigation"><?=$account_menu?></nav>
		</div>
	</header>

    <div id="content" class="container_12">
        <div id="main" class="grid_8">
			<h2><?=$title?></h2>
			<?=$content?>
        </div> <!-- #main -->

		<div id="sidebar" class="grid_4">

		</div>
	</div> <!-- #content -->

	<footer class="container_12">
		<ul id="footer-links">
			<li><?=HTML::link('about', 'About')?></li>
			<li><?=HTML::link('privacy', 'Privacy')?></li>
		</ul>

		<p id="copy-notice">Magic Rainbow Adventure! &copy; <?=date('Y')?>.</p>
	</footer>

    <script src="<?=URL::base();?>/basset/default.js"></script>

</body>
</html>
