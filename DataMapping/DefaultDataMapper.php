<?php

namespace Modera\ServerCrudBundle\DataMapping;

use Doctrine\ORM\EntityManager;
use Sli\ExtJsIntegrationBundle\DataMapping\EntityDataMapperService;

/**
 * @author    Sergei Lissovski <sergei.lissovski@modera.org>
 * @copyright 2013 Modera Foundation
 */
class DefaultDataMapper implements DataMapperInterface
{
    private $mapper;
    private $em;

    public function __construct(EntityDataMapperService $mapper, EntityManager $em)
    {
        $this->mapper = $mapper;
        $this->em = $em;
    }

    /**
     * @param string $entityClass
     *
     * @return string[]
     */
    private function getAllowedFields($entityClass)
    {
        $metadata = $this->em->getClassMetadata($entityClass);

        $fields = $metadata->getFieldNames();
        foreach ($metadata->getAssociationMappings() as $mapping) {
            $fields[] = $mapping['fieldName'];
        }

        return $fields;
    }

    /**
     * @inheritDoc
     */
    public function mapData(array $params, $entity)
    {
        $allowedFields = $this->getAllowedFields(get_class($entity));

        $this->mapper->mapEntity($entity, $params, $allowedFields);
    }

    static public function clazz()
    {
        return get_called_class();
    }
}