<?php

declare(strict_types = 1);

namespace App\Actions;

use App\ValueObjects\Version;
use Exception;
use Illuminate\Support\Collection;
use function Safe\{preg_match, preg_match_all};

/** Parse Versions from text content based on comment tag */
final class ParseVersionTag
{
    /**
     *
     * @return Collection<int, Version>
     */
    public static function handle(string $content): Collection
    {
        $versions = new Collection();

        $re = '/<!--LaraDumpsVersion--[^>]*>([\s\S]*?)<!--EndOfLaraDumpsVersion-->/m';

        preg_match_all($re, $content, $versionCandidates, PREG_SET_ORDER, 0);

        //Loop through identified versions extacting only the
        foreach ($versionCandidates as $versionCandidate) {
            preg_match('/(?:(\d+)\.)?(?:(\d+)\.)?(?:(\d+)\.\d+)/', $versionCandidate[0], $versionFound, PREG_OFFSET_CAPTURE);

            try {
                $versions->push(Version::fromString($versionFound[0][0]));
            } catch (\Exception $e) {
                throw new \Exception('Could not parse version');
            }
        }

        if ($versions->isEmpty()) {
            throw new Exception('Could not find the current LaraDumps version.');
        }

        return $versions->unique();
    }
}
