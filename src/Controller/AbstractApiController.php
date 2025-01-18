<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Response\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

abstract class AbstractApiController extends AbstractController
{
    protected function response(
        ?ResponseInterface $data = null,
        int $status = 200,
        array $groups = [],
        array $headers = [],
    ): JsonResponse {
        return new JsonResponse(
            $this->container->get('serializer')->serialize(
                $data,
                JsonEncoder::FORMAT,
                [AbstractNormalizer::GROUPS => $groups, AbstractObjectNormalizer::SKIP_NULL_VALUES => true]
            ),
            $status,
            $headers,
            !empty($data),
        );
    }
}
