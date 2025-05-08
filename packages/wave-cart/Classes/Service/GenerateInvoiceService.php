<?php

namespace TYPO3Incubator\WaveCart\Service;

use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3Incubator\WaveCart\Domain\Model\Order;
use TYPO3Incubator\WaveCart\Domain\Repository\OrderRepository;

class GenerateInvoiceService
{
    public function __construct(
        private ViewFactoryInterface $viewFactory,
        protected OrderRepository $orderRepository,
    ) {
    }

    /**
     * @throws MpdfException
     * @throws InsufficientFolderAccessPermissionsException
     */
    public function generateInvoicePdfAndAddToOrder(Order $order, Request $request): Order
    {
        $settings = $request->getAttribute('site')->getSettings();
        $companyName = $settings->get('waveCart.invoiceCompanyName');
        $companyStreet = $settings->get('waveCart.invoiceCompanyStreet');
        $companyCity = $settings->get('waveCart.invoiceCompanyCity');
        $companyPhone = $settings->get('waveCart.invoiceCompanyPhone');
        $iban = $settings->get('waveCart.invoiceIban');
        $bic = $settings->get('waveCart.invoiceBic');
        $bankName = $settings->get('waveCart.invoiceBankName');
        $creationDate = date('d.m.Y', $this->orderRepository->getCreationDateByOrderId($order->getUid()));
        $fileIdentifier = 'invoice-' . $order->getUid() . '.pdf';
        $tax = $this->calculateTax($order);

        $mpdf = new Mpdf([
            'margin_left' => 20,
            'margin_right' => 15,
            'margin_top' => 48,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);

        $mpdf->SetProtection(['print']);
        $mpdf->SetTitle($companyName . " - Invoice");
        $mpdf->SetAuthor($companyName);
        $mpdf->SetDisplayMode('fullpage');

        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: ['EXT:wave_cart/Resources/Private/Templates/Pdf'],
            request: $request,
        );
        $view = $this->viewFactory->create($viewFactoryData);
        $view->assign('order', $order);
        $view->assign('companyName', $companyName);
        $view->assign('companyStreet', $companyStreet);
        $view->assign('companyCity', $companyCity);
        $view->assign('companyPhone', $companyPhone);
        $view->assign('tax', $tax);
        $view->assign('iban', $iban);
        $view->assign('bic', $bic);
        $view->assign('bankName', $bankName);
        $view->assign('creationDate', $creationDate);
        $mpdf->WriteHTML($view->render('Invoice.html'));
        $mpdf->OutputFile('/tmp/' . $fileIdentifier);

        $storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
        $storage = $storageRepository->getDefaultStorage();
        $this->createFolder('invoice', $storage);
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

    protected function createFolder(string $folderName, $storage): void
    {
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        $folderObject = $resourceFactory->createFolderObject($storage, '', 'invoice');
        if (!$folderObject->hasFolder('invoice')) {
            $folderObject->createFolder('invoice');
        }
    }

    protected function calculateTax(Order $order): array
    {
        $tax = 0;
        $totalPrice = 0;
        foreach ($order->getOrderItems() as $orderItem) {
            $totalPrice += $orderItem->getPrice();
            $tax += ($orderItem->getPrice() * 0.07);
        }

        return [
            'tax' => $tax,
            'subtotal' => $totalPrice - $tax,
        ];
    }
}
