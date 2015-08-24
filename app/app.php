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

    //---------- Index page -------------
    //root page: loads into index.html.twig
    //Leads to stylist or clients page
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });


    //----------- Begin Stylist Page Functionality -----------

    //Lists all stylists with options to edit or add a new stylist.
    //Gives user options for add, edit, or delete all stylists.
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

    //Allows user to edit or delete a specific entry in stylist page
    $app->get("/stylist/{id}/edit", function($id) use($app) {
        $stylist = Stylist::find($id);
        return $app['twig']->render('stylist_edit.html.twig', array('stylist' => $stylist));
    });

    //Patches/Updates user specified stylist.
    //Comes from stylist_edit.html and renders to stylist list page.
    $app->patch("/stylist/{id}", function($id) use($app) {
        $stylist_name = $_POST['stylist_name'];
        $stylist = Stylist::find($id);
        $stylist->update($stylist_name);
        $stylists = Stylist::getAll();
        return $app['twig']->render('stylist.html.twig', array('stylists' => $stylists));
    });

    //Deletes user specified stylist.
    //Comes from stylist_edit.html and renders to stylist list page.
    $app->delete("/stylist/{id}", function($id) use ($app) {
        $stylist = Stylist::find($id);
        $stylist->deleteOne();
        return $app['twig']->render('stylist.html.twig', array('stylists' => stylist::getAll()));
    });



    // ------ End Stylist paage functionality --------

    // ------ Begin Client page functionality --------



    //Lists all clients and their associated stylists.
    //User options to add, edit, or delete all clients.
    //Comes from index, renders to self or to client_edit.html
    $app->get("/client", function() use ($app) {
        return $app['twig']->render('client.html.twig', array('clients' => Client::getAll(), 'stylists' => Stylist::getAll()));
    });

    //Adds a new client to the client list.
    //Posts from client list page to self.
    $app->post("/client", function () use ($app) {
        $id = null;
        $stylist_id = intval($_POST['stylist_id']);
        $stylist_name = Stylist::find($stylist_id);
        $client = new Client($_POST['client_name'], $id, $stylist_id);
        $client->save();
        return $app['twig']->render('client.html.twig', array('clients' => Client::getAll(), 'stylists' => Stylist::getAll()));
    });

    //Retrieves user specified stylist and returns the clients of that stylist.
    //Posts from client list page to self.
    $app->post("/get_stylist_clients", function () use ($app) {
        $stylist_id = intval($_POST['stylist_id']);
        return $app['twig']->render('client.html.twig', array('clients' => Client::getStylistClients($stylist_id), 'stylists' => Stylist::getAll()));
    });

    //Deletes all clients from the list.
    //Comes from client list page, renders to index.
    $app->post("/delete_clients", function() use ($app) {
        Client::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    //Gets user specified client ID
    //Comes from client list page, renders to client edit page.
    $app->get("/client/{id}/edit", function($id) use($app) {
        $client = Client::find($id);
        return $app['twig']->render('client_edit.html.twig', array('client' => $client));
    });

    //Patches/Updates the client name.
    //Comes from client_edit.html and redners to client list page.
    $app->patch("/client/{id}", function($id) use($app) {
        $client_name = $_POST['client_name'];
        $client = Client::find($id);
        $client->update($client_name);
        $clients = Client::getAll();
        return $app['twig']->render('client.html.twig', array('clients' => $clients, 'stylists' => Stylist::getAll()));
    });

    //Deletes user specified client from client list
    //Comes from client_edit.html and renders to client list page.
    $app->delete("/client/{id}", function($id) use ($app) {
        $client = Client::find($id);
        $client->deleteOne();
        return $app['twig']->render('client.html.twig', array('clients' => Client::getAll(), 'stylists' => Stylist::getAll()));
    });

    return $app;
?>
