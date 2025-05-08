<?php

declare(strict_types=1);

namespace TYPO3Incubator\WaveCart\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Page\PageRenderer;

#[AsController]
final readonly class ListOrderController
{
    public function __construct(
        private ModuleTemplateFactory $moduleTemplateFactory,
        private PageRenderer $pageRenderer,

    ) {
    }

    public function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->pageRenderer->addCssFile('EXT:wave_cart/Resources/Public/Css/module.css');;
        $template = $this->moduleTemplateFactory->create($request);

        $template = $this->renderTabs($template);
        return $template->renderResponse('OrderModule/index');
    }

    private function renderTabs(ModuleTemplate $template)
    {
        $tabs = [
            [
                'title' => '',
                'action' => '',
                'icon' => '<svg viewBox="0 0 24 24"><path d="M14,2A8,8 0 0,0 6,10A8,8 0 0,0 14,18A8,8 0 0,0 22,10H20C20,13.32 17.32,16 14,16A6,6 0 0,1 8,10A6,6 0 0,1 14,4C14.43,4 14.86,4.05 15.27,4.14L16.88,2.54C15.96,2.18 15,2 14,2Z"/></svg>'
            ],
            [
                'title' => '',
                'action' => '',
                'icon' => '<svg viewBox="0 0 24 24"><path d="M2,10.96C1.5,10.68 1.35,10.07 1.63,9.59L3.13,7C3.24,6.8 3.41,6.66 3.6,6.58L11.43,2.18C11.59,2.06 11.79,2 12,2C12.21,2 12.41,2.06 12.57,2.18L20.47,6.62C20.66,6.72 20.82,6.88 20.91,7.08L22.36,9.6C22.64,10.08 22.47,10.69 22,10.96Z"/></svg>'
            ],
            [
                'title' => '',
                'action' => '',
                'icon' => '<svg viewBox="0 0 24 24"><path d="M3,4A2,2 0 0,0 1,6V17H3A3,3 0 0,0 6,20A3,3 0 0,0 9,17H15A3,3 0 0,0 18,20A3,3 0 0,0 21,17H23V12L20,8H17V4Z"/></svg>'
            ],
        ];

        $tabsHtml = '';
        $listItemsHtml = '';
        $sectionsHtml = '';

        foreach ($tabs as $index => $tab) {
            $checked = ($index === 0) ? 'checked' : ''; // Eerste tab standaard geselecteerd
            $id = 'tab-' . htmlspecialchars($tab['action']); // Unieke ID gebaseerd op 'action'

            // Input radio buttons
            $tabsHtml .= '<input type="radio" id="' . $id . '" name="tab-control" ' . $checked . '>';

            // Navigatie (LI met labels en icons)
            $listItemsHtml .= '<li title="' . htmlspecialchars($tab['title']) . '">
        <label for="' . $id . '" role="button">
            ' . $tab['icon'] . '<br><span>' . htmlspecialchars($tab['title']) . '</span>
        </label>
    </li>';

            // Content secties
            $sectionsHtml .= '<section><h2>' . htmlspecialchars($tab['title']) . '</h2>
        <p>Inhoud voor ' . htmlspecialchars($tab['title']) . '...</p>
    </section>';
        }

        $template->assign('tabs', $tabsHtml);
        $template->assign('listItems', $listItemsHtml);
        $template->assign('sections', $sectionsHtml);
        return $template;
    }
}
