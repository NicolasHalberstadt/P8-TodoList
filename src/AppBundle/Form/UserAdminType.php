<?php


namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserAdminType
 *
 * @author Nicolas Halberstadt <halberstadtnicolas@gmail.com>
 * @package AppBundle\Form
 */
class UserAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        isset($options['user']) ? $user = $options['user']->getRole() : $user = 'ROLE_USER';
        $builder
            ->add('username', TextType::class, ['label' => "Nom d'utilisateur"])
            ->add('email', EmailType::class, ['label' => 'Adresse email'])
            ->add(
                'role',
                ChoiceType::class,
                [
                    'multiple' => false,
                    'expanded' => true,
                    'choices' => [
                        'User' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN',
                    ],
                    'empty_data' => $user,
                ]
            );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'user' => null,
            ]
        );
    }
    
}