<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;




class MailRecomendationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mail:recomendation')
            ->setDescription('Envia correo para pedir recomendación para solicitud')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
            ->addOption(
                'send',
                null,
                InputOption::VALUE_NONE,
                'Opción para enviar correos de solicitud de recomendación'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
/*        $argument = $input->getArgument('argument');

        if ($input->getOption('option')) {
            // ...
        }*/

        $em = $this->getContainer()->get('doctrine')->getManager();
        $query = $em->createQuery(
            'SELECT r
            FROM AppBundle:Registro r
            WHERE r.recomendacion IS NULL'
        );

        $registros = $query->getResult();

        $io = new SymfonyStyle($input, $output);
        $io->title('Recordatorio recomendación de solicitud');

        foreach($registros as $reg) {

            if($reg->getMailprofesor()) {

                if($input->getOption('send')) {

                    //Estas lineas son para cuando el servidor tiene un certificado no valido
                    $https['ssl']['verify_peer'] = FALSE;
                    $https['ssl']['verify_peer_name'] = FALSE;

                    $transport = \Swift_SmtpTransport::newInstance($this->getContainer()->getParameter('mailer_host'), $this->getContainer()->getParameter('mailer_port'), $this->getContainer()->getParameter('mailer_encryption'))
                        ->setUsername($this->getContainer()->getParameter('mailer_user'))
                        ->setPassword($this->getContainer()->getParameter('mailer_password'))
                        ->setStreamOptions($https);

                    // Envía correo al profesor
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Recordatorio Solicitud de recomendación. Escuela de Otoño y el Encuentro Nacional de Biología Matemática 2016')
                        ->setFrom('webmaster@matmor.unam.mx')
                        ->setTo(array($reg->getMailprofesor()))
                        ->setBcc(array('miguel@matmor.unam.mx'))
                        ->setBody($this->getContainer()->get('templating')->render('registro/mailprof.txt.twig', array('entity' => $reg)));
                    $this->getContainer()->get('mailer')->newInstance($transport)->send($message);

                }

                $output->writeln('<info>http://gaspacho.matmor.unam.mx/eobm16/registro/'.$reg->getSlug().'/recomendacion'.'</info>');
            }
            else {
                $output->writeln('<error>'.'http://gaspacho.matmor.unam.mx/eobm16/registro/'.$reg->getSlug().'</error>');
            }
        }

    }

}
