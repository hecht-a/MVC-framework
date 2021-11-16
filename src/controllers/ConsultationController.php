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