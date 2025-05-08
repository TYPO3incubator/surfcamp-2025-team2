<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Controller\Backend;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Incubator\WaveCart\Factory\TabFactory;
use TYPO3Incubator\WaveCart\Tab\TabcollectionBuilder;

#[AsController]
final  class OrderBackendController
{
    private ModuleTemplate $moduleTemplate;

    public function __construct(
        private ModuleTemplateFactory $moduleTemplateFactory,
        private TabcollectionBuilder $tabcollectionBuilder,
        private PageRenderer $pageRenderer,
    ) {
    }

    public function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->moduleTemplate = $this->moduleTemplateFactory->create($request);

        $tabData = [
            [
                'title' => 'Order',
                'content' => 'Order content',
                'active' => true,
                'position' => 10,
            ],
        ];

        $this->moduleTemplate->assign(
            'tabs',
            $this->tabcollectionBuilder->build($tabData)
        );

//        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
//        $pageRenderer->addJsFile('EXT:wave_cart/Resources/Public/JavaScript/Tabs.js');

        return $this->moduleTemplate->renderResponse('OrderModule/index');

    }

    private function renderTabs(ModuleTemplate $template): ModuleTemplate
    {
        $tabs = 'text';
        return $this->moduleTemplate->assign('tabs', $tabs);
    }
}
