<?php
declare(strict_types=1);

namespace App\Core\Middlewares;

abstract class BaseMiddleware
{
    public array $actions;
    
    abstract public function __construct(array $actions = []);
    
    abstract public function execute();
}