<?php

namespace App\Controller\Api\User;

use App\Repository\UserRepository;
use App\Service\ConstraintsService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class Signup extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var UserPasswordHasherInterface
     */
    protected $encoder;

    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * @var ConstraintsService
     */
    protected $constraintsService;

    /**
     * Signup constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $encoder
     * @param UserRepository $userRepo
     * @param ConstraintsService $constraintsService
     */
    public function __construct(
                                EntityManagerInterface $em,
                                UserPasswordHasherInterface $encoder,
                                UserRepository $userRepo,
                                ConstraintsService $constraintsService
                               )
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->userRepo = $userRepo;
        $this->constraintsService = $constraintsService;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function __invoke($data)
    {
            //dd($data);
        $constraints = $this->constraintsService->userValidator($data);
        if (!$constraints[0]) {
            return [
                'description' => $constraints[1],
                'message' => 'error'
            ];
        }

        if ($data->getIsEnterprise() == "true") {
            $data->setRoles(['ROLE_ENTERPRISE']);
        }
        
        $data->setCreatedAt(new DateTimeImmutable());
        $data->setPassword($this->encoder->hashPassword($data, $data->getPassword()));

        $this->em->persist($data);
        $this->em->flush();

        return [
            'user' => $data,
            'message' => 'success',
        ];
    }

  
}