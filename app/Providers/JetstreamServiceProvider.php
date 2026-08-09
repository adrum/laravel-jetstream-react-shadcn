<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
/* @chisel-account-deletion */
use App\Actions\Jetstream\DeleteUser;
/* @end-chisel-account-deletion */
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /* @chisel-teams-or-api */
        $this->configurePermissions();
        /* @end-chisel-teams-or-api */

        /* @chisel-teams */
        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        /* @end-chisel-teams */
        /* @chisel-account-deletion */
        Jetstream::deleteUsersUsing(DeleteUser::class);
        /* @end-chisel-account-deletion */
    }

    /* @chisel-teams-or-api */
    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        /* @chisel-api */
        Jetstream::defaultApiTokenPermissions(['read']);
        /* @end-chisel-api */

        Jetstream::role('admin', 'Administrator', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('Administrator users can perform any action.');

        Jetstream::role('editor', 'Editor', [
            'read',
            'create',
            'update',
        ])->description('Editor users have the ability to read, create, and update.');
    }
    /* @end-chisel-teams-or-api */
}
