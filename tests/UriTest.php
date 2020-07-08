<?php

namespace Hydra\Uri\Tests;

use Hydra\Uri\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    /** @test */
    public function it_parses_uri()
    {
        $uri = Uri::parse("https://www.google.com/path/to/file#fragment");

        $this->assertEquals("www.google.com", $uri->host());
        $this->assertEquals("https", $uri->scheme());
        $this->assertEquals("/path/to/file", $uri->path());
        $this->assertEquals("fragment", $uri->fragment());
    }

    /** @test */
    public function it_does_not_parse_empty_string()
    {
        $this->expectException(Exception::class);

        Uri::parse("");
    }

    /** @test */
    public function it_does_not_parse_string_with_spaces_only()
    {
        $this->expectException(Exception::class);

        Uri::parse("  ");
    }

    /** @test */
    public function it_trims_url_before_parsing()
    {
        $uri = Uri::parse("  https://www.google.com/  ");

        $this->assertNotEquals("  https", $uri->scheme());
        $this->assertNotEquals("/  ", $uri->path());
    }

    /** @test */
    public function it_sets_scheme_to_null_if_empty()
    {
        $uri = Uri::parse("www.google.com");

        $this->assertNull($uri->scheme());
    }

    /** @test */
    public function it_parses_port()
    {
        $uri = Uri::parse("http://127.0.0.1:8080");

        $this->assertEquals(8080, $uri->port());
    }

    /** @test */
    public function it_defaults_port_to_null()
    {
        $uri = Uri::parse("http://www.google.com");

        $this->assertNull($uri->port());
    }

    /** @test */
    public function it_sets_path_to_null_if_empty()
    {
        $uri = Uri::parse("https://www.google.com");

        $this->assertNull($uri->path());
    }

    /** @test */
    public function it_sets_path_to_null_if_has_query()
    {
        $uri = Uri::parse("https://www.google.com?a=b&c=&d");

        $this->assertNull($uri->path());
    }

    /** @test */
    public function it_sets_path_to_null_if_has_fragment()
    {
        $uri = Uri::parse("https://www.google.com#fragment");

        $this->assertNull($uri->path());
    }

    /** @test */
    public function it_sets_fragment_to_null_if_empty()
    {
        $uri = Uri::parse("https://www.google.com");

        $this->assertNull($uri->fragment());
    }

    /** @test */
    public function it_parses_query()
    {
        $uri = Uri::parse("http://www.google.com?a=b&c=d");

        $this->assertIsArray($uri->queries());
        $this->assertArrayHasKey("a", $uri->queries());
        $this->assertArrayHasKey("c", $uri->queries());
        $this->assertEquals("b", $uri->queries()["a"]);
        $this->assertEquals("d", $uri->queries()["c"]);
    }

    /** @test */
    public function it_sets_invalid_query_values_to_null()
    {
        $uri = Uri::parse("http://www.google.com?a=&&&c=d&e");

        $this->assertIsArray($uri->queries());
        $this->assertCount(3, $uri->queries());
        $this->assertArrayHasKey("a", $uri->queries());
        $this->assertArrayHasKey("c", $uri->queries());
        $this->assertArrayHasKey("e", $uri->queries());
        $this->assertNull($uri->queries()["a"]);
        $this->assertEquals("d", $uri->queries()["c"]);
        $this->assertNull($uri->queries()["e"]);
    }

    /** @test */
    public function it_checks_if_query_parameter_exists()
    {
        $uri = Uri::parse("https://www.google.com?a=b");

        $this->assertTrue($uri->queryHas("a"));
    }

    /** @test */
    public function it_checks_if_query_parameter_does_not_exists()
    {
        $uri = Uri::parse("https://www.google.com");

        $this->assertFalse($uri->queryHas("a"));
    }

    /** @test */
    public function it_gets_a_single_query_param()
    {
        $uri = Uri::parse("https://www.google.com?a=b&c");

        $this->assertEquals("b", $uri->query("a"));
        $this->assertNull($uri->query("c"));
        $this->assertNull($uri->query("asd"));
    }
}
