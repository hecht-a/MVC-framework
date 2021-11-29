<?php
declare(strict_types=1);

namespace App\Core;

class Session
{
    protected const FLASH_KEY = "flash_messages";
    
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage["remove"] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
    
    /**
     * Créer un FlashMessage identifié par $key
     * @param $key
     * @param $message
     */
    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            "remove" => false,
            "value" => $message
        ];
    }
    
    /**
     * Récupère un FlashMessage en fonction de $key
     * @param $key
     * @return false|mixed
     */
    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]["value"] ?? false;
    }
    
    /**
     * Créer une variable de session identifiée par $key
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Récupère une variable de session en fonction de $key
     * @param $key
     * @return false|mixed
     */
    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }
    
    /**
     * Supprime une variable de session identifiée par $key
     * @param $key
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }
    
    /**
     * Détruit la session
     */
    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage["remove"]) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}