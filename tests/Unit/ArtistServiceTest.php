<?php

use App\Data\Music\ArtistCreateData;
use App\Models\Music\Artist;
use App\Models\User;
use App\Services\Music\ArtistService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ArtistServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected ArtistService $artistService;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::find(1);
        $this->actingAs($user);

        $this->artistService = app(ArtistService::class);
    }

    /**
     * Тестирование сохранения исполнителя с изображением
     *
     * @return void
     */
    public function test_save_artist_with_image()
    {
        $artistData = ArtistCreateData::from([
            'user_id' => auth()->id(),
            'name' => 'Test Artist',
            'description' => 'Test Description',
            'country' => 'Test Country',
            'path' => '/test/path',
            'image' => UploadedFile::fake()->image('test-image.jpg')
        ]);

        $imageUploadMock = Mockery::mock('alias:App\Helpers\ImageUpload');
        $imageUploadMock->shouldReceive('make->setDiskName->setFolder->setSourceName->upload')
                        ->andReturn('path/to/uploaded/image.jpg');

        $artist = $this->artistService->saveArtist($artistData);

        $this->assertInstanceOf(Artist::class, $artist);
        $this->assertEquals('Test Artist', $artist->name);
        $this->assertEquals('Test Description', $artist->description);
        $this->assertEquals('Test Country', $artist->country);
        $this->assertEquals('/test/path', $artist->path);
        $this->assertEquals('path/to/uploaded/image.jpg', $artist->image);
    }
}
