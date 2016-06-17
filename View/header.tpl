<!DOCTYPE html >
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Color on mobile devices. Example: mariusdv.nl -->
    <meta name="theme-color" content="#2196F3">

    <link rel="stylesheet" href="https://bootswatch.com/paper/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.8/theme-default.min.css" rel="stylesheet"
          type="text/css"/>
    <link href='https://fonts.googleapis.com/css?family=PT+Sans|Indie+Flower' rel='stylesheet' type='text/css'>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/6.0.16/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.8/theme-default.min.css" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" type="text/css" href="/CSS/Style.css">
    <link rel="stylesheet" type="text/css" href="/CSS/charts.css">

    {if isset($title)}
        <title>Aladdin: {htmlspecialchars($title)}</title>
    {else}
        <title>Aladdin</title>
    {/if}


</head>

<body>

<script src="https://code.jquery.com/jquery-latest.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.8/jquery.form-validator.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>


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

            <a class="navbar-brand pull-left" href="/"><img alt="logo" class="logo" src="/Resources/Images/logo.png"/>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


            <ul class="nav navbar-nav navbar-left">
                <li><a href="/donate"><span class="glyphicon glyphicon-euro"></span> Doneren</a></li>
                <li><a href="/about"><span class="glyphicon glyphicon-heart"></span> Over Ons</a></li>
                <li><a href="/guestbook"><span class="glyphicon glyphicon-book"></span> Gastenboek</a></li>
                {if isset($user)}
                    <li><a href="/dashboard"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>
                    <li><a href="/wishes"> <span class="glyphicon glyphicon glyphicon-globe"></span> Wensen</a></li>
                    <li><a href="/Inbox"> <span class="glyphicon glyphicon glyphicon-envelope"></span> Berichten</a>
                    </li>
                    <li><a href="/Talents"> <span class="glyphicon glyphicon-align-justify"></span> Talenten</a></li>
                {/if}
            </ul>
            <ul class="nav navbar-nav navbar-right">

                {if isset($admin)}
                    <li class="dropdown dropdown-admin">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <span class="glyphicon glyphicon-eye-open"></span> {$admin->username}<span
                                    class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin">Statistiek</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/AdminWish">Wens beheer</a></li>
                            <li><a href="/AdminTalents">Talent beheer</a></li>
                            <li><a href="/AdminUser">Gebruikersbeheer</a></li>
                            <li><a href="/ForbiddenWords">Verboden woorden beheer</a></li>
                            <li><a href="/AdminManage">Moderator beheer</a></li>
                            <li><a href="/AdminSponsor">Sponsoren beheer</a></li>
                            <li role="separator" class="divider">
                            <li><a href="/Admin/action=logout">Log uit als {$admin->username}</a></li>
                        </ul>
                    </li>
                {/if}
                {if isset($user)}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false"><span class="glyphicon glyphicon-user"></span>
                            {$user->displayName}<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/profile">Mijn profiel</a></li>
                            <li role="separator" class="divider">
                            <li><a href="/account/action=logout">Log uit</a></li>
                        </ul>
                    </li>
                {else}
                    <li><a href="/account/action=login">Log in</a></li>
                {/if}


            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<!--
Menu
Breadcrumbs
-->

