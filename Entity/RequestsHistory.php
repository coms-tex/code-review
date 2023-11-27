<?php

declare(strict_types=1);

namespace App\Services\Entity;

use App\Services\Infrastructure\RequestsHistoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: RequestsHistoryRepository::class)]
class RequestsHistory
{
    public const DIRECTION_REQUEST = 'request';
    public const DIRECTION_RESPONSE = 'response';
    public const ALIAS_SETUP = 'setup';
    public const ALIAS_DISCONNECT = 'disconnect';
    public const ALIAS_GET_CONFIG = 'get-config';
    public const ALIAS_SET_CONFIG = 'set-config';
    public const ALIAS_SET_TESTS = 'set-tests';
    public const ALIAS_GET_TESTS = 'get-tests';
    public const ALIAS_REMOVE_TEST = 'remove-test';
    public const ALIAS_METRICS = 'metrics';
    public const ALIAS_FLUSH_TESTS = 'flush-tests';
    public const ALIAS_GENERATE_AGENT_TEST_TASK = 'generate-agent-test-task';
    public const ALIAS_OAT_FIND_BY_TEMPLATE_ID = 'oat-find-by-template-id';
    public const ALIAS_OAT_FIND_BY_INVENTORY_ID = 'oat-find-by-inventory-id';
    public const ALIAS_OAT_FIND_BY_CATEGORY_INSTANCE_OBJECT_ID = 'oat-find-by-category-instance-object-id';
    public const ALIAS_OAT_FIND_BY_INSTANCE_OBJECT_ID = 'oat-find-by-instance-object-id';
    public const ALIAS_OAT_FIND_BY_REMOVED_CATEGORY_INSTANCE_OBJECT_ID =
        'oat-find-by-removed-category-instance-object-id';
    public const ALIAS_OBJECT_UPDATE_BROADCAST = 'metric-manager-object-update';
    public const ALIAS_REMOVE_CATEGORY = 'remove-category';
    public const ALIAS_REMOVE_TEMPLATE = 'remove-template';
    public const ALIAS_CLEAN_UP = 'clean-up';
    public const MAX_REPEAT_NUMBER = 10;
    public const TIMEOUT_IN_SECONDS = 300;
    public const NAME_LIST_BY_ALIAS = [
        self::ALIAS_SETUP => 'Регистрация',
        self::ALIAS_DISCONNECT => 'Отключение Агента',
        self::ALIAS_GET_CONFIG => 'Запрос настроек',
        self::ALIAS_SET_CONFIG => 'Установка настроек',
        self::ALIAS_SET_TESTS => 'Отправка списка проверок',
        self::ALIAS_GET_TESTS => 'Получение списка тестов',
        self::ALIAS_METRICS => 'Получение результатов проверок',
        self::ALIAS_REMOVE_TEST => 'Удаление проверки по id',
        self::ALIAS_FLUSH_TESTS => 'Очистка заданий для агента',
        self::ALIAS_REMOVE_CATEGORY => 'Удаление группы шаблонов',
        self::ALIAS_GENERATE_AGENT_TEST_TASK => 'Генерация задач Агенту',
        self::ALIAS_OAT_FIND_BY_TEMPLATE_ID => 'Поиск пары Объект-Шаблон по ID шаблона',
        self::ALIAS_OAT_FIND_BY_INVENTORY_ID => 'Поиск пары Объект-Шаблон по ID инвентори',
        self::ALIAS_OAT_FIND_BY_CATEGORY_INSTANCE_OBJECT_ID => 'Поиск пары Объект-шаблон по ID категория-объект',
        self::ALIAS_OAT_FIND_BY_INSTANCE_OBJECT_ID => 'Поиск пары Объект-Шаблон по ID объекта',
        self::ALIAS_OAT_FIND_BY_REMOVED_CATEGORY_INSTANCE_OBJECT_ID =>
            'Поиск и удаление пары Объект-Шаблон по удаленным связям объект-категория',
        self::ALIAS_OBJECT_UPDATE_BROADCAST => 'Получение уведомления об обновление объекта',
        self::ALIAS_CLEAN_UP => 'Очистка данных шаблонов, категорий и метрик для удаляемых сущностей',
        self::ALIAS_REMOVE_TEMPLATE => 'Удаление шаблона'
    ];

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $alias;

    #[ORM\Column(type: 'string', length: 255, columnDefinition: 'direction_list')]
    private ?string $direction;

    #[ORM\Column(type: 'integer')]
    private int $timeout = self::TIMEOUT_IN_SECONDS;

    #[ORM\Column(type: 'integer')]
    private int $maxRepeatNumber = self::MAX_REPEAT_NUMBER;

    /**
     * @Ignore
     */
    #[ORM\OneToMany(mappedBy: 'agentRequestMethod', targetEntity: AgentRequest::class)]
    private Collection $agentRequest;

    public function __construct()
    {
        $this->agentRequest = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function getMaxRepeatNumber(): ?int
    {
        return $this->maxRepeatNumber;
    }

    public function setMaxRepeatNumber(int $maxRepeatNumber): self
    {
        $this->maxRepeatNumber = $maxRepeatNumber;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAgentRequest(): Collection
    {
        return $this->agentRequest;
    }

    public function addAgentRequest(AgentRequest $agentRequest): self
    {
        if (!$this->agentRequest->contains($agentRequest)) {
            $this->agentRequest[] = $agentRequest;
        }

        return $this;
    }

    public function removeAgentRequest(AgentRequest $agentRequest): self
    {
        $this->agentRequest->removeElement($agentRequest);

        return $this;
    }
}
