<?php

declare(strict_types=1);

namespace App\Services\ExportImport;

use App\Helpers\Uuid;
use Aws\S3\Exception\S3Exception;
use Generator;
use RuntimeException;
use Symfony\Component\Filesystem\Path;
use Throwable;
use ZipArchive;

final class GroupExporter extends AbstractExporter implements SendStatusInterface, DeferredInterface
{
    use SendStatusTrait;
    use DeferredTrait;

    private const ARCHIVE_FILENAME = 'export_group.zip';
    private const STORE_OBJECT_KEY_PREFIX = 'export_group_';
    private array $deferredFunctions = [];

    public function __construct(
        private readonly Export $exportService,
    ) {
    }


    public function export(): void
    {
        if (!$this->context instanceof ExportGroupContext) {
            throw new RuntimeException("Unsupported context");
        }

        $category = $this->exportService->getCategory($this->context->getGroupId());
        if ($category === null) {
            throw ExportException::makeNotFoundException();
        }

        $tmpDir = Path::normalize(sys_get_temp_dir() . '/export_' . Uuid::v4());
        $this->filesystem->mkdir($tmpDir);
        $this->defer(fn() => $this->filesystem->remove($tmpDir));

        $this->statusHelper->setStagesCount(count(ExportableItemsEnum::cases()));

        $zipArchive = new ZipArchive();
        $outputArchiveFilename = $tmpDir . '/' . "{$category->getAlias()}_" . self::ARCHIVE_FILENAME;
        if ($zipArchive->open(Path::normalize($outputArchiveFilename), ZipArchive::CREATE) !== true) {
            throw ExportException::makeArchiveException();
        }

        try {
            $zipArchive->addFile(
                ...$this->makeFile(
                $tmpDir,
                ExportableItemsEnum::TEST_TEMPLATES,
                $this->exportService->exportTestTemplates($this->instance, $category)
            )
            );
        } catch (Throwable $th) {
            throw ExportException::makeExportTemplatesException($th->getMessage());
        }

        $this->sendStatus($this->statusHelper->progress());

        try {
            $zipArchive->addFile(
                ...$this->makeFile(
                $tmpDir,
                ExportableItemsEnum::METRICS,
                $this->exportService->exportMetrics($this->instance, $category)
            )
            );
        } catch (Throwable $th) {
            throw ExportException::makeExportMetricsException($th->getMessage());
        }

        $this->sendStatus($this->statusHelper->progress());

        try {
            $zipArchive->addFile(
                ...$this->makeFile(
                $tmpDir,
                ExportableItemsEnum::INVENTORY,
                $this->exportService->exportInventory($this->instance, $category)
            )
            );
        } catch (Throwable $th) {
            throw ExportException::makeExportInventoryException($th->getMessage());
        }

        $this->sendStatus($this->statusHelper->progress());

        try {
            $zipArchive->addFile(
                ...$this->makeFile(
                $tmpDir,
                ExportableItemsEnum::TRIGGER_TEMPLATES,
                $this->exportService->exportTriggerTemplates($this->instance, $category)
            )
            );
        } catch (Throwable $th) {
            throw ExportException::makeExportTriggersException($th->getMessage());
        }

        $zipArchive->close();
        $storageObjectKey = "{$category->getAlias()}_" . self::STORE_OBJECT_KEY_PREFIX . Uuid::v4();
        $this->sendStatus($this->statusHelper->done($this->storeFile($outputArchiveFilename, $storageObjectKey)));
    }

    // another code...
}
