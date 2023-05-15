<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Group extends Model
{
    use HasFactory;

    private const PUBLIC_ID_LENGTH = 4;


    protected $fillable = [
        'name',
        'public_id'
    ];
    /**
     * Generating public random id for groups
     * 
     * @return string
     */
    public static function generateUniquePublicID()
    {
        $random_id = '';

        do
        {
            $random_id = bin2hex(random_bytes(self::PUBLIC_ID_LENGTH));
            $group = Group::where('public_id', $random_id)->get();
        }
        while(!$group->isEmpty());

        return $random_id;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'groups_users', 'group_id', 'user_id');
    }

    public static function joinUser(int $user_id, int $group_id)
    {
        $params = array(
            'user_id' => $user_id,
            'group_id' => $group_id
        );

        DB::table('groups_users')
        ->insert([
            $params
        ]);
    }
}
