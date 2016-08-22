<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Registro;
use AppBundle\Form\RegistroType;

/**
 * Registro controller.
 *
 * @Route("/registro")
 */
class RegistroController extends Controller
{
    /**
     * Lists all Registro entities.
     *
     * @Route("/", name="registro_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        // Access control
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Acceso restringido');

        $em = $this->getDoctrine()->getManager();

        $registros = $em->getRepository('AppBundle:Registro')->findAll();

        return $this->render('registro/index.html.twig', array(
            'registros' => $registros,
        ));
    }

    /**
     * Creates a new Registro entity.
     *
     * @Route("/new", name="registro_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $registro = new Registro();
        $form = $this->createForm('AppBundle\Form\RegistroType', $registro);
        $form->remove('recomendacion');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($registro);
            $em->flush();

            // Obtiene correo y msg de la forma de contacto
            $mailer = $this->get('mailer');

            $message = \Swift_Message::newInstance()
                ->setSubject('Escuela de Otoño y el Encuentro Nacional de Biología Matemática 2016')
                ->setFrom('webmaster@matmor.unam.mx')
                ->setTo(array($registro->getMail()))
                ->setBcc(array('rudos@matmor.unam.mx'))
                ->setBody($this->renderView('registro/mail.txt.twig', array('entity' => $registro)))
            ;
            $mailer->send($message);

            $message = \Swift_Message::newInstance()
                ->setSubject('Solicitud de recomendación. Escuela de Otoño y el Encuentro Nacional de Biología Matemática 2016')
                ->setFrom('webmaster@matmor.unam.mx')
                ->setTo(array($registro->getMailprofesor()))
                // >->setBcc(array('rudos@matmor.unam.mx'))
                ->setBody($this->renderView('registro/mailprof.txt.twig', array('entity' => $registro)))
            ;
            $mailer->send($message);

            return $this->render(':registro:confirmacion-registro.html.twig', array('id' => $registro->getId(),'entity'=>$registro));
            //return $this->redirectToRoute('registro_show', array('id' => $registro->getId()));
        }


        return $this->render('registro/new.html.twig', array(
            'registro' => $registro,
            'form' => $form->createView(),
        ));
    }
    /**
     * Displays a form to send recommendation file.
     *
     * @Route("/{slug}/recomendacion", name="recomendacion")
     * @Method({"GET", "POST"})
     */
    public function recomAction(Request $request, Registro $registro)
    {
        $editForm = $this->createForm('AppBundle\Form\RegistroType', $registro);

        $editForm->remove('nombre');
        $editForm->remove('paterno');
        $editForm->remove('materno');
        $editForm->remove('sexo');
        $editForm->remove('mail');
        $editForm->remove('tel');
        $editForm->remove('procedencia');
        $editForm->remove('carrera');
        $editForm->remove('semestre');
        $editForm->remove('porcentaje');
        $editForm->remove('promedio');
        $editForm->remove('profesor');
        $editForm->remove('univprofesor');
        $editForm->remove('mailprofesor');
        $editForm->remove('historialFile');
        $editForm->remove('evento');
        $editForm->remove('beca');
        $editForm->remove('razones');
        $editForm->remove('comentarios');
        $editForm->remove('eventos');
        $editForm->remove('charla');
        $editForm->remove('resumen');

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $registro->setUpdatedAt(new \DateTime());

            $em->persist($registro);
            $em->flush();

            return $this->render('registro/confirmRecom.html.twig', array('id' => $registro->getId(), 'entity' => $registro));

        }

        return $this->render('registro/recom.html.twig', array(
            'registro' => $registro,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Finds and displays a Registro entity.
     *
     * @Route("/{slug}", name="registro_show")
     * @Method("GET")
     */
    public function showAction(Registro $registro)
    {
        // Access control
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Acceso restringido');

        $deleteForm = $this->createDeleteForm($registro);

        return $this->render('registro/show.html.twig', array(
            'registro' => $registro,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Registro entity.
     *
     * @Route("/{id}/edit", name="registro_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Registro $registro)
    {
        // Access control
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Acceso restringido');

        $deleteForm = $this->createDeleteForm($registro);
        $editForm = $this->createForm('AppBundle\Form\RegistroType', $registro);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($registro);
            $em->flush();

            return $this->redirectToRoute('registro_edit', array('id' => $registro->getId()));
        }

        return $this->render('registro/edit.html.twig', array(
            'registro' => $registro,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Registro entity.
     *
     * @Route("/{id}", name="registro_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Registro $registro)
    {
        $form = $this->createDeleteForm($registro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($registro);
            $em->flush();
        }

        return $this->redirectToRoute('registro_index');
    }

    /**
     * Creates a form to delete a Registro entity.
     *
     * @param Registro $registro The Registro entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Registro $registro)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('registro_delete', array('id' => $registro->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
