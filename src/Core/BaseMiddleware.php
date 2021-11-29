<?php
declare(strict_types=1);

namespace App\Core;

abstract class BaseMiddleware
{
    public array $actions;
    
    abstract public function __construct(array $actions = []);
    
    /**
     * Execute le middleware
     */
    abstract public function execute();
}