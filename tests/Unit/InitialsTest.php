<?php

namespace Tests\Unit;

use App\Support\Initials;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InitialsTest extends TestCase
{
    public static function initialsProvider(): array
    {
        return [
            'prenom et nom' => ['Muhamed Sene', 'MS'],
            'prenom compose' => ['Jean Pierre Dupont', 'JD'],
            'nom unique' => ['Prince', 'PR'],
            'initiales identiques' => ['Jean Dupont', 'JD'],
            'initiales repondent' => ['Ana Lopez', 'AL'],
            'espace en debut et fin' => ['   Ada Lovelace   ', 'AL'],
            'espaces multiples' => ["Ada\t\nLovelace", 'AL'],
            'minuscules converties' => ['ada lovelace', 'AL'],
            'accents preserves' => ['Émile Zola', 'ÉZ'],
            'nom vide' => ['', ''],
            'null' => [null, ''],
            'espaces seuls' => ['   ', ''],
        ];
    }

    #[DataProvider('initialsProvider')]
    public function test_it_builds_initials(?string $name, string $expected): void
    {
        $this->assertSame($expected, Initials::make($name));
    }

    public function test_helper_returns_the_same_result(): void
    {
        require_once __DIR__.'/../../app/Support/helpers.php';

        $this->assertSame('MS', initials('Muhamed Sene'));
        $this->assertSame('PR', initials('Prince'));
    }

    public function test_single_letter_when_max_is_one(): void
    {
        $this->assertSame('M', Initials::make('Muhamed Sene', 1));
    }
}
