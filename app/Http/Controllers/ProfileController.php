<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Services\FinanceService\FinanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $currentUser = $request->user();
        $balance = FinanceService::getUserBalance($currentUser);

        return view('profile', [
            'currentUser'    => $currentUser,
            'balance'        => $balance->balance,
        ]);
    }

    public function operations(Request $request)
    {
        $currentUser = $request->user();
        $balance = FinanceService::getUserBalance($currentUser);

        return view('operations', [
            'currentUser'    => $currentUser,
            'balance'        => $balance->balance,
        ]);
    }

    public function tableOperations(Request $request)
    {
        $currentUser = $request->user();
        $limit = $request->input('limit', null);
        $lastOperations = Operation::getLastOperations($currentUser, $limit);
        $lastOperations = $lastOperations->map(function (Operation $operation) {
            return [
                Carbon::parse($operation->operation_date)->format('d-m-Y H:i:s'),
                $operation->operation,
                $operation->amount,
                $operation->description,
            ];
        })->toArray();
        return response()->json($lastOperations);
    }
}
