<?php
/* Smarty version 3.1.29, created on 2016-03-10 15:32:22
  from "C:\xampp\htdocs\View\header.php" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_56e18576627912_99000127',
  'file_dependency' => 
  array (
    '016991fde7ba0011fd04a1dc56efe40e6c5eb258' => 
    array (
      0 => 'C:\\xampp\\htdocs\\View\\header.php',
      1 => 1457620335,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_56e18576627912_99000127 ($_smarty_tpl) {
?>
<!DOCTYPE html >
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Color on mobile devices. Example: mariusdv.nl -->
    <meta name="theme-color" content="#2196F3">

    <link rel="stylesheet" href="https://bootswatch.com/paper/bootstrap.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.8/theme-default.min.css" rel="stylesheet" type="text/css"/>
    <link href='https://fonts.googleapis.com/css?family=PT+Sans|Indie+Flower' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/CSS/Style.css">
    <link rel="stylesheet" type="text/css" href="/CSS/Wish.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.8/theme-default.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/CSS/charts.css">

    <?php if (isset($_smarty_tpl->tpl_vars['title']->value)) {?>
    <title>Aladdin: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title']->value);?>
</title>
    <?php } else { ?>
    <title>Aladdin</title>
    <?php }?>


</head>

<body>

<?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-latest.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="//code.jquery.com/ui/1.10.1/jquery-ui.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.8/jquery.form-validator.min.js"><?php echo '</script'; ?>
>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand pull-left" href="/"><img alt="logo" class="logo" src="/Resources/Images/logo.png"/> </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">



            <ul class="nav navbar-nav navbar-left">
                <li><a href="/about/action=home"> <span class="glyphicon glyphicon glyphicon-home"></span> Over Aladdin</a></li>
                <?php if (isset($_smarty_tpl->tpl_vars['admin']->value)) {?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">
                        <span class="glyphicon glyphicon-eye-open"></span> Administrator<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/admin">Statistieken</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/">Wens Aanvragen</a></li>
                        <li><a href="/">Talent aanvragen</a></li>
                        <li><a href="/">Meldingen</a></li>
                        <li><a href="/">Berichten Centrum</a></li>
                        <li role="separator" class="divider">
                        <li><a href="/">Gebruikers</a></li>
                    </ul>
                </li>
                <?php }?>
                <?php if (isset($_smarty_tpl->tpl_vars['user']->value)) {?>
                <li><a href="/wishes"> <span class="glyphicon glyphicon glyphicon-globe"></span> Wensen</a></li>
                <li><a href="/Inbox"> <span class="glyphicon glyphicon glyphicon-envelope"></span> Berichten</a></li>
                <li><a href="/Talents"> <span class="glyphicon glyphicon-align-justify"></span> Talenten</a></li>
                <?php }?>
            </ul>
            <ul class="nav navbar-nav navbar-right">


                <?php if (isset($_smarty_tpl->tpl_vars['user']->value)) {?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><span class="glyphicon glyphicon-user"></span>
                        <?php echo $_smarty_tpl->tpl_vars['user']->value->displayName;?>
<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/profile">Mijn profiel</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="/account">Wensen</a></li>
                        <li role="separator" class="divider"></li>
<!--                        -->
<!--                        TIJDELIJK OM ADMIN WENS BEHEER MAKELIJKER TE BERIJKEN-->

                        <li><a href="/AdminWish">Wens Beheer</a></li>
                        <li role="separator" class="divider"></li>
<!--                        -->
<!--                        -->
                        <li><a href="/account/action=logout">Log uit</a></li>
                    </ul>
                </li>
                <?php } else { ?>
                <li><a href="/account/action=login">Log in</a></li>
                <?php }?>

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<!--
Menu
Breadcrumbs
-->

<?php }
}
