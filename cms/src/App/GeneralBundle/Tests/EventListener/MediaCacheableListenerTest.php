<?php

namespace App\GeneralBundle\Tests\EventListener;

use App\GeneralBundle\Tests\BasePHPUnitTest;
use App\GeneralBundle\EventListener\MediaCacheableListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\GeneralBundle\Driver\AnnotationDriver;
use App\GeneralBundle\Tests\Resources\Entity\TestMediaCacheableEntity;

class MediaCacheableListenerTest extends BasePHPUnitTest
{
    /**
     * @var MediaCacheableListener
     */
    private $listener;

    public function setUp()
    {
        parent::setUp();
        $driver = new AnnotationDriver($this->container->get('annotation_reader'));
        $this->imageCacheManagerMock = \Mockery::mock('Liip\ImagineBundle\Imagine\Cache\CacheManager');
        $this->preUpdateEventArgsMock = \Mockery::mock('Doctrine\ORM\Event\PreUpdateEventArgs');
        $this->lifecycleEventArgs = \Mockery::mock('Doctrine\ORM\Event\LifecycleEventArgs');
        $this->listener = new MediaCacheableListener($driver, $this->imageCacheManagerMock);
    }

    public function testPreUpdateTopImageIsUpdatedByNewFile()
    {
        $entity = new TestMediaCacheableEntity('logo.png', 'bottom_logo.png');
        $this
            ->preUpdateEventArgsMock
            ->shouldReceive('getEntity')
            ->andReturn($entity);
        $this
            ->preUpdateEventArgsMock
            ->shouldReceive('hasChangedField')
            ->with('topImage')
            ->once()
            ->andReturn(true);
        $this
            ->preUpdateEventArgsMock
            ->shouldReceive('hasChangedField')
            ->once('bottomImage')
            ->once()
            ->andReturn(false);
        $this
            ->preUpdateEventArgsMock
            ->shouldReceive('getOldValue')
            ->once()
            ->with('topImage')
            ->andReturn('old_logo.png');
        $this->prepareImageCacheManager('fake_upload_dir' . DIRECTORY_SEPARATOR . 'old_logo.png', 'top_filter1');
        $this->prepareImageCacheManager('fake_upload_dir' . DIRECTORY_SEPARATOR . 'old_logo.png', 'top_filter2');
        $this->prepareImageCacheManager('fake_upload_dir' . DIRECTORY_SEPARATOR . 'bottom_logo.png', 'bottom_filter');

        $this->listener->preUpdate($this->preUpdateEventArgsMock);
    }

    public function testPostRemove()
    {
        $entity = new TestMediaCacheableEntity('logo.png', 'bottom_logo.png');
        $this
            ->lifecycleEventArgs
            ->shouldReceive('getEntity')
            ->andReturn($entity);
        $this->prepareImageCacheManager('fake_upload_dir' . DIRECTORY_SEPARATOR . 'logo.png', 'top_filter1');
        $this->prepareImageCacheManager('fake_upload_dir' . DIRECTORY_SEPARATOR . 'logo.png', 'top_filter2');
        $this->prepareImageCacheManager('fake_upload_dir' . DIRECTORY_SEPARATOR . 'bottom_logo.png', 'bottom_filter');
        $this->listener->postRemove($this->lifecycleEventArgs);
    }

    /**
     * Prepare mocked image cache manager object
     *
     * @param string $path
     * @param string $filter
     */
    protected function prepareImageCacheManager($path, $filter)
    {
        $this
            ->imageCacheManagerMock
            ->shouldReceive('remove')
            ->once()
            ->with($path, $filter)
            ->andReturnNull();
    }
}
