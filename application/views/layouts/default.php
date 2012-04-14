<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta name="viewport" content="width=device-width,initial-scale=1">

	<title><?=$template->title?> &ndash; Magic Rainbow Adventure!</title>
    <meta name="description" content="">
    <meta name="author" content="Joseph Wynn / Magic Rainbow Adventure!">

	<link rel="shortcut icon" href="<?=base_url('favicon.png')?>">
	<link href="http://fonts.googleapis.com/css?family=Coustard" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" media="all" href="<?=base_url('serve/?b=css&f=reset.css,style.css,forms.css')?>">

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

    <div id="status">
	    <a class="close" href="#">x</a>
	    <div id="status-message"><?=$status?></div>
    </div>

    <header id="heading">
        <h1><?=anchor('/', 'Magic Rainbow Adventure!')?></h1>

        <p class="site-description">A quest for the ultimate collection of everything cute and funny.</p>
    </header>

    <nav id="navigation">
        <?=$navigation?>

        <div id="account-menu">
            <?=$account_menu?>
        </div>
    </nav>

    <div id="wrapper">

        <div id="purplethang">
            <a href="#happydays!"><img src="/img/purplethang-small.png" width="60" height="72" alt="" /></a>
        </div>

        <div id="main">

            <div id="content">
                <h2><?=$template->long_title?></h2>
                <?=$template->content?>
            </div> <!-- #content -->

        </div> <!-- #main -->

        <footer id="footer">
            <ul id="footer-links">
                <li><?=anchor('about', 'About')?></li>
                <li><?=anchor('privacy', 'Privacy')?></li>
            </ul>

            <p id="copy-notice">Magic Rainbow Adventure! &copy; <?=date('Y')?>.</p>
        </footer>

    </div> <!-- #wrapper -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?=base_url('js/jquery.js')?>"><\/script>')</script>
    <script src="<?=base_url("serve/?b=js&f={$template->scripts}")?>"></script>

</body>
</html>