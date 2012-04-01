<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta name="viewport" content="width=device-width,initial-scale=1">

	<title><?=$template->title?> &ndash; Magic Rainbow Adventure!</title>
    <meta name="description" content="">
    <meta name="author" content="Joseph Wynn / Magic Rainbow Adventure!">

	<link rel="shortcut icon" href="<?=base_url()?>favicon.png">
	<link href="http://fonts.googleapis.com/css?family=Coustard" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" media="all" href="<?=base_url()?>serve/?b=css&f=reset.css,style.css,admin.css,buttons.css">
</head>
<?php flush(); ?>

<body>

    <div id="status">
	    <a class="close" href="#">x</a>
	    <div id="status-message"><?=$status?></div>
    </div>

    <header id="heading">
        <h1><?=anchor('/', 'Magic Rainbow Adventure!')?></h1>
        <h2><?=anchor('admin', 'Admin Dashboard')?></h2>
    </header>

    <nav id="navigation">
        <div id="account-menu">
            <?=$account_menu?>
        </div>
    </nav>

    <div id="wrapper">

        <div id="sidebar">
            <?=$sidebar?>
        </div>

        <div id="main">

            <div id="content">
                <h2><?=$template->long_title?></h2>
                <?=$template->content?>
            </div> <!-- #content -->

        </div> <!-- #main -->

        <footer id="footer">
	        <span>
		        Page rendered in <?=$this->benchmark->elapsed_time()?> seconds.
		        <?=$this->em->getConfiguration()->getSqlLogger()->queries?> queries executed.
	        </span>
	        
            <p id="copy-notice">Magic Rainbow Adventure! &copy; <?=date('Y')?>.</p>
        </footer>

    </div> <!-- #wrapper -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.js"><\/script>')</script>
    <script src="<?=base_url("serve/?b=js&f={$template->scripts}")?>"></script>

</body>
</html>