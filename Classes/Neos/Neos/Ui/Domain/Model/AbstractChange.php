<?php
namespace Neos\Neos\Ui\Domain\Model;

use Neos\Neos\Ui\Domain\Model\Feedback\Operations\ReloadDocument;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Neos\Ui\TYPO3CR\Service\NodeService;
use Neos\Neos\Ui\Domain\Model\Feedback\Operations\UpdateWorkspaceInfo;
use Neos\Neos\Ui\Domain\Model\Feedback\Operations\DocumentNodeCreated;

abstract class AbstractChange implements ChangeInterface
{
    /**
     * @var NodeInterface
     */
    protected $subject;

    /**
     * @Flow\Inject
     * @var FeedbackCollection
     */
    protected $feedbackCollection;

    /**
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * Inject the persistence manager
     *
     * @param PersistenceManagerInterface $persistenceManager
     * @return void
     */
    public function injectPersistenceManager(PersistenceManagerInterface $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * Set the subject
     *
     * @param NodeInterface $subject
     * @return void
     */
    public function setSubject(NodeInterface $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get the subject
     *
     * @return NodeInterface
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Helper method to inform the client, that new workspace information is available
     *
     * @return void
     */
    protected function updateWorkspaceInfo()
    {
        $nodeService = new NodeService();
        $updateWorkspaceInfo = new UpdateWorkspaceInfo();
        $updateWorkspaceInfo->setDocument(
            $nodeService->getClosestDocument($this->getSubject())
        );

        $this->feedbackCollection->add($updateWorkspaceInfo);
    }

    /**
     * Inform the client to reload the currently-displayed document, because the rendering has changed.
     *
     * This method will be triggered if [nodeType].properties.[propertyName].ui.reloadIfChanged is TRUE.
     *
     * @return void
     */
    protected function reloadDocument()
    {
        $nodeService = new NodeService();
        $reloadDocument = new ReloadDocument();
        $reloadDocument->setDocument(
            $nodeService->getClosestDocument($this->getSubject())
        );

        $this->feedbackCollection->add($reloadDocument);
    }

    /**
     * Inform the client that a node has been created, the client decides if and which tree should react to this change.
     * @param NodeInterface $subject
     * @return void
     */
    protected function addDocumentNodeCreatedFeedback($subject = null)
    {
        $node = $subject ?: $this->getSubject();
        $nodeService = new NodeService();

        if ($nodeService->isDocument($node)) {
            $documentNodeCreated = new DocumentNodeCreated();
            $documentNodeCreated->setDocumentNode($node);

            $this->feedbackCollection->add($documentNodeCreated);
        }
    }
}
