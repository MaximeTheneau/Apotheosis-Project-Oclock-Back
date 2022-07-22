<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DoctrineDenormalizer implements DenormalizerInterface
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Appelle quand on a besoin de dénormaliser
     *
     * @param mixed $data : valeur que l'on veut dénormaliser (dans notre cas un id)
     * @param string $type : type que l'on veux obtenir
     * @param string|null $format :
     *
     * @return bool
     */
    public function supportsDenormalization($data, string $type, ?string $format = null)
    {
        
        $dataIsId = is_numeric($data);

        $regex = "/^(App\\\Entity)/";
        $typeIsEntity = preg_match($regex, $type);
        // dd($typeIsEntity);


        return $typeIsEntity && $dataIsId;
    }

    /**
     *
     * @param mixed $data : valeur que l'on veut dénormaliser (dans notre cas un id)
     * @param string $type : type que l'on veux obtenir
     * @param string|null $format
     * @param array $context
     * 
     * @return mixed
     */
    public function denormalize($data, string $type, ?string $format = null , array $context = [])
    {
        return $this->manager->find($type, $data);
    }
}