<?php

namespace App\Http\Traits;

use App\Models\User;
use phpcent\Client;

trait WebSocketTrait
{
  public function InitCentrifuge()
  {
    // $client = new Client("http://localhost:8000/api", "API key", "Secret key");
    // $client->publish("channel", ["message" => "Hello World"]);
  }
}
