<?php
namespace MawebDK\PregMatch;

/**
 * Use this class to perform a regular expression match using PHP's built-in function preg_match() returning true/false instead of 0/1 and
 * throwing an exception in case of an error.
 */
class PregMatch
{
    /**
     * Perform a regular expression match using PHP's built-in function preg_match().
     * @param string $pattern       Pattern to search for.
     * @param string $subject       Input string.
     * @param array|null $matches   If provided, then it will be filled with the result af search.
     *                              $matches[0] will contain the text that matched the full pattern.
     *                              $matches[1] will have the text that matched the first captured parenthesized subpattern, and so on.
     * @return bool                 True if pattern matches the given subject, false otherwise.
     * @throws PregMatchException   Failed to perform preg_match.
     */
    public static function pregMatch(string $pattern, string $subject, ?array &$matches = null): bool
    {
        // Warnings from preg_match() is intentionally suppressed.
        $result = @preg_match(pattern: $pattern, subject: $subject, matches: $matches);

        if ($result === 0):
            return false;
        elseif ($result === 1):
            return true;
        else:
            throw new PregMatchException(
                message: sprintf(
                    'pregMatch failed with errorMsg="%s", pattern="%s" and subject="%s".',
                    preg_last_error_msg(), $pattern, $subject
                ),
                code: preg_last_error(),
            );
        endif;
    }

    /**
     * Private constructor to avoid direct instantiation.
     */
    private function __construct()
    {
        // This body is empty on purpose.
    }
}