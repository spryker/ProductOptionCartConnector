<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface;

class ResponseRelationship implements ResponseRelationshipInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface
     */
    protected $resourceRelationshipProviderLoader;

    /**
     * @var array
     */
    protected $alreadyLoadedResources = [];

    /**
     * @var array
     */
    protected $includedResources = [];

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface $resourceRelationshipProviderLoader
     */
    public function __construct(ResourceRelationshipLoaderInterface $resourceRelationshipProviderLoader)
    {
        $this->resourceRelationshipProviderLoader = $resourceRelationshipProviderLoader;
    }

    /**
     * @param string $resourceName
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function loadRelationships(
        string $resourceName,
        array $resources,
        RestRequestInterface $restRequest
    ): void {

        if (!$this->canLoadResource($resourceName, $restRequest)) {
            return;
        }

        $resources = $this->applyRelationshipPlugins($resourceName, $resources, $restRequest);

        $this->alreadyLoadedResources[$resourceName] = true;

        foreach ($resources as $resource) {
            foreach ($resource->getRelationships() as $type => $resourceRelationships) {
                if (!$this->hasRelationship($type, $restRequest)) {
                    continue;
                }
                $this->loadRelationships($type, $resourceRelationships, $restRequest);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    public function processIncluded(array $resources, RestRequestInterface $restRequest): array
    {
        $included = [];
        foreach ($resources as $resource) {
            $this->processRelationships($resource->getRelationships(), $restRequest, $included);
        }

        return $included;
    }

    /**
     * @param array $resourceRelationships
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array $included
     *
     * @return void
     */
    protected function processRelationships(
        array $resourceRelationships,
        RestRequestInterface $restRequest,
        array &$included
    ): void {

        /** @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources */
        foreach ($resourceRelationships as $resourceType => $resources) {
            if (!$this->hasRelationship($resourceType, $restRequest)) {
                continue;
            }
            foreach ($resources as $resource) {
                if ($resource->getRelationships()) {
                    $this->processRelationships($resource->getRelationships(), $restRequest, $included);
                }

                $resourceIdentifier = $resourceType . ':' . $resource->getId();
                if (isset($this->includedResources[$resourceIdentifier])) {
                    continue;
                }

                $this->includedResources[$resourceIdentifier] = true;
                $included[] = $resource;
            }
        }
    }

    /**
     * @param string $resourceType
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    public function hasRelationship(string $resourceType, RestRequestInterface $restRequest): bool
    {
        if ($restRequest->getResource()->getType() === $resourceType) {
            return true;
        }

        $includes = $restRequest->getInclude();
        return ($includes && isset($includes[$resourceType])) || (!$includes && !$restRequest->getExcludeRelationship());
    }

    /**
     * @param string $resourceName
     * @param array $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function applyRelationshipPlugins(string $resourceName, array $resources, RestRequestInterface $restRequest): array
    {
        $relationshipPlugins = $this->resourceRelationshipProviderLoader->load($resourceName);
        foreach ($relationshipPlugins as $relationshipPlugin) {
            if (!$this->hasRelationship($relationshipPlugin->getRelationshipResourceType(), $restRequest)) {
                continue;
            }

            $relationshipPlugin->addResourceRelationships($resources, $restRequest);
        }

        return $resources;
    }

    /**
     * @param string $resourceName
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function canLoadResource(string $resourceName, RestRequestInterface $restRequest): bool
    {
        return !isset($this->alreadyLoadedResources[$resourceName]) && $restRequest->getExcludeRelationship() === false;
    }
}
