<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_todos_page(): void
    {
        $response = $this->get('/todos');
        $response->assertStatus(200);
        $response->assertSee('My Todos');
    }

    public function test_can_create_todo(): void
    {
        $response = $this->post('/todos', [
            'title' => 'Test Todo',
            'description' => 'This is a test todo',
        ]);

        $response->assertRedirect('/todos');
        $this->assertDatabaseHas('todos', [
            'title' => 'Test Todo',
            'description' => 'This is a test todo',
            'completed' => false,
        ]);
    }

    public function test_can_update_todo(): void
    {
        $todo = Todo::create([
            'title' => 'Original Title',
            'description' => 'Original Description',
        ]);

        $response = $this->put("/todos/{$todo->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'completed' => true,
        ]);

        $response->assertRedirect('/todos');
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'completed' => true,
        ]);
    }

    public function test_can_delete_todo(): void
    {
        $todo = Todo::create([
            'title' => 'To Be Deleted',
            'description' => 'This will be deleted',
        ]);

        $response = $this->delete("/todos/{$todo->id}");

        $response->assertRedirect('/todos');
        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    public function test_can_toggle_todo_status(): void
    {
        $todo = Todo::create([
            'title' => 'Test Todo',
            'completed' => false,
        ]);

        $response = $this->post("/todos/{$todo->id}/toggle");

        $response->assertRedirect('/todos');
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'completed' => true,
        ]);
    }

    public function test_api_can_list_todos(): void
    {
        Todo::create(['title' => 'Todo 1']);
        Todo::create(['title' => 'Todo 2']);

        $response = $this->getJson('/api/todos');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function test_api_can_create_todo(): void
    {
        $response = $this->postJson('/api/todos', [
            'title' => 'API Todo',
            'description' => 'Created via API',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'title' => 'API Todo',
            'description' => 'Created via API',
        ]);
    }

    public function test_api_can_toggle_todo(): void
    {
        $todo = Todo::create([
            'title' => 'Test Todo',
            'completed' => false,
        ]);

        $response = $this->postJson("/api/todos/{$todo->id}/toggle");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'completed' => true,
        ]);
    }

    public function test_title_is_required(): void
    {
        $response = $this->post('/todos', [
            'description' => 'Only description',
        ]);

        $response->assertSessionHasErrors('title');
    }
}
