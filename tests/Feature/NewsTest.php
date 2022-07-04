<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\News;
use App\Models\User;


class NewsTest extends TestCase
{
    use WithFaker;
	
	use RefreshDatabase;
	
	/** @test */
    public function a_news_can_be_created ()
    {	
        $this->withoutExceptionHandling();
				
		$data = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph
        ];
		
		$user = User::factory()->create();
		
        $response = $this->actingAs($user)->json('POST', 'news', $data);
			    
		$response->assertJsonStructure(['id', 'user_id', 'title', 'content', 'created_at', 'updated_at'])
            ->assertJson(['title' => $data['title'], 'content' => $data['content']])
            ->assertStatus(201) //Ok
            ->assertJson(compact('data'));
        
			$this->assertDatabaseHas('news', [
			'title' => $data['title'],
			'content' => $data['content']
        ]);

    }
	
	
	 public function news_validate_title()
    {
		$user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', '/news', [
            'title' => '',
			'content' => ''
        ]);
        
        //Estatus HTTP 422
        $response->assertStatus(422) 
            ->assertJsonValidationErrors('title');
    }

	
	 /** @test */
    public function a_news_can_be_updated ()
    {
        $this->withoutExceptionHandling();
		
		$user = User::factory()->create();
        $news = News::factory()->make();

        $response = $this->actingAs($user)->json('PUT', "/news/$news->id", [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,	
        ]);

        $response->assertJsonStructure(['id', 'user_id', 'title', 'content', 'created_at', 'updated_at'])
            ->assertJson(['title' => $news->title])
            ->assertStatus(200); //OK

        $this->assertDatabaseHas('news', ['title' => $news->title]);

    }
	
	
	public function news_404_show()
    {
		$user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', '/news/1000'); //id = 1 

        $response->assertStatus(404); //OK
    }
		
	
	/** @test */
    public function list_of_news()
    {
		$user = User::factory()->create();

        $news = News::factory()->count(30)->make();

        $response = $this->actingAs($user)->json('GET', 'news');

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'user_id', 'title',  'content', 'created_at', 'updated_at']
            ]
        ])->assertStatus(200); //OK
		
    }
	
	 /** @test */
	public function a_news_can_be_deleted()
    {
        $this->withoutExceptionHandling();
		
        $user = User::factory()->create();
        $news = News::factory()->create();

        $response = $this->actingAs($user)->json('DELETE', "/news/$news->id");

        $response->assertSee(null)
            ->assertStatus(204); //

        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }
    
	
	/** @test */
	public function test_guest()
    {
        $this->json('GET',    'news')->assertStatus(401);
        $this->json('POST',   'news')->assertStatus(401);
        $this->json('GET',    'news/1000')->assertStatus(401);
        $this->json('PUT',    'news/1000')->assertStatus(401);
        $this->json('DELETE', 'news/1000')->assertStatus(401);
    }



}
