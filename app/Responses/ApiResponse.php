<?php

namespace App\Responses;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ApiResponse
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function rollback(
        $e,
        $message = "Un problème d'origine inconnue est survenue. Prière de reprendre le processus."
    ) {
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw(
        $e,
        $message = "Un problème d'origine inconnue est survenue. Prière de reprendre le processus."
    ) {
        Log::info($e);
        throw new HttpResponseException(response()->json(["message" => $message], 500));
    }

    public static function sendResponse($requestState = true, $result, $message, $code = 200)
    {
        $response = [
            'success' => $requestState,
            'data' => $result
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        return response()->json($response, $code);
    }
}
