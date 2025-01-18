<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\App;
use App\Manager\AppManager;
use App\Model\Request\App\AppCreateRequest;
use App\Model\Response\App\AppCollectionResponse;
use App\Model\Response\App\AppReadResponse;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class AppController extends AbstractApiController
{
    public function __construct(
        private readonly AppManager $appManager,
    ) {
    }

    #[Route('/app', methods: [Request::METHOD_POST])]
    public function create(#[MapRequestPayload] AppCreateRequest $request): JsonResponse
    {
        $app = $this->appManager->create($request->name);

        return $this->response(
            AppReadResponse::fromEntity($app),
            Response::HTTP_CREATED
        );
    }

    #[Route('/app/{id}', methods: [Request::METHOD_GET])]
    public function read(int $id): JsonResponse
    {
        $app = $this->appManager->findById($id);

        if (!$app instanceof App) {
            throw new NotFoundHttpException('App not found.');
        }

        return $this->response(
            AppReadResponse::fromEntity($app),
            Response::HTTP_OK
        );
    }

    #[Route('/app/{id}', methods: [Request::METHOD_DELETE])]
    public function delete(int $id): JsonResponse
    {
        $app = $this->appManager->findById($id);

        if (!$app instanceof App) {
            throw new NotFoundHttpException('App not found.');
        }

        if (!$this->appManager->delete($app)) {
            throw new RuntimeException('Unable to delete app.');
        }

        return $this->response(
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route('/apps', methods: [Request::METHOD_GET])]
    public function collection(): Response
    {
        $apps = $this->appManager->findAll();

        if (empty($apps)) {
            throw new NotFoundHttpException('Apps not found.');
        }

        return $this->response(
            AppCollectionResponse::fromEntities($apps),
            Response::HTTP_OK
        );
    }
}
