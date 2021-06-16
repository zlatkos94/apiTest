<?php


namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    private $cityRepository;
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }


    /**
     * @Route("/city", name="add_city", methods={"POST"})
     */
    public function add(Request $request)
    {
        $data = json_decode($request->getContent(),true);

        $name = $data['name'];

        if(empty($name)){
            throw new NotFoundHttpException('missing mandatory parameters');
        }

        $this->cityRepository->saveCity($name);

        return new JsonResponse(['status'=>'City created'],Response::HTTP_CREATED);

    }
}