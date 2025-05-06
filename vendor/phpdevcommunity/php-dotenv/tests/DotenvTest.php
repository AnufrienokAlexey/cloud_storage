<?php

namespace Test\PhpDevCommunity;

use PhpDevCommunity\DotEnv;
use PhpDevCommunity\Processor\BooleanProcessor;
use PhpDevCommunity\Processor\NullProcessor;
use PhpDevCommunity\Processor\NumberProcessor;
use PhpDevCommunity\Processor\QuotedProcessor;
use PhpDevCommunity\UniTester\TestCase;

class DotenvTest extends TestCase
{
    protected function setUp(): void
    {
        // TODO: Implement setUp() method.
    }

    protected function tearDown(): void
    {
        $this->clearAllEnv();
    }

    private function clearAllEnv(): void
    {
        foreach ($_ENV as $key => $value) {
            unset($_ENV[$key]);
        }
        foreach ($_SERVER as $key => $value) {
            unset($_SERVER[$key]);
        }
    }

    protected function execute(): void
    {
        $this->clearAllEnv();
        $this->testLoad();
        $this->clearAllEnv();

        $this->testFileNotExist();
        $this->clearAllEnv();

        $this->testIncompatibleProcessors();
        $this->clearAllEnv();

        $this->testProcessBoolean();
        $this->clearAllEnv();

        $this->testDontProcessBoolean();
        $this->clearAllEnv();

        $this->testProcessQuotes();
        $this->clearAllEnv();

        $this->testDontProcessQuotes();
        $this->clearAllEnv();

        $this->testProcessNumbers();
    }

    private function env(string $file)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'env' . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoad() {

        (new DotEnv($this->env('.env.default')))->load();

        $this->assertEquals('dev', getenv('APP_ENV'));
        $this->assertEquals('mysql:host=localhost;dbname=test;', getenv('DATABASE_DNS'));
        $this->assertEquals('root', getenv('DATABASE_USER'));
        $this->assertEquals('password', getenv('DATABASE_PASSWORD'));
        $this->assertFalse(getenv('GOOGLE_API'));
        $this->assertFalse(getenv('GOOGLE_MANAGER_KEY'));
        $this->assertEquals(true, getenv('BOOLEAN_LITERAL'));
        $this->assertEquals('true', getenv('BOOLEAN_QUOTED'));

        $this->assertEquals('dev', $_ENV['APP_ENV']);
        $this->assertEquals('password', $_ENV['DATABASE_PASSWORD']);
        $this->assertFalse(array_key_exists('GOOGLE_API', $_ENV));
        $this->assertFalse(array_key_exists('GOOGLE_MANAGER_KEY', $_ENV));

        $this->assertEquals(true, $_ENV['BOOLEAN_LITERAL']);
        $this->assertEquals('true', $_ENV['BOOLEAN_QUOTED']);

        $this->assertEquals('mysql:host=localhost;dbname=test;', $_SERVER['DATABASE_DNS']);
        $this->assertEquals('root', $_SERVER['DATABASE_USER']);
        $this->assertEquals('password', $_SERVER['DATABASE_PASSWORD']);
        $this->assertFalse(array_key_exists('GOOGLE_API', $_SERVER));
        $this->assertFalse(array_key_exists('GOOGLE_MANAGER_KEY', $_SERVER));
        $this->assertEquals(true, $_SERVER['BOOLEAN_LITERAL']);
        $this->assertEquals('true', $_SERVER['BOOLEAN_QUOTED']);

        $this->assertEquals('🪄', $_SERVER['EMOJI']);

        $this->assertTrue(is_int($_SERVER['ZERO_LITERAL']));
        $this->assertEquals(0, $_SERVER['ZERO_LITERAL']);

        $this->assertTrue(is_string($_SERVER['ZERO_QUOTED']));
        $this->assertEquals('0', $_SERVER['ZERO_QUOTED']);

        $this->assertTrue(is_int($_SERVER['NUMBER_LITERAL']));
        $this->assertEquals(1111, $_SERVER['NUMBER_LITERAL']);

        $this->assertTrue(is_string($_SERVER['NUMBER_QUOTED']));
        $this->assertEquals('1111', $_SERVER['NUMBER_QUOTED']);

        $this->assertNull($_SERVER['NULL_LITERAL']);
        $this->assertTrue(array_key_exists('NULL_LITERAL', $_SERVER));

        $this->assertEquals('null', $_SERVER['NULL_QUOTED']);

        $this->assertEquals('', $_SERVER['EMPTY_LITERAL']);
        $this->assertEquals('', $_SERVER['EMPTY_QUOTED']);
    }

