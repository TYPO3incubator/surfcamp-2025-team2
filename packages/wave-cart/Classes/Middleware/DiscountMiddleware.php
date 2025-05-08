<?php

namespace TYPO3Incubator\WaveCart\Middleware;

use Doctrine\DBAL\Exception;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3Incubator\WaveCart\Domain\Repository\DiscountCodeRepository;
use TYPO3Incubator\WaveCart\Enum\DiscountTypeEnum;

readonly class DiscountMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected ResponseFactoryInterface $responseFactory,
        protected DiscountCodeRepository $discountCodeRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (str_starts_with($request->getRequestTarget(), '/api/discount')) {
            $response = $this->responseFactory->createResponse()
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withHeader('Cache-Control', 'max-age=0,no-cache,no-store,must-revalidate')
                ->withHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT')
                ->withHeader('X-CMS-Generated', '1');

            $params = $request->getQueryParams();
            if (!isset($params['code'])) {
                $response = $response->withStatus(500);
                $response->getBody()->write(json_encode(['error' => 'No code provided']));
                return $response;
            }

            $discountCode = $this->discountCodeRepository->findByCode($params['code']);
            if (!$discountCode) {
                $response = $response->withStatus(500);
                $response->getBody()->write(json_encode(['error' => 'Invalid or expired code provided']));
                return $response;
            }

            if ($discountCode['has_redeem_maximum'] === 1 && $discountCode['current_redeem_amount'] === 0) {
                $response = $response->withStatus(500);
                $response->getBody()->write(json_encode(['error' => 'Discount code is overused']));
                return $response;
            }

            $response->getBody()->write(json_encode([
                'code' => $discountCode['code'],
                'type' => DiscountTypeEnum::from($discountCode['type'])->name,
                'discount' => floatval($discountCode['discount'])
            ]));

            return $response;
        }

        return $handler->handle($request);
    }
}
