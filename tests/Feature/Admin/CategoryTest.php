<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'Admin']);
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Admin');
    }

    public function test_admin_can_view_categories_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
    }

    public function test_non_admin_cannot_view_categories()
    {
        $user = User::factory()->create(); // No admin role

        $response = $this->actingAs($user)->get(route('admin.categories.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_create_category()
    {
        $data = [
            'name' => 'Electronics',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), $data);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Electronics']);
    }
}
