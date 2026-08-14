<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function カテゴリー一覧が表示される(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->get('/categories');

        $response->assertStatus(200);
    }

    /** @test */
    public function カテゴリーを作成できる(): void
    {
        $response = $this->post('/categories', [
            'name' => 'テストカテゴリー',
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'name' => 'テストカテゴリー',
        ]);
    }

    /** @test */
    public function カテゴリー名が空だとバリエーションエラーになる(): void
    {
        $response = $this->post('/categories', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function カテゴリー名は10文字まで入力できる(): void
    {
        $response = $this->post('/categories', [
            'name' => str_repeat('あ', 10),
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'name' => str_repeat('あ', 10),
        ]);
    }

    /** @test */
    public function カテゴリー名が11文字以上だとバリデーションエラーになる(): void
    {
        $response = $this->post('/categories', [
            'name' => str_repeat('あ', 11),
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function カテゴリーを更新できる(): void
    {
        $category = Category::factory()->create();

        $response = $this->patch('/categories/update', [
            'id' => $category->id,
            'name' => '更新後のカテゴリー名',
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => '更新後のカテゴリー名',
        ]);
    }

    /** @test */
    public function カテゴリーを削除できる(): void
    {
        $category = Category::factory()->create();

        $response = $this->delete('/categories/delete', [
            'id' => $category->id
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
