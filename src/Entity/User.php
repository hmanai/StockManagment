<?php

namespace App\Entity;

use App\Entity\Transactions;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 * 
 *       itemOperations={
 *          "get"={"normalization_context"={"groups"={"read:user"}}},
 *          "put"={},
 *          "patch"={},
 *      
 *       },
 *          
 *       collectionOperations={
 *          "get"={"normalization_context"={"groups"={"read:user"}}},
 *          "api_signup"={
 *              "method"="POST",
 *              "normalization_context"={"groups"={"read:signup"}},
 *              "path"="/user/signup",
 *              "controller"=App\Controller\Api\User\Signup::class
 *          },
 *              
 *              
 *       }
 * 
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface

{   

    
    
    /**
    * @ORM\Column(type="datetime", nullable=true)
    * @var \DateTime
    */
   private $passwordRequestedAt;
   /**
   * @var string
   *
   * @ORM\Column(type="string", length=255, nullable=true)
   */
   private $tokenPasswordReset;


    ///////////////////////////////////////////////////////
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:signup", "read:user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read:signup", "read:user"})
     */

  
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read:user"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"read:user"})
     */
    private $createdAt;


    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user")
     */
    private $products;

    private $isEnterprise;

    /**
     * @ORM\OneToMany(targetEntity=Invoice::class, mappedBy="invoice_user", orphanRemoval=true)
     */
    private $invoices;



    public function __construct()
    {
        $this->products = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsEnterprise(): ?string
    {
        return $this->isEnterprise;
    }

    public function setIsEnterprise(string $isEnterprise): self
    {
        $this->isEnterprise = $isEnterprise;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }
	/**
	 * 
	 * @return \DateTime
	 */
	public function getPasswordRequestedAt() {
		return $this->passwordRequestedAt;
	}
	
	/**
	 * 
	 * @param \DateTime $passwordRequestedAt 
	 * @return self
	 */
	public function setPasswordRequestedAt($passwordRequestedAt): self {
		$this->passwordRequestedAt = $passwordRequestedAt;
		return $this;
	}

	/**
	 * 
	 * @return string
	 */
	public function getTokenPasswordReset() {
		return $this->tokenPasswordReset;
	}
	
	/**
	 * 
	 * @param string $tokenPasswordReset 
	 * @return self
	 */
	public function settokenPasswordReset($tokenPasswordReset): self {
		$this->tokenPasswordReset = $tokenPasswordReset;
		return $this;
	}
    


    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }


}

