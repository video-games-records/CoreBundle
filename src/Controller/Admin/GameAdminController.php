<?php

declare(strict_types=1);

namespace VideoGamesRecords\CoreBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use VideoGamesRecords\CoreBundle\Manager\GameManager;
use Yokai\SonataWorkflow\Controller\WorkflowControllerTrait;

class GameAdminController extends CRUDController
{
    use WorkflowControllerTrait;

    private GameManager $gameManager;

    public function __construct(GameManager $gameManager)
    {
        $this->gameManager = $gameManager;
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function copyAction($id): RedirectResponse
    {
        if ($this->admin->hasAccess('create')) {
            $game = $this->admin->getSubject();

            $this->gameManager->copy($game);
            $this->addFlash('sonata_flash_success', 'Copied successfully');
        }

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function majAction($id): RedirectResponse
    {
        $this->gameManager->maj($this->admin->getSubject());
        $this->addFlash('sonata_flash_success', 'Game maj successfully');
        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function setProofVideoOnly($id): RedirectResponse
    {
        $this->gameManager->setProofVideoOnly($this->admin->getSubject());
        $this->addFlash('sonata_flash_success', 'Game maj successfully');
        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
