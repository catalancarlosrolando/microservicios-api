<?php

require_once 'vendor/autoload.php';

// Configurar la aplicación Laravel para usar en consola
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use Carbon\Carbon;

echo "=== PRÁCTICA DE MODELOS ELOQUENT ===\n\n";

$categoria = Customer::create(
    [
    "first_name"=> "Carlos",
    "last_name"=>"Catalan",
    "email"=> "roberto@gmail.com",
    "phone"=> "26453553",
    "birth_date"=>"2025-4-23",
    ]
    );



