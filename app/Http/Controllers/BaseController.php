<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * Return a response to the user.
     */
    public function response($request, $route, $message = null, $withMessage = 'message', $status = 200, $routeDataName = null, $routeData = null): mixed
    {
        if ($request->bearerToken()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
            ]);
        }

        return redirect()->route($route, [$routeDataName => $routeData])->with($withMessage, $message);
    }
}
