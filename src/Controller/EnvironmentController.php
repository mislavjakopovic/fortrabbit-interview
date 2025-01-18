<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Environment;
use App\Manager\EnvironmentManager;
use App\Model\Request\Environment\EnvironmentCreateRequest;
use App\Model\Response\Environment\EnvironmentCollectionResponse;
use App\Model\Response\Environment\EnvironmentReadResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
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

    #[OA\Tag(name: 'environment')]
    #[OA\RequestBody(
        content: new Model(type: EnvironmentCreateRequest::class)
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Successfully created new environment resource.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: EnvironmentReadResponse::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Validation failed while creating new environment resource.',
    )]
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

    #[OA\Tag(name: 'environment')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successfully retrieved requested environment resource.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: EnvironmentReadResponse::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Unable to find requested environment resource.',
    )]
    #[Route('/environment/{publicId}', methods: [Request::METHOD_GET])]
    public function read(string $publicId): JsonResponse
    {
        $environment = $this->environmentManager->findByPublicId($publicId);

        if (!$environment instanceof Environment) {
            throw new NotFoundHttpException('Environment not found.');
        }

        return $this->response(
            EnvironmentReadResponse::fromEntity($environment),
            Response::HTTP_OK
        );
    }

    #[OA\Tag(name: 'environment')]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Successfully deleted requested environment resource.'
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Unable to find requested environment resource for deletion.',
    )]
    #[Route('/environment/{publicId}', methods: [Request::METHOD_DELETE])]
    public function delete(string $publicId): JsonResponse
    {
        $environment = $this->environmentManager->findByPublicId($publicId);

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

    #[OA\Tag(name: 'environment')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Successfully retrieved collection of requested environment resources.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: EnvironmentCollectionResponse::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Unable to find any requested environment resources.',
    )]
    #[Route('/environment', methods: [Request::METHOD_GET])]
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
