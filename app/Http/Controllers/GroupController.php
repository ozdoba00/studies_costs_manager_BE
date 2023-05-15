<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{

    public function index()
    {
        try {
            $user_id = auth()->user()->id;
            $groups =  Group::whereHas('users', function ($q) use ($user_id) {
                $q->where([['user_id', $user_id]]);
            })
                ->get();

            if ($groups->isEmpty()) {
                return response()->json(['groups' => null], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            abort(400, $th->getMessage());
        }

        return response()->json(['groups' => $groups], Response::HTTP_OK);
    }

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
                $user_id => ['admin' => true]
            );

            $group->users()->sync($params);
        } catch (\Throwable $th) {
            abort(400, $th->getMessage());
        }

        return response()->json(['message' => 'Group created successfully'], Response::HTTP_CREATED);
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
        return response()->json(['message' => 'User joined to group successfully'], Response::HTTP_OK);
    }

    public function show(string $public_id)
    {
        try {
            $user_id = auth()->user()->id;
            $group =  Group::whereHas('users', function ($q) use ($user_id) {
                $q->where([['user_id', $user_id]]);
            })
                ->where('public_id', $public_id)->get();

            if ($group->isEmpty()) {
                return response()->json(['message' => 'UNAUTHORIZED'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Throwable $th) {
            abort(400, $th->getMessage());
        }

        return response()->json([$group], Response::HTTP_OK);
    }
}
