<?php
namespace App\ParamConverter;

use App\Entity\Item;
use App\Entity\Property;
use App\Entity\Room;
use App\Interfaces\EntityWithWarrantyInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use LogicException;

/**
 * Class EntityWarrantConverter
 * @package App\ParamConverter
 */
class EntityWarrantConverter implements ParamConverterInterface
{
    /**
     * Aliases for entity
     */
    private const ENTITY_ALIAS = [
        'property' => Property::class,
        'room'     => Room::class,
        'item'     => Item::class,
    ];

    private $entityManager;

    /**
     * EntityWarrantConverter constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        if (!array_key_exists($request->get('entity'),self::ENTITY_ALIAS)){
            throw new LogicException('You cannot create new warranty from wrong entity.');
        }
        $entityName=$request->get('entity');
        $entityId=$request->get('entity_id');
        $entityRep = $this->entityManager->getRepository(self::ENTITY_ALIAS[$entityName]);
        $entity = $entityRep->find($entityId);
        $request->attributes->set($configuration->getName(),$entity);
        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration
     *
     * @return  True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === EntityWithWarrantyInterface::class;
    }
}
