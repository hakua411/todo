<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function Todo一覧が表示される(): void
    {
        Todo::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function Todoを作成できる(): void
    {
        $response = $this->post('/todos', [
            'content' => 'テストTodo',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('todos', [
            'content' => 'テストTodo',
        ]);
    }

    /** @test */
    public function Todoの内容が空だとバリエーションエラーになる(): void
    {
        $response = $this->post('/todos', [
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    /** @test */
    public function Todoの内容は20文字まで入力できる(): void
    {
         $response = $this->post('/todos', [
            'content' => str_repeat('あ', 20),
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('todos', [
            'content' => str_repeat('あ', 20),
        ]);
    }

    /** @test */
    public function Todoの内容が21文字以上だとバリデーションエラーになる(): void
    {
        $response = $this->post('/todos', [
            'content' => str_repeat('あ', 21),
        ]);

        $response->assertSessionHasErrors('content');
    }

    /** @test */
    public function Todoを更新できる(): void
    {
        $todo = Todo::factory()->create();

        $response = $this->patch('/todos/update', [
            'id' => $todo->id,
            'content' => '更新後の内容',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'content' => '更新後の内容',
        ]);
    }

    /** @test */
    public function Todoを削除できる(): void
    {
        $todo = Todo::factory()->create();

        $response = $this->delete('/todos/delete', ['id' => $todo->id]);

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }
}
