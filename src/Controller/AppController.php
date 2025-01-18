<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\App;
use App\Manager\AppManager;
use App\Model\Request\App\AppCreateRequest;
use App\Model\Response\App\AppCollectionResponse;
use App\Model\Response\App\AppReadResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
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

    #[OA\Tag(name: 'app')]
    #[OA\RequestBody(
        content: new Model(type: AppCreateRequest::class)
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Successfully created new app resource.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: AppReadResponse::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Validation failed while creating new app resource.',
    )]
    #[Route('/app', methods: [Request::METHOD_POST])]
    public function create(#[MapRequestPayload] AppCreateRequest $request): JsonResponse
    {
        $app = $this->appManager->create($request->name);

        return $this->response(
            AppReadResponse::fromEntity($app),
            Response::HTTP_CREATED
        );
    }

    #[OA\Tag(name: 'app')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successfully retrieved requested app resource.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: AppReadResponse::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Unable to find requested app resource.',
    )]
    #[Route('/app/{publicId}', methods: [Request::METHOD_GET])]
    public function read(string $publicId): JsonResponse
    {
        $app = $this->appManager->findByPublicId($publicId);

        if (!$app instanceof App) {
            throw new NotFoundHttpException('App not found.');
        }

        return $this->response(
            AppReadResponse::fromEntity($app),
            Response::HTTP_OK
        );
    }

    #[OA\Tag(name: 'app')]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Successfully deleted requested app resource.'
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Unable to find requested app resource for deletion.',
    )]
    #[Route('/app/{publicId}', methods: [Request::METHOD_DELETE])]
    public function delete(string $publicId): JsonResponse
    {
        $app = $this->appManager->findByPublicId($publicId);

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

    #[OA\Tag(name: 'app')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successfully retrieved collection of requested app resources.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: AppCollectionResponse::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Unable to find any requested app resources.',
    )]
    #[Route('/app', methods: [Request::METHOD_GET])]
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
