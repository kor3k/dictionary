<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints;

class BackendController extends \Core\AbstractController
{
    public function indexAction()
    {
        return $this->app
            ->redirect( $this->app->url( 'get_passwords' ) );
    }

    public function getPasswordAction( $id )
    {

    }

    public function getPasswordsAction()
    {

    }

    public function newPasswordAction()
    {
        $form   =   $this->createPasswordFormBuilder();
        $form
            ->setMethod( 'POST' )
            ->setAction( $this->app->url( 'post_passwords' ) )
        ;

        return $this->app
            ->render( '/passwords/new.html.twig' , [ 'form' => $form->getForm()->createView() ] );
    }

    public function postPasswordsAction()
    {
        $form   =   $this->createPasswordFormBuilder();
        $form
            ->setMethod( 'POST' )
            ->setAction( $this->app->url( 'post_passwords' ) )
        ;
        $form   =   $form->getForm();
        $form->handleRequest( $this->app['request'] );

        if( $form->isValid() )
        {
            $this->app->redirect( $this->app->url( 'get_passwords' ) , 201 );
        }
        else
        {
            //bad request automaticky nebo růčo?
            return $this->app
                ->render( '/passwords/new.html.twig' , [ 'form' => $form->createView() ] );
        }
    }

    protected function connect( \Silex\ControllerCollection $controllers )
    {
        $controllers
            ->get( '/', array( $this , 'indexAction' ) )
            ->bind( 'admin_index' )
        ;

        $controllers
            ->get( '/new' , array( $this , 'newPasswordAction' ) )
            ->bind( 'new_password' )
        ;

        $controllers
            ->post( '/' , array( $this , 'postPasswordsAction' ) )
            ->bind( 'post_passwords' )
        ;

        $controllers
            ->get( '/' , array( $this , 'getPasswordsAction' ) )
            ->bind( 'get_passwords' )
        ;

        $controllers
            ->get( '/{id}' , array( $this , 'getPasswordAction' ) )
            ->bind( 'get_password' )
            ->assert( 'id' , '\d+' )
        ;

        return $controllers;
    }

    /**********/

    protected function createPasswordFormBuilder()
    {
        return $this->app->form( null )
            ->add( 'title' , 'text' , [
                'constraints'   =>  [
                    new Constraints\NotBlank() ,
                    new Constraints\Length([ 'min' => 2 , 'max' => 255 ]) ,
                ],
            ] )
            ->add( 'send' , 'submit' )
            ;
    }
}
