<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Url;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Orm\Zed\Url\Persistence\SpyUrlRedirectQuery;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Url\Business\UrlFacade;
use Spryker\Zed\Url\Persistence\UrlQueryContainer;
use Spryker\Zed\Url\UrlConfig;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Url
 * @group UrlFacadeTest
 */
class UrlFacadeTest extends Test
{

    /**
     * @var \Spryker\Zed\Url\Business\UrlFacade
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainer
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->urlFacade = new UrlFacade();
        $this->localeFacade = new LocaleFacade();
        $this->urlQueryContainer = new UrlQueryContainer();
        $this->touchQueryContainer = new TouchQueryContainer();
    }

    /**
     * @return void
     */
    public function testCreateUrlPersistsNewEntityToDatabase()
    {
        $urlQuery = $this->urlQueryContainer->queryUrls();
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');

        $urlTransfer = new UrlTransfer();
        $urlTransfer
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale());

        $urlCountBeforeCreation = $urlQuery->count();
        $newUrlTransfer = $this->urlFacade->createUrl($urlTransfer);
        $urlCountAfterCreation = $urlQuery->count();

        $this->assertGreaterThan(
            $urlCountBeforeCreation,
            $urlCountAfterCreation,
            'Number of url entities in database should be higher after creating new entity.'
        );

        $this->assertNotNull($newUrlTransfer->getIdUrl(), 'Returned transfer object should have url ID.');

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_URL, $newUrlTransfer->getIdUrl(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
        $this->assertEquals(1, $touchQuery->count(), 'New entity should have active touch entry after creation.');
    }

    /**
     * @return void
     */
    public function testUpdateUrlPersistsChangedToDatabase()
    {
        $localeTransfer1 = $this->localeFacade->createLocale('ab_CD');
        $localeTransfer2 = $this->localeFacade->createLocale('ef_GH');

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/SoManyPageUrls')
            ->setFkLocale($localeTransfer1->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer
            ->setUrl('/SoManyPageUrls-2')
            ->setIdUrl($urlEntity->getIdUrl())
            ->setFkLocale($localeTransfer2->getIdLocale());

        $urlTransfer = $this->urlFacade->updateUrl($urlTransfer);

        $urlEntity = $this->urlQueryContainer
            ->queryUrl('/SoManyPageUrls-2')
            ->findOne();
        $this->assertInstanceOf(SpyUrl::class, $urlEntity, 'Url entity with new data should be in database after update.');
        $this->assertEquals($urlTransfer->getFkLocale(), $urlEntity->getFkLocale(), 'Url entity should have updated locale ID.');

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_URL, $urlEntity->getIdUrl(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);
        $this->assertEquals(1, $touchQuery->count(), 'Url entity should have active touch entry after update.');
    }

    /**
     * @return void
     */
    public function testFindUrlEntityByUrl()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl('/some/url/like/string');

        $urlTransfer = $this->urlFacade->findUrl($urlTransfer);

        $this->assertNotNull($urlTransfer, 'Finding existing URL entity by path should return transfer object.');
        $this->assertEquals($urlEntity->getIdUrl(), $urlTransfer->getIdUrl(), 'Reading URL entity by path should return transfer with proper data.');
    }

    /**
     * @return void
     */
    public function testFindUrlEntityById()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $urlTransfer = $this->urlFacade->findUrl($urlTransfer);

