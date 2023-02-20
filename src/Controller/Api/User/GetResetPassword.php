<?php

namespace App\Controller\Api\User;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetResetPassword extends AbstractController
{

    /**
     * @var UserRepository
     */
    protected $userRepo;

    /**
     * GetResetPassword constructor.
     * @param UserRepository $userRepo
     */
    public function __construct(
        UserRepository $userRepo
                               )
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function __invoke($tokenPasswordReset)
    {
        $userByToken = $this->userRepo->getUserInfoByToken($tokenPasswordReset);

        // dd($userByToken);

        if (empty($userByToken)) {
            return [
            false,
                "Token not found."
            ];
        }
        else{
            return [
                'UserId' => $userByToken[0]->getId(),
                'message' => 'success, User Found',
            ];
        }
      
    }
 
}