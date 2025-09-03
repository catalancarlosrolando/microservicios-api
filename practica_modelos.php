<?php

use Ramsey\Uuid\Type\Decimal;

//Usamos faker para crear numeros aleatorios
use Faker\Factory as Faker;

require_once 'vendor/autoload.php';

// Configurar la aplicación Laravel para usar en consola
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use Carbon\Carbon;

echo "=== PRÁCTICA DE MODELOS ELOQUENT ===\n\n";

//$categoria = Customer::create(
//    [
//    "first_name"=> "Carlos",
//    "last_name"=>"Catalan",
//    "email"=> "roberto@gmail.com",
//    "phone"=> "26453553",
//    "birth_date"=>"2025-4-23",
//    ]
//    );

$prod = Product::find(1);

echo "producto numero 1:", $prod->name ,"\n";

$customer = Customer::where("first_name", "like","Carlos")->get();

foreach ($customer as $custom){
    echo "Nombre: ", $custom->first_name,"\n";
}


$customer1 = Customer::where("email", "like","roberto@gmail.com")->first();

echo "Nombre segun email:", $customer1->first_name ,"\n";


//cantidad de productos

$prodcant = Product::count("name");

echo "Cantidad de productos:", $prodcant,"\n";

//productos ordenados por cantidad

$prodOrden = Product::orderBy("stock", "desc")->get();

foreach ($prodOrden as $prod){
    echo "nombre:", $prod->name ,"\n";
}

//solo los primeros 5 productos

$prod5 = Product::limit(5)->get();

foreach ($prod5 as $prod){
        echo "", $prod->id,"\n";
}

// cambiar el nombre de una sola categoria
Category::where("name","tenetur")->update(["name" => "Plastico"]);


// cambiar los precios del producto y preguntando al usuario
//$faker = Faker::create();
// $product = Product::all();
//$precio = 0.0;
// foreach($product as $prod)
// {
//    $prod->price = $faker->randomFloat(2,10,500);
//    $prod->save();
//}


 //vamos a consultar el precio promedio de los productos

 $precioprom = Product::avg("price");

 echo "Precio promedio:",$precioprom, "\n";


 //Ordenado por precio y con un limite de 5

 $precioOrden = Product::orderBy("price")->limit(5)->get();

 foreach ($precioOrden as $precio){
    echo "Precio:", $precio->id, "-", "precio:", $precio->price, "\n";
 }
