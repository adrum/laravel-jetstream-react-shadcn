<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /* @chisel-teams */
    /**
     * Create a new action instance.
     */
    public function __construct(protected DeletesTeams $deletesTeams) {}
    /* @end-chisel-teams */

    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            /* @chisel-teams */
            $this->deleteTeams($user);
            /* @end-chisel-teams */
            /* @chisel-profile-photos */
            $user->deleteProfilePhoto();
            /* @end-chisel-profile-photos */
            /* @chisel-api */
            $user->tokens->each->delete();
            /* @end-chisel-api */
            $user->delete();
        });
    }

    /* @chisel-teams */
    /**
     * Delete the teams and team associations attached to the user.
     */
    protected function deleteTeams(User $user): void
    {
        $user->teams()->detach();

        $user->ownedTeams->each(function (Team $team) {
            $this->deletesTeams->delete($team);
        });
    }
    /* @end-chisel-teams */
}
