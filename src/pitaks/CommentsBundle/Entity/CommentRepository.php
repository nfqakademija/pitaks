<?php
namespace pitaks\CommentsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use pitaks\KickerBundle\Entity\Tables;
use pitaks\UserBundle\Entity\User;

class CommentRepository extends EntityRepository
{
    /**
     * @param string $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder($alias = 'c')
    {
        return $this->createQueryBuilder($alias);
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getListQuery()
    {
        return $this
            ->getQueryBuilder()
            ->select('c')
            ->getQuery();
    }

    /**
     * @param array $data
     * @param Tables $table
     * @param User $user
     */
    public function insert(array $data, Tables $table, User $user)
    {
        $comment = $this->getComment($data);
        $comment->addTableId($table);
        $comment->setUserId($user);
        $this->save($comment);
    }

    /**
     * @param Comment $comment
     */
    protected function save(Comment $comment)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    /**
     * @param array $data
     * @return Comment
     */
    protected function getComment(array $data)
    {
        $comment = new Comment();
        foreach ($data as $field => $value) {
            $method = 'set'.ucfirst($field);
            if (method_exists($comment, $method)) {
                $comment->$method($value);
            }
        }

        return $comment;
    }
}