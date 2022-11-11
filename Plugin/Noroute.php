<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 * 
 * @package  Czargroup_CustomUrlRedirectPlugin
 * @author   
 */
namespace Czargroup\CustomUrlRedirectPlugin\Plugin;

use Magento\Framework\Exception\NoSuchEntityException;

class Noroute
{
	/**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
	
    /**
     * @var UrlInterface _urlManager
     */
    protected $_urlManager;

    /**
     * @var ResponseFactory _responseFactory
     */
    protected $_responseFactory;

    /**
     * @param Magento\Framework\UrlInterface $urlManager
     * @param Magento\Framework\App\ResponseFactory $responseFactory
	 * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlManager,
        \Magento\Framework\App\ResponseFactory $responseFactory,
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    )
    {
        $this->_urlManager = $urlManager;
        $this->_responseFactory = $responseFactory;
		$this->productRepository = $productRepository;
    }

    /**
     * Render search page
     * 
     * @return Magento\Framework\App\ResponseFactory
     */
    public function beforeExecute()
    {
        $currentUrl = $this->_urlManager->getCurrentUrl();
        $baseUrl = $this->_urlManager->getBaseUrl();
        $scr = str_replace($baseUrl,'',$currentUrl);
        $a = explode('.',$scr);
        try{
            $productUrl = $this->productRepository->get($a[0])->getProductUrl();
            if($productUrl) {
                $this->_responseFactory->create()->setRedirect($productUrl)->sendResponse();
                exit;
            }
        } catch (NoSuchEntityException $noSuchEntityException) {
            $productUrl = null;
        }
    }
}