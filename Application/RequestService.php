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
     * @throws JsonException
     * @throws ExceptionInterface
     */
    public function createMessageSetTestsExternal(array $tasks, Agent $agent): string
    {
        $message = new MessageSetTestsRequestDto();
        $message->tasks = $tasks;

        return $this->sendMessage(
            $message,
            $agent->getInstance(),
            AgentRequestMethod::ALIAS_SET_TESTS,
            MessageType::EXTERNAL->value,
            MessageDirection::REQUEST->value,
            $agent->getUuid(),
        );
    }

    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function createMessageSetConfigExternal($agent, ?string $parentRequestUuid = null): string {
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

    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function createMessageRemoveTestExternal(array $tasks, mixed $agent): string
    {
        $messageRemoveTestDto = new MessageRemoveTestRequestDto();
        $messageRemoveTestDto->tasks = $tasks;

        return $this->sendMessage(
            $messageRemoveTestDto,
            $agent->getInstance(),
            AgentRequestMethod::ALIAS_REMOVE_TEST,
            MessageType::EXTERNAL->value,
            MessageDirection::REQUEST->value,
            $agent->getUuid()
        );
    }

    // another code...
}
