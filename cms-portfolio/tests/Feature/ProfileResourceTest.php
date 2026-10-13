<?php

namespace Tests\Feature;

use App\Filament\Admin\Resources\Profiles\Pages\CreateProfile;
use App\Filament\Admin\Resources\Profiles\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.env' => 'local']);
    }

    public function test_admin_can_view_profiles_list_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/profiles')->assertSuccessful();
    }

    public function test_admin_can_view_create_profile_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/profiles/create')->assertSuccessful();
    }

    public function test_admin_can_view_edit_profile_page(): void
    {
        $this->actingAs(User::factory()->create());

        $profile = $this->makeProfile();

        $this->get("/admin/profiles/{$profile->id}/edit")->assertSuccessful();
    }

    public function test_admin_can_create_profile_with_translations_for_each_locale(): void
    {
        $this->actingAs(User::factory()->create());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $component = Livewire::test(CreateProfile::class)
            ->fillForm([
                'name' => 'Bayu Prayitno',
                'title' => 'Mahasiswa Informatika',
                'about' => 'Tentang saya dalam Bahasa Indonesia',
                'gpa' => 3.75,
            ])
            ->set('activeLocale', 'en')
            ->fillForm([
                'title' => 'Informatics Student',
                'about' => 'About me in English',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $profile = Profile::firstOrFail();

        $this->assertSame('Bayu Prayitno', $profile->name);
        $this->assertSame('Mahasiswa Informatika', $profile->getTranslation('title', 'id'));
        $this->assertSame('Informatics Student', $profile->getTranslation('title', 'en'));
        $this->assertSame('Tentang saya dalam Bahasa Indonesia', $profile->getTranslation('about', 'id'));
        $this->assertSame('About me in English', $profile->getTranslation('about', 'en'));
    }

    public function test_admin_can_upload_a_resume(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(CreateProfile::class)
            ->fillForm([
                'name' => 'Bayu Prayitno',
                'title' => 'Mahasiswa Informatika',
                'about' => 'Tentang saya',
                'resume' => UploadedFile::fake()->create('resume.pdf', 500, 'application/pdf'),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $profile = Profile::firstOrFail();

        $this->assertNotNull($profile->resume);
        Storage::disk('public')->assertExists($profile->resume);
    }

    public function test_admin_can_fill_in_social_media_links(): void
    {
        $this->actingAs(User::factory()->create());
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(CreateProfile::class)
            ->fillForm([
                'name' => 'Bayu Prayitno',
                'title' => 'Mahasiswa Informatika',
                'about' => 'Tentang saya',
                'instagram' => 'https://instagram.com/bayu',
                'whatsapp' => 'https://wa.me/6281234567890',
                'github' => 'https://github.com/bayu',
                'linkedin' => 'https://linkedin.com/in/bayu',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $profile = Profile::firstOrFail();

        $this->assertSame('https://instagram.com/bayu', $profile->instagram);
        $this->assertSame('https://wa.me/6281234567890', $profile->whatsapp);
        $this->assertSame('https://github.com/bayu', $profile->github);
        $this->assertSame('https://linkedin.com/in/bayu', $profile->linkedin);
    }

    public function test_list_page_redirects_to_edit_page_when_profile_already_exists(): void
    {
        $this->actingAs(User::factory()->create());

        $profile = $this->makeProfile();

        $this->get('/admin/profiles')
            ->assertRedirect(ProfileResource::getUrl('edit', ['record' => $profile]));
    }

    public function test_create_page_redirects_to_edit_page_when_profile_already_exists(): void
    {
        $this->actingAs(User::factory()->create());

        $profile = $this->makeProfile();

        $this->get('/admin/profiles/create')
            ->assertRedirect(ProfileResource::getUrl('edit', ['record' => $profile]));
    }

    public function test_cannot_create_a_second_profile(): void
    {
        $this->makeProfile();

        $this->assertFalse(ProfileResource::canCreate());
    }

    public function test_can_create_profile_when_none_exists_yet(): void
    {
        $this->assertTrue(ProfileResource::canCreate());
    }

    private function makeProfile(): Profile
    {
        return Profile::create([
            'name' => 'Bayu Prayitno',
            'title' => ['id' => 'Mahasiswa Informatika', 'en' => 'Informatics Student'],
            'about' => ['id' => 'Tentang saya', 'en' => 'About me'],
            'gpa' => 3.75,
        ]);
    }
}
