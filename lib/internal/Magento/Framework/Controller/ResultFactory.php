<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright  Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Framework\Controller;

use Magento\Framework\ObjectManager;

/**
 * Result Factory
 */
class ResultFactory
{
    /**#@+
     * Allowed result types
     */
    const TYPE_JSON     = 'json';
    const TYPE_RAW      = 'raw';
    const TYPE_REDIRECT = 'redirect';
    const TYPE_FORWARD  = 'forward';
    const TYPE_LAYOUT   = 'layout';
    const TYPE_PAGE     = 'page';
    /**#@-*/

    /**
     * Map of types which are references to classes
     *
     * @var array
     */
    protected $typeMap = [
        self::TYPE_JSON     => 'Magento\Framework\Controller\Result\JSON',
        self::TYPE_RAW      => 'Magento\Framework\Controller\Result\Raw',
        self::TYPE_REDIRECT => 'Magento\Framework\Controller\Result\Redirect',
        self::TYPE_FORWARD  => 'Magento\Framework\Controller\Result\Forward',
        self::TYPE_LAYOUT   => 'Magento\Framework\View\Result\Layout',
        self::TYPE_PAGE     => 'Magento\Framework\View\Result\Page',
    ];

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * Constructor
     *
     * @param ObjectManager $objectManager
     * @param array $typeMap
     */
    public function __construct(
        ObjectManager $objectManager,
        array $typeMap = []
    ) {
        $this->objectManager = $objectManager;
        $this->mergeTypes($typeMap);
    }

    /**
     * Add or override result types
     *
     * @param array $typeMap
     * @return void
     */
    protected function mergeTypes(array $typeMap)
    {
        foreach ($typeMap as $typeInfo) {
            if (isset($typeInfo['type']) && isset($typeInfo['class'])) {
                $this->typeMap[$typeInfo['type']] = $typeInfo['class'];
            }
        }
    }

    /**
     * Create new page regarding its type
     *
     * @param string $type
     * @param array $arguments
     * @throws \InvalidArgumentException
     * @return ResultInterface
     */
    public function create($type, array $arguments = [])
    {
        if (empty($this->typeMap[$type])) {
            throw new \InvalidArgumentException('"' . $type . ': isn\'t allowed');
        }

        $resultInstance = $this->objectManager->create($this->typeMap[$type], $arguments);
        if (!$resultInstance instanceof ResultInterface) {
            throw new \InvalidArgumentException(get_class($resultInstance) . ' isn\'t instance of ResultInterface');
        }

        /**
         * TODO: Temporary solution, must be removed after full refactoring to the new result rendering system
         *
         * Used for knowledge how result page was created, page was created through result factory or it's default page
         * in App\View created in constructor
         */
        if ($resultInstance instanceof \Magento\Framework\View\Result\Layout) {
            // Initialization has to be in constructor of ResultPage
            $resultInstance->addDefaultHandle();
        }

        return $resultInstance;
    }
}
