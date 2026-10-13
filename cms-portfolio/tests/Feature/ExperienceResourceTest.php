<?php

namespace Tests\Feature;

use App\Filament\Admin\Resources\Experiences\ExperienceResource;
use App\Filament\Admin\Resources\Experiences\Pages\CreateExperience;
use App\Filament\Admin\Resources\Experiences\Pages\EditExperience;
use App\Models\Experience;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ExperienceResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.env' => 'local']);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_view_experiences_list_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/experiences')->assertSuccessful();
    }

    public function test_admin_can_create_a_certification_with_a_pdf_file(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateExperience::class)
            ->fillForm([
                'title' => 'Sertifikasi Laravel',
                'description' => 'Sertifikasi keahlian framework Laravel.',
                'category' => 'certification',
                'year' => '2026',
                'certificate_file' => UploadedFile::fake()->create('certificate.pdf', 100, 'application/pdf'),
            ])
            ->set('activeLocale', 'en')
            ->fillForm([
                'title' => 'Laravel Certification',
                'description' => 'Laravel framework proficiency certification.',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $experience = Experience::firstOrFail();

        $this->assertSame('Sertifikasi Laravel', $experience->getTranslation('title', 'id'));
        $this->assertSame('Laravel Certification', $experience->getTranslation('title', 'en'));
        $this->assertSame('certification', $experience->category);
        $this->assertNotNull($experience->certificate_file);
        Storage::disk('public')->assertExists($experience->certificate_file);
        $this->assertNull($experience->certificate_link);
    }

    public function test_admin_can_create_a_certification_with_a_link_instead_of_a_file(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateExperience::class)
            ->fillForm([
                'title' => 'Sertifikasi AWS',
                'category' => 'certification',
                'certificate_link' => 'https://example.com/certificates/aws.pdf',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $experience = Experience::firstOrFail();

        $this->assertSame('https://example.com/certificates/aws.pdf', $experience->certificate_link);
        $this->assertNull($experience->certificate_file);
    }

    public function test_admin_can_create_an_activity_with_a_photo(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateExperience::class)
            ->fillForm([
                'title' => 'Lomba Hackathon',
                'category' => 'activity',
                'year' => '2025',
                'photo' => UploadedFile::fake()->image('activity.png'),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $experience = Experience::firstOrFail();

        $this->assertSame('activity', $experience->category);
        $this->assertNotNull($experience->photo);
        Storage::disk('public')->assertExists($experience->photo);
    }

    public function test_admin_can_create_a_publication_with_a_link(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateExperience::class)
            ->fillForm([
                'title' => 'Publikasi Jurnal',
                'category' => 'publication',
                'publication_link' => 'https://example.com/journal/article',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $experience = Experience::firstOrFail();

        $this->assertSame('publication', $experience->category);
        $this->assertSame('https://example.com/journal/article', $experience->publication_link);
    }

    public function test_creating_an_experience_redirects_to_the_list_page(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateExperience::class)
            ->fillForm([
                'title' => 'Pengalaman Baru',
                'category' => 'activity',
            ])
            ->call('create')
            ->assertRedirect(ExperienceResource::getUrl('index'));
    }

    public function test_category_label_is_localized(): void
    {
        $experience = Experience::create([
            'title' => ['id' => 'Publikasi Jurnal', 'en' => 'Journal Publication'],
            'category' => 'publication',
        ]);

        $this->assertSame('Publikasi', $experience->getCategoryLabel('id'));
        $this->assertSame('Publication', $experience->getCategoryLabel('en'));
    }

    public function test_admin_can_edit_an_experience(): void
    {
        $this->actingAs(User::factory()->create());

        $experience = $this->makeExperience();

        Livewire::test(EditExperience::class, ['record' => $experience->getRouteKey()])
            ->fillForm(['year' => '2027'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('2027', $experience->refresh()->year);
    }

    public function test_admin_can_delete_an_experience(): void
    {
        $this->actingAs(User::factory()->create());

        $experience = $this->makeExperience();

        Livewire::test(EditExperience::class, ['record' => $experience->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($experience);
    }

    public function test_multiple_experiences_can_coexist(): void
    {
        $this->makeExperience();
        $this->makeExperience();

        $this->assertSame(2, Experience::count());
    }

    private function makeExperience(): Experience
    {
        return Experience::create([
            'title' => ['id' => 'Pengalaman Satu', 'en' => 'Experience One'],
            'category' => 'activity',
            'year' => '2025',
        ]);
    }
}
