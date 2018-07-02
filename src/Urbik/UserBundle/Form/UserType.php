<?php

namespace Urbik\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sexChoices = [
            'homme' => 'homme',
            'femme' => 'femme'
        ];

        $jobChoices = [
            'exploitant agricole' => 'exploitant agricole',
            'salarié agricole' => 'salarié agricole',
            'patron' => 'patron de l\'industrie et du commerce',
            'cadre sup' => 'cadre supérieur',
            'cadre' => 'cadre',
            'Employé public' => 'employé du service public',
            'employé privé' => 'employé du privé',
            'ouvrier' => 'ouvrier',
            'employé service' => 'personnel de services',
            'sans emploi' => 'sans emploi',
            'etudiant' => 'étudiant',
            'retraite' => 'retraité',
            'autre' => 'autre'
        ];

        $request = Request::createFromGlobals();
        $country = $this->get_country($request);

        $builder
        ->add('nom', TextType::class)
        ->add('prenom', TextType::class)
        ->add('birthdate', BirthdayType::class, array(
            'format' => 'dd/MM/yyyy'
        ))
        ->add('mail', EmailType::class)
        ->add('sex', ChoiceType::class, array(
            'choices' => $sexChoices,
            'expanded' => true,
            'multiple' => false,
            'label' => 'sexe',
            'required' => true
        ))
        ->add('country', CountryType::class, array(
            'preferred_choices' => array($country['countryCode']),
            'label' => 'pays/country'
        ))
        ->add('region', HiddenType::class, array(
            'data' => $country['region'],
            'disabled' => true
        ))
        ->add('job', ChoiceType::class, array(
            'choices' => $jobChoices,
            'expanded' => false,
            'multiple' => false,
            'label' => 'Profession',
            'required' => true
        ))
        ->add('save', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Urbik\UserBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'urbik_userbundle_user';
    }

    public function get_country(Request $request) {

        // $request = Request::createFromGlobals();
        $ip = $request->getClientIp();

        $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip)); //connection au serveur de ip-api.com et recuperation des données
    
        if($query && $query['status'] == 'success') 
        {
             //code avec les variables
             //  echo "Bonjour visiteur de " . $query['country'] . "," . $query['city'];
            return array(
                'country' => $query['country'],
                'countryCode' => $query['countryCode'],
                'region' => $query['regionName'],
                'ip' => $country['query']
            );
        }
    //  else {
    //     echo "Erreur de connection à l'adresse: ". $ip;
    // }
    }
}
