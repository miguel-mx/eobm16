<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistroType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('paterno')
            ->add('materno')
            ->add('sexo', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices'  => array(
                    'Masculino' => 'M',
                    'Femenino' => 'F',
                ),
                    'choices_as_values' => true,
            ))
            ->add('mail')
            ->add('tel')
            ->add('procedencia')
            ->add('carrera', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices'  => array(
                    'Licenciatura' => 'Licenciatura',
                    'Posgrado' => 'Posgrado',
                ),
                'choices_as_values' => true,
            ))
            ->add('porcentaje')
            ->add('promedio')
            ->add('profesor')
            ->add('univprofesor')
            ->add('mailprofesor')
            ->add('recomendacion', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array('required'    => false))
//            ->add('cartaName')
            ->add('eventos', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array('required'    => false))
            ->add('beca', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices'  => array(
                    'Solo comida' => 'Solo comida',
                    'Comida y hospedaje' => 'Comida y hospedaje',
                ),
                'choices_as_values' => true,
            ))
            ->add('razones', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array('required'    => false))
            ->add('charla')
            ->add('resumen', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array('required'    => false))
        ;

        $builder->add('historialFile', 'vich_file', array(
            'label' => 'Historial académico',
            'required'      => true,
        ));

    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Registro'
        ));
    }
}
