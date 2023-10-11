<?php

namespace Tests\Feature;

use Database\Seeders\PersonSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PersonTest extends TestCase {
	/**
	 * A basic feature test example.
	 */

	use RefreshDatabase;

	protected $seed = true;
	protected $seeder = PersonSeeder::class;

	public function test_index(): void {
		$response = $this->get('/api/persons');

		$response
			->assertStatus(200)
			->assertJsonCount(50);
	}

	public function test_post(): void {
		$firstName = fake()->firstName();
		$lastName = fake()->lastName();
		$response = $this->postJson('/api/persons/', ['first_name' => $firstName, 'last_name' => $lastName]);

		$this->postedPerson = $response->json();

		$response->assertStatus(201)
			->assertJson([
				'firstName' => $firstName,
				'lastName' => $lastName
			]);
//		print_r($this->postedPerson);
	}

	public function test_find(): void {
		$firstName = fake()->firstName();
		$lastName = fake()->lastName();
		$postResponse = $this->postJson('/api/persons/', ['first_name' => $firstName, 'last_name' => $lastName]);

		$response = $this->get('/api/persons/' . $postResponse->json()['guid']);

		$response
			->assertStatus(200)
			->assertExactJson($postResponse->json());
	}

	public function test_search(): void {
		$firstName = fake()->firstName();
		$lastName = fake()->lastName();
		$postResponse = $this->postJson('/api/persons/', ['first_name' => $firstName, 'last_name' => $lastName]);

		$response = $this->get('/api/persons/search?first_name=' . $postResponse->json()['firstName'] . '&last_name=' . $postResponse->json()['lastName']);

		$response->assertExactJson([
			$postResponse->json()
		]);
	}

	public function test_update(): void {
		$firstName = fake()->firstName();
		$lastName = fake()->lastName();
		$postResponse = $this->postJson('/api/persons/', ['first_name' => $firstName, 'last_name' => $lastName]);

		$firstName = fake()->firstName();
		$lastName = fake()->lastName();
		$response = $this->putJson('/api/persons/' . $postResponse->json()['guid'], ['first_name' => $firstName, 'last_name' => $lastName]);

		$response->assertExactJson([
			'guid' => $postResponse->json()['guid'],
			'firstName' => $firstName,
			'lastName' => $lastName
		]);
	}

	public function test_delete(): void {
		$firstName = fake()->firstName();
		$lastName = fake()->lastName();
		$postResponse = $this->postJson('/api/persons/', ['first_name' => $firstName, 'last_name' => $lastName]);

		$response = $this->delete('/api/persons/' . $postResponse->json()['guid']);

		$response->assertStatus(204);
	}
}
