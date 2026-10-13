<?php

namespace Tests\Feature\Api;

use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_profile_data_as_json(): void
    {
        $this->makeProfile();

        $response = $this->getJson('/api/profile');

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'title',
                    'about',
                    'photo',
                    'gpa',
                    'total_projects',
                    'total_technologies',
                ],
            ])
            ->assertJsonPath('data.name', 'Bayu Prayitno');
    }

    public function test_it_responds_in_indonesian_by_default(): void
    {
        $this->makeProfile();

        $response = $this->getJson('/api/profile', [
            'Accept-Language' => 'id',
        ]);

        $response->assertJsonPath('data.title', 'Mahasiswa Informatika')
            ->assertJsonPath('data.about', 'Tentang saya');
    }

    public function test_it_responds_in_english_when_requested(): void
    {
        $this->makeProfile();

        $response = $this->getJson('/api/profile', [
            'Accept-Language' => 'en',
        ]);

        $response->assertJsonPath('data.title', 'Informatics Student')
            ->assertJsonPath('data.about', 'About me');
    }

    public function test_it_falls_back_to_default_locale_when_header_is_unsupported(): void
    {
        $this->makeProfile();

        $response = $this->getJson('/api/profile', [
            'Accept-Language' => 'fr',
        ]);

        $response->assertJsonPath('data.title', 'Mahasiswa Informatika');
    }

    public function test_it_returns_404_when_no_profile_exists_yet(): void
    {
        $this->getJson('/api/profile')->assertNotFound();
    }

    private function makeProfile(): Profile
    {
        return Profile::create([
            'name' => 'Bayu Prayitno',
            'title' => ['id' => 'Mahasiswa Informatika', 'en' => 'Informatics Student'],
            'about' => ['id' => 'Tentang saya', 'en' => 'About me'],
            'gpa' => 3.75,
            'total_projects' => 5,
            'total_technologies' => 10,
        ]);
    }
}
