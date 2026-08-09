<?php

require getenv('LARAVEL_INSTALLER_AUTOLOADER') ?: __DIR__.'/vendor/autoload.php';

use Laravel\Chisel\Chisel;
use Laravel\Chisel\Question;
use Laravel\Prompts\Support\Logger;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\task;

function chiselRun(array $command, string $label): void
{
    $process = task(
        label: $label,
        keepSummary: true,
        callback: function (Logger $logger) use ($command) {
            $process = new Process($command);
            $process->run(function ($type, $line) use ($logger) {
                $logger->line($line);
            });

            if ($process->isSuccessful()) {
                $logger->success(implode(' ', $command));

                return $process;
            }

            $logger->error(implode(' ', $command));
            $logger->error('Error output: '.trim($process->getErrorOutput()));
            $logger->error('Chisel: Your project may be in a partially-modified state — review the output above before continuing.');

            return $process;
        },
    );

    if (! $process->isSuccessful()) {
        exit($process->getExitCode());
    }
}

function chiselSkipsNode(): bool
{
    return filter_var(
        $_ENV['LARAVEL_INSTALLER_NO_NODE']
            ?? $_SERVER['LARAVEL_INSTALLER_NO_NODE']
            ?? getenv('LARAVEL_INSTALLER_NO_NODE'),
        FILTER_VALIDATE_BOOL,
    );
}

/**
 * Drop npm packages a deselected feature owned. When the installer was asked to
 * skip Node we cannot shell out to the package manager, so the dependency line
 * is removed from package.json directly.
 */
function chiselRemoveNpmPackages(Chisel $c, string ...$packages): void
{
    if (! chiselSkipsNode()) {
        $c->npm()->remove(...$packages);

        return;
    }

    foreach ($packages as $package) {
        $c->file('package.json')->removeLinesContaining('"'.$package.'":');
    }
}

/**
 * Framework-specific file paths are supplied by the sibling chisel-paths.php.
 *
 * @var array{
 *     app_layout: string,
 *     types: string,
 *     update_profile_form: string,
 *     profile_show: string,
 *     login: string,
 *     welcome: string,
 *     register: string,
 *     registration_files: list<string>,
 *     two_factor_files: list<string>,
 *     terms_files: list<string>,
 *     passkey_files: list<string>,
 *     passkey_npm_package: string,
 *     account_deletion_files: list<string>,
 *     teams_files: list<string>,
 *     teams_photo_files: list<string>,
 *     teams_factory_callers: list<string>,
 *     api_files: list<string>,
 *     email_verification_files: list<string>,
 *  } $paths
 */
$paths = require __DIR__.'/chisel-paths.php';

/**
 * Shared files carry a feature's markers and survive either way: the markers
 * are cleaned when the feature is kept and the sections stripped when it isn't.
 */
$registrationSharedFiles = [
    'config/fortify.php',
    'app/Providers/FortifyServiceProvider.php',
    'routes/web.php',
    $paths['welcome'],
    $paths['login'],
];

$emailVerificationSharedFiles = [
    'config/fortify.php',
    'app/Models/User.php',
    'routes/web.php',
    'database/factories/UserFactory.php',
    $paths['types'],
    $paths['update_profile_form'],
];

$twoFactorSharedFiles = [
    'config/fortify.php',
    'app/Models/User.php',
    'app/Providers/FortifyServiceProvider.php',
    'database/factories/UserFactory.php',
    $paths['types'],
    $paths['profile_show'],
];

$termsSharedFiles = [
    'config/jetstream.php',
    'app/Actions/Fortify/CreateNewUser.php',
    $paths['types'],
    $paths['register'],
];

$passkeySharedFiles = [
    'config/fortify.php',
    'app/Models/User.php',
    'app/Providers/AppServiceProvider.php',
    'app/Providers/FortifyServiceProvider.php',
    $paths['types'],
    $paths['profile_show'],
    $paths['login'],
];

$teamsSharedFiles = [
    'app/Models/User.php',
    'config/jetstream.php',
    'app/Providers/JetstreamServiceProvider.php',
    'app/Actions/Fortify/CreateNewUser.php',
    'app/Actions/Jetstream/DeleteUser.php',
    'database/factories/UserFactory.php',
    'database/migrations/0001_01_01_000000_create_users_table.php',
    $paths['app_layout'],
    $paths['types'],
];

$apiSharedFiles = [
    'app/Models/User.php',
    'config/jetstream.php',
    'app/Actions/Jetstream/DeleteUser.php',
    'app/Providers/JetstreamServiceProvider.php',
    $paths['app_layout'],
    $paths['types'],
];

