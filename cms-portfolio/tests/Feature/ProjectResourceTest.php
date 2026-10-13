<?php

namespace Tests\Feature;

use App\Filament\Admin\Resources\Projects\Pages\CreateProject;
use App\Filament\Admin\Resources\Projects\Pages\EditProject;
use App\Filament\Admin\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.env' => 'local']);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_view_projects_list_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/projects')->assertSuccessful();
    }

    public function test_admin_can_create_a_project_with_translations_and_thumbnail(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateProject::class)
            ->fillForm([
                'title' => 'Sistem Informasi Akademik',
                'description' => 'Aplikasi untuk mengelola data akademik kampus.',
                'category' => ['website', 'machine_learning'],
                'tech_stack' => ['Laravel', 'React', 'MySQL'],
                'image' => UploadedFile::fake()->image('thumbnail.png'),
            ])
            ->set('activeLocale', 'en')
            ->fillForm([
                'title' => 'Academic Information System',
                'description' => 'An application to manage campus academic data.',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $project = Project::firstOrFail();

        $this->assertSame('Sistem Informasi Akademik', $project->getTranslation('title', 'id'));
        $this->assertSame('Academic Information System', $project->getTranslation('title', 'en'));
        $this->assertSame('Aplikasi untuk mengelola data akademik kampus.', $project->getTranslation('description', 'id'));
        $this->assertSame('An application to manage campus academic data.', $project->getTranslation('description', 'en'));
        $this->assertSame(['website', 'machine_learning'], $project->category);
        $this->assertSame(['Laravel', 'React', 'MySQL'], $project->tech_stack);
        $this->assertNotNull($project->image);
        Storage::disk('public')->assertExists($project->image);
    }

    public function test_creating_a_project_redirects_to_the_list_page(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateProject::class)
            ->fillForm([
                'title' => 'Proyek Baru',
                'category' => ['website'],
            ])
            ->call('create')
            ->assertRedirect(ProjectResource::getUrl('index'));
    }

    public function test_category_labels_are_localized(): void
    {
        $project = Project::create([
            'title' => ['id' => 'Proyek', 'en' => 'Project'],
            'category' => ['design', 'iot'],
        ]);

        $this->assertSame(['Desain', 'IoT'], $project->getCategoryLabels('id'));
        $this->assertSame(['Design', 'IoT'], $project->getCategoryLabels('en'));
    }

    public function test_admin_can_edit_a_project(): void
    {
        $this->actingAs(User::factory()->create());

        $project = $this->makeProject();

        Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
            ->fillForm(['tech_stack' => ['Laravel', 'Vue', 'PostgreSQL']])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(['Laravel', 'Vue', 'PostgreSQL'], $project->refresh()->tech_stack);
    }

    public function test_admin_can_delete_a_project(): void
    {
        $this->actingAs(User::factory()->create());

        $project = $this->makeProject();

        Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($project);
    }

    public function test_multiple_projects_can_coexist(): void
    {
        $this->makeProject();
        $this->makeProject();

        $this->assertSame(2, Project::count());
    }

    private function makeProject(): Project
    {
        return Project::create([
            'title' => ['id' => 'Proyek Satu', 'en' => 'Project One'],
            'category' => ['website'],
            'tech_stack' => ['Laravel', 'React'],
        ]);
    }
}
