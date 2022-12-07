<?php
namespace App\Http\Controllers;

use App\Models\Group;

//use http\Client\Curl\User;
use App\Models\Operation;
use App\Models\User;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function makeDeposit(Group $group, Request $request)
    {
        $request->validate([
            'amount'=> 'required',
            'user_id'=> 'required|numeric'
        ]);
        $amount = $request->amount;
        $user = User::find($request->user_id);
        Operation::create([
            'group_id' => $group->id,
            'type' => 'debit',
            'amount' => $amount,
            'user_id' => $request->user_id,
            'operator_id' => auth()->user()->id,
            'comment' => 'Пополнение бюджета',
        ]);
        return redirect()->back()
            ->with('success', "$user->username: успешное пополнение бюджета на $amount.");
    }
}
