<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cors {
    public function handle() {
        // Allow from any origin
        header("Access-Control-Allow-Origin: *");

        // Allow specific methods
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        // Allow specific headers
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Handle preflight requests (for CORS in React)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
}