$profilePhotoSharedFiles = [
    'config/jetstream.php',
    'app/Models/User.php',
    'app/Actions/Fortify/UpdateUserProfileInformation.php',
    'app/Actions/Jetstream/DeleteUser.php',
    'database/factories/UserFactory.php',
    'database/migrations/0001_01_01_000000_create_users_table.php',
    $paths['app_layout'],
    $paths['types'],
    $paths['update_profile_form'],
    ...$paths['teams_photo_files'],
];

$accountDeletionSharedFiles = [
    'config/jetstream.php',
    'app/Providers/JetstreamServiceProvider.php',
    $paths['types'],
    $paths['profile_show'],
];

return Chisel::script(__DIR__)
    ->questions([
        Question::multiselect(
            name: 'auth_features',
            label: 'Which authentication features would you like to enable?',
            options: [
                'registration' => 'Registration',
                'email-verification' => 'Email verification',
                '2fa' => 'Two-factor authentication',
                'passkeys' => 'Passkeys',
                'terms' => 'Terms of service & privacy policy',
            ],
            default: ['registration', 'email-verification', '2fa', 'passkeys'],
            hint: 'Use space to select, enter to confirm.',
        ),
        Question::multiselect(
            name: 'jetstream_features',
            label: 'Which Jetstream features would you like to enable?',
            options: [
                'teams' => 'Teams',
                'api' => 'API tokens',
                'profile-photos' => 'Profile photos',
                'account-deletion' => 'Account deletion',
            ],
            default: ['teams', 'api', 'account-deletion'],
            hint: 'Use space to select, enter to confirm.',
        ),
    ])
    // Teams is evaluated first so the ->withPersonalTeam() calls inside shared
    // tests are stripped while those files still exist.
    ->selected(
        'jetstream_features',
        'teams',
        then: function (Chisel $c) use ($teamsSharedFiles) {
            $c->files(...$teamsSharedFiles)->removeSectionMarkers('teams');
        },
        else: function (Chisel $c) use ($paths, $teamsSharedFiles) {
            // Collapse the personal-team wrapper rather than marking it, so we
            // are not left with an empty tap() closure.
            $c->file('app/Actions/Fortify/CreateNewUser.php')->replace(
                <<<'PHP'
                        return DB::transaction(function () use ($input) {
                            return tap(User::create([
                                'name' => $input['name'],
                                'email' => $input['email'],
                                'password' => Hash::make($input['password']),
                            ]), function (User $user) {
                                $this->createTeam($user);
                            });
                        });
                PHP,
                <<<'PHP'
                        return User::create([
                            'name' => $input['name'],
                            'email' => $input['email'],
                            'password' => Hash::make($input['password']),
                        ]);
                PHP,
            );

            $c->files(...$teamsSharedFiles)->removeSection('teams');

            $c->files(...$paths['teams_factory_callers'])
                ->replace('->withPersonalTeam()', '');

            $c->files(...$paths['teams_files'])->delete();
        },
    )
    ->selected(
        'jetstream_features',
        'api',
        then: function (Chisel $c) use ($apiSharedFiles) {
            $c->files(...$apiSharedFiles)->removeSectionMarkers('api');
        },
        else: function (Chisel $c) use ($paths, $apiSharedFiles) {
            $c->files(...$apiSharedFiles)->removeSection('api');

            $c->files(...$paths['api_files'])->delete();
        },
    )
    // Jetstream's roles carry both the team member roles and the API token
    // permissions, so they stay as long as either feature is in play.
    ->selectedAny(
        'jetstream_features',
        ['teams', 'api'],
        then: function (Chisel $c) {
            $c->file('app/Providers/JetstreamServiceProvider.php')
                ->removeSectionMarkers('teams-or-api');
        },
        else: function (Chisel $c) {
            $c->file('app/Providers/JetstreamServiceProvider.php')
                ->removeSection('teams-or-api');
        },
    )
    ->selected(
        'jetstream_features',
        'profile-photos',
        then: function (Chisel $c) use ($profilePhotoSharedFiles) {
            $c->file('config/jetstream.php')
                ->replace('// Features::profilePhotos(),', 'Features::profilePhotos(),');

            $c->files(...$profilePhotoSharedFiles)->removeSectionMarkers('profile-photos');
        },
        else: function (Chisel $c) use ($profilePhotoSharedFiles) {
            $c->files(...$profilePhotoSharedFiles)->removeSection('profile-photos');
        },
    )
    ->selected(
        'jetstream_features',
        'account-deletion',
        then: function (Chisel $c) use ($accountDeletionSharedFiles) {
            $c->files(...$accountDeletionSharedFiles)->removeSectionMarkers('account-deletion');
        },
        else: function (Chisel $c) use ($paths, $accountDeletionSharedFiles) {
            $c->files(...$accountDeletionSharedFiles)->removeSection('account-deletion');

            $c->files(...$paths['account_deletion_files'])->delete();
        },
    )
    ->selected(
        'auth_features',
        'registration',
        then: function (Chisel $c) use ($registrationSharedFiles) {
            $c->files(...$registrationSharedFiles)->removeSectionMarkers('registration');
        },
        else: function (Chisel $c) use ($paths, $registrationSharedFiles) {
            $c->files(...$registrationSharedFiles)->removeSection('registration');

            $c->files(...$paths['registration_files'])->delete();
        },
    )
    ->selected(
        'auth_features',
        'email-verification',
        then: function (Chisel $c) use ($emailVerificationSharedFiles) {
            $c->files(...$emailVerificationSharedFiles)->removeSectionMarkers('email-verification');
        },
        else: function (Chisel $c) use ($paths, $emailVerificationSharedFiles) {
            $c->files(...$emailVerificationSharedFiles)->removeSection('email-verification');

            // The interface list is trimmed rather than marked so that dropping
            // email verification and passkeys in any order stays valid PHP.
            $c->file('app/Models/User.php')
                ->replace('implements MustVerifyEmail, PasskeyUser', 'implements PasskeyUser')
                ->replace(' implements MustVerifyEmail', '');

            $c->files(...$paths['email_verification_files'])->delete();
        },
    )
    ->selected(
        'auth_features',
        '2fa',
        then: function (Chisel $c) use ($twoFactorSharedFiles) {
            $c->files(...$twoFactorSharedFiles)->removeSectionMarkers('2fa');
        },
        else: function (Chisel $c) use ($paths, $twoFactorSharedFiles) {
            $c->files(...$twoFactorSharedFiles)->removeSection('2fa');

            $c->files(...$paths['two_factor_files'])->delete();
        },
    )
    ->selected(
        'auth_features',
        'passkeys',
        then: function (Chisel $c) use ($passkeySharedFiles) {
            $c->files(...$passkeySharedFiles)->removeSectionMarkers('passkeys');
        },
        else: function (Chisel $c) use ($paths, $passkeySharedFiles) {
            $c->files(...$passkeySharedFiles)->removeSection('passkeys');

            $c->file('app/Models/User.php')
                ->replace('implements MustVerifyEmail, PasskeyUser', 'implements MustVerifyEmail')
                ->replace(' implements PasskeyUser', '');

            chiselRemoveNpmPackages($c, $paths['passkey_npm_package']);

            $c->files(...$paths['passkey_files'])->delete();
        },
    )
    ->selected(
        'auth_features',
        'terms',
        then: function (Chisel $c) use ($termsSharedFiles) {
            $c->file('config/jetstream.php')
                ->replace('// Features::termsAndPrivacyPolicy(),', 'Features::termsAndPrivacyPolicy(),');

            $c->files(...$termsSharedFiles)->removeSectionMarkers('terms');
        },
        else: function (Chisel $c) use ($paths, $termsSharedFiles) {
            $c->files(...$termsSharedFiles)->removeSection('terms');

            $c->files(...$paths['terms_files'])->delete();
        },
    )
    ->apply(function (Chisel $c): void {
        // Prune directories left empty after a feature's files were deleted
        // (deepest first so parents become empty before we reach them).
        foreach ([
            'resources/js/Pages/Teams/Partials',
            'resources/js/Pages/Teams',
            'resources/js/Pages/API/Partials',
            'resources/js/Pages/API',
            'resources/markdown',
        ] as $dir) {
            $path = __DIR__.'/'.$dir;

            if (is_dir($path) && count(scandir($path)) === 2) {
                @rmdir($path);
            }
        }

        chiselRun([__DIR__.'/vendor/bin/pint', '--quiet'], 'Pint');
        chiselRun(['npx', 'prettier', '--write', 'resources/js'], 'Prettier');

        // The composer script entry is the last in its list, so it has to go
        // with its leading comma to keep the JSON valid. The installer hook is
        // the only entry in its own list and can go by line.
        $c->file('composer.json')
            ->replace(",\n            \"@php artisan install:features --ansi\"", '')
            ->removeLinesContaining('"@php artisan install:features --ansi"');

        $c->files(
            'app/Console/Commands/InstallFeaturesCommand.php',
            'chisel.php',
            'chisel-paths.php',
        )->delete();
    });
