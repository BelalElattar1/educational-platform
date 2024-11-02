<?php

namespace App;

trait Response
{
    public function response($message, $status = 201, $data = []) {

        return response()->json([
            'Message' => $message,
            'data'    => $data
        ], $status);

    }
}
