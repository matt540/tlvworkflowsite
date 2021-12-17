<?php

namespace App\Repository;

use App\Entities\AgentLogImages;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class AgentLogImageRepository extends EntityRepository {

    private $class = 'App\Entities\AgentLogImages';
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function create(AgentLogImages $agent_log_images) {
        $this->em->persist($agent_log_images);
        $this->em->flush();
        return $agent_log_images->getId();
    }

    public function prepareData($data) {
        return new AgentLogImages($data);
    }

    public function update(AgentLogImages $agentLogImage, $data) {

        if (isset($data['name'])) {
            $agentLogImage->setName($data['name']);
        }

        if (isset($data['priority'])) {
            $agentLogImage->setPriority($data['priority']);
        }

        $this->em->persist($agentLogImage);
        $this->em->flush();
        return 1;
    }

    public function ImageOfId($id) {

        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function getImageById($id) {
        $query = $this->em->createQueryBuilder()
                ->select('u')
                ->from('App\Entities\Images', 'u')
                ->where('u.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $data = $query->getResult(Query::HYDRATE_ARRAY);
        return $data[0];
    }

    public function delete(AgentLogImages $agentLogImage) {
        $this->em->remove($agentLogImage);
        $this->em->flush();
    }

}
