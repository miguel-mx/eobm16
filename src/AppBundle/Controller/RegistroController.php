<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Registro;
use AppBundle\Form\RegistroType;
use Symfony\Component\Validator\Constraints\DateTime;


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
     * Lists all Registro entities.
     *
     * @Route("/aceptados", name="registro_aceptados")
     * @Method("GET")
     */
    public function aceptadosAction()
    {
        // Access control
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Acceso restringido');

        $em = $this->getDoctrine()->getManager();

        $registros = $em->getRepository('AppBundle:Registro')->findAllAccepted();

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
        $now = new \DateTime();
        $deadline = new \DateTime('2016-09-15');

        if($now >= $deadline)
            return $this->render(':registro:closed.html.twig');

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

            $formStatus = $form["status"]->getData();

            if($formStatus == 'Estudiante') {

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
                    ->setBody($this->renderView('registro/mailprof.txt.twig', array('entity' => $registro)));
                $mailer->send($message);
            }
            else {

                $message = \Swift_Message::newInstance()
                    ->setSubject('Escuela de Otoño y el Encuentro Nacional de Biología Matemática 2016')
                    ->setFrom('webmaster@matmor.unam.mx')
                    ->setTo(array($registro->getMail()))
                    ->setBcc(array('rudos@matmor.unam.mx'))
                    ->setBody($this->renderView('registro/solicitudProfesor.txt.twig', array('entity' => $registro)))
                ;
                $mailer->send($message);
            }

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
        if($registro->getRecomendacion()) {
            return $this->render('registro/confirmRecom.html.twig', array('id' => $registro->getId(), 'entity' => $registro));
        }

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

        // Form event Postsubmit trata de revisar el estatus para utilizar los grupos de validación
        $editForm->remove('status');

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();

            $registro->setUpdatedAt(new \DateTime());

            // email solicitante y profesor

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
     * @Route("/{slug}/edit", name="registro_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Registro $registro)
    {
        // Access control
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Acceso restringido');

        //$deleteForm = $this->createDeleteForm($registro);
        //$editForm = $this->createForm('AppBundle\Form\RegistroType', $registro);
        //$editForm->handleRequest($request);

        $evalForm = $this->createFormBuilder()
            ->add('aceptado',  'Symfony\Component\Form\Extension\Core\Type\CheckboxType',  array('data' => $registro->isAceptado(),'required' => false ))
            ->add('comentarios',  'Symfony\Component\Form\Extension\Core\Type\TextareaType',  array('data' => $registro->getComentarios(), 'required' => false))
//            ->add('eval', 'Symfony\Component\Form\Extension\Core\Type\ButtonType')
            ->getForm();

        $evalForm->handleRequest($request);

        if ($evalForm->isSubmitted() && $evalForm->isValid()) {

            $data = $evalForm->getData();
            $registro->setAceptado($data['aceptado']);
            $registro->setComentarios($data['comentarios']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($registro);
            $em->flush();

            $this->addFlash(
                'notice',
                'Los cambios han sido guardados'
            );

            return $this->redirectToRoute('registro_show', array('slug' => $registro->getSlug()));
        }

        return $this->render('registro/eval.html.twig', array(
            'registro' => $registro,
            'eval_form' => $evalForm->createView(),
        ));
    }

    /**
     * Confirmación
     *
     * @Route("/{slug}/confirmacion", name="registro_confirmacion")
     * @Method({"GET", "POST"})
     */
    public function confirmAction(Request $request, Registro $registro)
    {
        $now = new \DateTime();
        $deadline = new \DateTime('2016-09-22 13:00');

        if($now >= $deadline)
            return $this->render(':registro:closedConfirmacion.html.twig');

        // Revisa si ya está confirmado
        if($registro->isConfirmado())
            return $this->render('registro/confirmAsistencia.html.twig', array(
                'entity' => $registro,
            ));

        $confirmForm = $this->createFormBuilder()
            ->add('confirmacion',  'Symfony\Component\Form\Extension\Core\Type\CheckboxType',  array('data' => $registro->isConfirmado(),'required' => false ))
            ->getForm();

        $confirmForm->handleRequest($request);

        if ($confirmForm->isSubmitted() && $confirmForm->isValid()) {

            $data = $confirmForm->getData();
            $registro->setConfirmado($data['confirmacion']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($registro);
            $em->flush();

            $mailer = $this->get('mailer');

            $message = \Swift_Message::newInstance()
                ->setSubject('Confirmación - Escuela de Otoño y el Encuentro Nacional de Biología Matemática 2016')
                ->setFrom('webmaster@matmor.unam.mx')
                ->setTo(array($registro->getMail()))
                ->setBcc(array('rudos@matmor.unam.mx'))
                ->setBody($this->renderView('registro/confirmacion.txt.twig', array('entity' => $registro)))
            ;
            $mailer->send($message);

            $this->addFlash(
                'notice',
                'Registro confirmado'
            );

            return $this->render('registro/confirmAsistencia.html.twig', array(
                'entity' => $registro,
            ));
        }

        return $this->render('registro/asistencia.html.twig', array(
            'registro' => $registro,
            'confirm_form' => $confirmForm->createView(),
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
