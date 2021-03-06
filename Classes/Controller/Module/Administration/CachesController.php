<?php
namespace Flownative\Neos\CacheManagement\Controller\Module\Administration;

/*
 * This file is part of the Flownative.Neos.CacheManagement package.
 *
 * (c) Robert Lemke / Flownative - www.flownative.com
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cache\CacheManager;
use TYPO3\Flow\Error\Message;
use TYPO3\Neos\Controller\Module\AbstractModuleController;

/**
 * A Cache Management module controller
 */
class CachesController extends AbstractModuleController
{

    /**
     * @Flow\Inject
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @return string
     */
    public function indexAction()
    {
        $contentCache = $this->cacheManager->getCache('TYPO3_TypoScript_Content');
        $caches = [
            'TYPO3_TypoScript_Content' => [
                'identifier' => 'TYPO3_TypoScript_Content',
                'label' => 'Neos Content',
                'backendType' => get_class($contentCache->getBackend())
            ],
            'Flow_Mvc_Routing_Route' => [
                'identifier' => 'Flow_Mvc_Routing_Route',
                'label' => 'Routes (Matching)',
                'backendType' => get_class($contentCache->getBackend())
            ],
            'Flow_Mvc_Routing_Resolve' => [
                'identifier' => 'Flow_Mvc_Routing_Resolve',
                'label' => 'Routes (Resolving)',
                'backendType' => get_class($contentCache->getBackend())
            ]
        ];
        $this->view->assign('caches', $caches);
    }

    /**
     * Create a new user
     *
     * @param string $cacheIdentifier
     * @Flow\Validate(argumentName="cacheIdentifier", type="\TYPO3\Flow\Validation\Validator\NotEmptyValidator")
     * @return void
     */
    public function flushAction($cacheIdentifier)
    {
        $this->cacheManager->getCache($cacheIdentifier)->flush();
        $this->addFlashMessage('Successfully flushed the cache "%s".', 'User created', Message::SEVERITY_OK, [ $cacheIdentifier ], 1448033946);
        $this->redirect('index');
    }
}
