<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Middlewares\ConsultationMiddleware;
use App\Core\Request;
use App\Core\Response;
use App\Models\Animals;
use App\Models\Consultation;
use App\Models\TypeConsultation;

class ConsultationController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new ConsultationMiddleware(["index"]));
    }
    
    public function index(Request $request, Response $response): bool|array|string
    {
        $data = [
            "animals" => array_map(fn($m) => ["id" => $m->id, "animal" => $m->type_animal], Animals::findAll()),
            "typeConsultation" => array_map(fn($m) => ["id" => $m->id, "type" => $m->consultation], TypeConsultation::findAll())
        ];
        $consultation = new Consultation();
        if($request->isPost()) {
            $consultation->loadData(array_merge($request->getBody(), ["user" => Application::$app->session->get("user")]));
            if ($consultation->validate() && $consultation->save()) {
                Application::$app->session->setFlash("success", "Rendez-vous pris avec succès");
                $response->redirect("/");
                exit;
            }
            $this->setLayout("footer");
            return $this->render("consultation", array_merge($consultation->errors, $consultation->data(), $data));
        }
        /** @var Animals $animals */
        
        $this->setLayout("footer");
        return $this->render("consultation", $data);
    }
}