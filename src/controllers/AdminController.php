<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Middlewares\AdminMiddleware;
use App\Core\Request;
use App\Core\Response;
use App\Models\User;

class AdminController extends Controller
{
    
    public function __construct()
    {
        $this->registerMiddleware(new AdminMiddleware(["index", "consultations", "users", "deleteUser", "changeAdmin"]));
    }
    
    public function index(): bool|array|string
    {
        return $this->render("admin/index");
    }
    
    public function consultations(): bool|array|string
    {
        return $this->render("admin/consultations");
    }
    
    public function users(): bool|array|string
    {
        return $this->render("admin/users");
    }
    
    public function deleteUser()
    {
    
    }
    
    public function changeAdmin(Request $request, Response $response)
    {
        $body = $request->getBody();
        $userId = explode("_", array_keys($body)[0])[1];
        /** @var User $targetedUser */
        $targetedUser = User::findOne(["id" => $userId]);
        $targetedUser->admin = $body["user_$userId"] === "true" ? 1 : 0;
        $targetedUser->update();
        $response->redirect("/admin/users");
    }
}