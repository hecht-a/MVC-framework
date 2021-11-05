<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    public string $title = "";
    
    public function setTitle(string $title) {
        $this->title = $title;
    }
    
    public function renderView(string $view, $params = []): array|bool|string
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        $content = str_replace("{{content}}", $viewContent, $layoutContent);
        
        if (Application::$app->session->get("user")) {
            $content = str_replace("{{user}}", "<li><a href='/profile'>profil</a></li><li><a href='/logout'>déconnexion</a></li>", $content);
        } else {
            $content = str_replace("{{user}}", "<li><a href='/login'>connexion</a></li>", $content);
        }
        
        if (Application::$app->session->getFlash("success")) {
            $content = str_replace("{{flashMessages}}", "<div class='notification success'>" . Application::$app->session->getFlash("success") . " </div>", $content);
        }
        $content = $this->setTitleInView($content, $this->title);
        return preg_replace("/.*({{.*}}).*/", "", $content);
    }
    
    public function renderContent($viewContent): array|bool|string
    {
        $layoutContent = $this->layoutContent();
        $content = str_replace("{{content}}", $viewContent, $layoutContent);
        return preg_replace("/.*({{.*}}).*/", "", $content);
    }
    
    protected function layoutContent(): bool|string
    {
        $layout = Application::$app->controller->layout ?? "main";
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }
    
    protected function renderOnlyView($view, $params): bool|string
    {
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        $content = ob_get_clean();
        foreach ($params as $key => $value) {
            $content = str_replace("{{" . $key . "}}", strval($value), $content);
        }
        return preg_replace("/.*({{.*}}).*/", "", $content);
    }
    
    private function setTitleInView(string $content, string $title): string
    {
        return str_replace("{{title}}", $title, $content);
    }
}