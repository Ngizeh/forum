<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{
	use RefreshDatabase;

	/** @test **/
	public function members_can_only_upload_an_avatar()
	{
		$response = $this->json('post', '/api/users/1/avatar');

		$response->assertStatus(401);
	}

	/** @test **/
	public function validate_the_avatar()
	{
		$this->be(User::factory()->create());

		$response = $this->json('post', '/api/users/'.auth()->id().'/avatar', [
			'avatar' => 'not-valid-avatar'
		]);

		$response->assertStatus(422);
	}

	/** @test **/
	public function a_user_my_add_an_avatar_for_their_profile()
	{

		$this->be(User::factory()->create());

		Storage::disk('public');

		$this->json('post', '/api/users/'.auth()->id().'/avatar', [
			'avatar' => $file = UploadedFile::fake()->image('avatar.jpeg')
		]);

		$this->assertEquals(asset('avatars/'.$file->hashName()), auth()->user()->avatar_path);

		Storage::disk('public')->assertExists('avatars/'.$file->hashName());
	}
}
