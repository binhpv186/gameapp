<!DOCTYPE html>
<html>
<head>
    <base href="/">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $this->title; ?> - izgame</title>
    <meta name="description" content="{{metadescription || 'Game html5'}}" />
    <meta property="og:url" content="" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{metatitle || 'PGame - Play game html5'}}" />
    <meta property="og:description" content="{{metadescription || 'Game html5'}}" />
    <meta property="og:image" content="" />
    <meta name="robots" content="index, follow, noarchive" />
    <meta http-equiv="Cache-control" content="public, max-age=600">
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="<?php echo $this->getTemplatePath(); ?>/assets/css/materialize.min.css"  media="screen,projection"/>

    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }
        @media only screen and (min-width: 980px) {
            nav {height: 48px; line-height: 48px;}
        }
        section.logo-wrapper {padding: 5px 24px;line-height: 36px;}
        section.logo-wrapper .brand-logo {text-transform: uppercase;}
        section.logo-wrapper .input-field {margin: 0;background: #399c3e;border-radius: 2px; position: relative;}
        section.logo-wrapper .input-field label {top: 50%; margin-top: -10px;transform: none;}
        section.logo-wrapper .input-field label.active {transform: none;color:#000;}
        section.logo-wrapper .input-field label i {color:#FFF;}
        section.logo-wrapper .input-field label.active i {color:#000;}
        section.logo-wrapper .input-field input {width: 350px;margin: 0;border:none;background: 0;height: 36px;border-radius: 2px;transition: color .2s;color: #fff;}
        section.logo-wrapper .input-field .autocomplete-content {position: absolute; top: 145%; left: 0;}
        section.logo-wrapper .input-field .autocomplete-content li img {position: absolute; right: 0;}
        section.logo-wrapper .input-field .autocomplete-content li a {float: left;margin-right: 50px;}
        nav ul a {
            border-bottom: 2px solid transparent;
            border-top: 2px solid transparent;
            color: rgba(255,255,255,.7);
            display: inline-block;
            font: 500 14px/44px Roboto,sans-serif;
            margin: 0;
            padding: 0 24px;
            text-transform: uppercase;
            transition: color .2s;
        }
        nav ul a:hover {color:#FFF;}
        .category-item .game-item {padding: 0.75rem}
        .category-item .item-image {margin-bottom: 0;height: 135px;overflow: hidden;}
        .category-item .item-image img {width: 100%;min-height: 135px;}
        .category-item .item-image a {display: block;}
        .category-item .item-content {padding: 5px;}
        .category-item .item-name {display: block;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;}
    </style>
</head>
<body id="body">
<header>
    <section class="hide-on-med-and-down green darken-3 logo-wrapper">
        <a href="" class="brand-logo white-text text-lighten-4">PGame</a>
        <form class="right">
            <div class="input-field">
                <input id="search" type="search" required class="autocomplete">
                <label for="search"><i class="material-icons text-lighten-5">search</i></label>
            </div>
        </form>
    </section>
    <nav class="green darken-3">
        <div class="nav-wrapper">
            <a href="" class="brand-logo hide-on-med-and-up white-text text-lighten-4">PGAMES</a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">search</i></a>
            <?php \app\widgets\Category::widget(); ?>
            <form class="right hide-on-med-and-up">
                <div class="input-field">
                    <input id="search-mobile" type="search" required>
                    <label for="search" class="right"><i class="material-icons">search</i></label>
                    <i class="material-icons">close</i>
                </div>
            </form>
            <ul class="side-nav" id="mobile-demo">
                <li ng-repeat="x in categories"><a href="{{x.slug}}/">{{x.title}}</a><span class="divider"></span></li>
                <li class="divider"></li>
            </ul>
        </div>
    </nav>
</header>
<main>
    <div class="container">
        <div class="row">
            <div class="col s12">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
</main>
<footer class="page-footer blue-grey darken-3">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h5 class="white-text">Footer Content</h5>
                <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
            </div>
        </div>
    </div>
    <div class="footer-copyright blue-grey darken-4">
        <div class="container">
            &copy; 2014 Copyright Text
            <a class="grey-text text-lighten-4 right">Go top</a>
        </div>
    </div>
</footer>

<script src="<?php echo $this->getTemplatePath(); ?>/assets/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo $this->getTemplatePath(); ?>/assets/js/materialize.min.js"></script>
<!--<script src="assets/js/angularjs/angular.min.js"></script>-->
<!--<script src="assets/js/angularjs/angular-route.min.js"></script>-->
<!--<script src="assets/js/angularjs/angular-sanitize.min.js"></script>-->
<!--<script src="app/app.js"></script>-->
<script>
    $( document ).ready(function(){
        $(".button-collapse").sideNav({closeOnClick: true});
    });
</script>
</body>
</html>