<?php

namespace App\Controller\Api\User;

use App\Repository\UserRepository;
use App\Service\ConstraintsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;



class ResetPassword extends AbstractController
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
     * ResetPassword constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $encoder
     * @param UserRepository $userRepo

     */
    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $encoder,
        UserRepository $userRepo
        
    )
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->userRepo = $userRepo;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function __invoke($data, $tokenPasswordReset)
    {
        $user = $this->userRepo->getUserInfoByToken($tokenPasswordReset);
       if(empty($user)){
        return [
                false,
                "Token not found."
            ];
        } else {

            $newPass = $data->getPassword();
            $user[0]->setPassword($this->encoder->hashPassword($user[0], $newPass ));
            $user[0]->settokenPasswordReset(NULL);
            $user[0]->setPasswordRequestedAt(NULL);
            $this->em->flush();
           
                return [
                    true,
                    "Password has been changed"
                ];
              }    
        }
    }
   
        
    



   

  
