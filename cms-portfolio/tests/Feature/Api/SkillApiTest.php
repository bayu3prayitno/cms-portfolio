<?php

namespace Tests\Feature\Api;

use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_an_array_of_skills_as_json(): void
    {
        Skill::create(['name' => 'Laravel', 'icon' => 'skill-icons/laravel.png']);
        Skill::create(['name' => 'React', 'icon' => 'skill-icons/react.png']);

        $response = $this->getJson('/api/skills');

        $response->assertSuccessful()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'name', 'icon'],
                ],
            ])
            ->assertJsonPath('data.0.name', 'Laravel')
            ->assertJsonPath('data.0.icon', asset('storage/skill-icons/laravel.png'))
            ->assertJsonPath('data.1.name', 'React');
    }

    public function test_it_returns_an_empty_array_when_no_skills_exist(): void
    {
        $response = $this->getJson('/api/skills');

        $response->assertSuccessful()
            ->assertJsonCount(0, 'data');
    }

    public function test_icon_is_null_when_skill_has_no_icon(): void
    {
        Skill::create(['name' => 'Node.js']);

        $response = $this->getJson('/api/skills');

        $response->assertJsonPath('data.0.icon', null);
    }
}
