<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Operation;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $group = Group::create([
            'owner' => auth()->user()->id,
            'name' => $request->name,
            'token' => uniqid(),
            'token_expired' => date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . ' +1 day'))
        ]);

        $group->users()->attach(auth()->user()->id);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group, $action = null)
    {


    }

    public function createInviteLink(Group $group)
    {

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function join(Request $request, Group $group)
    {
        if ($group->isTokenExpired()) {
            return redirect('/')->with('error', 'Token expired!');
        }
        if (count(auth()->user()->groups()->get()) > 0) {
            return redirect('/')->with('error', 'Вы уже состоите в какой-то группе!');
        }
        auth()->user()->groups()->sync([$group->id]);
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function leave(Request $request, Group $group)
    {
        auth()->user()->groups()->detach($group->id);
        return redirect('/');
    }

    public function removeUser(Request $request, Group $group, User $user){
        $user->groups()->detach($group->id);
        return redirect()->back()->with('success', 'Успешно удален пользователь');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }

    public function toggleAdmin(Group $group, Request $request)
    {

        $user = User::find($request->user_id);
        if (!$user->isMemberOf($group)) {
            return redirect()
                ->back()
                ->with('error', 'Пользователь не в группе');
        }
        $group->admins()->toggle($request->user_id);
        return redirect()->back()->with('success', 'Успешно изменены права пользователя');
    }

    /**
     * Разнесение общей суммы покупки на участников группы
     *
     * @param Group $group
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function spendMoney(Group $group, Request $request)
    {
//        dd($request);


        $validator = Validator::make($request->all(), [
            'data.*.personal_amount' => 'nullable|numeric',
            'amount' => 'required',
        ]);
$validator->validate();
        $mainOperation = Operation::create([
            'group_id' => $group->id,
            'type' => 'credit',
            'amount' => -$request->amount,
            'operator_id' => auth()->user()->id,
            'comment' => $request->comment,
        ]);
        $personal_amounts = 0;
        $payersCount = 0;
        foreach ($request->data as $user_id => $el) {

            $user = User::find($user_id);
            if (isset($el['personal_amount'])) {
                $personal_amounts += $el['personal_amount'];
                Operation::create([
                    'group_id' => $group->id,
                    'amount' => -$el['personal_amount'],
                    'user_id' => $user_id,
                    'operation_id' => $mainOperation->id,
                    'comment' => $el['personal_comment'],
                ]);
            }
            if (isset($el['common'])) {
                $payersCount += $user['multiplicator'];
            }
        }
        $payForEachAmount = ($request->amount - $personal_amounts) / $payersCount;
        if ($payForEachAmount > 0) {
            foreach ($request->data as $user_id => $el) {
                if (!isset($el['common'])) {
                    continue;
                }
                $user = User::find($user_id);
                Operation::create([
                    'group_id' => $group->id,
                    'amount' => -$payForEachAmount * $user['multiplicator'],
                    'user_id' => $user_id,
                    'operation_id' => $mainOperation->id,
                    'comment' => $request->comment . ' ' . $user['multiplicator'] . "/$payersCount часть",
                ]);
            }
        }
        return redirect()->back()
            ->with('success', " Бюджет: " . $group->budget());
    }

}
