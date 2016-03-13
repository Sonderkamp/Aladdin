<!DOCTYPE html >
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Color on mobile devices. Example: mariusdv.nl -->
    <meta name="theme-color" content="#2196F3">

    <link rel="stylesheet" href="https://bootswatch.com/paper/bootstrap.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.8/theme-default.min.css" rel="stylesheet"
          type="text/css"/>
    <link href='https://fonts.googleapis.com/css?family=PT+Sans|Indie+Flower' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/CSS/Style.css">
    <link rel="stylesheet" type="text/css" href="/CSS/Wish.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.8/theme-default.min.css" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" type="text/css" href="/CSS/charts.css">

    {if isset($title)}
    <title>Gsm.nl: {htmlspecialchars($title)}</title>
    {else}
    <title>Gsm.nl</title>
    {/if}


</head>

<!--//TODO: hier moet de navigatie komen vanuit de database-->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Brand</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                <li><a href="#">Link</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Heren <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">T-shirt</a></li>
                        <li><a href="#">Boxershort</a></li>
                        <li><a href="#">Sokken</a></li>

                    </ul>
                </li>
            </ul>
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>