<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Environment;
use App\Manager\EnvironmentManager;
use App\Model\Request\Environment\EnvironmentCreateRequest;
use App\Model\Response\Environment\EnvironmentCollectionResponse;
use App\Model\Response\Environment\EnvironmentReadResponse;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class EnvironmentController extends AbstractApiController
{
    public function __construct(
        private readonly EnvironmentManager $environmentManager,
    ) {
    }

    #[Route('/environment', methods: [Request::METHOD_POST])]
    public function create(#[MapRequestPayload] EnvironmentCreateRequest $request): JsonResponse
    {
        $environment = $this->environmentManager->create(
            $request->name,
            $request->phpVersion,
            $request->appPublicId,
        );

        return $this->response(
            EnvironmentReadResponse::fromEntity($environment),
            Response::HTTP_CREATED
        );
    }

    #[Route('/environment/{id}', methods: [Request::METHOD_GET])]
    public function read(int $id): JsonResponse
    {
        $environment = $this->environmentManager->findById($id);

        if (!$environment instanceof Environment) {
            throw new NotFoundHttpException('Environment not found.');
        }

        return $this->response(
            EnvironmentReadResponse::fromEntity($environment),
            Response::HTTP_OK
        );
    }

    #[Route('/environment/{id}', methods: [Request::METHOD_DELETE])]
    public function delete(int $id): JsonResponse
    {
        $environment = $this->environmentManager->findById($id);

        if (!$environment instanceof Environment) {
            throw new NotFoundHttpException('Environment not found.');
        }

        if (!$this->environmentManager->delete($environment)) {
            throw new RuntimeException('Unable to delete environment.');
        }

        return $this->response(
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route('/environments', methods: [Request::METHOD_GET])]
    public function collection(): Response
    {
        $environments = $this->environmentManager->findAll();

        if (empty($environments)) {
            throw new NotFoundHttpException('Environments not found.');
        }

        return $this->response(
            EnvironmentCollectionResponse::fromEntities($environments),
            Response::HTTP_OK
        );
    }
}
