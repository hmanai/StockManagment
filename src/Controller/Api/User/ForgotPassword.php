<?php

namespace App\Controller\Api\User;

use DateTimeImmutable;
use App\Repository\UserRepository;
use App\Service\ConstraintsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;



class ForgotPassword extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var UserRepository
     */
    protected $userRepo;
    /**
     * ForgotPassword constructor.
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepo
     * @param tokenGenerator $tokenGenerator
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepo,
        RequestStack $request_stack
        
    )
    {
        $this->em = $em;
        $this->userRepo = $userRepo;
        $this->request = $request_stack->getCurrentRequest();

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function __invoke($data, TokenGeneratorInterface $tokenGenerator, Request $request)

    {
        $email = $data->getEmail();
        $donnee = $this->userRepo->verifyEmail($email);
        if(empty($donnee)){
            return [
                    false,
                    "Adress mail not found."
                ];
        } else {

           $tokenPasswordReset =  ($tokenGenerator->generateToken());
           $PasswordRequestedAt =  $data->setPasswordRequestedAt(new \Datetime());
           $userToken = $data->settokenPasswordReset($tokenPasswordReset);
          //dd($data);
           $donnee = $this->userRepo->getUserInfoByEmail($email);
            $donnee[0]->setPasswordRequestedAt(new \Datetime());
            $donnee[0]-> settokenPasswordReset($tokenPasswordReset);
            $this->em->flush();
            $domain = $this->request->getSchemeAndHttpHost();
            return [
                true,
                "an email has been sent to you",
                "$domain/api/user/resetPassword/$tokenPasswordReset"
            ];
        }
    }
   
        
    }



   

  
