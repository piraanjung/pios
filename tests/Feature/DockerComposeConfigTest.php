<?php

namespace Tests\Feature;

use Tests\TestCase;

class DockerComposeConfigTest extends TestCase
{
    /**
     * Test that docker-compose.yml exists and defines all 6 micro-containers.
     */
    public function test_docker_compose_defines_all_micro_containers(): void
    {
        $composePath = base_path('docker-compose.yml');
        $this->assertFileExists($composePath, 'docker-compose.yml must exist');

        $content = file_get_contents($composePath);

        $expectedServices = ['nginx', 'app', 'db', 'redis', 'vite', 'phpmyadmin'];
        foreach ($expectedServices as $service) {
            $this->assertMatchesRegularExpression(
                '/^\s{2}' . preg_quote($service, '/') . ':/m',
                $content,
                "docker-compose.yml must define service '{$service}'"
            );
        }

        // Port checks
        $this->assertStringContainsString('8080:80', $content, 'Nginx port 8080:80 must be mapped');
        $this->assertStringContainsString('8081:80', $content, 'phpMyAdmin port 8081:80 must be mapped');
        $this->assertStringContainsString('5173:5173', $content, 'Vite port 5173:5173 must be mapped');
    }

    /**
     * Test that all required docker configuration and template files exist.
     */
    public function test_docker_infrastructure_files_exist(): void
    {
        $files = [
            'docker/php/Dockerfile',
            'docker/php/local.ini',
            'docker/php/entrypoint.sh',
            'docker/nginx/default.conf',
            'docker/mysql/initdb.d/00-init-grants.sql',
            '.env.docker',
            'Dockerfile', // Root Cloud Run Dockerfile preserved
        ];

        foreach ($files as $file) {
            $this->assertFileExists(base_path($file), "Infrastructure file '{$file}' must exist");
        }

        $this->assertTrue(is_executable(base_path('docker/php/entrypoint.sh')), 'docker/php/entrypoint.sh must be executable');
    }

    /**
     * Test that database configuration contains mysql, envsogo_main, and tenant connections.
     */
    public function test_database_connections_are_configured(): void
    {
        $connections = config('database.connections');

        $this->assertArrayHasKey('envsogo_main', $connections);
        $this->assertArrayHasKey('tenant', $connections);
        $this->assertArrayHasKey('mysql', $connections);
    }
}
