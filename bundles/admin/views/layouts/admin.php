<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$title?> &ndash; Magic Rainbow Adventure Admin Interface</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">

	<link href='http://fonts.googleapis.com/css?family=Coustard|Open+Sans:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" media="all" href="<?=URL::base();?>/basset/magicrainbowadventure.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?=URL::base();?>/basset/admin.css">

	<script src="<?=URL::base();?>/assets/js/modernizr-2.5.3.min.js"></script>
</head>
<?php flush(); ?>

<body class="admin">

<header>
	<div class="container" id="heading">
		<div class="row">
			<h1 class="fivecol">
				<?=HTML::link('/', 'Magic Rainbow Adventure!')?>
				<span class="label label-info">beta</span>
			</h1>

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

	<script src="<?=URL::base();?>/basset/default.js"></script>

	<div class="row">
		<div id="main" class="twelvecol">
			<?=$content?>
		</div> <!-- #main -->
	</div>

</div> <!-- #content -->

<footer class="container">
	<div class="row">
		<div class="eightcol">
			<p>
				Magic Rainbow Adventure Admin Interface<br />
				<strong>You are <?=Request::ip()?>, <?=$_SERVER['HTTP_USER_AGENT']?></strong>
			</p>

			<p><?=IoC::resolve('doctrine::manager')->getConfiguration()->getSqlLogger()->queries?> queries executed.</p>
		</div>
	</div>
</footer>

<script src="<?=URL::base();?>/basset/admin.js"></script>
<?=Basset::inline('assets')->scripts();?>

</body>
</html>
