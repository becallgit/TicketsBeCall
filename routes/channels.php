<?php
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('team.{team_id}', function ($user, $team_id) {
    $isTecnologia = 1;
    $isOnTeam = (int)$user->team_id === (int)$team_id;
    Log::info($team_id);
    return $isOnTeam;
});
Broadcast::channel('user.{user_id}', function ($user, $user_id) {
 
    $isuser= (int)$user->id === (int)$user_id;
    Log::info($user_id);
    return $isuser;
});