<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Support\Facades\DB;

trait UserTrait
{
  public function show($id)
  {
    return User::with(['role'])
      ->exclude(['password', 'remember_token'])
      ->find($id);
    // return User::with(['role', 'cart.good'])->select('*')->find($id);
  }
}
