<?php

declare(strict_types=1);

namespace App\Services\Infrastructure;

class RequestsHistoryRepository
{
    // some another code...

    public function getAllHistory(int $requestId): array {
        $requests = [];

        foreach ($this->entityManager->findBy(['request_id' => $requestId]) as $request) {
            $tags = $this->tagsRepository->findByRequest(['request_id' => $request->getId()]);
            $request->setTags($tags);
            $requests[] = $request;
        }

        return $requests;
    }
    // some another code...
}
