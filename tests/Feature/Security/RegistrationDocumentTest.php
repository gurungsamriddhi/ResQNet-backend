<?php

namespace Tests\Feature\Security;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationDocumentTest extends TestCase
{
    use RefreshDatabase;

    private Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'organization']);

        Storage::fake('local');
        Storage::fake('public');
        Notification::fake();

        $this->post(route('register.organization.submit'), [
            'user_name' => 'Doc Owner',
            'user_email' => 'owner@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'org_name' => 'Doc Shelter',
            'org_email' => 'shelter@example.com',
            'registration_number' => 'REG-9',
            'document' => UploadedFile::fake()->createWithContent('registration.pdf', '%PDF-1.4 private'),
        ])->assertRedirect(route('registration.pending'));

        $this->organization = Organization::where('email', 'shelter@example.com')->firstOrFail();
    }

    public function test_registration_document_is_stored_on_the_private_disk()
    {
        $media = $this->organization->getFirstMedia('registration_documents');

        $this->assertSame('local', $media->disk);
        $this->assertEmpty(Storage::disk('public')->allFiles());
        Storage::disk('local')->assertExists($media->id . '/' . $media->file_name);
    }

    public function test_only_super_admins_can_download_the_document()
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('super_admin');

        $orgUser = User::factory()->create(['is_active' => true]);
        $orgUser->assignRole('organization');

        $url = route('admin.organizations.document', $this->organization);

        $this->get($url)->assertRedirect(route('admin.login'));
        $this->actingAs($orgUser)->get($url)->assertForbidden();
        $this->actingAs($admin)->get($url)->assertOk();
    }
}
