<?php
declare(strict_types=1);

namespace App\Core;

class Response
{
    /**
     * Définir le status HTTP de la réponse
     * @param int $code
     */
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }
    
    /**
     * Redirige l'utilisateur à l'url donné
     * @param string $url
     */
    public function redirect(string $url)
    {
        header("Location: $url");
    }
}