<?php

namespace AppBundle\Features\Context;

use AppKernel;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommonGivenContext extends CommonContext
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @Given entities :entityName :
     * @Given entity :entityName :
     */
    public function entities($entityName, TableNode $table)
    {
        foreach ($table as $row) {
            $entity = new $entityName();
            foreach ($row as $propertyName => $value) {
                if (!$value || empty($value)) {
                    continue;
                }

                $entityNameAndFieldName = explode(":", $propertyName);
                $countEntityNameAndFieldName = count($entityNameAndFieldName);

                if ($countEntityNameAndFieldName > 1) {
                    // is Entity
                    $relatedEntity = $this->findEntityByField($entityNameAndFieldName[$countEntityNameAndFieldName - 2], $entityNameAndFieldName[$countEntityNameAndFieldName - 1], $value);
                    if ($countEntityNameAndFieldName === 3) {
                        $property = "set" . $entityNameAndFieldName[$countEntityNameAndFieldName - 3];
                    } else {
                        $entityNameExploded = explode("\\", $entityNameAndFieldName[$countEntityNameAndFieldName - 2]);
                        $property = "set" . $entityNameExploded[count($entityNameExploded) - 1];
                    }
                    $entity->$property($relatedEntity);
                } else {
                    $property = "set$propertyName";
                    preg_match('#\(+(.*)\)+#', $propertyName, $variableType);
                    if ($variableType) {
                        $value = new $variableType[1]($value);
                        $propertyName = explode("(", $propertyName)[0];
                        $property = "set$propertyName";
                    }
                    $entity->$property($value);
                }

            }

            $this->em->persist($entity);
        }
        $this->em->flush();
    }

    /**
     * @param string $entityName
     * @param string $fieldName
     * @param string $value
     * @return Entity
     * @throws
     */
    private function findEntityByField($entityName, $fieldName, $value)
    {
        /** @var EntityRepository $repository */
        $repository = $this->em->getRepository($entityName);
        $property = "findOneBy$fieldName";
        $entity = $repository->$property($value);

        if (null === $entity) {
            throw new \Exception("Can't find Entity $entityName, with $fieldName = '$value''");
        }
        return $entity;
    }

}
