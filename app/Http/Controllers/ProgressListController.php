<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\ProgressList;
use Illuminate\Support\Facades\Auth;

class ProgressListController extends Controller
{
    protected $token;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->token = '';
    }

    public function index()
    {
        $data = ProgressList::all();

        $metadata = [
            'response' => [
                'code' => '200',
                'status' => 'ok',
            ],
            'metadata' => [
                'data' => $data
            ]
        ];

        return response()->json($metadata);
    }
}
