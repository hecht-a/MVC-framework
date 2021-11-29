<?php
declare(strict_types=1);

namespace App\Core;

class Request
{
    /**
     * Retourne l'url par l'utilisateur
     * @return string
     */
    public function getPath(): string
    {
        $path = $_SERVER["REQUEST_URI"] ?? "/";
        $position = strpos($path, "?");
        if (!$position) {
            return $path;
        }
        return substr($path, 0, $position);
    }
    
    /**
     * true si la methode HTTP est GET, false sinon
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->method() === "get";
    }
    
    /**
     * Retourne la methode HTTP
     * @return string
     */
    public function method(): string
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }
    
    /**
     * true si la methode HTTP est POST, false sinon
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method() === "post";
    }
    
    /**
     * Retourne le contenu de la requête
     * @return array
     */
    public function getBody(): array
    {
        $body = [];
        if ($this->method() === "get") {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        
        if ($this->method() === "post") {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}