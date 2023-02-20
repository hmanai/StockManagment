<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InvoiceRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Annotations\AnnotationRegistry;


/**
 *  @ApiResource(
 * 
 *       itemOperations={
 *          "get"={"normalization_context"={"groups"={"read:invoice"}}},
 *          "put"={},
 *          "patch"={}
 *       },
 *       collectionOperations={
 *          "get"={"normalization_context"={"groups"={"read:invoices"}}},
 *          "get_invoice_by_user"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"read:invoices"}},
 *              "path"="/invoice/get/user/{userId}",
 *              "controller"=App\Controller\Api\Invoice\GetInvoiceByUser::class
 *          },
 *          "get_invoice_by_date"={
 *              "method"="POST",
 *              "normalization_context"={"groups"={"read:invoices"}},
 *              "path"="/invoice/get/date",
 *              "controller"=App\Controller\Api\Invoice\GetInvoiceByDate::class
 *          },
 *          "post_invoice"={
 *              "method"="POST",
 *              "normalization_context"={"groups"={"read:post_invoice"}},
 *              "path"="/invoice/post",
 *              "controller"=App\Controller\Api\Invoice\PostInvoice::class
 *          },
 *      
 *      }
 * )
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 */
class Invoice 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $invoice_name;

    /**
     * @ORM\Column(type="date")
     */
    private $invoice_date;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invoices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $invoice_user;

    public $dateArray;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceName(): ?string
    {
        return $this->invoice_name;
    }

    public function setInvoiceName(string $invoice_name): self
    {
        $this->invoice_name = $invoice_name;

        return $this;
    }

    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoice_date;
    }

    public function setInvoiceDate(\DateTimeInterface $invoice_date): self
    {
        $this->invoice_date = $invoice_date;

        return $this;
    }



    public function getInvoiceUser(): ?User
    {
        return $this->invoice_user;
    }

    public function setInvoiceUser(?User $invoice_user): self
    {
        $this->invoice_user = $invoice_user;

        return $this;
    }

	

}
