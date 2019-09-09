<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AtlanticSoft Test</title>
    <link rel="stylesheet" href="css/app.css" />
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
</head>
<body>
    <!--<h1>My App</h1>
    <div id="app">
        <h1>Hello App!</h1>
        <p>
            <router-link to="/foo">Go to Foo</router-link>
            <router-link to="/bar">Go to Bar</router-link>
        </p>

        <router-view></router-view>
    </div>-->
    <!--<nav class="navbar navbar-light bg-primary">
        <a class="navbar-brand">Navbar</a>
        <form class="form-inline">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </nav>-->
    <div id="app">
        <!--<nav class="navbar navbar-expand-lg content-admin">
            <ul class="navbar-nav mr-auto"></ul>
            <span class="navbar-text navbar-rigth">
                <router-link to="/admin">Ingreso Administrador</router-link>
            </span>
        </nav>-->
        <nav class="navbar navbar-expand-lg navbar-white bg-white nav-modulos">
            <a class="navbar-brand" href="#">
                <img class="logo-app" src="{{ asset('img/logo.png') }}"  alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link to="/shop?c=0&page=1" refresh>Tienda</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/category">Categoria</router-link>
                    </li>
                    <li class="nav-item"> 
                        <router-link to="/product">Productos</router-link>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container">
        <router-view></router-view>
        </div>
    </div>
    <script src="js/app.js"></script>
    <script>
        //VALIDATE FORM
        var forms = document.getElementsByClassName('needs-validation');
           
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
</body>
</html>