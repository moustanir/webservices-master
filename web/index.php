<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/dao_user.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

function toJson($resultat, $httpCode = 200)
{
    global $app;
    $response["success"] = true;
    $response["results"]["nb"] = count($resultat);
    $response["results"]["users"] = $resultat;
    return $app->json($response, $httpCode);
}

$app->get('/', function () {
    return 'Hello world';
});

$app->get("/user", function () {
    return toJson(DaoUser::findAll());
});

$app->post("/user", function (Request $request) {
    $columnToFetch = array("email", "login", "nom", "password", "prenom", "profil");
    $data = array();
    foreach ($columnToFetch as $column) {
        $data[$column] = $request->request->get($column);
    }
    return toJson(DaoUser::add($data));
});

$app->get("/user/{id}", function ($id) {
    return toJson(DaoUser::find($id));
});

$app->put("/user/{id}", function (Request $request, $id) {
    $columnToFetch = array("email", "login", "nom", "password", "prenom", "profil");
    $data = array();
    foreach ($columnToFetch as $column) {
        $data[$column] = $request->request->get($column);
    }
    return toJson(DaoUser::update($id, $data));
});
$app->delete("/user/{id}", function ($id) {
    return toJson(DaoUser::delete($id));
});
$app->run();
