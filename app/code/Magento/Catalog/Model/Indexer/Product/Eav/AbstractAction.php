<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Catalog\Model\Indexer\Product\Eav;

/**
 * Abstract action reindex class
 */
abstract class AbstractAction
{
    /**
     * EAV Indexers by type
     *
     * @var array
     */
    protected $_types;

    /**
     * @var \Magento\Catalog\Model\Resource\Product\Indexer\Eav\SourceFactory
     */
    protected $_eavSourceFactory;

    /**
     * @var \Magento\Catalog\Model\Resource\Product\Indexer\Eav\DecimalFactory
     */
    protected $_eavDecimalFactory;

    /**
     * @param \Magento\Catalog\Model\Resource\Product\Indexer\Eav\DecimalFactory $eavDecimalFactory
     * @param \Magento\Catalog\Model\Resource\Product\Indexer\Eav\SourceFactory $eavSourceFactory
     */
    public function __construct(
        \Magento\Catalog\Model\Resource\Product\Indexer\Eav\DecimalFactory $eavDecimalFactory,
        \Magento\Catalog\Model\Resource\Product\Indexer\Eav\SourceFactory $eavSourceFactory
    ) {
        $this->_eavDecimalFactory = $eavDecimalFactory;
        $this->_eavSourceFactory = $eavSourceFactory;
    }

    /**
     * Execute action for given ids
     *
     * @param array|int $ids
     * @return void
     */
    abstract public function execute($ids);

    /**
     * Retrieve array of EAV type indexers
     *
     * @return \Magento\Catalog\Model\Resource\Product\Indexer\Eav\AbstractEav[]
     */
    public function getIndexers()
    {
        if (is_null($this->_types)) {
            $this->_types = [
                'source' => $this->_eavSourceFactory->create(),
                'decimal' => $this->_eavDecimalFactory->create(),
            ];
        }

        return $this->_types;
    }

    /**
     * Retrieve indexer instance by type
     *
     * @param string $type
     * @return \Magento\Catalog\Model\Resource\Product\Indexer\Eav\AbstractEav
     * @throws \Magento\Framework\Model\Exception
     */
    public function getIndexer($type)
    {
        $indexers = $this->getIndexers();
        if (!isset($indexers[$type])) {
            throw new \Magento\Framework\Model\Exception(__('Unknown EAV indexer type "%1".', $type));
        }
        return $indexers[$type];
    }

    /**
     * Reindex entities
     *
     * @param null|array|int $ids
     * @return void
     */
    public function reindex($ids = null)
    {
        foreach ($this->getIndexers() as $indexer) {
            if (is_null($ids)) {
                $indexer->reindexAll();
            } else {
                $indexer->reindexEntities($ids);
            }
        }
    }
}
