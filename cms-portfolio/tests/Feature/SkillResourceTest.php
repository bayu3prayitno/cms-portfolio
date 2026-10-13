<?php

namespace Tests\Feature;

use App\Filament\Admin\Resources\Skills\Pages\CreateSkill;
use App\Filament\Admin\Resources\Skills\Pages\EditSkill;
use App\Filament\Admin\Resources\Skills\SkillResource;
use App\Models\Skill;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SkillResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.env' => 'local']);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_view_skills_list_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/skills')->assertSuccessful();
    }

    public function test_admin_can_create_a_skill_with_an_icon(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateSkill::class)
            ->fillForm([
                'name' => 'Laravel',
                'icon' => UploadedFile::fake()->image('laravel.png'),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $skill = Skill::firstOrFail();

        $this->assertSame('Laravel', $skill->name);
        $this->assertNotNull($skill->icon);
        Storage::disk('public')->assertExists($skill->icon);
    }

    public function test_creating_a_skill_redirects_to_the_list_page(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateSkill::class)
            ->fillForm(['name' => 'Laravel'])
            ->call('create')
            ->assertRedirect(SkillResource::getUrl('index'));
    }

    public function test_admin_can_edit_a_skill(): void
    {
        $this->actingAs(User::factory()->create());

        $skill = Skill::create(['name' => 'React']);

        Livewire::test(EditSkill::class, ['record' => $skill->getRouteKey()])
            ->fillForm(['name' => 'React.js'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('React.js', $skill->refresh()->name);
    }

    public function test_admin_can_delete_a_skill(): void
    {
        $this->actingAs(User::factory()->create());

        $skill = Skill::create(['name' => 'Node.js']);

        Livewire::test(EditSkill::class, ['record' => $skill->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($skill);
    }

    public function test_multiple_skills_can_coexist(): void
    {
        Skill::create(['name' => 'Laravel']);
        Skill::create(['name' => 'React']);
        Skill::create(['name' => 'Node.js']);

        $this->assertSame(3, Skill::count());
    }
}
