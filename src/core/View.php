<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    public string $title = "";
    
    public array $style = ["index", "bootstrap", "footer", "burger"];
    
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    
    public function renderView(string $view, array $params = []): array|bool|string
    {
        $content = $this->applyLayoutFunction($this->renderOnlyView($view, $params));
        $layoutContent = $this->layoutContent();
        $content = str_replace("{{content}}", $content, $layoutContent);
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
        preg_match_all("/{{ @add_style\(([\"'][a-zA-Z0-9_\/]+[\"']|\[(\"[a-zA-Z0-9_\/]+\"+\s?,?\s?)+])\) }}/", $content, $matches);
        foreach ($matches[0] as $match) {
            $styles = $this->getParam($match);
            if (is_array($styles)) {
                foreach ($styles as $style) {
                    array_push($this->style, $style);
                }
            } else {
                array_push($this->style, $styles);
            }
        }
        $links = join("\n", array_map(fn($link) => "<link rel='stylesheet' href='/css/$link.css'/>", $this->style));
        if (preg_match("/{{links}}/", $content)) {
            $content = preg_replace("/{{links}}/", $links, $content);
        }
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
    
    public function applyLayoutFunction(string $content): array|string|null
    {
        preg_match_all("/{{ @layout\([\"'][a-zA-Z0-9_\/]+[\"']\) }}/", $content, $matches);
        foreach ($matches[0] as $match) {
            Application::$app->controller->setLayout($this->getParam($match));
        }
        return preg_replace("/{{ @layout\([\"'][a-zA-Z0-9_\/]+[\"']\) }}/", "", $content);
    }
    
    public function getComponent(string $content): array|string|null
    {
        preg_match_all("/{{ @component\([\"'][a-zA-Z0-9_\/]+[\"']\) }}/s", $content, $matches);
        foreach ($matches[0] as $match) {
            $component = $this->getParam($match);
            ob_start();
            include_once Application::$ROOT_DIR . "/views/components/$component.php";
            $content = preg_replace("/{{ @component\([\"']" . $component . "[\"']\) }}/", ob_get_clean(), $content);
        }
        return $content;
    }
    
    public function renderContent(string $viewContent): array|bool|string
    {
        $layoutContent = $this->layoutContent();
        $content = str_replace("{{content}}", $viewContent, $layoutContent);
        return preg_replace("/.*({{.*}}).*/", "", $content);
    }
    
    private function getParam(string $content): string|array
    {
        $params = preg_split("/\)/", preg_split("/\(/", $content)[1])[0];
        return str_starts_with($params, "[") && str_ends_with($params, "]")
            ? array_map(fn($str) => substr(trim($str), 1, -1), preg_split("/,/", substr($params, 1, -1)))
            : substr($params, 1, -1);
    }
}