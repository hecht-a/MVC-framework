<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Exceptions\NotFoundException;
use App\Core\HttpError;
use App\Core\Middlewares\ConsultationMiddleware;
use App\Core\Request;
use App\Core\Response;
use App\Models\Animals;
use App\Models\Consultation;
use App\models\Times;
use App\Models\TypeConsultation;
use App\Models\User;
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
    private function data(): array
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
        return [
            "animals" => array_map(fn($m) => ["id" => $m->id, "animal" => $m->type_animal], Animals::findAll()),
            "typeConsultation" => array_map(fn($m) => ["id" => $m->id, "type" => $m->consultation], TypeConsultation::findAll()),
            "times" => $times
        ];
    }
    
    /**
     * @throws Exception
     */
    public function index(Request $request, Response $response, array $params): bool|array|string
    {
        if (isset($params["id"])) {
            /** @var array $requestedConsultation */
            $requestedConsultation = json_decode(json_encode(Consultation::findOne(["id" => $params["id"]])), true);
            $requestedConsultation["animal"] = Animals::findOne(["id" => $requestedConsultation["animal"]]);
            $requestedConsultation["user"] = User::findOne(["id" => $requestedConsultation["user"]]);
            $requestedConsultation["type_consultation"] = TypeConsultation::findOne(["id" => $requestedConsultation["type_consultation"]]);
            $requestedConsultation["rdv"] = Times::findOne(["id" => $requestedConsultation["rdv"]]);
        }
        $this->setLayout("footer");
        return $this->render("consultation", array_merge($this->data(), ["requestedConsultation" => $requestedConsultation ?? null]));
    }
    
    /**
     * @throws Exception
     */
    public function add(Request $request, Response $response)
    {
        $consultation = new Consultation();
        $consultation->loadData(array_merge($request->getBody(), ["user" => Application::$app->session->get("user")]));
        if ($consultation->validate() && $consultation->save()) {
            Application::$app->session->setFlash("success", "Rendez-vous pris avec succès");
            $response->redirect("/profile/consultations");
            exit;
        }
        $this->setLayout("footer");
        return $this->render("consultation", array_merge($consultation->errors, $consultation->data(), $this->data()));
    }
    
    public function list(): bool|array|string
    {
        $this->setLayout("footer");
        return $this->render("/user/consultations", ["consultations" => Consultation::findAll()]);
    }
    
    /**
     * @throws NotFoundException
     */
    public function edit(Request $request, Response $response, array $q)
    {
        if (!Consultation::isConsultationOwner($q["id"])) {
            HttpError::e404();
        }
        /** @var Consultation $consultation */
        $consultation = Consultation::findOne(["id" => $q["id"]]);
        $consultation->loadData($request->getBody());
        $consultation->update();
        return $this->render("user/consultation", ["consultation" => $consultation]);
    }
    
    /**
     * @throws NotFoundException
     */
    public function delete(Request $request, Response $response, array $q)
    {
        if (!Consultation::isConsultationOwner($q["id"])) {
            HttpError::e404();
        }
        Consultation::delete(["id" => $q["id"]]);
        Application::$app->session->setFlash("success", "Rendez-vous supprimé avec succès");
        $response->redirect("/profile/consultations");
        exit;
    }
    
    /**
     * @throws NotFoundException
     */
    public function show(Request $request, Response $response, array $q): bool|array|string
    {
        $consultation = Consultation::findOne(["id" => $q["id"]]);
        if (!Consultation::isConsultationOwner($q["id"])) {
            HttpError::e404();
        }
        $this->setLayout("footer");
        return $this->render("user/consultation", ["consultation" => $consultation]);
    }
}