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
	
	
	/**
	* Store News .
	*
	* @param submit form
	*
	* @return Response
	*/
	public function news_store()
    {
	    $this->withoutExceptionHandling();	
		
		$user = User::factory()->create();
	
        $response = $this->actingAs($user)->json('POST', 'news', [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph
        ]);

        $response->assertJsonStructure(['id', 'user_id', 'title', 'content', 'created_at', 'updated_at'])
            ->assertJson(['title' => $this->faker->sentence])
            ->assertStatus(201); //
        $this->assertDatabaseHas('news', ['title' => $this->faker->sentence]);
    }

	/**
	* Test field validation.
	*
	* @param $title
	*
	* @return Response
	*/
    public function news_validate_title()
    {
		$this->withoutExceptionHandling();
		
		$user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', 'news', [
            'title' => ''
        ]);
        
        //status HTTP 422
        $response->assertStatus(422) 
            ->assertSessionHasErrors('title');
    }
	
	
	/**
	* Test field validation.
	*
	* @param $content
	*
	* @return Response
	*/
	 public function news_validate_content()
	 {
		$this->withoutExceptionHandling();	

		$user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', 'news', [
            'content' => ''
        ]);
        //status HTTP 422
        $response->assertStatus(422) 
            ->assertSessionHasErrors('content');
    }

	/**
	* Test show news .
	*
	* @param $id
	*
	* @return news array
	*/
    public function news_show()
    {
		$this->withoutExceptionHandling();	

		$user = User::factory()->create();
        $news = News::factory()->create();

        $response = $this->actingAs($user)->json('GET', "/news/$news->id"); //id = 1 

        $response->assertJsonStructure(['user_id', 'title', 'content', 'created_at', 'updated_at'])
            ->assertJson(['title' => $news->title])
            ->assertStatus(200); //OK
    }

	/**
	* Test 404.
	*
	* @param $id
	*
	* @return error
	*/
    public function news_404_show()
    {
		$this->withoutExceptionHandling();	

		$user = User::factory()->create();

        $response = $this->actingAs($user)->json('GET', '/news/500'); //id = 1 

        $response->assertStatus(404); //OK
    }


	/**
	* Test News update.
	*
	* @param $title, $content form
	*
	* @return Response
	*/
    public function news_update()
    {
        $this->withoutExceptionHandling();
		
		$user = User::factory()->create();
        $news = News::factory()->create();

        $response = $this->actingAs($user)->json('PUT', "/news/$news->id", [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
			
        ]);

        $response->assertJsonStructure(['user_id', 'title', 'content', 'created_at', 'updated_at'])
            ->assertJson(['title' => $news->title])
            ->assertStatus(200); //OK

        $this->assertDatabaseHas('news', ['title' => $news->title]);
    }

	/**
	* Test delete News .
	*
	* @param $id
	*
	* @return Response
	*/
    public function news_delete()
    {
        $this->withoutExceptionHandling();

		$user = User::factory()->create();
        $news = News::factory()->create();

        $response = $this->actingAs($user)->json('delete', "/news/$news->id");

        $response->assertSee(null)
            ->assertStatus(204); //

        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

	
    public function news_index()
    {
		$this->withoutExceptionHandling();
		
		$user = User::factory()->create();

        factory(News::class, 5)->create();

        $response = $this->actingAs($user)->json('GET', '/news');

        $response->assertJsonStructure([
            'data' => [
                '*' => ['user_id', 'title', 'content', 'created_at', 'updated_at']
            ]
        ])->assertStatus(200); //OK
    }

    public function news_guest()
    {
        $this->json('GET',    '/news')->assertStatus(401);
        $this->json('POST',   '/news')->assertStatus(401);
        $this->json('GET',    '/news/500')->assertStatus(401);
        $this->json('PUT',    '/news/500')->assertStatus(401);
        $this->json('DELETE', '/news/500')->assertStatus(401);
    }
   
		
	
}
