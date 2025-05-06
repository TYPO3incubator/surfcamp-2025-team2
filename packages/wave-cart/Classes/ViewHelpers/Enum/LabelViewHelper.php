<?php

namespace TYPO3Incubator\WaveCart\ViewHelpers\Enum;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class LabelViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument(
            'enumClassName',
            'string',
            'The className of the enum class (you need to specific the full namespace with the class name, e.g. \Vendor\Package\ExampleEnum)',
            true
        );
        $this->registerArgument(
            'value',
            'string',
            'The value to get label from the enum',
            true
        );
    }

    public function render()
    {
        return $this->arguments['enumClassName']::from($this->arguments['value'])->getLabel();
    }
}
