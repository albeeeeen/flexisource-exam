<?php

namespace App\Repositories;

use DateTime;
use App\Entities\Customer;
use Doctrine\ORM\EntityManagerInterface;

/** 
 * Class CustomerRepository
 * @Author: Alvin Dela Cruz <delacruzalvinstaana@gmail.com 
 * @Date: 2024-01-17
 */
class CustomerRepository
{
    /**
     * customer entity class name
     * @var strings
     */
    private $entityName = Customer::class;

    /**
     * Entity manager interface
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Customer repository constructor
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * create or update customer data
     *
     * @param array $customers
     * @return void
     */
    public function createOrUpdateCustomer(array $customers): void
    {
        foreach ($customers as $customer) {
            $customerEntity = $this->entityManager->getRepository($this->entityName)->findOneBy(['email' => $customer['email']]);

            if (!$customerEntity) {
                $customerEntity = new Customer();
                $customerEntity->setEmail($customer['email']);
                $customerEntity->setCreatedAt(new DateTime());
            } else {
                $customerEntity->setUpdatedAt(new DateTime());
            }

            $customerEntity->setFirstName($customer['first_name']);
            $customerEntity->setLastName($customer['last_name']);
            $customerEntity->setGender($customer['gender']);
            $customerEntity->setCountry($customer['country']);
            $customerEntity->setCity($customer['city']);
            $customerEntity->setUsername($customer['username']);
            $customerEntity->setPassword(md5($customer['password']));
            $customerEntity->setPhone($customer['phone']);

            $this->entityManager->persist($customerEntity);
        }

        $this->entityManager->flush();
    }
}