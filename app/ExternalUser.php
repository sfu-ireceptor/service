<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalUser extends Model
{
    protected $table = 'external_users';

    public static function checkPermissions($filter)
    {
        if (config('app.auth')) {
            if (! isset($filter['username'])) {
                app()->abort(401, 'The username parameter is required.');
            }

            $username = $filter['username'];
            $user = static::where('ireceptor_username', '=', $username)->get();
            if (! isset($user[0]) || $user[0]->permission_level != 1) {
                app()->abort(401, 'The user ' . $username . ' is not authorized to access this service.');
            }
        }

        return true;
    }
}
