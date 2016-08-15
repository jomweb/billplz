<?php

include "vendor/autoload.php";

use Sami\Sami;
use Symfony\Component\Finder\Finder;
use Sami\Version\GitVersionCollection;
use Sami\RemoteRepository\GitHubRemoteRepository;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir = 'src');

$versions = GitVersionCollection::create('/')
    ->add('master', 'master branch');

return new Sami($iterator, [
    'versions'             => $versions,
    'title'                => 'Billplz API',
    'build_dir'            => __DIR__.'/api/%version%',
    'cache_dir'            => __DIR__.'/cache/%version%',
    'remote_repository'    => new GitHubRemoteRepository('jomweb/billplz', '/'),
    'default_opened_level' => 2,
]);
