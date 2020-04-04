<?php

namespace Tests\Unit;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use \Illuminate\Support\Str;

class ItemMediaTest extends TestCase
{
    use DatabaseTransactions;

    public function testImagePost()
    {
        list($user, $item) = $this->makeItem();

        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/ItemMedia/'.$item->id, [
                'image' => $file
            ])
            ->assertStatus(200)->assertJson([
                'status'  => true,
                'message' => 'Media saved and uploaded!',
            ]);
    }

    public function testImagePostFails()
    {
        list($user, $item) = $this->makeItem();
        
        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/ItemMedia/'.$item->id, [
            ])
            ->assertStatus(200)->assertJson([
                'status'  => false,
                'message' => 'Error Uploading',
            ]);
    }
}
