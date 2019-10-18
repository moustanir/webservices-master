<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/dao_client.php';
require_once __DIR__ . '/../models/dao_commande.php';
require_once __DIR__ . '/../models/dao_produit.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});
header("Access-Control-Allow-Origin: *");
function toJson($resultat, $httpCode = 200)
{
    global $app;
    $response["success"] = true;
    $response["results"]["nb"] = count($resultat);
    $response["results"]["data"] = $resultat;
    return $app->json($response, $httpCode);
}

$app->get('/', function () {
    return 'Hello world';
});

// ----------- Clients ----------- \\

$app->get("/client", function () {
    return toJson(DaoClient::findAll());
});

$app->post("/client", function (Request $request) {
    $columnToFetch = array("nom", "prenom", "adresse", "date_naissance", "civilite", "numero", "id_ville");
    $data = array();
    foreach ($columnToFetch as $column) {
        $data[$column] = $request->request->get($column);
    }
    return toJson(DaoClient::add($data));
});

$app->get("/client/{id}", function ($id) {
    return toJson(DaoClient::find($id));
});

$app->put("/client/{id}", function (Request $request, $id) {
    $columnToFetch = array("nom", "prenom", "adresse", "date_naissance", "civilite", "numero", "id_ville");
    $data = array();
    foreach ($columnToFetch as $column) {
        $data[$column] = $request->request->get($column);
    }
    return toJson(DaoClient::update($id, $data));
});
$app->delete("/client/{id}", function ($id) {
    return toJson(DaoClient::delete($id));
});

$app->get("/client/{id}/commande", function ($id) {
    return toJson(DaoClient::findUserCommande($id));
});

// ----------- Fin Clients ----------- \\

// ----------- Commandes ----------- \\

$app->get("/commande", function () {
    return toJson(DaoCommande::findAll());
});

$app->post("/commande", function (Request $request) {
    $columnToFetch = array("nom", "prenom", "adresse", "date_naissance", "civilite", "numero", "id_ville");
    $data = array();
    foreach ($columnToFetch as $column) {
        $data[$column] = $request->request->get($column);
    }
    return toJson(DaoCommande::add($data));
});

$app->get("/commande/{id}", function ($id) {
    return toJson(DaoCommande::find($id));
});

$app->put("/commande/{id}", function (Request $request, $id) {
    $columnToFetch = array("nom", "prenom", "adresse", "date_naissance", "civilite", "numero", "id_ville");
    $data = array();
    foreach ($columnToFetch as $column) {
        $data[$column] = $request->request->get($column);
    }
    return toJson(DaoCommande::update($id, $data));
});
$app->delete("/commande/{id}", function ($id) {
    return toJson(DaoCommande::delete($id));
});

$app->get("/commande/{id}/produit", function ($id) {
    return toJson(DaoCommande::findCommandeProduit($id));
});

// ----------- Fin Commandes ----------- \\

// ----------- Produits ----------- \\

$app->get("/produit", function () {
    return toJson(DaoProduit::findAll());
});

$app->post("/produit", function (Request $request) {
    $columnToFetch = array("nom", "prenom", "adresse", "date_naissance", "civilite", "numero", "id_ville");
    $data = array();
    foreach ($columnToFetch as $column) {
        $data[$column] = $request->request->get($column);
    }
    return toJson(DaoProduit::add($data));
});

$app->get("/produit/{id}", function ($id) {
    return toJson(DaoProduit::find($id));
});

$app->put("/produit/{id}", function (Request $request, $id) {
    $columnToFetch = array("nom", "prenom", "adresse", "date_naissance", "civilite", "numero", "id_ville");
    $data = array();
    foreach ($columnToFetch as $column) {
        $data[$column] = $request->request->get($column);
    }
    return toJson(DaoProduit::update($id, $data));
});
$app->delete("/produit/{id}", function ($id) {
    return toJson(DaoProduit::delete($id));
});

// ----------- Fin Produits ----------- \\

$app->run();
