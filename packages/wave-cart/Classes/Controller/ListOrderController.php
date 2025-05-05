<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Controller;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Clipboard\Clipboard;
use TYPO3\CMS\Backend\Controller\RecordListController;
use TYPO3\CMS\Backend\Module\ModuleData;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\DropDown\DropDownItemInterface;
use TYPO3\CMS\Backend\Template\Components\Buttons\DropDown\DropDownToggle;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Incubator\WaveCart\Provider\StoragePageProvider;

class ListOrderController extends RecordListController
{
    public function __construct(
        IconFactory $iconFactory,
        PageRenderer $pageRenderer,
        EventDispatcherInterface $eventDispatcher,
        UriBuilder $uriBuilder,
        ModuleTemplateFactory $moduleTemplateFactory,
        private readonly StoragePageProvider $storagePageProvider,
    ) {
        parent::__construct($iconFactory, $pageRenderer, $eventDispatcher, $uriBuilder, $moduleTemplateFactory);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        /** @var ModuleData $moduleData */
        $moduleData = $request->getAttribute('moduleData');
        $moduleData->set('searchBox', 1);
        $moduleData->set('clipBoard', 0);

        $storagePage = $this->storagePageProvider->getStoragePage();

        $queryParameters['id'] = $storagePage['pid'];
        $queryParameters['table'] = 'tx_wavecart_domain_model_order';
        $request = $request->withQueryParams($queryParameters)->withAttribute('moduleData', $moduleData);

        return $this->mainAction($request);
    }

