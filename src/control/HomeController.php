<?php
 
namespace lbs\control ;
 
use Slim\Views\Twig as View;
 
class HomeController 
 
{
 
   protected $views;
 
   public function __construct(Views $views)
 
   {
 
       return $this->view = $views;
 
   }
 
   public function index($request, $response)
 
   {
 
      return $this->view->render($response, 'home.twig');

   }
 
}