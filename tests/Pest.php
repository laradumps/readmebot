<?php

declare(strict_types = 1);

use Symfony\Component\Console\Input\ArgvInput;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

// uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeSemVer', function () {
    return $this->toBeString()
        ->toMatch('/((\d+)\.)?(?:(\d+)\.)?(?:(\d+)\.\d+)/');
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function randomStr(): string
{
    return strval(md5(strval(rand())));
}

function app()
{
    require __DIR__ . '/../bin/readmebot';

    return $application;
}

/**
 * Only runs the test when authentication is provided
 */
function requiresAuthentication(): void
{
    if (boolval(getenv('READMEBOT_KEY')) === false) {
        test()->markTestSkipped('Requires authentication to run.');
    }
}

/**
 * Only runs the test when the
 * the specified group is caled.
 * @author @emtudo
 * @see https://twitter.com/emtudo/status/1556700525227347969/photo/1
 */
function requireGroupToBeCalled(string $group): void
{
    $groups = (new ArgvInput())->getParameterOption('--group');

    if (is_string($groups)) {
        $groups = explode(',', $groups);
        if (in_array($group, $groups)) {
            return;
        }
    }

    test()->markTestSkipped("Only runs when group '{$group}' is called");
}

function fileContent(): string
{
    return <<<EOF
    # README FILE

    Welcome to LaraDumps <!--LaraDumpsVersion-->v1.2.3<!--EndOfLaraDumpsVersion-->
    
    This other version was not updated in a long time: <!--LaraDumpsVersion-->1.2.3<!--EndOfLaraDumpsVersion-->
    
    This markdown file is listed in `files.txt` and will be updated by the bot.
    
    ## Requirements
    
    The versions below should not change:
    
    - PHP 9.10.23
    - Ubuntu 3.4.5
    
    ## Install
    
    Run:
    
    <!--LaraDumpsVersion-->
    ```php
    composer require laradumps/laradumps-util:1.2.4
    ```
    <!--EndOfLaraDumpsVersion-->
    
    ## Download
    
    These links should be always up-to-date:
    
    <!--LaraDumpsVersion-->
      <a href="https://github.com/laradumps/app/releases/download/v1.2.3/LaraDumps-Setup-1.2.3.exe">Download win!</a>
      <a href="https://github.com/laradumps/app/releases/download/v1.2.3/LaraDumps-Setup-1.2.3.exe">Download mac!</a>
      <a href="https://github.com/laradumps/app/releases/download/v1.2.3/LaraDumps-Setup-1.2.3.exe">Download linux!</a>
    <!--EndOfLaraDumpsVersion-->
    EOF;
}
