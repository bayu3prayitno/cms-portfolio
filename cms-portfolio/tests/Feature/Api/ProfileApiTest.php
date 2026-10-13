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

        $response = $this->getJson('/api/id/profile');

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'title',
                    'about',
                    'photo',
                    'gpa',
                    'resume',
                    'social_links' => [
                        'instagram',
                        'whatsapp',
                        'github',
                        'linkedin',
                    ],
                ],
            ])
            ->assertJsonPath('data.name', 'Bayu Prayitno');
    }

    public function test_it_responds_in_indonesian_on_the_id_endpoint(): void
    {
        $this->makeProfile();

        $response = $this->getJson('/api/id/profile');

        $response->assertJsonPath('data.title', 'Mahasiswa Informatika')
            ->assertJsonPath('data.about', 'Tentang saya');
    }

    public function test_it_responds_in_english_on_the_en_endpoint(): void
    {
        $this->makeProfile();

        $response = $this->getJson('/api/en/profile');

        $response->assertJsonPath('data.title', 'Informatics Student')
            ->assertJsonPath('data.about', 'About me');
    }

    public function test_unsupported_locale_in_url_returns_404(): void
    {
        $this->makeProfile();

        $this->getJson('/api/fr/profile')->assertNotFound();
    }

    public function test_resume_is_returned_as_a_full_url(): void
    {
        $this->makeProfile();

        $this->getJson('/api/id/profile')
            ->assertJsonPath('data.resume', asset('storage/profile-resumes/resume.pdf'));
    }

    public function test_social_links_are_included_in_the_response(): void
    {
        Profile::create([
            'name' => 'Bayu Prayitno',
            'title' => ['id' => 'Mahasiswa', 'en' => 'Student'],
            'about' => ['id' => 'Tentang', 'en' => 'About'],
            'instagram' => 'https://instagram.com/bayu',
            'whatsapp' => 'https://wa.me/6281234567890',
            'github' => 'https://github.com/bayu',
            'linkedin' => 'https://linkedin.com/in/bayu',
        ]);

        $this->getJson('/api/id/profile')
            ->assertJsonPath('data.social_links.instagram', 'https://instagram.com/bayu')
            ->assertJsonPath('data.social_links.whatsapp', 'https://wa.me/6281234567890')
            ->assertJsonPath('data.social_links.github', 'https://github.com/bayu')
            ->assertJsonPath('data.social_links.linkedin', 'https://linkedin.com/in/bayu');
    }

    public function test_it_returns_404_when_no_profile_exists_yet(): void
    {
        $this->getJson('/api/id/profile')->assertNotFound();
    }

    private function makeProfile(): Profile
    {
        return Profile::create([
            'name' => 'Bayu Prayitno',
            'title' => ['id' => 'Mahasiswa Informatika', 'en' => 'Informatics Student'],
            'about' => ['id' => 'Tentang saya', 'en' => 'About me'],
            'gpa' => 3.75,
            'resume' => 'profile-resumes/resume.pdf',
        ]);
    }
}
