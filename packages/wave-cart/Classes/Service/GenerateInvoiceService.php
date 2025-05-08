<?php

namespace TYPO3Incubator\WaveCart\Service;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3Incubator\WaveCart\Domain\Model\Order;

class GenerateInvoiceService
{
    public function __construct(
        private ViewFactoryInterface $viewFactory,
    ) {}

    public function generateInvoicePdfAndAddToOrder(Order $order, Request $request): Order
    {
        $settings = $request->getAttribute('site')->getSettings();
        $company = $settings->get('waveCart.invoice.company');
        $fileIdentifier = 'invoice-' . $order->getUid() . '.pdf';
        $tax = $this->calculateTax($order);

        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);

        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle($company . " - Invoice");
        $mpdf->SetAuthor($company);
        $mpdf->SetDisplayMode('fullpage');

        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: ['EXT:wave_cart/Resources/Private/Templates/Pdf'],
            request: $request,
        );
        $view = $this->viewFactory->create($viewFactoryData);
        $view->assign('order', $order);
        $view->assign('tax', $tax);
        $mpdf->WriteHTML($view->render('Invoice.html'));
        $mpdf->OutputFile('/tmp/' . $fileIdentifier);

        $storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
        $storage = $storageRepository->getDefaultStorage();
        /** @var File $newFile */
        $newFile = $storage->addFile(
            '/tmp/' . $fileIdentifier,
            $storage->getFolder('invoice'),
            $fileIdentifier,
        );

        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        $fileReference = $resourceFactory->createFileReferenceObject(
            [
                'uid_local' => $newFile->getUid(),
                'uid_foreign' => StringUtility::getUniqueId('NEW_'),
                'uid' => $order,
                'crop' => null,
            ]
        );

        $extbaseFileReference = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $extbaseFileReference->setOriginalResource($fileReference);

        $order->setInvoice($extbaseFileReference);

        return $order;
    }

    protected function calculateTax(Order $order): array
    {
        $tax = 0;
        foreach ($order->getOrderItems() as $orderItem) {
            $tax += ($orderItem->getPrice() * 0.07);
        }

        return [
            'tax' => $tax,
            'subtotal' => $order->getTotalPrice() - $tax,
        ];
    }
}
