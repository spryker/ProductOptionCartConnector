<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @deprecated Will be removed with next major release
 */
class ServiceMethodTagBuilder extends AbstractSingleFileMethodTagBuilder
{

    const METHOD_STRING_PATTERN = '@method {{className}} service()';
    const APPLICATION_SERVICE = 'Service';
    const FILE_NAME_SUFFIX = 'Service.php';
    const PATH_PATTERN = '/';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            self::OPTION_KEY_APPLICATION => self::APPLICATION_SERVICE,
            self::OPTION_KEY_METHOD_STRING_PATTERN => self::METHOD_STRING_PATTERN,
            self::OPTION_KEY_PATH_PATTERN => self::PATH_PATTERN,
            self::OPTION_KEY_FILE_NAME_SUFFIX => self::FILE_NAME_SUFFIX,
        ]);
    }

    /**
     * @param string $bundle
     * @param array $methodTags
     *
     * @return array
     */
    public function buildMethodTags($bundle, array $methodTags = [])
    {
        $methodTag = $this->getMethodTag($bundle);
        if ($methodTag) {
            $methodTags[] = $methodTag;
        }

        return $methodTags;
    }

}
