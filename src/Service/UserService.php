<?php 


namespace App\Service;
use Doctrine\ORM\EntityManager;


class UserService extends AbstractService


{
    public function __construct(EntityManager $em, $entityName)
    {
        $this->em = $em;
        $this->model = $em->getRepository($entityName);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getUser($user_id)
    {
        return $this->find($user_id);
    }

    public function getUsers()
    {
        return $this->findAll();
    }

    public function addUser()
    {
        return $this->save();
    }

    public function deleteUser($id)
    {   
        return $this->delete($this->find($id));
    }
}