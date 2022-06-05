<?php

use PHPUnit\Framework\TestCase;

class TestDatabaseConnection extends TestCase
{
    public function testDatabaseConnection()
    {
        $this->checkDockerIsRunning = file_get_contents(__DIR__ . "/logs/check_docker.log");
        $this->checkDockerIsRunning = filter_var(
            $this->checkDockerIsRunning,
            FILTER_SANITIZE_STRING,
            FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW
        );
        $this->assertEquals( "Docker is installed", $this->checkDockerIsRunning);
    }
}
