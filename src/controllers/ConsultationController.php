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
use App\models\Times;
use App\Models\TypeConsultation;
use DateTime;
use Exception;

class ConsultationController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new ConsultationMiddleware(["index"]));
    }
    
    /**
     * @throws Exception
     */
    public function index(Request $request, Response $response): bool|array|string
    {
        $times = [];
        /** @var Times $time */
        foreach (Times::findAllFree() as $time) {
            $day = new DateTime($time->day);
            if (!in_array($day->format("l d F Y"), array_keys($times))) {
                $times[$day->format("l d F Y")] = [];
            }
            array_push($times[$day->format("l d F Y")], ["hour" => $day->format("H:i"), "id" => $time->id]);
        }
        $data = [
            "animals" => array_map(fn($m) => ["id" => $m->id, "animal" => $m->type_animal], Animals::findAll()),
            "typeConsultation" => array_map(fn($m) => ["id" => $m->id, "type" => $m->consultation], TypeConsultation::findAll()),
            "times" => $times
        ];
        $consultation = new Consultation();
        if ($request->isPost()) {
            $consultation->loadData(array_merge($request->getBody(), ["user" => Application::$app->session->get("user")]));
            if ($consultation->validate() && $consultation->save()) {
                Application::$app->session->setFlash("success", "Rendez-vous pris avec succès");
                $response->redirect("/profile/consultations");
                exit;
            }
            $this->setLayout("footer");
            return $this->render("consultation", array_merge($consultation->errors, $consultation->data(), $data));
        }
        $this->setLayout("footer");
        return $this->render("consultation", $data);
    }
    
    public function list(): bool|array|string
    {
        $this->setLayout("footer");
        return $this->render("/user/consultations", ["consultations" => Consultation::findAll()]);
    }
    
    public function edit(Request $request, Response $response, array $q)
    {
        Application::$app->session->setFlash("success", "Rendez-vous modifié avec succès");
        $response->redirect("/profile/consultations");
        exit;
    }
    
    public function delete(Request $request, Response $response, array $q)
    {
        Consultation::delete(["id" => $q["id"]]);
        Application::$app->session->setFlash("success", "Rendez-vous supprimé avec succès");
        $response->redirect("/profile/consultations");
        exit;
    }
    
    public function show(Request $request, Response $response, array $q): bool|array|string
    {
        $consultation = Consultation::findOne(["id" => $q["id"]]);
        $this->setLayout("footer");
        return $this->render("user/consultation", ["consultation" => $consultation]);
    }
}