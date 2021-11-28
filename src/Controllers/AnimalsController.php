<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Animals;

class AnimalsController extends Controller
{
    public function list()
    {
        $animals = Animals::findAll();
        return $this->render("animals/index");
    }
    
    public function index(Request $request, Response $response, array $q)
    {
        $animal = Animals::findOne(["id" => $q["id"]]);
        return $this->render("animals/animal");
    }
    
    public function edit(Request $request, Response $response, array $q)
    {
        $animal = Animals::findOne(["id" => $q["id"]]);
        if ($request->isPost()) {
            $animal->loadData($request->getBody());
            if ($animal->validate() && $animal->update()) {
                echo "<pre>";
                var_dump("ok");
                echo "</pre>";
                exit;
            }
        }
        echo "<pre>";
        var_dump($animal);
        echo "</pre>";
        exit;
    }
}