<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ApiRouter extends Controller
{
    public function dispatch() { 
        // Full path after domain, e.g. "api/content/save"
        $fullPath = $this->request->getUri()->getPath();
        $path = str_replace('api/', '', ltrim($fullPath, '/'));
        
        [$controller, $method] = array_pad(explode('/', $path), 2, 'index');
        $class = 'App\\Controllers\\Api\\' . ucfirst($controller);

        if (!class_exists($class)) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON(['error' => "Unknown API controller: {$controller}"]);
        }

        // Create instance
        $instance = new $class();

        // Inject dependencies manually
        $instance->request  = Services::request();
        $instance->response = Services::response();
        $instance->logger   = Services::logger();

        if (!method_exists($instance, $method)) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON(['error' => "Unknown method: {$method}"]);
        }

        return $instance->$method();
    }
}
