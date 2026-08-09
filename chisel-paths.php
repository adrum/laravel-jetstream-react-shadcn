<?php

/*
|--------------------------------------------------------------------------
| Chisel Paths
|--------------------------------------------------------------------------
|
| Framework-specific file paths consumed by the sibling chisel.php script.
| Keeping them here lets the chisel logic stay declarative and makes the
| React (TS) page/component locations easy to audit in one place.
|
*/

return [
    'app_layout' => 'resources/js/Layouts/AppLayout.tsx',
    'types' => 'resources/js/types.ts',
    'update_profile_form' => 'resources/js/Pages/Profile/Partials/UpdateProfileInformationForm.tsx',
    'profile_show' => 'resources/js/Pages/Profile/Show.tsx',
    'login' => 'resources/js/Pages/Auth/Login.tsx',
    'welcome' => 'resources/js/Pages/Welcome.tsx',
    'register' => 'resources/js/Pages/Auth/Register.tsx',

    'registration_files' => [
        'app/Actions/Fortify/CreateNewUser.php',
        'resources/js/Pages/Auth/Register.tsx',
        'tests/Feature/RegistrationTest.php',
    ],

    'two_factor_files' => [
        'resources/js/Pages/Auth/TwoFactorChallenge.tsx',
        'resources/js/Pages/Profile/Partials/TwoFactorAuthenticationForm.tsx',
        'database/migrations/2025_03_21_025423_add_two_factor_columns_to_users_table.php',
        'tests/Feature/TwoFactorAuthenticationSettingsTest.php',
    ],

    'terms_files' => [
        'resources/js/Pages/TermsOfService.tsx',
        'resources/js/Pages/PrivacyPolicy.tsx',
        'resources/markdown/terms.md',
        'resources/markdown/policy.md',
    ],

    'passkey_files' => [
        'app/Http/Controllers/UserProfileController.php',
        'database/migrations/2024_01_01_000000_create_passkeys_table.php',
        'resources/js/Components/PasskeyVerify.tsx',
        'resources/js/Pages/Profile/Partials/ManagePasskeysForm.tsx',
        'tests/Feature/PasskeysTest.php',
    ],

    'passkey_npm_package' => '@laravel/passkeys',

    'account_deletion_files' => [
        'app/Actions/Jetstream/DeleteUser.php',
        'resources/js/Pages/Profile/Partials/DeleteUserForm.tsx',
        'tests/Feature/DeleteAccountTest.php',
    ],

    'teams_files' => [
        // Models, policies & actions
        'app/Models/Team.php',
        'app/Models/TeamInvitation.php',
        'app/Models/Membership.php',
        'app/Policies/TeamPolicy.php',
        'app/Actions/Jetstream/AddTeamMember.php',
        'app/Actions/Jetstream/CreateTeam.php',
        'app/Actions/Jetstream/DeleteTeam.php',
        'app/Actions/Jetstream/InviteTeamMember.php',
        'app/Actions/Jetstream/RemoveTeamMember.php',
        'app/Actions/Jetstream/UpdateTeamName.php',
        // Database
        'database/factories/TeamFactory.php',
        'database/migrations/2025_03_21_025431_create_teams_table.php',
        'database/migrations/2025_03_21_025432_create_team_user_table.php',
        'database/migrations/2025_03_21_025433_create_team_invitations_table.php',
        // Views & front-end
        'resources/views/emails/team-invitation.blade.php',
        'resources/js/Pages/Teams/Create.tsx',
        'resources/js/Pages/Teams/Show.tsx',
        'resources/js/Pages/Teams/Partials/CreateTeamForm.tsx',
        'resources/js/Pages/Teams/Partials/DeleteTeamForm.tsx',
        'resources/js/Pages/Teams/Partials/TeamMemberManager.tsx',
        'resources/js/Pages/Teams/Partials/UpdateTeamNameForm.tsx',
        // Tests
        'tests/Feature/CreateTeamTest.php',
        'tests/Feature/DeleteTeamTest.php',
        'tests/Feature/InviteTeamMemberTest.php',
        'tests/Feature/LeaveTeamTest.php',
        'tests/Feature/RemoveTeamMemberTest.php',
        'tests/Feature/UpdateTeamMemberRoleTest.php',
        'tests/Feature/UpdateTeamNameTest.php',
    ],

    // Team page partials that render the owner's or a member's avatar. They
    // survive the profile photo question, so only the <img> is stripped.
    'teams_photo_files' => [
        'resources/js/Pages/Teams/Partials/CreateTeamForm.tsx',
        'resources/js/Pages/Teams/Partials/TeamMemberManager.tsx',
        'resources/js/Pages/Teams/Partials/UpdateTeamNameForm.tsx',
    ],

    // Files that call ->withPersonalTeam() but are kept regardless of the
    // teams selection — the call is stripped via replace() when teams are off.
    'teams_factory_callers' => [
        'database/seeders/DatabaseSeeder.php',
        'tests/Feature/PasswordConfirmationTest.php',
        'tests/Feature/EmailVerificationTest.php',
        'tests/Feature/PasskeysTest.php',
        'tests/Feature/ApiTokenPermissionsTest.php',
        'tests/Feature/CreateApiTokenTest.php',
        'tests/Feature/DeleteApiTokenTest.php',
    ],

    'api_files' => [
        'resources/js/Pages/API/Index.tsx',
        'resources/js/Pages/API/Partials/APITokenManager.tsx',
        'database/migrations/2025_03_21_025431_create_personal_access_tokens_table.php',
        'tests/Feature/ApiTokenPermissionsTest.php',
        'tests/Feature/CreateApiTokenTest.php',
        'tests/Feature/DeleteApiTokenTest.php',
    ],

    'email_verification_files' => [
        'resources/js/Pages/Auth/VerifyEmail.tsx',
        'tests/Feature/EmailVerificationTest.php',
    ],
];
