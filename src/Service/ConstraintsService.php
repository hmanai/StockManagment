<?php
namespace App\Service;

use App\Repository\UserRepository;

class ConstraintsService
{
    /**
     * @var UserRepository
     */
    protected $userRepo;


    public function __construct(
        UserRepository $userRepo
    ) {
        $this->userRepo = $userRepo;
    }
    
    public function userValidator($user){

        if(!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)){
            return [
                false,
                "Adress mail is'nt valid."
            ];
        }
        
        foreach ($this->userRepo->findAll() as $key => $value) {
            
            if ($value->getEmail() == $user->getEmail()) {
                return [
                    false,
                    "Email already exist."
                ];
            }
       
        }

        return [
            true,
            "success"
        ];

    }

    public function advertisementValidator($advertisement){
        return [
            true,
            "success"
        ];
    }

    public function conversationValidator($conversation){
        return [
            true,
            "success"
        ];
    }

    public function messageValidator($message){
        return [
            true,
            "success"
        ];
    }

    public function opinionValidator($opinion){
        return [
            true,
            "success"
        ];
    }
}
