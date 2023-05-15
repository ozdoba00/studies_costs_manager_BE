<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    public function createGroup(GroupRequest $request)
    {
        try {

            $request->validated();
            $public_id = Group::generateUniquePublicID();

            $group = Group::create([
                'public_id' => $public_id,
                'name' => $request->name
            ]);
            $user_id = auth()->user()->id;
            $params = array(
                $user_id =>
                [
                    'admin' => true
                ]
            );
            $group->users()->sync($params);
        } catch (\Throwable $th) {

            abort(400, $th->getMessage());
        }

        return response()->json(['message' => 'Group created successfully'], 201);
    }

    public function joinUser(Request $request)
    {
        try {
            $request->validate([
                'public_id' => 'required'
            ]);

            $group = Group::where('public_id', $request->public_id)->first();
            $user_id = auth()->user()->id;
            Group::joinUser($user_id, $group->id);
        } catch (\Throwable $th) {

            abort(400, $th->getMessage());
        }
        return response()->json(['message' => 'User joined to group successfully'], 201);

    }
}
