<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response_page($data)
    {
        $res_data = [
            'data' => $data->items(),
            'status' => 200,
            'success' => true,
        ];

        if (!empty($data->perPage())) {
            $res_data['pagination'] = [
                'count' => $data->count(),
                'current_page' => $data->currentPage(),
                'links' => [],
                'perPage' => $data->perPage(),
                'total' => $data->total(),
                'totalPages' => $data->lastPage(),
            ];
        }

        return $res_data;
    }

    public function response_data($data = null)
    {
        $res_data = [
            'data' => $data,
            'status' => 200,
            'success' => true,
        ];

        return $res_data;
    }
}
