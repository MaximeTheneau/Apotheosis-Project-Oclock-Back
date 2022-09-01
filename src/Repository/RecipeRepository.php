<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Service\RecipeService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{

    private $recipeService;

    public function __construct(ManagerRegistry $registry, RecipeService $recipeService)
    {
        parent::__construct($registry, Recipe::class);
        $this->recipeService = $recipeService;
    }

    public function add(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findMostMiamsRecipes()
    {
        $qb = $this->createQueryBuilder('r')
            ->getQuery()
            ->getResult();

        $this->recipeService->countNbMiams($qb);
        
        $result = $this->recipeService->sortRecipesNbMiams($qb);

        return $result;
        
    }

    public function findLastRecipes()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function findRandomRecipes()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('RAND()')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function search(string $searched)
    {
        $query = $this->createQueryBuilder('r');
        return $query->where(
            $query->expr()->like('r.title', ':search')
            )
            ->setParameter(':search', "%{$searched}%")
            ->getQuery()
            ->getResult();
}

    public function searchWithCategory(int $categoryId, string $search)
    {
        $query = $this->createQueryBuilder('r');
        
        return $query->where(
            $query->expr()->andX(
                $query->expr()->eq('r.category', ':categoryId'),
                $query->expr()->like('r.title', ':search')
            )
        )
                    ->setParameter(':categoryId', $categoryId)
                    ->setParameter(':search', "%{$search}%")
                    ->getQuery()
                    ->getResult();

    }

    public function findNbRecipes()
    {
        $result =  $this->createQueryBuilder('r')
            
            ->getQuery()
            ->getResult();
        return count($result);
        
        
    }

//    /**
//     * @return Recipe[] Returns an array of Recipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recipe
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
