<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    public string $title = "";
    
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    
    public function renderView(string $view, array $params = []): array|bool|string
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $this->getLayoutFunction($viewContent);
        $layoutContent = $this->layoutContent();
        $content = str_replace("{{content}}", $viewContent, $layoutContent);
        $content = $this->getComponent($content);
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
    
    protected function layoutContent(): bool|string
    {
        $layout = Application::$app->controller->layout ?? "main";
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }
    
    protected function renderOnlyView(string $view, array $params = []): bool|string
    {
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        $content = ob_get_clean();
        foreach ($params as $key => $value) {
            if (is_string($value) || $key === "code") {
                $content = str_replace("{{" . $key . "}}", strval($value), $content);
            }
        }
        return preg_replace("/.*({{.*}}).*./", "", $content);
    }
    
    private function setTitleInView(string $content, string $title): string
    {
        return str_replace("{{title}}", $title, $content);
    }
    
    public function getLayoutFunction(string $content)
    {
        if (preg_match("/{{ @layout\([\"'][a-zA-Z0-9]+[\"']\) }}/", $content)) {
            $layout = preg_split("/@layout\([\"']/", $content)[1];
            $layout = preg_split("/[\"']\)/", $layout)[0];
            Application::$app->controller->setLayout($layout);
        }
    }
    
    public function getComponent(string $content)
    {
        while (preg_match("/{{ @component\([\"'][a-zA-Z0-9_]+[\"']\) }}/", $content)) {
            $layout = preg_split("/@component\([\"']/", $content)[1];
            $layout = preg_split("/[\"']\)/", $layout)[0];
            ob_start();
            include_once Application::$ROOT_DIR . "/views/components/$layout.php";
            $content = preg_replace("/{{ @component\([\"']" . $layout . "[\"']\) }}/", ob_get_clean(), $content);
        }
        return $content;
    }
    
    public function renderContent(string $viewContent): array|bool|string
    {
        $layoutContent = $this->layoutContent();
        $content = str_replace("{{content}}", $viewContent, $layoutContent);
        return preg_replace("/.*({{.*}}).*/", "", $content);
    }
}