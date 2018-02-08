<?php

namespace BackOfficeBundle\Repository;

use BackOfficeBundle\Entity\Post;
use BackOfficeBundle\Entity\Product;
use BackOfficeBundle\Entity\ProductOrder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;

class ProductRepository extends EntityRepository
{
    /**
     * @var
     */
    private $data;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * CustomerShippingAddressRepository constructor.
     * @param EntityManager $entityManager
     * @param Mapping\ClassMetadata $metadata
     */
    public function __construct(EntityManager $entityManager, Mapping\ClassMetadata $metadata)
    {
        parent::__construct($entityManager, $metadata);
    }

    /**
     * Get report data
     *
     * @param int $product
     * @param $occurrence
     * @param $periodFrom
     * @param string $periodTo
     *
     * @return $this
     */
    public function getAccountingReport($product = 0, $occurrence, $periodFrom, $periodTo = ''){


        if($occurrence === 0) { // no occurrence


            $qb = $this->getEntityManager()->createQueryBuilder('BackOfficeBundle:ProductOrder')
                ->select('SUM(o.total) AS total')
                ->from('BackOfficeBundle:ProductOrder', 'o')
                ->Where('o.product = :product')
                ->andWhere('o.shippedAt >= :from')
                ->andWhere('o.shippedAt <= :to')
                ->setParameters(['product' => $product, 'from' => $periodFrom, 'to' => $periodTo]);


            $result = $qb->getQuery()->getArrayResult();
            $this->setData('total', isset($result[0]['total']) ? $result[0]['total'] : 0);
        }

        if($occurrence === 86400){ // daily

            $qb = $this->getEntityManager()->createQueryBuilder('BackOfficeBundle:ProductOrder')
                ->select('o.id, IDENTITY(o.product) as PRODUCT, o.total, o.day')
                ->from('BackOfficeBundle:ProductOrder', 'o')
                ->where('1=1')
                ->andWhere('o.product = :product')
                ->andWhere('o.shippedAt = :from')
                ->setParameters(['product' => $product, 'from' => $periodFrom]);

            $qb->addGroupBy('o.id, o.shippedAt');

            $this->setData('total', count($qb->getQuery()->getArrayResult()));
        }

        if($occurrence === 604800){ // weekly

            $qb = $this->getEntityManager()->createQueryBuilder('BackOfficeBundle:ProductOrder')
                ->select('o.id, IDENTITY(o.product) as PRODUCT, o.total, o.week')
                ->from('BackOfficeBundle:ProductOrder', 'o')
                ->where('1=1')
                ->andWhere('o.product = :product')
                ->andWhere('o.week = :from')
                ->setParameters(['product' => $product, 'from' => $periodFrom]);

            $qb->addGroupBy('o.id, o.week');

            $this->setData('total', count($qb->getQuery()->getArrayResult()));
        }

        if($occurrence === 2592000){ // monthly


            $qb = $this->getEntityManager()->createQueryBuilder('BackOfficeBundle:ProductOrder')
                ->select('o.id, IDENTITY(o.product) as PRODUCT, o.total, o.month')
                ->from('BackOfficeBundle:ProductOrder', 'o')
                ->where('1=1')
                ->andWhere('o.product = :product')
                ->andWhere('o.month = :from')
                ->setParameters(['product' => $product, 'from' => $periodFrom]);


            $qb->addGroupBy('o.id, o.month');

            $this->setData('total', count($qb->getQuery()->getArrayResult()));
        }

        return $this;
    }

    /**
     * Get product pricing for reporting
     *
     * @param $id
     * @return $this
     */
    public function getProductPricing($id){

        $qb = $this->getEntityManager()->createQueryBuilder('BackOfficeBundle:ProductPurchaseOrder')
            ->select('p.productCost AS product_cost, p.shippingCost AS shipping_cost')
            ->from('BackOfficeBundle:ProductPurchaseOrder', 'p')
            ->where('p.product = :product')
            ->setParameter(':product', $id)
            ->addOrderBy('p.modifiedAt', 'DESC')
            ->setMaxResults(1);

        $pricing = $qb->getQuery()->getArrayResult();

        if(empty($pricing)){
            $this->setData('ppu',  '');
            $this->setData('shipping',  '');
        } else {
            $this->setData('ppu', $pricing[0]['product_cost']);
            $this->setData('shipping', $pricing[0]['shipping_cost']);
        }

        return $this;
    }

    /**
     * @param $post
     * @return Product
     * @throws \Exception
     */
    public function findOneByPostOrFail($post){

        $post = $this->getEntityManager()->getRepository(Post::class)->findOneBy(['link' => $post, 'active' => 1]);

        if(!$post instanceof Post)
            throw new \Exception(sprintf('Attempted to get a non existing product for given post id: %d', $post));

        return $this->find($post->getProduct());
    }
}
