<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$title?> &ndash; Magic Rainbow Adventure!</title>
    <meta name="description" content="">
	<meta name="viewport" content="width=device-width">

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

<body class="<?=(Auth::check()) ? 'logged-in' : 'guest'?>">

    <header>
		<div class="container" id="heading">
			<div class="row">
				<h1 class="fivecol"><?=HTML::link('/', 'Magic Rainbow Adventure!')?></h1>

				<nav id="main-navigation" class="threecol"><?=$navigation?></nav>
				<nav id="account-navigation" class="fourcol last"><?=$account_menu?></nav>
			</div>
		</div>
	</header>

    <div id="content" class="container">
		<div class="row">
			<div class="twelvecol">
				<h2><?=$title?></h2>
			</div>
		</div>

		<?=View::make($content_layout, array(
			'content' => $content
		))?>

	</div> <!-- #content -->

	<footer class="container">
		<div class="row">
			<div class="eightcol">
				<p>Magic Rainbow Adventure! &copy; <?=date('Y')?>.</p>
			</div>

			<ul id="footer-links" class="fourcol last">
				<li><?=HTML::link('about', 'About')?></li>
				<li><?=HTML::link('privacy', 'Privacy')?></li>
			</ul>
		</div>
	</footer>

	<?=$login_required_popover?>

    <script src="<?=URL::base();?>/basset/default.js"></script>
	<?=Basset::inline('assets')->scripts();?>

</body>
</html>
