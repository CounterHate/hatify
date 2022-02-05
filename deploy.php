<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'hatify');

// Project repository
set('repository', 'git@github.com:CounterHate/hatify.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts

host('91.192.224.142')
    ->set('deploy_path', '/var/www/html/dev/{{application}}');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
    run('cd {{release_path}} && npm install');
    run('cd {{release_path}} && npm run dev');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');



// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');

