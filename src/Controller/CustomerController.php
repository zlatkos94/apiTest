<?php


namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class CustomerController extends AbstractController
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("/customer", name="add_customer", methods={"POST"})
     */
    public function add(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $phoneNumber = $data['phoneNumber'];


        if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber)) {
            throw new NotFoundHttpException('Missing mandatory parameters!');
        }

        $this->customerRepository->saveCustomer($firstName, $lastName, $email, $phoneNumber);

        return new JsonResponse(['status' => 'Customer created!'], Response::HTTP_CREATED);

    }

    /**
     * @Route("/customer", name="get_all_customers", methods={"GET"})
     */
    public function getAllCustomers(): JsonResponse
    {
        $customers = $this->customerRepository->findAll();
        $data = [];

        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->getId(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phoneNumber' => $customer->getPhoneNumber(),
            ];
        }

        return new JsonResponse(['customers' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/customer/{id}", name="get_one_customer", methods={"GET"})
     */
    public function getOneCustomer($id): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $customer->getId(),
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'email' => $customer->getEmail(),
            'phoneNumber' => $customer->getPhoneNumber(),
        ];

        return new JsonResponse(['customer' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/customer/{id}", name="update_customer", methods={"PUT"})
     */
    public function updateCustomer(Request $request, int $id): JsonResponse
    {

        $customer = $this->customerRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        $this->customerRepository->updateCustomer($customer, $data);

        return new JsonResponse(['status' => 'customer updated!']);

    }


}