    public function testFileNotExist() {
        $this->expectException(\InvalidArgumentException::class, function () {
            (new DotEnv($this->env('.env.not-exists')))->load();
        });
    }

    /**
     * @runInSeparateProcess
     */
    public function testIncompatibleProcessors() {
        $this->assertProcessors(
            [],
            []
        );

        $this->assertProcessors(
            null,
            [
                NullProcessor::class,
                BooleanProcessor::class,
                NumberProcessor::class,
                QuotedProcessor::class
            ]
        );

        $this->assertProcessors(
            [null],
            []
        );

        $this->assertProcessors(
            [new \stdClass()],
            []
        );

        $this->assertProcessors(
            [QuotedProcessor::class, null],
            [QuotedProcessor::class]
        );
    }

    /**
     * @runInSeparateProcess
     */
    private function assertProcessors(array $processorsToUse = null, array $expectedProcessors = [])
    {
        $dotEnv = new DotEnv($this->env('.env.default'), $processorsToUse);
        $dotEnv->load();

        $this->assertEquals(
            $expectedProcessors,
            $this->getProtectedProperty($dotEnv)
        );
    }

    private function getProtectedProperty(object $object) {
        $reflection = new \ReflectionClass($object);
        $reflectionProperty = $reflection->getProperty('processors');
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }

    /**
     * @runInSeparateProcess
     */
    public function testProcessBoolean()
    {
        (new DotEnv($this->env('.env.boolean'), [
            BooleanProcessor::class
        ]))->load();

        $this->assertEquals(false, $_ENV['FALSE1']);
        $this->assertEquals(false, $_ENV['FALSE2']);
        $this->assertEquals(false, $_ENV['FALSE3']);
        $this->assertEquals("'false'", $_ENV['FALSE4']); // Since we don't have the QuotedProcessor::class this will be the result
        $this->assertEquals('0', $_ENV['FALSE5']);

        $this->assertEquals(true, $_ENV['TRUE1']);
        $this->assertEquals(true, $_ENV['TRUE2']);
        $this->assertEquals(true, $_ENV['TRUE3']);
        $this->assertEquals("'true'", $_ENV['TRUE4']); // Since we don't have the QuotedProcessor::class this will be the result
        $this->assertEquals('1', $_ENV['TRUE5']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testDontProcessBoolean()
    {
        (new DotEnv($this->env('.env.boolean'), []))->load();

        $this->assertEquals('false', $_ENV['FALSE1']);
        $this->assertEquals('true', $_ENV['TRUE1']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testProcessQuotes()
    {
        (new DotEnv($this->env('.env.quotes'), [
            QuotedProcessor::class
        ]))->load();

        $this->assertEquals('q1', $_ENV['QUOTED1']);
        $this->assertEquals('q2', $_ENV['QUOTED2']);
        $this->assertEquals('"q3"', $_ENV['QUOTED3']);
        $this->assertEquals('This is a "sample" value', $_ENV['QUOTED4']);
        $this->assertEquals('\"This is a "sample" value\"', $_ENV['QUOTED5']);
        $this->assertEquals('"q6', $_ENV['QUOTED6']);
        $this->assertEquals('q7"', $_ENV['QUOTED7']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testDontProcessQuotes()
    {
        (new DotEnv($this->env('.env.quotes'), []))->load();

        $this->assertEquals('"q1"', $_ENV['QUOTED1']);
        $this->assertEquals('\'q2\'', $_ENV['QUOTED2']);
        $this->assertEquals('""q3""', $_ENV['QUOTED3']);
        $this->assertEquals('"This is a "sample" value"', $_ENV['QUOTED4']);
        $this->assertEquals('\"This is a "sample" value\"', $_ENV['QUOTED5']);
        $this->assertEquals('"q6', $_ENV['QUOTED6']);
        $this->assertEquals('q7"', $_ENV['QUOTED7']);
        $this->assertEquals('0', $_ENV['ZERO_LITERAL']);
        $this->assertEquals('"0"', $_ENV['ZERO_QUOTED']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testProcessNumbers()
    {
        (new DotEnv($this->env('.env.number'), [
            NumberProcessor::class
        ]))->load();

        $this->assertEquals(0, $_ENV['NUMBER1']);
        $this->assertTrue(is_numeric($_ENV['NUMBER1']));
        $this->assertEquals(0.0001, $_ENV['NUMBER2']);
        $this->assertEquals(123456789, $_ENV['NUMBER3']);
        $this->assertEquals(123456789.0, $_ENV['NUMBER4']);
    }
}
