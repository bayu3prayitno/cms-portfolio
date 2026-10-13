<?php

namespace Tests\Feature\Api;

use App\Models\Experience;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExperienceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_an_array_of_experiences_as_json(): void
    {
        $this->makeCertification();

        $response = $this->getJson('/api/id/experiences');

        $response->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'title', 'description', 'category', 'year', 'photo', 'certificate_file', 'certificate_link', 'publication_link'],
                ],
            ]);
    }

    public function test_it_responds_in_indonesian_on_the_id_endpoint(): void
    {
        $this->makeCertification();

        $response = $this->getJson('/api/id/experiences');

        $response->assertJsonPath('data.0.title', 'Sertifikasi Laravel')
            ->assertJsonPath('data.0.description', 'Sertifikasi keahlian framework Laravel.')
            ->assertJsonPath('data.0.category', 'Sertifikasi');
    }

    public function test_it_responds_in_english_on_the_en_endpoint(): void
    {
        $this->makeCertification();

        $response = $this->getJson('/api/en/experiences');

        $response->assertJsonPath('data.0.title', 'Laravel Certification')
            ->assertJsonPath('data.0.description', 'Laravel framework proficiency certification.')
            ->assertJsonPath('data.0.category', 'Certification');
    }

    public function test_it_can_filter_by_category(): void
    {
        $this->makeCertification();
        $this->makeActivity();

        $response = $this->getJson('/api/id/experiences?category=activity');

        $response->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Lomba Hackathon');
    }

    public function test_it_returns_an_empty_array_when_no_experiences_exist(): void
    {
        $this->getJson('/api/id/experiences')->assertJsonCount(0, 'data');
    }

    public function test_links_and_file_urls_are_included(): void
    {
        $this->makeCertification();

        $response = $this->getJson('/api/id/experiences');

        $response->assertJsonPath('data.0.certificate_file', asset('storage/experience-certificates/certificate.pdf'))
            ->assertJsonPath('data.0.certificate_link', null)
            ->assertJsonPath('data.0.photo', null)
            ->assertJsonPath('data.0.publication_link', null);
    }

    private function makeCertification(): Experience
    {
        return Experience::create([
            'title' => ['id' => 'Sertifikasi Laravel', 'en' => 'Laravel Certification'],
            'description' => ['id' => 'Sertifikasi keahlian framework Laravel.', 'en' => 'Laravel framework proficiency certification.'],
            'category' => 'certification',
            'year' => '2026',
            'certificate_file' => 'experience-certificates/certificate.pdf',
        ]);
    }

    private function makeActivity(): Experience
    {
        return Experience::create([
            'title' => ['id' => 'Lomba Hackathon', 'en' => 'Hackathon Competition'],
            'category' => 'activity',
            'year' => '2025',
            'photo' => 'experience-photos/activity.png',
        ]);
    }
}