        $this->assertNotNull($urlTransfer, 'Finding existing URL entity by ID should return transfer object.');
        $this->assertEquals($urlEntity->getUrl(), $urlTransfer->getUrl(), 'Reading URL entity by ID should return transfer with proper data.');
    }

    /**
     * @return void
     */
    public function testHasUrlEntityByUrl()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl('/some/url/like/string');

        $hasUrl = $this->urlFacade->hasUrl($urlTransfer);

        $this->assertTrue($hasUrl, 'Checking if URL entity exists by path should return true.');
    }

    /**
     * @return void
     */
    public function testHasUrlEntityById()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $hasUrl = $this->urlFacade->hasUrl($urlTransfer);

        $this->assertTrue($hasUrl, 'Checking if URL entity exists by ID should return true.');
    }

    /**
     * @return void
     */
    public function testDeleteUrlShouldRemoveEntityFromDatabase()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $urlQuery = SpyUrlQuery::create()->filterByIdUrl($urlEntity->getIdUrl());

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_URL, $urlTransfer->getIdUrl(), SpyTouchTableMap::COL_ITEM_EVENT_DELETED);

        $this->assertEquals(1, $urlQuery->count(), 'Url entity should exist before deleting it.');
        $this->assertEquals(0, $touchQuery->count(), 'Entity should not have deleted touch entry before deletion.');

        $this->urlFacade->deleteUrl($urlTransfer);

        $this->assertEquals(0, $urlQuery->count(), 'Url entity should not exist after deleting it.');
        $this->assertEquals(1, $touchQuery->count(), 'Entity should have deleted touch entry before deletion.');
    }

    /**
     * @return void
     */
    public function testActivateUrlShouldCreateActiveTouchEntry()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_URL, $urlTransfer->getIdUrl(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);

        $this->assertEquals(0, $touchQuery->count(), 'New entity should not have active touch entry before activation.');
        $this->urlFacade->activateUrl($urlTransfer);
        $this->assertEquals(1, $touchQuery->count(), 'New entity should have active touch entry after activation.');
    }

    /**
     * @return void
     */
    public function testDeactivateUrlShouldCreateDeletedTouchEntry()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_URL, $urlTransfer->getIdUrl(), SpyTouchTableMap::COL_ITEM_EVENT_DELETED);

        $this->assertEquals(0, $touchQuery->count(), 'New entity should not have deleted touch entry before activation.');
        $this->urlFacade->deactivateUrl($urlTransfer);
        $this->assertEquals(1, $touchQuery->count(), 'New entity should have deleted touch entry after activation.');
    }

    /**
     * @return void
     */
    public function testCreateUrlRedirectEntityPersistsToDatabase()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $sourceUrlTransfer = new UrlTransfer();
        $sourceUrlTransfer
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale());
        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer
            ->setSource($sourceUrlTransfer)
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(123);

        $urlRedirectTransfer = $this->urlFacade->createUrlRedirect($urlRedirectTransfer);

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_REDIRECT, $urlRedirectTransfer->getIdUrlRedirect(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);

        $this->assertNotNull($urlRedirectTransfer->getIdUrlRedirect(), 'Newly created URL redirect entity should have ID returned.');
        $this->assertEquals(1, $touchQuery->count(), 'New entity should have active touch entry after creation.');
    }

    /**
     * @return void
     */
    public function testUpdateUrlRedirectEntityPersistsToDatabase()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $sourceUrlTransfer = new UrlTransfer();
        $sourceUrlTransfer
            ->setIdUrl($urlEntity->getIdUrl())
            ->setUrl('/updated/url/like/string');

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer
            ->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->setToUrl('/updated/url/to/redirect/to')
            ->setSource($sourceUrlTransfer);

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_REDIRECT, $urlRedirectTransfer->getIdUrlRedirect(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);

        $this->assertEquals(0, $touchQuery->count(), 'Url redirect entity should not have active touch entry before update.');

        $updatedUrlRedirectTransfer = $this->urlFacade->updateUrlRedirect($urlRedirectTransfer);

        $this->assertSame($urlRedirectTransfer->getToUrl(), $updatedUrlRedirectTransfer->getToUrl(), 'Updated URL redirect entity should have proper data returned.');
        $this->assertEquals(1, $touchQuery->count(), 'New entity should have active touch entry after update.');
    }

    /**
     * @return void
     */
    public function testFindUrlRedirectEntityById()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $urlRedirectTransfer = $this->urlFacade->findUrlRedirect($urlRedirectTransfer);

        $this->assertNotNull($urlRedirectTransfer, 'Finding existing URL redirect entity by ID should return transfer object.');
        $this->assertEquals($urlRedirectEntity->getToUrl(), $urlRedirectTransfer->getToUrl(), 'Reading URL redirect entity by ID should return transfer with proper data.');
    }

    /**
     * @return void
     */
    public function testHasUrlRedirectEntityById()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $hasUrlRedirect = $this->urlFacade->hasUrlRedirect($urlRedirectTransfer);

        $this->assertTrue($hasUrlRedirect, 'Checking if URL redirect entity exists by ID should return true.');
    }

    /**
     * @return void
     */
    public function testDeleteUrlRedirectShouldRemoveEntityFromDatabaseAlongWithUrlEntity()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $urlQuery = SpyUrlRedirectQuery::create()->filterByIdUrlRedirect($urlRedirectTransfer->getIdUrlRedirect());
        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_REDIRECT, $urlRedirectTransfer->getIdUrlRedirect(), SpyTouchTableMap::COL_ITEM_EVENT_DELETED);

        $this->assertEquals(1, $urlQuery->count(), 'Url entity should exist before deleting it.');
        $this->assertEquals(0, $touchQuery->count(), 'Entity should not have deleted touch entry before deletion.');

        $this->urlFacade->deleteUrlRedirect($urlRedirectTransfer);

        $this->assertEquals(0, $urlQuery->count(), 'Url entity should not exist after deleting it.');
        $this->assertEquals(1, $touchQuery->count(), 'Entity should have deleted touch entry before deletion.');
    }

    /**
     * @return void
     */
    public function testActivateUrlRedirectShouldCreateActiveTouchEntry()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_REDIRECT, $urlRedirectTransfer->getIdUrlRedirect(), SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE);

        $this->assertEquals(0, $touchQuery->count(), 'New entity should not have active touch entry before activation.');
        $this->urlFacade->activateUrlRedirect($urlRedirectTransfer);
        $this->assertEquals(1, $touchQuery->count(), 'New entity should have active touch entry after activation.');
    }

    /**
     * @return void
     */
    public function testDeactivateUrlRedirectShouldCreateDeletedTouchEntry()
    {
        $localeTransfer = $this->localeFacade->createLocale('ab_CD');
        $urlRedirectEntity = new SpyUrlRedirect();
        $urlRedirectEntity
            ->setToUrl('/some/url/to/redirect/to')
            ->setStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/some/url/like/string')
            ->setFkLocale($localeTransfer->getIdLocale())
            ->setFkResourceRedirect($urlRedirectEntity->getIdUrlRedirect())
            ->save();

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer->setIdUrlRedirect($urlRedirectEntity->getIdUrlRedirect());

        $touchQuery = $this->touchQueryContainer->queryUpdateTouchEntry(UrlConfig::RESOURCE_TYPE_REDIRECT, $urlRedirectTransfer->getIdUrlRedirect(), SpyTouchTableMap::COL_ITEM_EVENT_DELETED);

        $this->assertEquals(0, $touchQuery->count(), 'New entity should not have deleted touch entry before activation.');
        $this->urlFacade->deactivateUrlRedirect($urlRedirectTransfer);
        $this->assertEquals(1, $touchQuery->count(), 'New entity should have deleted touch entry after activation.');
    }

}
