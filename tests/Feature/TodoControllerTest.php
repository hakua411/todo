<?php

namespace Tests\Feature;

use App\Models\Category;
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
        $category = Category::factory()->create();

        $response = $this->post('/todos', [
            'content' => 'テストTodo',
            'category_id' => $category->id,
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
        $category = Category::factory()->create();

         $response = $this->post('/todos', [
            'content' => str_repeat('あ', 20),
            'category_id' => $category->id,
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
        $category = Category::factory()->create();

        $response = $this->patch("/todos/{$todo}", [
            'id' => $todo->id,
            'content' => '更新後の内容',
            'category_id' => $category->id,
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
        $category = Category::factory()->create();

        $response = $this->delete("/todos/{$todo}", ['id' => $todo->id, 'category_id' => $category->id]);

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    /** @test */
    public function 検索キーワードに一致するTodoが表示される(): void
    {
        Todo::factory()->create([
            'content' => 'Laravelを勉強する',
        ]);

        Todo::factory()->create([
            'content' => 'PHPを勉強する',
        ]);

        $response = $this->get('/todos/search?keyword=Laravel');

        $response->assertStatus(200);
        $response->assertSee('Laravelを勉強する');
        $response->assertDontSee('PHPを勉強する');
    }

    /** @test */
    public function 部分一致検索ができる(): void
    {
        Todo::factory()->create([
            'content' => 'Laravelを勉強する',
        ]);

        Todo::factory()->create([
            'content' => 'LaravelでTodoアプリを作る',
        ]);

        Todo::factory()->create([
            'content' => 'PHPを勉強する',
        ]);

        $response = $this->get('/todos/search?keyword=Laravel');

        $response->assertSee('Laravelを勉強する');
        $response->assertSee('LaravelでTodoアプリを作る');
        $response->assertDontSee('PHPを勉強する');
    }

    /** @test */
    public function 検索キーワードが空の場合全件表示される(): void
    {
        Todo::factory()->create([
            'content' => 'Laravelを勉強する',
        ]);

        Todo::factory()->create([
            'content' => 'PHPを勉強する',
        ]);

        $response = $this->get('/todos/search?keyword=');

        $response->assertStatus(200);
        $response->assertSee('Laravelを勉強する');
        $response->assertSee('PHPを勉強する');
    }

    /** @test */
    public function カテゴリーで検索できる(): void
    {
        $workCategory = 
        Category::factory()->create([
            'name' => '仕事',
        ]);

        $programmingCategory = 
        Category::factory()->create([
            'name' => 'プログラミング',
        ]);

        Todo::factory()->create([
            'content' => '資料を作る',
            'category_id' => $workCategory->id,
        ]);

        Todo::factory()->create([
            'content' => 'Laravelを勉強する',
            'category_id' => $programmingCategory->id,
        ]);

        $response = $this->get('/todos/search?category_id=' . $workCategory->id);

        $response->assertStatus(200);
        $response->assertSee('資料を作る');
        $response->assertDontSee('Laravelを勉強する');
    }

    /** @test */
    public function キーワード＋カテゴリーで検索できる(): void
    {
        $programmingCategory = 
        Category::factory()->create([
            'name' => 'プログラミング',
        ]);

        $dairyCategory = 
        Category::factory()->create([
            'name' => '日常',
        ]);

        Todo::factory()->create([
            'content' => 'Laravelを勉強する',
            'category_id' => $programmingCategory->id,
        ]);

        Todo::factory()->create([
            'content' => 'Laravelの本を買う',
            'category_id' => $dairyCategory->id,
        ]);

        Todo::factory()->create([
            'content' => 'PHPを勉強する',
            'category_id' => $programmingCategory->id,
        ]);

        $response = $this->get('/todos/search?keyword=Laravel&category_id=' . $programmingCategory->id);

        $response->assertSee('Laravelを勉強する');
        $response->assertDontSee('Laravelの本を買う');
        $response->assertDontSee('PHPを勉強する');
    }
}
