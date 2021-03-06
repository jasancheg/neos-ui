<?php
namespace Neos\Neos\Ui\View;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Neos.Neos.Ui".          *
 *                                                                        *
 *                                                                        */
use Neos\Flow\Annotations as Flow;
use Neos\Fusion\View\TypoScriptView;

class BackendTypoScriptView extends TypoScriptView
{

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $this->setTypoScriptPathPattern('resource://Neos.Neos.Ui/Private/Fusion/Backend');
    }
}
