<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_an_array_of_projects_as_json(): void
    {
        $this->makeProject();

        $response = $this->getJson('/api/id/projects');

        $response->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'title', 'description', 'category', 'tech_stack', 'image'],
                ],
            ]);
    }

    public function test_it_responds_in_indonesian_on_the_id_endpoint(): void
    {
        $this->makeProject();

        $response = $this->getJson('/api/id/projects');

        $response->assertJsonPath('data.0.title', 'Sistem Informasi Akademik')
            ->assertJsonPath('data.0.description', 'Aplikasi akademik kampus.')
            ->assertJsonPath('data.0.category', ['Desain', 'IoT']);
    }

    public function test_it_responds_in_english_on_the_en_endpoint(): void
    {
        $this->makeProject();

        $response = $this->getJson('/api/en/projects');

        $response->assertJsonPath('data.0.title', 'Academic Information System')
            ->assertJsonPath('data.0.description', 'Campus academic application.')
            ->assertJsonPath('data.0.category', ['Design', 'IoT']);
    }

    public function test_tech_stack_is_returned_as_an_array(): void
    {
        $this->makeProject();

        $this->getJson('/api/id/projects')
            ->assertJsonPath('data.0.tech_stack', ['Laravel', 'React']);
    }

    public function test_it_returns_an_empty_array_when_no_projects_exist(): void
    {
        $this->getJson('/api/id/projects')->assertJsonCount(0, 'data');
    }

    private function makeProject(): Project
    {
        return Project::create([
            'title' => ['id' => 'Sistem Informasi Akademik', 'en' => 'Academic Information System'],
            'description' => ['id' => 'Aplikasi akademik kampus.', 'en' => 'Campus academic application.'],
            'category' => ['design', 'iot'],
            'tech_stack' => ['Laravel', 'React'],
        ]);
    }
}