    protected function getDocHeaderButtons(
        ModuleTemplate $view,
        Clipboard $clipboard,
        ServerRequestInterface $request,
        string $table,
        string $listUrl,
        array $moduleSettings
    ): void {
        $queryParams = $request->getQueryParams();
        $buttonBar = $view->getDocHeaderComponent()->getButtonBar();
        $lang = $this->getLanguageService();
        // If edit permissions are set, see BackendUserAuthentication

        // Paste
        if (($this->pagePermissions->createPagePermissionIsGranted(
                ) || $this->pagePermissions->editContentPermissionIsGranted()) && $this->editLockPermissions()) {
            $elFromTable = $clipboard->elFromTable();
            if (!empty($elFromTable)) {
                $confirmMessage = $clipboard->confirmMsgText('pages', $this->pageInfo, 'into', $elFromTable);
                $pasteButton = $buttonBar->makeLinkButton()
                    ->setHref($clipboard->pasteUrl('', $this->id))
                    ->setTitle(
                        $lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_mod_web_list.xlf:clip_paste')
                    )
                    ->setClasses('t3js-modal-trigger')
                    ->setDataAttributes(
                        [
                            'severity' => 'warning',
                            'bs-content' => $confirmMessage,
                            'title' => $lang->sL(
                                'LLL:EXT:core/Resources/Private/Language/locallang_mod_web_list.xlf:clip_paste'
                            ),
                        ]
                    )
                    ->setIcon($this->iconFactory->getIcon('actions-document-paste-into', IconSize::SMALL))
                    ->setShowLabelText(true);
                $buttonBar->addButton($pasteButton, ButtonBar::BUTTON_POSITION_LEFT, 40);
            }
        }
        // Cache
        if ($this->id !== 0) {
            $clearCacheButton = $buttonBar->makeLinkButton()
                ->setHref('#')
                ->setDataAttributes(['id' => $this->id])
                ->setClasses('t3js-clear-page-cache')
                ->setTitle($lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.clear_cache'))
                ->setIcon($this->iconFactory->getIcon('actions-system-cache-clear', IconSize::SMALL));
            $buttonBar->addButton($clearCacheButton, ButtonBar::BUTTON_POSITION_RIGHT);
        }
        if ($table
            && !($this->modTSconfig['noExportRecordsLinks'] ?? false)
            && $this->getBackendUserAuthentication()->isExportEnabled()
        ) {
            // Export
            if (ExtensionManagementUtility::isLoaded('impexp')) {
                $url = (string)$this->uriBuilder->buildUriFromRoute(
                    'tx_impexp_export',
                    ['tx_impexp' => ['list' => [$table . ':' . $this->id]]]
                );
                $exportButton = $buttonBar->makeLinkButton()
                    ->setHref($url)
                    ->setTitle($lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:rm.export'))
                    ->setIcon($this->iconFactory->getIcon('actions-document-export-t3d', IconSize::SMALL))
                    ->setShowLabelText(true);
                $buttonBar->addButton($exportButton, ButtonBar::BUTTON_POSITION_LEFT, 50);
            }
        }
        // Reload
        $reloadButton = $buttonBar->makeLinkButton()
            ->setHref($listUrl)
            ->setTitle($lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.reload'))
            ->setIcon($this->iconFactory->getIcon('actions-refresh', IconSize::SMALL));
        $buttonBar->addButton($reloadButton, ButtonBar::BUTTON_POSITION_RIGHT);

        // ViewMode
        $viewModeItems = [];
        if ($this->allowSearch) {
            $viewModeItems[] = GeneralUtility::makeInstance(DropDownToggle::class)
                ->setActive((bool)$this->moduleData->get('searchBox'))
                ->setHref(
                    $this->createModuleUri(
                        $request,
                        ['searchBox' => $this->moduleData->get('searchBox') ? 0 : 1, 'searchTerm' => '']
                    )
                )
                ->setLabel(
                    $lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.view.showSearch')
                )
                ->setIcon($this->iconFactory->getIcon('actions-search'));
        }
        if ($this->allowClipboard) {
            $viewModeItems[] = GeneralUtility::makeInstance(DropDownToggle::class)
                ->setActive((bool)$this->moduleData->get('clipBoard'))
                ->setHref(
                    $this->createModuleUri($request, ['clipBoard' => $this->moduleData->get('clipBoard') ? 0 : 1])
                )
                ->setLabel(
                    $lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.view.showClipboard')
                )
                ->setIcon($this->iconFactory->getIcon('actions-clipboard'));
        }
        if (!empty($viewModeItems)) {
            $viewModeButton = $buttonBar->makeDropDownButton()
                ->setLabel($lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.view'))
                ->setShowLabelText(true);
            foreach ($viewModeItems as $viewModeItem) {
                /** @var DropDownItemInterface $viewModeItem */
                $viewModeButton->addItem($viewModeItem);
            }
            $buttonBar->addButton($viewModeButton, ButtonBar::BUTTON_POSITION_RIGHT, 3);
        }

        // Shortcut
        $shortCutButton = $buttonBar->makeShortcutButton()->setRouteIdentifier('web_list');
        $arguments = [
            'id' => $this->id,
        ];
        $potentialArguments = [
            'pointer',
            'table',
            'searchTerm',
            'search_levels',
            'sortField',
            'sortRev',
        ];
        foreach ($potentialArguments as $argument) {
            if (!empty($queryParams[$argument])) {
                $arguments[$argument] = $queryParams[$argument];
            }
        }
        foreach ($moduleSettings as $moduleSettingKey => $moduleSettingValue) {
            $arguments['GET'][$moduleSettingKey] = $moduleSettingValue;
        }
        $shortCutButton->setArguments($arguments);
        $shortCutButton->setDisplayName($this->getShortcutTitle($arguments));
        $buttonBar->addButton($shortCutButton, ButtonBar::BUTTON_POSITION_RIGHT);

        // Back
        if ($this->returnUrl) {
            $backButton = $buttonBar->makeLinkButton()
                ->setHref($this->returnUrl)
                ->setTitle($lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.goBack'))
                ->setShowLabelText(true)
                ->setIcon($this->iconFactory->getIcon('actions-view-go-back', IconSize::SMALL));
            $buttonBar->addButton($backButton, ButtonBar::BUTTON_POSITION_LEFT);
        }
    }
}
