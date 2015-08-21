<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Stylist.php";
    require_once __DIR__."/../src/Client.php";


    $app = new Silex\Application();
    $app['debug'] = true;


    //Setting PDO for mysql.  Note ** localhost port set to 8080
    $server = 'mysql:host=localhost:8080;dbname=hair_salon';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    //root page: loads into index.html.twig
    //Leads to stylist or clients page
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    //Lists all stylists with options to edit or add a new stylist.
    //Comes from index.html, leads to self or stylist edit page.
    $app->get("/stylist", function() use ($app) {
        return $app['twig']->render('stylist.html.twig', array('stylists' => Stylist::getAll()));
    });

    //Post adds a new stylist to the database
    //Gets new stylist from stylist.html and redirects back to self with updated stylist list.
    $app->post("/stylist", function() use ($app) {
        $stylist = new Stylist($_POST['stylist_name']);
        $stylist->save();
        return $app['twig']->render('stylist.html.twig', array('stylists' => Stylist::getAll()));
    });

    //Deletes all stylists from database
    //Comes from stylist post function, renders back to index page.
    $app->post("/delete_stylists", function() use ($app) {
        Stylist::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    return $app;
?>
