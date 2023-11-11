<?php

declare(strict_types=1);

namespace App\Services\ExportImport;

use Generator;

class Export
{
    // ...some constructor

    public function exportMetrics(Instance $instance, Category $category, bool $serialize = false): Generator
    {
        if ($serialize) {
            foreach ($this->getMetrics($instance, $category) as $metricItem) {
                yield $this->serializer->serialize($metricItem, 'json');
            }
        }else{
            yield from $this->getMetrics($instance, $category);
        }
    }

}
