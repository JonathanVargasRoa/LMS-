<?php

declare(strict_types=1);

/* For licensing terms, see /license.txt */

namespace Chamilo\Tests\CourseBundle\Repository;

use Chamilo\CoreBundle\Repository\Node\CourseRepository;
use Chamilo\CourseBundle\Entity\CGroupCategory;
use Chamilo\CourseBundle\Repository\CGroupCategoryRepository;
use Chamilo\Tests\AbstractApiTest;
use Chamilo\Tests\ChamiloTestTrait;

class CGroupCategoryRepositoryTest extends AbstractApiTest
{
    use ChamiloTestTrait;

    public function testCreate(): void
    {
        $em = $this->getEntityManager();
        $categoryRepo = self::getContainer()->get(CGroupCategoryRepository::class);
        $courseRepo = self::getContainer()->get(CourseRepository::class);

        $course = $this->createCourse('new');
        $teacher = $this->createUser('teacher');

        $category = (new CGroupCategory())
            ->setTitle('category')
            ->setParent($course)
            ->setCreator($teacher)
            ->setDescription('desc')
            ->setSelfRegAllowed(true)
            ->setSelfUnregAllowed(true)
            ->setAnnouncementsState(true)
            ->setCalendarState(true)
            ->setChatState(true)
            ->setDocState(true)
            ->setDocumentAccess(1)
            ->setForumState(true)
            ->setWikiState(true)
            ->setWorkState(true)
            ->setGroupsPerUser(10)
            ->setMaxStudent(100)
        ;
        $this->assertHasNoEntityViolations($category);
        $em->persist($category);
        $em->flush();

        $this->assertSame('category', (string) $category);
        $this->assertSame('desc', $category->getDescription());
        $this->assertTrue($category->getSelfRegAllowed());
        $this->assertTrue($category->getSelfUnregAllowed());
        $this->assertTrue($category->getAnnouncementsState());
        $this->assertTrue($category->getCalendarState());
        $this->assertTrue($category->getChatState());
        $this->assertTrue($category->getForumState());
        $this->assertTrue($category->getWikiState());
        $this->assertTrue($category->getWorkState());
        $this->assertSame(1, $category->getDocumentAccess());
        $this->assertSame(10, $category->getGroupsPerUser());
        $this->assertSame(100, $category->getMaxStudent());
        $this->assertSame('desc', $category->getDescription());

        $this->assertSame($category->getResourceIdentifier(), $category->getIid());
        $this->assertSame(1, $categoryRepo->count([]));

        $categoryRepo->delete($category);

        $this->assertSame(0, $categoryRepo->count([]));
        $this->assertSame(1, $courseRepo->count([]));
    }
}
