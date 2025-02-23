<?php
namespace MawebDK\PregMatch\Test;

use MawebDK\PregMatch\PregMatch;
use MawebDK\PregMatch\PregMatchException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PregMatchTest extends TestCase
{
    /**
     * @throws PregMatchException
     */
    #[DataProvider('dataProviderPregMatch')]
    public function testPregMatch(string $pattern, string $subject, array $expectedDataArray)
    {
        $result = PregMatch::pregMatch(pattern: $pattern, subject: $subject, matches: $matches);

        $actualDataArray = [
            'result'  => $result,
            'matches' => $matches
        ];
        $this->assertSame(expected: $expectedDataArray, actual: $actualDataArray);
    }

    public static function dataProviderPregMatch(): array
    {
        return [
            'Simple with match' => [
                'pattern'           => '#\A[0-9]+\Z#',
                'subject'           => '1234567890',
                'expectedDataArray' => ['result' => true, 'matches' => ['1234567890']]
            ],
            'Simple with named matches' => [
                'pattern'           => '#\A(?P<firstname>[a-zA-Z]+) (?P<lastname>[a-zA-Z]+)\Z#',
                'subject'           => 'John Doe',
                'expectedDataArray' => ['result' => true, 'matches' => [0 => 'John Doe', 'firstname' => 'John', 1 => 'John', 'lastname' => 'Doe', 2 => 'Doe']]
            ],
            'Simple without match' => [
                'pattern'           => '#\A[0-9]+\Z#',
                'subject'           => 'abcdefgh',
                'expectedDataArray' => ['result' => false, 'matches' => []]
            ],
        ];
    }

    #[DataProvider('dataProviderPregMatch_PregMatchException')]
    public function testPregMatch_PregMatchException(string $pattern, string $subject, string $expectedExceptionMessage, int $expectedExceptionCode)
    {
        $this->expectException(exception: PregMatchException::class);
        $this->expectExceptionMessage(message: $expectedExceptionMessage);
        $this->expectExceptionCode(code: $expectedExceptionCode);

        PregMatch::pregMatch(pattern: $pattern, subject: $subject);
    }

    public static function dataProviderPregMatch_PregMatchException(): array
    {
        return [
            'Empty pattern' => [
                'pattern'                  => '',
                'subject'                  => 'Sample',
                'expectedExceptionMessage' => 'pregMatch failed with errorMsg="Internal error", pattern="" and subject="Sample".',
                'expectedExceptionCode'    => PREG_INTERNAL_ERROR,
            ],
            'Missing delimiters' => [
                'pattern'                  => '[a-z]{8}',
                'subject'                  => 'Sample',
                'expectedExceptionMessage' => 'pregMatch failed with errorMsg="Internal error", pattern="[a-z]{8}" and subject="Sample".',
                'expectedExceptionCode'    => PREG_INTERNAL_ERROR,
            ],
            'Backtrack limit error' => [
                'pattern'                  => '#(?:\D+|<\d+>)*[!?]#',
                'subject'                  => 'foobar foobar foobar',
                'expectedExceptionMessage' => 'pregMatch failed with errorMsg="Backtrack limit exhausted", pattern="#(?:\D+|<\d+>)*[!?]#" and subject="foobar foobar foobar".',
                'expectedExceptionCode'    => PREG_BACKTRACK_LIMIT_ERROR,
            ],
        ];
    }
}
