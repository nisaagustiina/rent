<?php

namespace App\Traits;

trait ApiResponser
{
    /**
     * Return a success JSON response
     *
     * @param array|string $data
     * @param string $message
     * @param int|null $code
     * return \Illuminate\Http\JsonResponse
     * @return void
     */
    public function success($data = null, string $message = null, int $code = 200)
    {
        return [
            'success' => true,
            'ecode' => $code,
            'message' => $message,
            'data' => $data
        ];
    }

   /**
     * Return an error JSON response
     *
     * @param array|string $data
     * @param string $message
     * @param int|null $code
     * return \Illuminate\Http\JsonResponse
     * @return void
     */

    public function error($data = null, string $message = null, int $code = 401)
    {
        return [
            'success' => false,
            'ecode' => $code,
            'message' => $message,
            'data' => $data
        ];
    }
}