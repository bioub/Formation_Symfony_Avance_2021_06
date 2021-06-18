<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Manager\ContactManager;
use App\Manager\ContactManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    /** @var ContactManagerInterface */
    protected $contactManager;

    /**
     * ContactController constructor.
     * @param ContactManagerInterface $contactManager
     */
    public function __construct(ContactManagerInterface $contactManager)
    {
        $this->contactManager = $contactManager;
    }

    /**
     * @Route("/contacts/")
     */
    public function list()
    {
//        $repo = $this->getDoctrine()->getRepository(Contact::class);
//        $contactsList = $repo->findAll();
        $contactsList = $this->contactManager->getAll();

        return $this->render('contact/list.html.twig', [
            'contacts' => $contactsList,
        ]);
    }

    /**
     * @Route("/contacts/add")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function add(Request $request)
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash(
                'success',
                "Contact {$contact->getFirstName()} {$contact->getLastName()} has been created successfully"
            );

            return $this->redirectToRoute('app_contact_list');
        }

        return $this->render('contact/add.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/contacts/{id}", requirements={"id": "[1-9][0-9]*"})
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Contact::class);
        $contact = $repo->findWithCompany($id);

        return $this->render('contact/show.html.twig', [
            'contact' => $contact
        ]);
    }

    /**
     * @Route("/contacts/{id}/update")
     */
    public function update($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('App:Contact');
        $contact = $repo->find($id);

        $form = $this->createForm(ContactType::class);
        $form->setData($contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash(
                'success',
                "Contact {$contact->getFirstName()} {$contact->getLastName()} has been modified successfully"
            );

            return $this->redirectToRoute('app_contact_list');
        }

        return $this->render('contact/update.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contacts/{id}/delete")
     */
    public function delete($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('App:Contact');
        $contact = $repo->find($id);

        if ($request->get('confirm') === 'yes') {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contact);
            $em->flush();

            $this->addFlash(
                'success',
                "Contact {$contact->getFirstName()} {$contact->getLastName()} has been deleted successfully"
            );
        }

        if ($request->isMethod('POST')) {
            return $this->redirectToRoute('app_contact_list');
        }

        return $this->render('contact/delete.html.twig', [
            'contact' => $contact
        ]);
    }

}
