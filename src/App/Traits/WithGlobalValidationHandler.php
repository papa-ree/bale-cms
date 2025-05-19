<?php

namespace Paparee\BaleCms\App\Traits;

trait WithGlobalValidationHandler
{
    public function rendering(): void
    {
        if (count($this->getErrorBag()->all()) > 0) {
            $this->dispatch('message-failed');
        }
    }
}