<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_get_all_books()
    {
        $books = Book::factory(4)->create();
        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title
            ])
            ->assertJsonFragment([
                'title' => $books[1]->title
            ]);
    }

    public function test_can_get_one_book()
    {
        $book = Book::factory()->create();
        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    public function test_can_create_books()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'My new book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'My new book'
        ]);
    }

    public function test_can_update_books()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
                'title' => 'New title of the book'
            ])->assertJsonFragment([
                'title' => 'New title of the book'
            ]);

        $this->assertDatabaseHas('books', [
            'title' => 'New title of the book'
        ]);
    }

    public function test_can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseMissing('books', [
            'title' => 'My new book'
        ]);
    }
}
