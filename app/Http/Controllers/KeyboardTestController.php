<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KeyboardTestController extends Controller
{
  public function store(Request $request)
  {
    $data = $request->all();
    if (!empty($data)) {
      foreach ($data as $key => $duration) {
        if ($duration > 5000) {
          $status = 'Stuck';
        } else if ($duration < 50) {
          $status = 'Error';
        } else {
          $status = 'Working well';
        }
      }
    } else {
      $key = 'no input key yet..';
      $status = 'no status return..';
    }

    return response()->json([
      'key' => $key,
      'status' => $status
    ]);
  }
}

