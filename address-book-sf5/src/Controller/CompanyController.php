<?php

namespace App\Controller;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company/")
     */
    public function list()
    {
        $repo = $this->getDoctrine()->getRepository(Company::class);
        $companies = $repo->findAll();

        return $this->render('company/list.html.twig', [
            'companies' => $companies,
        ]);
    }

    /**
     * @Route("/company/{id}")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Company::class);
        $company = $repo->find($id);

        return $this->render('company/show.html.twig', [
            'company' => $company
        ]);
    }

}
