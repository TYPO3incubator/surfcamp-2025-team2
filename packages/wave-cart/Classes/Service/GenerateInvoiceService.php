<?php

namespace TYPO3Incubator\WaveCart\Service;

use TYPO3\CMS\Core\Core\Environment;
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
        $path = Environment::getPublicPath() . '/fileadmin/invoice/';
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
        $mpdf->OutputFile($path . 'invoice-' . $order->getUid() . '.pdf');

        $order->setInvoice($path . 'invoice-' . $order->getUid() . '.pdf');

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
