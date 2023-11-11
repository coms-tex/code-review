<?php

declare(strict_types=1);

use App\Services\Entity\RequestsHistory;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class RequestService
{
    public function __construct(
        private readonly ValidateDtoService $validateDtoService,
        private readonly MessageProducerService $messageProducerService,
    ) {
    }

    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function createMessageSetConfigExternal($agent): string {
        $messageRequestDto = new MessageSetConfigRequestDto();
        $messageRequestDto->sendInterval = $agent->getSendInterval();
        $messageRequestDto->fastPoolSize = $agent->getFastPoolSize();
        $messageRequestDto->slowPoolSize = $agent->getSlowPoolSize();
        $messageRequestDto->resultsQueueCapacity = $agent->getResultsQueueCapacity();
        $messageRequestDto->unsentResultsMaxSize = $agent->getUnsentResultsMaxSize();

        $requestUuid = $this->sendMessage(
            $messageRequestDto,
            $agent->getInstance(),
            RequestsHistory::ALIAS_SET_CONFIG,
            MessageType::EXTERNAL->value,
            MessageDirection::REQUEST->value,
            $agent->getUuid()
        );

        return $requestUuid;
    }

    // another code...
}
