<?php

declare(strict_types=1);

namespace App\Services\ExportImport;

use Generator;

class Export
{
    // ...some code

    public function exportMetrics(Instance $instance, Category $category, bool $serialize = false): Generator
    {
        $counter = 0;
        if ($serialize) {
            foreach ($this->getMetrics($instance, $category) as $metricItem) {
                $counter++;
                yield $this->serializer->serialize($metricItem, 'json');
            }
        } else {
            yield from $this->getMetrics($instance, $category);
        }

        return $counter;
    }

    private function getMetrics(Instance $instance, Category $category): iterable
    {
        return $this->repository->read($instance, $category);
    }
}
